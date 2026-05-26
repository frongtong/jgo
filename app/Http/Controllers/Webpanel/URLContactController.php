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
use App\Models\Backend\URLContact;
use App\Models\Backend\CategoryUrlContact;


class URLContactController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'url_contact';

    public function category_destroy(Request $request, $id)
    {
        $datas = CategoryUrlContact::find(explode(',', $id));
        if (@$datas) {
            foreach ($datas as $data) {
                $query = CategoryUrlContact::destroy($data->id);
            }
        }

        if (@$query) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function items_category($parameters)
    {
        $search = Arr::get($parameters, 'keyword');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new CategoryUrlContact;
        if ($search) {
            $query = $query->where('name', 'LIKE', '%' . trim($search) . '%');
            $query = $query->orwhere('icon', 'LIKE', '%' . trim($search) . '%');
        }
        // $query = $query->orderBy('sort', $sort);
        $query = $query->orderBy('id', 'asc');
        $results = $query->paginate($paginate);
        return $results;
    }
    public function create_modal(Request $request)
    {

        return view("$this->prefix.pages.$this->folder.category.modal-create", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
        ]);
    }

    public function edit_modal(Request $request, $id)
    {
 
        $data = CategoryUrlContact::find($id);
        return view("$this->prefix.pages.$this->folder.category.modal-edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'row' => $data,
        ]);
    }
    public function category_index(Request $request)
    {

        $items = $this->items_category($request->all());
        $items->pages = new \stdClass();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ติดต่อเรา", "last" => 0],
            '1' => ['url' => "$this->segment/contact/url", 'name' => "ประเภท", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.category.index", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'items' => $items,
            'navs' => $navs
        ]);
    }
   public function category_store(Request $request, $id = null)
    {
        try {
            DB::beginTransaction();

            // กำหนด base path ให้ถูกต้อง
            // ใช้ public_path() เพื่อสร้างเส้นทางแบบเต็มตั้งแต่แรก
            $path = public_path('upload/icon');

            if ($id == null) {
                foreach ($request->name as $index => $name) {
                    $data = new CategoryUrlContact();
                    $data->name = $name;

                    $iconFile = $request->file('icon')[$index];
                    $fileName = $name . '-' . time() . '-' . $index . '.' . $iconFile->getClientOriginalExtension();

                    // แก้ไขตรงนี้: move() ต้องการแค่ folder ปลายทางเท่านั้น
                    // และเราใช้ $fileName เป็นชื่อไฟล์ปลายทาง
                    $iconFile->move($path, $fileName);

                    // เก็บเส้นทางที่ถูกต้องในฐานข้อมูล (ต้องเป็นเส้นทางที่เข้าถึงได้จากเว็บ)
                    // เราเก็บแค่ 'upload/icon/ชื่อไฟล์.ext'
                    $data->icon = 'upload/icon/' . $fileName;
                    $data->save();
                }

            } else {
                $editdata = CategoryUrlContact::find($id);
                $editdata->name = $request->name;

                if ($request->hasFile('icon')) {
                    $iconFile = $request->file('icon');
                    $fileName = $editdata->name . '-' . time() . '-' . now()->timestamp . '.' . $iconFile->getClientOriginalExtension();

                    // แก้ไขตรงนี้
                    $iconFile->move($path, $fileName);

                    // แก้ไขตรงนี้
                    $editdata->icon = 'upload/icon/' . $fileName;
                }

                $editdata->updated_at = date('Y-m-d H:i:s');
                $editdata->save();
            }

            DB::commit();
            return view("$this->prefix.alert.success", ['url' => url("$this->segment/contact/url/category/index")]);
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e); // ควรปิด dd() เมื่อทำงานเสร็จ
            $error_log = $e->getMessage();
            $error_line = $e->getLine();
            $type_log = 'backend';
            $error_url = url()->current();
            // ตรวจสอบว่า Class LogsController มีอยู่จริง
            if (class_exists('App\Http\Controllers\LogsController')) {
                 LogsController::logInsert($error_line, $error_url, $error_log, $type_log);
            }
            return view("$this->prefix.alert.alert", [
                'url' => $error_url,
                'title' => "ไม่สามารถทำรายการได้",
                'text' => "กรุณาทำรายการใหม่อีกครั้ง !",
                'icon' => 'error'
            ]);
        }
    }
    public function items($parameters)
    {
        $search = Arr::get($parameters, 'keyword');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new URLContact;
        if ($search) {
            $query = $query->where('type', 'LIKE', '%' . trim($search) . '%');
            $query = $query->orwhere('url', 'LIKE', '%' . trim($search) . '%');
        }
        // $query = $query->orderBy('sort', $sort);
        $query = $query->orderBy('id', 'asc');
        $results = $query->paginate($paginate);
        return $results;
    }
    public function index(Request $request)
    {

        $items = $this->items($request->all());
        $items->pages = new \stdClass();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ติดต่อเรา", "last" => 0],
            '1' => ['url' => "$this->segment/contact/url", 'name' => "ช่องทางการติดตาม", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.index", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'items' => $items,
            'navs' => $navs
        ]);
    }

    public function add()
    {
        $types = CategoryUrlContact::all();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ติดต่อเรา", "last" => 0],
            '1' => ['url' => "$this->segment/contact/url", 'name' => "ช่องทางการติดตาม", "last" => 1],
            '2' => ['url' => "$this->segment/contact/url/add", 'name' => "Add", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.add", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'types' => $types
        ]);
    }

    public function edit(Request $request, $id)
    {
        $data = URLContact::find($id);
        $types = CategoryUrlContact::all();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ติดต่อเรา", "last" => 0],
            '1' => ['url' => "$this->segment/contact/url", 'name' => "ช่องทางการติดตาม", "last" => 1],
            '2' => ['url' => "$this->segment/contact/url/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'data' => $data,
            'types' => $types
        ]);
    }
    public function destroy(Request $request, $id)
    {

        $datas = URLContact::find(explode(',', $id));
        
        if (@$datas) {
            foreach ($datas as $data) {
                if ($data->type === 'map') {
                    return response()->json(false);
                }
                $query = URLContact::destroy($data->id);
            }
        }
        if (@$query) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }
    //==== Function Insert Update Delete Status Sort & Others ====
    public function insert(Request $request, $id = null)
    {
        return $this->store($request, $id = null);
    }
    public function update(Request $request, $id)
    {
        return $this->store($request, $id);
    }
    public function store($request, $id = null)
    {
        try {
            DB::beginTransaction();
            if ($id == null) {
                $data = new URLContact();
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
            } else {
                $data = URLContact::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
            }

            $data->url = $request->url;
            $data->type = $request->type;
            $data->active = $request->active ?? 'off';

            if ($data->save()) {
                DB::commit();
                return view("$this->prefix.alert.success", ['url' => url("$this->segment/contact/url")]);
            } else {
                return view("$this->prefix.alert.error", ['url' => url("$this->segment/contact/url")]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            $error_log = $e->getMessage();
            $error_line = $e->getLine();
            $type_log = 'backend';
            $error_url = url()->current();
            LogsController::logInsert($error_line, $error_url, $error_log, $type_log);
            return view("$this->prefix.alert.alert", [
                'url' => $error_url,
                'title' => "ไม่สามารถทำรายการได้",
                'text' => "กรุณาทำรายการใหม่อีกครั้ง !",
                'icon' => 'error'
            ]);
        }
    }
}
