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
use App\Models\Backend\Category1;

use Intervention\Image\ImageManagerStatic as Image;

class Category1Controller extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'category1';
    public function items($parameters)
    {
        $search = Arr::get($parameters, 'search');
        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new Category1;
        if ($search) {
            $query = $query->where('name_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->where('name_en', 'LIKE', '%' . trim($search) . '%');
        }
        // $query = $query->orderBy('sort', $sort);
        $query = $query->orderBy('id', 'desc');
        $results = $query->paginate($paginate);
        return $results;
    }

    public function index(Request $request)
    {

        $items = Category1::query()->paginate(15);
        $items->pages = new Category1();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "จัดการงาน", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "หมวดหมู่", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.index", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'items' => $items,
            'navs' => $navs
        ]);
    }

    public function add(Request $request)
    {
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "จัดการงาน", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "หมวดหมู่", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/add", 'name' => "Add", "last" => 2],
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
        $data = Category1::find($id);
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "จัดการงาน", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "หมวดหมู่", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'data' => $data
        ]);
    }
    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $item = Category1::find($id);

        if ($item) {
            Storage::disk('public')->delete($item->image);
            $item->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Item not found']);
    }

    // public function destroy(Request $request)
    // {

    //     $datas = Category1::find(explode(',', $request->id));
    //     if (@$datas) {
    //         foreach ($datas as $data) {
    //             $query = Category1::destroy($data->id);
    //         }
    //     }

    //     if (@$query) {
    //         return response()->json(true);
    //     } else {
    //         return response()->json(false);
    //     }
    // }
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
                $data = new Category1();
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
            } else {
                $data = Category1::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
            }
            $data->name_en = $request->name_en;
            $data->name_th = $request->name_th;
            $data->description_en = $request->description_en;
            $data->description_th = $request->description_th;

            $allow = ['png', 'jpeg', 'jpg', 'webp'];
            $path = 'upload/category1';
            if ($fileimage = $request->file('image')) {
                if ($data->image) {
                    $oldImagePath = public_path($data->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $image = $path . '/image-' . time() . '.' . $fileimage->getClientOriginalExtension();
                // if (in_array($fileimage->getClientOriginalExtension(), $allow)) {
                //     $fileimage->move(public_path($path), $image);
                //     $data->image = $image;
                // }
                $fileimage->move(public_path($path), $image);
                $data->image = $image;
            }

            if ($data->save()) {
                DB::commit();
                return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder")]);
            } else {
                return view("$this->prefix.alert.error", ['url' => url("$this->segment/$this->folder")]);
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
            $item = Category1::find($request->id);
            $item->status = $request->status;
            $item->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
}
