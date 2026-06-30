<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\Http\Controllers\Webpanel\LogsController;

use App\Models\Backend\Job;

use App\Models\Backend\Category1;
use App\Models\Backend\Category2;
use App\Models\Backend\JobCategory;
use App\Models\Backend\Location;
use App\Models\Backend\CompanyModel;

class JobController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'job';

    /*
    |--------------------------------------------------------------------------
    | ITEMS
    |--------------------------------------------------------------------------
    */

    public function items($parameters)
    {
        $search = Arr::get($parameters, 'search');
        $paginate = Arr::get($parameters, 'total', 15);

        $query = Job::with([
            'company',
            'province',
            'city'
        ]);

        if ($search) {

            $query = $query->where(function ($q) use ($search) {

                $q->where(
                    'title_th',
                    'LIKE',
                    '%' . trim($search) . '%'
                );

            });

        }

        $query = $query->orderBy('id', 'desc');

        $results = $query->paginate($paginate);

        return $results;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $items = $this->items($request);

        $items->pages = new Job();
        $items->pages->start =
            ($items->perPage() * $items->currentPage())
            - $items->perPage();
        $companies = CompanyModel::orderBy(
        'name_th',
        'asc'
        )->get();
         $provinces = Location::whereNull(
        'parent_id'
        )->orderBy('name', 'asc')->get();
        $navs = [

            '0' => [
                'url' => "javascript:void(0)",
                'name' => "จัดการงาน",
                "last" => 0
            ],

            '1' => [
                'url' => "$this->segment/$this->folder",
                'name' => "งาน",
                "last" => 1
            ],

        ];

        return view(
            "$this->prefix.pages.$this->folder.index",
            [
                'segment' => $this->segment,
                'prefix' => $this->prefix,
                'folder' => $this->folder,
                'items' => $items,
                'companies' => $companies,
                'provinces' => $provinces,
                'navs' => $navs
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ADD
    |--------------------------------------------------------------------------
    */

    public function add(Request $request)
    {
        $companies = CompanyModel::orderBy(
            'name_th',
            'asc'
        )->get();

        $category1 = Category1::where(
            'status',
            'on'
        )->orderBy('name_th', 'asc')->get();

        $provinces = Location::whereNull(
            'parent_id'
        )->orderBy('name', 'asc')->get();

        $navs = [

            '0' => [
                'url' => "javascript:void(0)",
                'name' => "จัดการงาน",
                "last" => 0
            ],

            '1' => [
                'url' => "$this->segment/$this->folder",
                'name' => "งาน",
                "last" => 1
            ],

            '2' => [
                'url' => "$this->segment/$this->folder/add",
                'name' => "Add",
                "last" => 2
            ],

        ];

        return view(
            "$this->prefix.pages.$this->folder.add",
            [
                'segment' => $this->segment,
                'prefix' => $this->prefix,
                'folder' => $this->folder,

                'companies' => $companies,
                'category1' => $category1,
                'provinces' => $provinces,

                'navs' => $navs
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Request $request, $id)
    {
        $data = Job::find($id);

        $companies = CompanyModel::orderBy(
            'name_th',
            'asc'
        )->get();

        $category1 = Category1::where(
            'status',
            'on'
        )->orderBy('name_th', 'asc')->get();

        $provinces = Location::whereNull(
            'parent_id'
        )->orderBy('name', 'asc')->get();

        $cities = Location::where(
            'parent_id',
            $data->province_id
        )->orderBy('name', 'asc')->get();

        $jobCategories = JobCategory::where(
            'job_id',
            $id
        )->pluck('category2_id')->toArray();

        $navs = [

            '0' => [
                'url' => "javascript:void(0)",
                'name' => "จัดการงาน",
                "last" => 0
            ],

            '1' => [
                'url' => "$this->segment/$this->folder",
                'name' => "งาน",
                "last" => 1
            ],

            '2' => [
                'url' => "$this->segment/$this->folder/edit/$id",
                'name' => "Edit",
                "last" => 2
            ],

        ];

        return view(
            "$this->prefix.pages.$this->folder.edit",
            [
                'segment' => $this->segment,
                'prefix' => $this->prefix,
                'folder' => $this->folder,

                'data' => $data,

                'companies' => $companies,
                'category1' => $category1,
                'provinces' => $provinces,
                'cities' => $cities,

                'jobCategories' => $jobCategories,

                'navs' => $navs
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT
    |--------------------------------------------------------------------------
    */

    public function insert(Request $request)
    {
        return $this->store($request);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        return $this->store($request, $id);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store($request, $id = null)
    {
        try {

            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | INSERT
            |--------------------------------------------------------------------------
            */

            if ($id == null) {

                $data = new Job();

                $data->created_at = now();

            } else {

                $data = Job::find($id);

            }

            /*
            |--------------------------------------------------------------------------
            | DATA
            |--------------------------------------------------------------------------
            */

            $data->company_id = $request->company_id;

            $data->title_th = $request->title_th;

            $data->title_en = $request->title_en;

            $data->title_jp = $request->title_jp;

            $data->job_type = $request->job_type;

            $data->salary_type = $request->salary_type;

            $data->salary_min = $request->salary_min;

            $data->salary_max = $request->salary_max;

            $data->currency = $request->currency;

            $data->gender = $request->gender;

            $data->age_min = $request->age_min;

            $data->age_max = $request->age_max;

            $data->qty = $request->qty;
            $data->date = $request->date;

            $data->province_id = $request->province_id;

            $data->city_id = $request->city_id;

            $data->work_time = $request->work_time;

            $data->overtime = $request->overtime;

            $data->holiday = $request->holiday;

            $data->start_work_date =
                $request->start_work_date;

            $data->detail = $request->detail;

            $data->welfare = $request->welfare;

            $data->map_link = $request->map_link;

            $data->status = $request->status ?? 'on';

            /*
            |--------------------------------------------------------------------------
            | LOGO
            |--------------------------------------------------------------------------
            */

            $path = 'upload/job';

            if ($file = $request->file('logo')) {

                if ($data->logo) {

                    @unlink(public_path($data->logo));

                }

                $filename =
                    $path .
                    '/logo-' .
                    time() .
                    '.' .
                    $file->getClientOriginalExtension();

                $file->move(
                    public_path($path),
                    $filename
                );

                $data->logo = $filename;

            }

            /*
            |--------------------------------------------------------------------------
            | BANNER
            |--------------------------------------------------------------------------
            */

            if ($file = $request->file('banner_image')) {

                if ($data->banner_image) {

                    @unlink(
                        public_path($data->banner_image)
                    );

                }

                $filename =
                    $path .
                    '/banner-' .
                    time() .
                    '.' .
                    $file->getClientOriginalExtension();

                $file->move(
                    public_path($path),
                    $filename
                );

                $data->banner_image = $filename;

            }

            /*
            |--------------------------------------------------------------------------
            | SAVE
            |--------------------------------------------------------------------------
            */

            $data->save();

            /*
            |--------------------------------------------------------------------------
            | CATEGORY
            |--------------------------------------------------------------------------
            */

            JobCategory::where(
                'job_id',
                $data->id
            )->delete();

            if ($request->category2_id) {

                foreach (
                    $request->category2_id
                    as $category2
                ) {

                    $category = Category2::find(
                        $category2
                    );

                    if ($category) {

                        JobCategory::create([

                            'job_id' => $data->id,

                            'category1_id' =>
                                $category->category1_id,

                            'category2_id' =>
                                $category->id,

                        ]);

                    }

                }

            }

            DB::commit();

            return view(
                "$this->prefix.alert.success",
                [
                    'url' =>
                    url("$this->segment/$this->folder")
                ]
            );

        } catch (\Exception $e) {

            DB::rollback();

            $error_log = $e->getMessage();

            $error_line = $e->getLine();

            $type_log = 'backend';

            $error_url = url()->current();

            LogsController::logInsert(
                $error_line,
                $error_url,
                $error_log,
                $type_log
            );

            return view(
                "$this->prefix.alert.alert",
                [
                    'url' => $error_url,

                    'title' =>
                    "ไม่สามารถทำรายการได้",

                    'text' =>
                    "กรุณาทำรายการใหม่อีกครั้ง !",

                    'icon' => 'error'
                ]
            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(Request $request)
    {
        $id = $request->id;

        $item = Job::find($id);

        if ($item) {

            @unlink(public_path($item->logo));

            @unlink(
                public_path($item->banner_image)
            );

            JobCategory::where(
                'job_id',
                $id
            )->delete();

            $item->delete();

            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

   public function updateStatus(Request $request)
{
    try {

        $item = Job::find($request->id);

        $item->status = $request->status;

        $item->save();

        return response()->json([
            'status' => true,
            'message' => 'success'
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 500);

    }
}

    /*
    |--------------------------------------------------------------------------
    | CITY
    |--------------------------------------------------------------------------
    */

    public function getCity($id)
    {
        $items = Location::where(
            'parent_id',
            $id
        )->orderBy('name_th', 'asc')->get();

        return response()->json($items);
    }

    /*
    |--------------------------------------------------------------------------
    | CATEGORY2
    |--------------------------------------------------------------------------
    */

    public function getCategory2($id)
    {
        $items = Category2::where(
            'category1_id',
            $id
        )->where(
            'status',
            'on'
        )->orderBy('name_th', 'asc')->get();

        return response()->json($items);
    }
}
