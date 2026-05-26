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
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Backend\Home;

class HomeController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'home';
    public function updateRowOrder(Request $request, $id = null)
    {
        $id = $request->input('id');
        $old_sort = (int) $request->input('old_sort');
        $new_sort = (int) $request->input('new_sort');

        $itemToMove = Home::find($id);

        if ($old_sort > $new_sort) {
            Home::where('sort', '>=', $new_sort)
                ->where('sort', '<', $old_sort)
                ->update([
                    'sort' => DB::raw('`sort` + 1'),
                ]);
        } else {
            Home::where('sort', '<=', $new_sort)
                ->where('sort', '>', $old_sort)
                ->update([
                    'sort' => DB::raw('`sort` - 1'),
                ]);
        }
        $itemToMove->sort = $new_sort;
        $itemToMove->save();
        return response()->json(['success' => true]);
    }

    public function items($parameters)
    {
        
        $status = Arr::get($parameters, 'status');
        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new Home;
        
        if ($status) {
            $query = $query->where('status',$status);
        }
        // $query = $query->orderBy('sort', $sort);
        $query = $query->orderby('sort', 'asc');
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
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "แบนเนอร์หลัก", "last" => 1],
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

        $category = Home::all();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "หน้าหลัก", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "แบนเนอร์หลัก", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/add", 'name' => "Add", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.add", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'category' => $category
        ]);
    }

    public function edit(Request $request, $id)
    {
        $data = Home::find($id);
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "หน้าหลัก", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "แบนเนอร์หลัก", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'data' => $data,
        ]);
    }
    public function destroy(Request $request)
    {

        if ($request->id == null) {
            return response()->json(false);
        } else {
            $datas = Home::find(explode(',', $request->id));

            if (@$datas) {
                foreach ($datas as $data) {
                    Storage::disk('public')->delete($data->img_bg);
                    Storage::disk('public')->delete($data->video_bg);
                    Home::where('sort', '>', $data->sort)
                        ->update([
                            'sort' => DB::raw('`sort` - 1'),
                        ]);
                    $query = Home::destroy($data->id);
                }
            }


            if (@$query) {
                return response()->json(true);
            } else {
                return response()->json(false);
            }
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
                $sort = Home::max('sort') + 1;
                $data = new Home();

                $data->sort = $sort;
                $data->created_at = now();
            } else {
                $data = Home::find($id);
            }
            $data->updated_at = now();

            $data->link = $request->link;
          

            $path = "upload/Home";

            if ($request->file('img_bg')) {
                $img_bg = $request->file('img_bg');
                if ($data->img_bg) {
                    $oldimg_bg = public_path($data->img_bg);
                    if (file_exists($oldimg_bg)) {
                        unlink($oldimg_bg);
                    }
                }
                // ลบไฟล์ video ถ้าสลับจาก video มาเป็น image
                if ($data->video_bg) {
                    $oldvideo_bg = public_path($data->video_bg);
                    if (file_exists($oldvideo_bg)) {
                        unlink($oldvideo_bg);
                    }
                }
                $name_img_bg = $path . '/img_bg-' . time() . '.' . $img_bg->getClientOriginalExtension();
                $img_bg->move(public_path($path), $name_img_bg);
                $data->img_bg = $name_img_bg;
            }

            if ($request->file('video_bg')) {
                $video_bg = $request->file('video_bg');
                if ($data->video_bg) {
                    $oldvideo_bg = public_path($data->video_bg);
                    if (file_exists($oldvideo_bg)) {
                        unlink($oldvideo_bg);
                    }
                }
                // ลบไฟล์ image ถ้าสลับจาก image มาเป็น video
                if ($data->img_bg) {
                    $oldimg_bg = public_path($data->img_bg);
                    if (file_exists($oldimg_bg)) {
                        unlink($oldimg_bg);
                    }
                }
                $name_video_bg = $path . '/video_bg-' . time() . '.' . $video_bg->getClientOriginalExtension();
                $video_bg->move(public_path($path), $name_video_bg);
                $data->video_bg = $name_video_bg;
            }
            
            $data->save();
            DB::commit();
            return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder")]);
        } catch (\Exception $e) {
            DB::rollback();

            // Log the error for debugging
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
            $item = Home::find($request->id);
            $item->status = $request->status;
            $item->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }

    // Sort หลิว
    public function updateSortOrder(Request $request)
    {
        $order = $request->order;

        foreach ($order as $item) {
            Home::where('id', $item['id'])->update(['sort' => $item['sort']]);
        }

        return response()->json(['success' => true]);
    }
}
