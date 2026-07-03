<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Models\Backend\Job;
use App\Models\Backend\JobApplication;
use App\Models\Backend\JobApplicationLog;

class JobApplicationController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'jobapplication';

    /*
    |--------------------------------------------------------------------------
    | List
    |--------------------------------------------------------------------------
    */

    public function items($parameters)
    {
        $paginate = Arr::get($parameters, 'total', 15);

        return $this->query($parameters)
            ->orderBy('id', 'desc')
            ->paginate($paginate);
    }

    protected function query($parameters)
    {
        $search = Arr::get($parameters, 'search', Arr::get($parameters, 'keyword'));
        $status = Arr::get($parameters, 'status');
        $jobId = Arr::get($parameters, 'job_id');
        $dateFrom = Arr::get($parameters, 'date_from');
        $dateTo = Arr::get($parameters, 'date_to');

        $query = JobApplication::with([
            'member',
            'job.company',
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($jobId) {
            $query->where('job_id', $jobId);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        return $query;
    }

    protected function statuses(): array
    {
        return JobApplication::statusLabels();
    }

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $items = $this->items($request->all());
        $jobs = Job::orderBy('title_th', 'asc')->get();
        $statuses = $this->statuses();

        $items->pages = new \stdClass();
        $items->pages->start =
            ($items->perPage() * $items->currentPage())
            - $items->perPage();

        $navs = [
            '0' => [
                'url' => 'javascript:void(0)',
                'name' => 'จัดการงาน',
                'last' => 0,
            ],
            '1' => [
                'url' => "$this->segment/$this->folder",
                'name' => 'ใบสมัครงาน',
                'last' => 1,
            ],
        ];

        return view(
            "$this->prefix.pages.$this->folder.index",
            compact('items', 'jobs', 'statuses', 'navs')
            + [
                'segment' => $this->segment,
                'prefix' => $this->prefix,
                'folder' => $this->folder,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Detail
    |--------------------------------------------------------------------------
    */

    public function edit(Request $request, $id)
    {
        $data = JobApplication::with([
            'member.profile',
            'member.educations',
            'member.applicationDetail',
            'job.company',
        ])->findOrFail($id);

        $logs = JobApplicationLog::where('application_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        $statuses = $this->statuses();

        $navs = [
            '0' => [
                'url' => 'javascript:void(0)',
                'name' => 'จัดการงาน',
                'last' => 0,
            ],
            '1' => [
                'url' => "$this->segment/$this->folder",
                'name' => 'ใบสมัครงาน',
                'last' => 1,
            ],
            '2' => [
                'url' => "$this->segment/$this->folder/edit/$id",
                'name' => 'รายละเอียด',
                'last' => 2,
            ],
        ];

        return view(
            "$this->prefix.pages.$this->folder.edit",
            [
                'segment' => $this->segment,
                'prefix' => $this->prefix,
                'folder' => $this->folder,
                'data' => $data,
                'logs' => $logs,
                'statuses' => $statuses,
                'navs' => $navs,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Status
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => [
                    'required',
                    'in:' . implode(',', array_keys($this->statuses())),
                ],
                'interview_date' => ['nullable', 'date'],
                'interview_time' => ['nullable', 'string', 'max:255'],
                'interview_location' => ['nullable', 'string', 'max:255'],
                'hr_note' => ['nullable', 'string'],
                'remark' => ['nullable', 'string'],
            ]);

            DB::beginTransaction();

            $application = JobApplication::findOrFail($id);
            $oldStatus = $application->status;

            $application->status = $request->status;
            $application->interview_date = $request->interview_date;
            $application->interview_time = $request->interview_time;
            $application->interview_location = $request->interview_location;
            $application->hr_note = $request->hr_note;
            $application->save();

            JobApplicationLog::create([
                'application_id' => $application->id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'remark' => $request->remark,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return view(
                "$this->prefix.alert.success",
                [
                    'url' => url("$this->segment/$this->folder"),
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return view(
                "$this->prefix.alert.alert",
                [
                    'url' => url("$this->segment/$this->folder/edit/$id"),
                    'title' => 'ไม่สามารถบันทึกข้อมูลได้',
                    'text' => $e->getMessage(),
                    'icon' => 'error',
                ]
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Export
    |--------------------------------------------------------------------------
    */

    public function export(Request $request): StreamedResponse
    {
        $fileName = 'job-applications-' . now()->format('Ymd-His') . '.xls';
        $statuses = $this->statuses();
        $applications = $this->query($request->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->streamDownload(function () use ($applications, $statuses) {
            echo "\xEF\xBB\xBF";
            echo '<table border="1">';
            echo '<tr>';
            foreach ([
                'ชื่อผู้สมัคร',
                'เบอร์โทร',
                'อีเมล',
                'ตำแหน่งที่สมัคร',
                'วันที่สมัคร',
                'สถานะ',
                'วันที่สัมภาษณ์',
                'สถานที่สัมภาษณ์',
            ] as $heading) {
                echo '<th>' . e($heading) . '</th>';
            }
            echo '</tr>';

            foreach ($applications as $application) {
                echo '<tr>';
                foreach ([
                    trim($application->first_name . ' ' . $application->last_name),
                    $application->phone,
                    $application->email,
                    $application->job->title_th ?? '',
                    optional($application->created_at)->format('d/m/Y H:i'),
                    $statuses[$application->status] ?? $application->status,
                    optional($application->interview_date)->format('d/m/Y'),
                    $application->interview_location,
                ] as $value) {
                    echo '<td>' . e($value) . '</td>';
                }
                echo '</tr>';
            }

            echo '</table>';
        }, $fileName, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(Request $request)
    {
        try {
            $item = JobApplication::find($request->id);

            if (!$item) {
                return response()->json([
                    'success' => false,
                ]);
            }

            JobApplicationLog::where('application_id', $item->id)->delete();
            $item->delete();

            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
            ]);
        }
    }
}
