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
use App\Models\Backend\Category1;
use Illuminate\Support\Arr;
use App\Models\Backend\Category3;
use App\Models\Backend\Category2;

use Intervention\Image\ImageManagerStatic as Image;

class Category3Controller extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'category3';
    public function getCategory3($category2_id)
    {
        $category2 = Category3::where('status','on')->where('category2_id', $category2_id)->get();
        return response()->json($category2);
    }
    public function items($parameters)
    {
        $search = Arr::get($parameters, 'search');
        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new Category3;
        if ($search) {
            $query = $query->where('name_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->where('name_en', 'LIKE', '%' . trim($search) . '%');
        }
        // $query = $query->orderBy('sort', $sort);
        $query = $query->orderBy('id', 'desc');
        $results = $query->paginate($paginate);
        return $results;
    }

    public function index(Request $request,$category1_id,$category2_id)
    {
        $items = Category3::query()->where('category2_id', $category2_id)->paginate(15);
        $items->pages = new Category3();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ผลิตภัณฑ์ของเรา", "last" => 0],
            '1' => ['url' => "$this->segment/category1", 'name' => "หมวดหมู่", "last" => 1],
            '2' => ['url' => "$this->segment/category2/$category1_id", 'name' => "หมวดหมู่รอง", "last" => 2],
            '3' => ['url' => "$this->segment/$this->folder/$category1_id/$category2_id", 'name' => "หมวดหมู่รองที่สอง", "last" => 3],

        ];
        return view("$this->prefix.pages.$this->folder.index", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'items' => $items,
            'navs' => $navs,
            'category1_id' => $category1_id,
            'category2_id' => $category2_id
        ]);
    }

    public function add(Request $request,$category1_id,$category2_id)
    {
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ผลิตภัณฑ์ของเรา", "last" => 0],
            '1' => ['url' => "$this->segment/category1", 'name' => "หมวดหมู่", "last" => 1],
            '2' => ['url' => "$this->segment/category2/$category1_id", 'name' => "หมวดหมู่รอง", "last" => 2],
            '3' => ['url' => "$this->segment/$this->folder/$category1_id/$category2_id", 'name' => "หมวดหมู่รองที่สอง", "last" => 3],
            '4' => ['url' => "$this->segment/$this->folder/$category1_id/add/$category2_id", 'name' => "Add", "last" => 4],
        ];
        return view("$this->prefix.pages.$this->folder.add", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'category1_id' => $category1_id,
            'category2_id' => $category2_id
        ]);
    }

    public function edit(Request $request,$category1_id,$category2_id,$id)
    {
        $data = Category3::find($id);
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ผลิตภัณฑ์ของเรา", "last" => 0],
            '1' => ['url' => "$this->segment/category1", 'name' => "หมวดหมู่", "last" => 1],
            '2' => ['url' => "$this->segment/category2/$category1_id", 'name' => "หมวดหมู่รอง", "last" => 2],
            '3' => ['url' => "$this->segment/$this->folder/$category1_id/$category2_id", 'name' => "หมวดหมู่รองที่สอง", "last" => 3],
            '4' => ['url' => "$this->segment/$this->folder/$category1_id/$category2_id/edit/$id", 'name' => "Edit", "last" => 4],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'category1_id' => $category1_id,
            'category2_id' => $category2_id,
            'id' => $id,
            'data' => $data
        ]);
    }
    
    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $item = Category3::find($id);

        if ($item) {
            $item->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Item not found']);
    }

    //==== Function Insert Update Delete Status Sort & Others ====
    public function insert(Request $request, $category1_id, $category2_id, $id = null)
    {
        return $this->store($request, $category1_id, $category2_id, $id = null);
    }
    public function update(Request $request, $category1_id, $category2_id, $id)
    {
        return $this->store($request, $category1_id, $category2_id, $id);
    }
    public function store($request, $category1_id, $category2_id, $id = null)
    {
        {
            try {
                DB::beginTransaction();
                if ($id == null) {
                    $data = new Category3();
                    $data->created_at = date('Y-m-d H:i:s');
                    $data->updated_at = date('Y-m-d H:i:s');
                    $data->category1_id = $category1_id;
                    $data->category2_id = $category2_id;
                } else {
                    $data = Category3::find($id);
                    $data->updated_at = date('Y-m-d H:i:s');
                }
                $data->name_en = $request->name_en;
                $data->name_th = $request->name_th;
                if ($data->save()) {
                    DB::commit();
                    return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder/$category1_id/$category2_id")]);
                } else {
                    return view("$this->prefix.alert.error", ['url' => url("$this->segment/$this->folder/$category1_id/$category2_id")]);
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

    public function updateStatus(Request $request)
    {
        try {
            $item = Category3::find($request->id);
            $item->status = $request->status;
            $item->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
}
