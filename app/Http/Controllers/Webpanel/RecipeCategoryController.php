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
use App\Models\Backend\RecipecategoryModel;

class RecipeCategoryController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'recipecategory';
    public function items($parameters)
    {
        $search = Arr::get($parameters, 'keyword');
        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new RecipecategoryModel;
        if ($search) {
            $query = $query->where('name_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->orwhere('name_en', 'LIKE', '%' . trim($search) . '%');
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
            '0' => ['url' => "javascript:void(0)", 'name' => "สูตรอาหาร", "last" => 0],
            '1' => ['url' => "$this->segment/recipe/category", 'name' => "หมวดหมู่", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.index", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'items' => $items,
            'navs' => $navs
        ]);
    }
    public function status(Request $request, $id = null)
    {
        try {
            DB::beginTransaction();
             $data = RecipecategoryModel::find($id);

            $data->status = $request->status;
            $data->save();
            DB::commit();
            return response()->json(true);
        } catch (\Exception $e) {
            dd($e);

            DB::rollback();
            return response()->json(false);
        }
    }
    public function add()
    {
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "สูตรอาหาร", "last" => 0],
            '1' => ['url' => "$this->segment/recipe/category", 'name' => "หมวดหมู่", "last" => 1],
            '2' => ['url' => "$this->segment/recipe/category/add", 'name' => "Add", "last" => 2],
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
        $data = RecipecategoryModel::find($id);
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ศูนย์นวัตกรรม", "last" => 0],
            '1' => ['url' => "$this->segment/recipe/category", 'name' => "หมวดหมู่", "last" => 1],
            '2' => ['url' => "$this->segment/recipe/category/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'data' => $data
        ]);
    }
    public function destroy(Request $request, $id)
    { 
        $datas = RecipecategoryModel::find(explode(',', $id));
        if (@$datas) {
            foreach ($datas as $data) {
                $query = RecipecategoryModel::destroy($data->id);
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
                $data = new RecipecategoryModel();
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
            } else {
                $data = RecipecategoryModel::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
            }
            $data->name_en = $request->name_en;
            $data->name_th = $request->name_th;
            $data->status = $request->status ?? 'off';
            if ($data->save()) {
                DB::commit();
                return view("$this->prefix.alert.success", ['url' => url("$this->segment/recipe/category")]);
            } else {
                return view("$this->prefix.alert.error", ['url' => url("$this->segment/recipe/category")]);
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
