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
use App\Models\Backend\VideoCategory2;


use Intervention\Image\ImageManagerStatic as Image;

class VideoCategory2Controller extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'videoctegory2';
    public function getCategory2($category1_id)
    {
        $category2 = VideoCategory2::where('status','on')->where('category1_id', $category1_id)->get();
        return response()->json($VideoCategory2);
    }
    public function items($parameters)
    {
        $search = Arr::get($parameters, 'search');
        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new VideoCategory2;
        if ($search) {
            $query = $query->where('name_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->where('name_en', 'LIKE', '%' . trim($search) . '%');
        }
        // $query = $query->orderBy('sort', $sort);
        $query = $query->orderBy('id', 'desc');
        $results = $query->paginate($paginate);
        return $results;
    }

    public function index(Request $request, $category1_id)
    {

        $items = VideoCategory2::query()->where('category1_id', $category1_id)->paginate(15);
        $items->pages = new VideoCategory2();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "วีดีโอ", "last" => 0],
            '1' => ['url' => "$this->segment/videoctegory1", 'name' => "หมวดหมู่", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/$category1_id", 'name' => "หมวดหมู่รอง", "last" => 2],

        ];
        return view("$this->prefix.pages.$this->folder.index", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'items' => $items,
            'navs' => $navs,
            'category1_id' => $category1_id
        ]);
    }

    public function add(Request $request, $category1_id, $id=null)
    {
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "วีดีโอ", "last" => 0],
            '1' => ['url' => "$this->segment/videoctegory1", 'name' => "หมวดหมู่", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/$category1_id", 'name' => "หมวดหมู่รอง", "last" => 2],
            '3' => ['url' => "$this->segment/$this->folder/add/$category1_id", 'name' => "Add", "last" => 3],
        ];
        return view("$this->prefix.pages.$this->folder.add", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'category1_id' => $category1_id
        ]);
    }

    public function edit(Request $request, $category1_id, $id)
    {
        $data = VideoCategory2::find($id);
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "วีดีโอ", "last" => 0],
            '1' => ['url' => "$this->segment/videoctegory1", 'name' => "หมวดหมู่", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/$category1_id", 'name' => "หมวดหมู่รอง", "last" => 2],
            '3' => ['url' => "$this->segment/$this->folder/$category1_id/edit/$id", 'name' => "Edit", "last" => 3],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'category1_id' => $category1_id,
            'id' => $id,
            'data' => $data
        ]);
    }

    // public function destroy(Request $request)
    // {
    //     $VideoCategory2 = VideoCategory2::with('VideoCategory2_link')->whereIn('id', $request->id)->get();

    //     if ($VideoCategory2->isEmpty()) {
    //         return response()->json(false);
    //     }

    //     foreach ($VideoCategory2 as $cat) {

    //         foreach ($cat->VideoCategory2_link as $cat_image) {
    //             Storage::disk('public')->delete($cat_image->image);
    //         }

    //         $VideoCategory2->product_image()->delete();
    //         $VideoCategory2->delete();
    //     }

    //     return response()->json(true);
    // }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $VideoCategory2 = VideoCategory2::find($id);

        if ($VideoCategory2) {

          
            $VideoCategory2->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Item not found']);
    }

    //==== Function Insert Update Delete Status Sort & Others ====
    public function insert(Request $request, $category1_id, $id = null)
    {
        return $this->store($request, $category1_id, $id = null);
    }
    public function update(Request $request, $category1_id, $id)
    {
        return $this->store($request, $category1_id, $id);
    }
    public function store($request, $category1_id, $id = null)
    {
        try {

            DB::beginTransaction();
            if ($id == null) {
                $data = new VideoCategory2();
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
                $data->category1_id = $category1_id;

            } else {
                $data = VideoCategory2::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
            }
            $data->name_th = $request->name_th;
             $data->status = "on";
            if ($data->save()) {


                DB::commit();
                return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder/$category1_id")]);
            } else {
                return view("$this->prefix.alert.error", ['url' => url("$this->segment/$this->folder/$category1_id")]);
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
            $item = VideoCategory2::find($request->id);
            $item->status = $request->status;
            $item->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
}
