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
use App\Models\Backend\BannerSub;

class BannerSubController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'bannersub';

    public function items($parameters)
    {
        $search = Arr::get($parameters, 'search');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new BannerSub;
        if ($search) {
            $query = $query->where('name_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->where('name_en', 'LIKE', '%' . trim($search) . '%');
        }
        $query = $query->orderby('id', 'asc');
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
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "แบนเนอร์รอง", "last" => 1],
        ];
        return view("$this->prefix.pages.bannersub.index", [
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
            '0' => ['url' => "javascript:void(0)", 'name' => "หน้าหลัก", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "แบนเนอร์รอง", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/add", 'name' => "Add", "last" => 2],
        ];

        return view("$this->prefix.pages.bannersub.add", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs
        ]);
    }

    public function edit(Request $request, $id)
    {
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "หน้าหลัก", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "แบนเนอร์รอง", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.bannersub.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'data' => BannerSub::find($id)
        ]);
    }

    public function destroy(Request $request)
    {
        if ($request->id == null) {
            return response()->json(false);
        } else {
            $datas = BannerSub::find(explode(',', $request->id));
            if (@$datas) {
                foreach ($datas as $data) {
                    Storage::disk('public')->delete($data->image);
                    $query = BannerSub::destroy($data->id);
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
                $data = new BannerSub();
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
            } else {
                $data = BannerSub::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
            }

            $data->name = $request->name;
            $path = 'upload/bannersub';
            if ($request->file('image')) {
                $file = $request->file('image');
                if ($data->image) {
                    $oldImagePath = public_path($data->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $name = $path . '/bannersub-img-' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path($path), $name);
                $data->image = $name;
            }
            $data->save();
            DB::commit();
            return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder")]);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
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
            $item = BannerSub::find($request->id);
            $item->status = $request->status;
            $item->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
}
