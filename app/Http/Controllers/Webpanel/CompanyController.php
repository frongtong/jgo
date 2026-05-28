<?php

namespace App\Http\Controllers\Webpanel;

use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Webpanel\LogsController;
use App\Helpers\Helper;
use Illuminate\Support\Arr;

use App\Models\Backend\CompanyModel;
use App\Models\Backend\Location;

use Intervention\Image\ImageManagerStatic as Image;

class CompanyController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'company';


    /*
    |--------------------------------------------------------------------------
    | ITEMS
    |--------------------------------------------------------------------------
    */

    public function items($parameters)
    {
        $search = Arr::get($parameters, 'search');

        $paginate = Arr::get($parameters, 'total', 15);

        $query = new CompanyModel;

        if ($search) {

            $query = $query->where(function ($q) use ($search) {

                $q->where(
                    'name_th',
                    'LIKE',
                    '%' . trim($search) . '%'
                );

                $q->orWhere(
                    'name_en',
                    'LIKE',
                    '%' . trim($search) . '%'
                );

                $q->orWhere(
                    'name_jp',
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

        $items->pages = new CompanyModel();
        $items->pages->start =
            ($items->perPage() * $items->currentPage())
            - $items->perPage();

        $navs = [
            '0' => [
                'url' => "javascript:void(0)",
                'name' => "จัดการงาน",
                "last" => 0
            ],

            '1' => [
                'url' => "$this->segment/$this->folder",
                'name' => "บริษัท",
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

        $provinces = Location::whereNull('parent_id')
            ->orderBy('name', 'asc')
            ->get();

        $navs = [

            '0' => [
                'url' => "javascript:void(0)",
                'name' => "จัดการงาน",
                "last" => 0
            ],

            '1' => [
                'url' => "$this->segment/$this->folder",
                'name' => "บริษัท",
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
                'navs' => $navs,
                'provinces' => $provinces
            ]
        );
    }

       public function getCity($id)
    {
        $items = Location::where('parent_id', $id)
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($items);
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Request $request, $id)
{
    $data = CompanyModel::find($id);

    $provinces = Location::whereNull('parent_id')
        ->orderBy('name', 'asc')
        ->get();

    $cities = Location::where(
        'parent_id',
        $data->province_id
    )->orderBy('name', 'asc')->get();

    $navs = [

        '0' => [
            'url' => "javascript:void(0)",
            'name' => "จัดการงาน",
            "last" => 0
        ],

        '1' => [
            'url' => "$this->segment/$this->folder",
            'name' => "บริษัท",
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
            'navs' => $navs,
            'data' => $data,
            'provinces' => $provinces,
            'cities' => $cities
        ]
    );
}


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(Request $request)
    {

        $id = $request->input('id');

        $item = CompanyModel::find($id);

        if ($item) {

            if ($item->logo) {

                $oldImagePath = public_path($item->logo);

                if (file_exists($oldImagePath)) {

                    unlink($oldImagePath);
                }
            }


            if ($item->cover_image) {

                $oldImagePath = public_path($item->cover_image);

                if (file_exists($oldImagePath)) {

                    unlink($oldImagePath);
                }
            }


            $item->delete();

            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item not found'
        ]);
    }



    /*
    |--------------------------------------------------------------------------
    | INSERT
    |--------------------------------------------------------------------------
    */

    public function insert(Request $request, $id = null)
    {
        return $this->store($request, $id = null);
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
            | NEW
            |--------------------------------------------------------------------------
            */

            if ($id == null) {

                $data = new CompanyModel();

                $data->created_at = date('Y-m-d H:i:s');

                $data->updated_at = date('Y-m-d H:i:s');

            } else {

                $data = CompanyModel::find($id);

                $data->updated_at = date('Y-m-d H:i:s');
            }



            /*
            |--------------------------------------------------------------------------
            | DATA
            |--------------------------------------------------------------------------
            */

            $data->name_th = $request->name_th;

            $data->name_en = $request->name_en;

            $data->name_jp = $request->name_jp;

            $data->description = $request->description;

            $data->website = $request->website;

            $data->address = $request->address;

            $data->country_id = $request->country_id;

            $data->province_id = $request->province_id;

            $data->city_id = $request->city_id;



            /*
            |--------------------------------------------------------------------------
            | LOGO
            |--------------------------------------------------------------------------
            */

            $allow = ['png', 'jpeg', 'jpg', 'webp'];

            $path = 'upload/company';

            if ($fileimage = $request->file('logo')) {

                if ($data->logo) {

                    $oldImagePath = public_path($data->logo);

                    if (file_exists($oldImagePath)) {

                        unlink($oldImagePath);
                    }
                }

                $image =
                    $path .
                    '/logo-' .
                    time() .
                    '.' .
                    $fileimage->getClientOriginalExtension();

                $fileimage->move(
                    public_path($path),
                    $image
                );

                $data->logo = $image;
            }



            /*
            |--------------------------------------------------------------------------
            | COVER IMAGE
            |--------------------------------------------------------------------------
            */

            if ($fileimage = $request->file('cover_image')) {

                if ($data->cover_image) {

                    $oldImagePath = public_path(
                        $data->cover_image
                    );

                    if (file_exists($oldImagePath)) {

                        unlink($oldImagePath);
                    }
                }

                $image =
                    $path .
                    '/cover-' .
                    time() .
                    '.' .
                    $fileimage->getClientOriginalExtension();

                $fileimage->move(
                    public_path($path),
                    $image
                );

                $data->cover_image = $image;
            }



            /*
            |--------------------------------------------------------------------------
            | SAVE
            |--------------------------------------------------------------------------
            */

            if ($data->save()) {

                DB::commit();

                return view(
                    "$this->prefix.alert.success",
                    [
                        'url' => url(
                            "$this->segment/$this->folder"
                        )
                    ]
                );

            } else {

                return view(
                    "$this->prefix.alert.error",
                    [
                        'url' => url(
                            "$this->segment/$this->folder"
                        )
                    ]
                );
            }

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
                    'title' => "ไม่สามารถทำรายการได้",
                    'text' => "กรุณาทำรายการใหม่อีกครั้ง !",
                    'icon' => 'error'
                ]
            );
        }
    }



    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request)
    {

        try {

            $item = CompanyModel::find($request->id);

            $item->status = $request->status;

            $item->save();

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