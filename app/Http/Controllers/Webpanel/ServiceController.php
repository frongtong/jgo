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
use App\Models\Backend\Service;

class ServiceController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'service';
    public function items($parameters)
    {
        $keyword = Arr::get($parameters, 'keyword');
        $status = Arr::get($parameters, 'status');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new Service;
        if ($keyword) {
            $query = $query->where('title_th', 'LIKE', '%' . trim($keyword) . '%');
            $query = $query->orWhere('title_en', 'LIKE', '%' . trim($keyword) . '%');
        }
        if ($status) {
            $query = $query->where('status',$status);
        }
        $query = $query->orderBy('sort', 'asc');
        $results = $query->paginate($paginate);
        return $results;
    }
    public function upload(Request $request)
    {

        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = 'upload/CKEDITOR/' . $fileName;
            $file->move(public_path('upload/CKEDITOR'), $fileName);
            $url = asset('upload/CKEDITOR/' . $fileName);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }

    public function index(Request $request)
    {
        $items = $this->items($request->all());
        $items->pages = new \stdClass();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();

        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "บริการของเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เนื้อหา", "last" => 1],
        ];
        return view("$this->prefix.pages.service.index", [
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
            '0' => ['url' => "javascript:void(0)", 'name' => "บริการของเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เนื้อหา", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/add", 'name' => "Add", "last" => 2],
        ];

        return view('back-end/pages/service/add', [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs
        ]);
    }

    public function edit(Request $request, $id)
    {
        $data = Service::find($id);
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "บริการของเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เนื้อหา", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.service.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'data' => $data
        ]);
    }
    public function destroy(Request $request)
    {

        if ($request->id == null) {
            return response()->json(false);
        } else {
            $datas = Service::find(explode(',', $request->id));
            if (@$datas) {
                foreach ($datas as $data) {
                    Storage::disk('public')->delete($data->banner);
                    Service::where('sort', '>', $data->sort)
                        ->update([
                            'sort' => DB::raw('`sort` - 1'),
                        ]);
                    $query = Service::destroy($data->id);
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
                $data = new Service();
                $data->created_at = now();
            } else {
                $data = Service::find($id);
            }
            $data->updated_at = now();
            $data->title_th = $request->title_th;
            $data->title_en = $request->title_en;
            $data->content_th = $request->content_th;
            $data->content_en = $request->content_en;

            $path = "upload/service";
            if ($request->file('banner')) {
                $fileimage = $request->file('banner');
                if ($data->banner) {
                    $oldImagePath = public_path($path . '/' . $data->banner);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $image = 'banner-' . time() . '.' . $fileimage->getClientOriginalExtension();
                $fileimage->move(public_path($path), $image);
                $data->banner = $path .'/'. $image;
            }

            $data->save();

            DB::commit();
            return view("$this->prefix.alert.success", ['url' => url("$this->segment/service")]);
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
            $item = Service::find($request->id);
            $item->status = $request->status;
            $item->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
}
