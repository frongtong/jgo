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
use App\Models\Backend\CompanyHome;

class CompanyHomeController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'companyhome';
    public function items($parameters)
    {
        $search = Arr::get($parameters, 'keyword');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = CompanyHome::where('id','<=','4');
        if ($search) {
            $query = $query->where('name_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->orWhere('name_en', 'LIKE', '%' . trim($search) . '%');
            $query = $query->orWhere('detail_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->orWhere('detail_en', 'LIKE', '%' . trim($search) . '%');
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
            '0' => ['url' => "javascript:void(0)", 'name' => "หน้าหลัก", "last" => 0],
            '1' => ['url' => "$this->segment/home/company", 'name' => "ประวัติบริษัท", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.index", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'items' => $items,
            'navs' => $navs
        ]);
    }
    public function items_icon($parameters)
    {
        $search = Arr::get($parameters, 'keyword');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = CompanyHome::where('id','>','4');
        if ($search) {
            $query = $query->where('name_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->orWhere('name_en', 'LIKE', '%' . trim($search) . '%');
            $query = $query->orWhere('detail_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->orWhere('detail_en', 'LIKE', '%' . trim($search) . '%');
        }
        // $query = $query->orderBy('sort', $sort);
        $query = $query->orderBy('id', 'asc');
        $results = $query->paginate($paginate);
        return $results;
    }

    public function index_icon(Request $request)
    {

        $items = $this->items_icon($request->all());
        $items->pages = new \stdClass();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "หน้าหลัก", "last" => 0],
            '1' => ['url' => "$this->segment/home/company", 'name' => "รูปภาพไอคอน", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.index_icon", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'items' => $items,
            'navs' => $navs
        ]);
    }
    public function add()
    {
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "หน้าหลัก", "last" => 0],
            '1' => ['url' => "$this->segment/home/company", 'name' => "ประวัติบริษัท", "last" => 1],
            '2' => ['url' => "$this->segment/home/company/add", 'name' => "Add", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.add", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs
        ]);
    }

    public function edit(Request $request, $id)
    {
        $data = CompanyHome::find($id);
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "หน้าหลัก", "last" => 0],
            '1' => ['url' => "$this->segment/home/company", 'name' => "ประวัติบริษัท", "last" => 1],
            '2' => ['url' => "$this->segment/home/company/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'data' => $data
        ]);
    }
    public function edit_icon(Request $request, $id)
    {
        $data = CompanyHome::find($id);
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "หน้าหลัก", "last" => 0],
            '1' => ['url' => "$this->segment/home/company", 'name' => "รูปภาพไอคอน", "last" => 1],
            '2' => ['url' => "$this->segment/home/company/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.edit_icon", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'data' => $data
        ]);
    }
    public function destroy(Request $request, $id)
    {
        $datas = CompanyHome::find(explode(',', $id));
        if (@$datas) {
            foreach ($datas as $data) {
                $query = CompanyHome::destroy($data->id);
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
                $data = new CompanyHome();
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
            } else {
                $data = CompanyHome::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
            }

            $data->name_th = $request->name_th;
            $data->name_en = $request->name_en;
            $data->detail_th = $request->detail_th;
            $data->detail_en = $request->detail_en;

            $path = 'upload/Home';
            if ($request->file('image')) {
                $file = $request->file('image');
                if ($data->image) {
                    $oldImagePath = public_path($data->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $name = $path . '/company-img-' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path($path), $name);
                $data->banner = $name;
            }

            if ($data->save()) {
                DB::commit();
                return view("$this->prefix.alert.success", ['url' => url("$this->segment/home/company")]);
            } else {
                return view("$this->prefix.alert.error", ['url' => url("$this->segment/home/company")]);
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
    public function update_icon(Request $request, $id)
    {
        return $this->store_icon($request, $id);
    }
    public function store_icon($request, $id = null)
    {
        try {
            DB::beginTransaction();
            if ($id == null) {
                $data = new CompanyHome();
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
            } else {
                $data = CompanyHome::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
            }

            $data->name_th = $request->name_th;
            $data->name_en = $request->name_en;
            $data->detail_th = $request->detail_th;
            $data->detail_en = $request->detail_en;

            $path = 'upload/Home';
            if ($request->file('image')) {
                $file = $request->file('image');
                if ($data->image) {
                    $oldImagePath = public_path($data->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $name = $path . '/companyhome-img-' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path($path), $name);
                $data->banner = $name;
            }

            if ($data->save()) {
                DB::commit();
                return view("$this->prefix.alert.success", ['url' => url("$this->segment/home/icon")]);
            } else {
                return view("$this->prefix.alert.error", ['url' => url("$this->segment/home/icon")]);
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
     public function updateStatus(Request $request)
    {
        try {
            $item = CompanyHome::find($request->id);
            $item->status = $request->status;
            $item->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
}
