<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

use App\Models\Backend\JobApplication;
use App\Models\Backend\JobApplicationLog;
use App\Models\Backend\Job;
use App\Models\Backend\Member;

class JobApplicationController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'job-application';

    /*
    |--------------------------------------------------------------------------
    | List
    |--------------------------------------------------------------------------
    */

    public function items($parameters)
    {
        $search = Arr::get($parameters, 'keyword');
        $paginate = Arr::get($parameters, 'total', 15);

        $query = JobApplication::with([
            'member',
            'job'
        ]);

        if ($search) {

            $query->where(function ($q) use ($search) {

                $q->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");

            });
        }

        return $query
            ->orderBy('id', 'desc')
            ->paginate($paginate);
    }

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $items = $this->items($request->all());

        $items->pages = new \stdClass();
        $items->pages->start =
            ($items->perPage() * $items->currentPage())
            - $items->perPage();

        $navs = [

            '0' => [
                'url' => 'javascript:void(0)',
                'name' => 'จัดการงาน',
                'last' => 0
            ],

            '1' => [
                'url' => "$this->segment/$this->folder",
                'name' => 'ใบสมัครงาน',
                'last' => 1
            ]

        ];

        return view(
            "$this->prefix.pages.$this->folder.index",
            compact(
                'items',
                'navs'
            )
            +
            [
                'segment' => $this->segment,
                'prefix' => $this->prefix,
                'folder' => $this->folder
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
            'job'
        ])->findOrFail($id);

        $logs = JobApplicationLog::where(
            'application_id',
            $id
        )
        ->orderBy('id', 'desc')
        ->get();

        $navs = [

            '0' => [
                'url' => 'javascript:void(0)',
                'name' => 'จัดการงาน',
                'last' => 0
            ],

            '1' => [
                'url' => "$this->segment/$this->folder",
                'name' => 'ใบสมัครงาน',
                'last' => 1
            ],

            '2' => [
                'url' => "$this->segment/$this->folder/edit/$id",
                'name' => 'รายละเอียด',
                'last' => 2
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
                'navs' => $navs
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

            DB::beginTransaction();

            $application = JobApplication::findOrFail($id);

            $oldStatus = $application->status;

            $application->status =
                $request->status;

            $application->note =
                $request->note;

            $application->save();

            JobApplicationLog::create([

                'application_id' => $application->id,

                'old_status' => $oldStatus,

                'new_status' => $request->status,

                'remark' => $request->remark,

                'created_by' => auth()->id()

            ]);

            DB::commit();

            return view(
                "$this->prefix.alert.success",
                [
                    'url' => url("$this->segment/$this->folder")
                ]
            );

        } catch (\Exception $e) {

            DB::rollback();

            dd($e->getMessage());

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(Request $request)
    {
        try {

            $item = JobApplication::find(
                $request->id
            );

            if (!$item) {

                return response()->json([
                    'success' => false
                ]);
            }

            JobApplicationLog::where(
                'application_id',
                $item->id
            )->delete();

            $item->delete();

            return response()->json([
                'success' => true
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false
            ]);

        }
    }
}