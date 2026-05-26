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
use App\Models\Backend\BannerAboutus;

class BannerAboutusController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'aboutus/banner';
    public function updateRowOrder(Request $request, $id = null)
    {
        $id = $request->input('id');
        $old_sort = (int) $request->input('old_sort');
        $new_sort = (int) $request->input('new_sort');

        $Banner_image = BannerAboutus::find($id); 
 
          

        if ($old_sort > $new_sort) {
            BannerAboutus::where('order', '>=', $new_sort)
                ->where('order', '<', $old_sort)
                ->update([
                   'order' => DB::raw('`order` + 1'), 
                ]);
        } else {
            BannerAboutus::where('order', '<=', $new_sort)
                ->where('order', '>', $old_sort)
                ->update([
                    'order' => DB::raw('`order` - 1'), 
                ]);
        }

        $Banner_image->order = $new_sort;
        $Banner_image->save(); 
       

        return response()->json(['success' => true]);
    }
    public function items($parameters)
    {
        $search = Arr::get($parameters, 'search');
        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new BannerAboutus;
        if ($search) {
            $query = $query->where('name_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->where('name_en', 'LIKE', '%' . trim($search) . '%');
        }
        // $query = $query->orderBy('sort', $sort);
        $query = $query->orderby('order', 'asc');
        $results = $query->paginate($paginate);
        return $results;
    }

    public function index(Request $request)
    {
        $items = $this->items($request->all());
        $items->pages = new \stdClass();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();

        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "เกี่ยวกับเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "Banner", "last" => 1],
        ];
        return view("$this->prefix.pages.aboutus.banner.index", [
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
            '0' => ['url' => "javascript:void(0)", 'name' => "เกี่ยวกับเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "Banner", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/add", 'name' => "Add", "last" => 2],
        ];

        return view('back-end/pages/aboutus/banner/add', [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs
        ]);
    }

    public function edit(Request $request, $id)
    {
        $data = BannerAboutus::find($id);
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "เกี่ยวกับเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เกี่ยวกับเรา", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.aboutus.banner.edit", [
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
            $datas = BannerAboutus::find(explode(',', $request->id));
            if (@$datas) {
                foreach ($datas as $data) {
                    BannerAboutus::where('order', '>', $data->order)
                    ->update([
                        'order' => DB::raw('`order` - 1'),
                    ]);
                    $query = BannerAboutus::destroy($data->id);
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
        try {
            DB::beginTransaction();
            $data = BannerAboutus::find($id);
            $path = 'upload/aboutus/main';

            if ($request->file('path')) {
                $file = $request->file('path');
                // if ($data->path) {
                //     $oldImagePath = public_path($data->path);
                //     if (file_exists($oldImagePath)) {
                //         unlink($oldImagePath);
                //     }
                // }
                $imgName = $path . '/banner-img-' . time() . '-' . $id . '.' . $file->getClientOriginalExtension();
                $file->move(public_path($path), $imgName);
                $data->path = $imgName;
                $data->status = $request->status ?? 'off';
                $data->updated_at = date('Y-m-d H:i:s');
                $data->save();
            }

            $data->order = BannerAboutus::count() + 1;
            $data->save();

            DB::commit();
            return view("$this->prefix.alert.success", ['url' => url("$this->segment/aboutus/banner")]);
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
    public function store($request, $id = null)
    {
        try {
            DB::beginTransaction();
             $path = 'upload/aboutus/main';

            // Handle file uploads
            $files = $request->file("path");
            foreach ($files as $index => $file) {
                $imgName = $path . '/banner-img-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
                $file->move(public_path($path), $imgName);

                $data = new BannerAboutus();
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
                $data->path = $imgName;
                $data->status = $request->status ?? 'off';
                $data->order = BannerAboutus::count() + 1;
                $data->save();
            }

            DB::commit();
            return view("$this->prefix.alert.success", ['url' => url("$this->segment/aboutus/banner")]);
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
    public function status(Request $request, $id = null)
    {
        try {
            DB::beginTransaction();
             $data = BannerAboutus::find($id);

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
}
