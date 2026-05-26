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

class ManageraboutsController extends Controller
{
   protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'managerabouts';
    public function items($parameters)
    {
        $search = Arr::get($parameters, 'keyword');
        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new Manager_aboutsModel;
        if ($search) {
            $query = $query->where('name_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->orwhere('name_en', 'LIKE', '%' . trim($search) . '%');
        }
        // $query = $query->orderBy('sort', $sort);
        $query = $query->orderBy('sort', 'asc');
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
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "ผู้บริหาร", "last" => 1],
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
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "ผู้บริหาร", "last" => 1],
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
        $data = Manager_aboutsModel::find($id);
      
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "เกี่ยวกับเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "ผู้บริหาร", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'row' => $data
           
        ]);
    }
    public function destroy(Request $request)
    {

        if ($request->id == null) {
            return response()->json(false);
        } else {
            $datas = Manager_aboutsModel::find(explode(',', $request->id));
            if (@$datas) {
                foreach ($datas as $data) {
                    $query = Manager_aboutsModel::destroy($data->id);
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
                $sort = Manager_aboutsModel::max('sort') + 1;
                $data = new Manager_aboutsModel();
                $data->sort = $sort;
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
            } else {
                $data = Manager_aboutsModel::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
            }

            $data->name_th      = $request->name_th;
            $data->name_en      = $request->name_en;
            $data->position_th  = $request->position_th;
            $data->position_en  = $request->position_en;


            $path = 'upload/aboutus/second';
            if ($request->file("img")) {
                $file_img = $request->file("img");
                // if ($data->img) {
                //     $oldImage = public_path($data->img);
                //     if (file_exists($oldImage)) {
                //         unlink($oldImage);
                //     }
                // }
                $img_name = $path . '/manager' . '-' . time() . '.' . $file_img->getClientOriginalExtension();
                $save_img = $file_img->move(public_path($path), $img_name);
                $data->image = $img_name;
            }
            $data->save();
           

            

            DB::commit();
            return view("$this->prefix.alert.success", ['url' => url("webpanel/aboutus/manager/")]);
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
       public function updateSortOrder(Request $request)
    {
        $order = $request->order;

        foreach ($order as $item) {
            Manager_aboutsModel::where('id', $item['id'])->update(['sort' => $item['sort']]);
        }

        return response()->json(['success' => true]);
    }
}
