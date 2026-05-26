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
use App\Models\Backend\Aboutus;
use App\Models\Backend\LogoAboutus;
use App\Models\Backend\Manager_aboutsModel;

use Intervention\Image\ImageManagerStatic as Image;


class AboutusController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'aboutus';
    public function items($parameters)
    {
        $search = Arr::get($parameters, 'keyword');
        $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = Aboutus::where('type','=','1');
        if ($search) {
            $query = $query->where('head_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->orwhere('head_en', 'LIKE', '%' . trim($search) . '%');
        }
        $query = $query->orderBy('sort', $sort);
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
            '0' => ['url' => "javascript:void(0)", 'name' => "เกี่ยวกับเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เนื้อหา", "last" => 1],
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
            '0' => ['url' => "javascript:void(0)", 'name' => "เกี่ยวกับเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เนื้อหา", "last" => 1],
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
        $data = Aboutus::find($id);
        $logos = LogoAboutus::where('id_aboutus', $data->id)->get();

        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "เกี่ยวกับเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เนื้อหา", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'row' => $data,
            'logos' => $logos
        ]);
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
                $data = new Aboutus();
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
            } else {
                $data = Aboutus::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
            }
            $data->type = "1";
            $data->head_en = $request->head_en;
            $data->head_th = $request->head_th;

           
            $data->th_1 = $request->th_1;
            $data->en_1 = $request->en_1;

            
            $path = 'upload/aboutus/second';
            if ($request->file("img")) {
                $file_img = $request->file("img");
                // if ($data->img) {
                //     $oldImage = public_path($data->img);
                //     if (file_exists($oldImage)) {
                //         unlink($oldImage);
                //     }
                // }
                $img_name = $path . '/profile' . '-' . time() . '.' . $file_img->getClientOriginalExtension();
                $save_img = $file_img->move(public_path($path), $img_name);
                $data->img = $img_name;
            }
            $data->save();
    

            DB::commit();
            return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder")]);
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
    public function destroy(Request $request)
    {

        if ($request->id == null) {
            return response()->json(false);
        } else {
            $datas = Aboutus::find(explode(',', $request->id));
            if (@$datas) {
                foreach ($datas as $data) {
                    $query = Aboutus::destroy($data->id);
                }
            }

            if (@$query) {
                return response()->json(true);
            } else {
                return response()->json(false);
            }
        }
    }
     public function updateStatus(Request $request)
    {
        try {
            $item = Aboutus::find($request->id);
            $item->status = $request->status;
            $item->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
     public function updateSortOrder(Request $request)
    {
        $order = $request->order;

        foreach ($order as $item) {
            Aboutus::where('id', $item['id'])->update(['sort' => $item['sort']]);
        }

        return response()->json(['success' => true]);
    }

}
