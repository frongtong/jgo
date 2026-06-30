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
        return [
            JobApplication::STATUS_PENDING => 'รอดำเนินการ',
            JobApplication::STATUS_INTERVIEW => 'นัดสัมภาษณ์',
            JobApplication::STATUS_APPROVED => 'อนุมัติ',
            JobApplication::STATUS_REJECTED => 'ไม่ผ่าน',
            JobApplication::STATUS_CANCELLED => 'ยกเลิก',
            JobApplication::STATUS_COMPLETED => 'เสร็จสิ้น',
        ];
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
            'member',
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
                'remark' => ['nullable', 'string'],
            ]);

            DB::beginTransaction();

            $application = JobApplication::findOrFail($id);
            $oldStatus = $application->status;

            $application->status = $request->status;
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
        $fileName = 'job-applications-' . now()->format('Ymd-His') . '.csv';
        $statuses = $this->statuses();
        $applications = $this->query($request->all())
            ->orderBy('id', 'desc')
            ->get();

        return response()->streamDownload(function () use ($applications, $statuses) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'ID',
                'ชื่อ',
                'นามสกุล',
                'เบอร์โทร',
                'อีเมล',
                'ตำแหน่งงาน',
                'บริษัท',
                'สถานะ',
                'วันที่สมัคร',
            ]);

            foreach ($applications as $application) {
                fputcsv($handle, [
                    $application->id,
                    $application->first_name,
                    $application->last_name,
                    $application->phone,
                    $application->email,
                    $application->job->title_th ?? '',
                    $application->job->company->name_th ?? '',
                    $statuses[$application->status] ?? $application->status,
                    optional($application->created_at)->format('d/m/Y H:i'),
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
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
