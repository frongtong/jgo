<?php

namespace App\Http\Controllers\Webpanel\Administrator;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Functions\MenuControl;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Webpanel\LogsController;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\DB;

use App\Models\Authuse\Admin;
use App\Models\Backend\RoleModel;

class UserController extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $controller = 'user';
    protected $folder_controller = 'administrator.user';
    protected $folder = 'administrator/user';
    protected $name_page = "รายการผู้ดูแล";

    public function imageSize($find = null)
    {
        $arr = [
            'cover' => [
                'sm' => ['x' => 34, 'y' => 34],
                'md' => ['x' => 2505, 'y' => 1305],
            ],
        ];
        if ($find == null) {
            return $arr;
        } else {
            switch ($find) {
                case 'cover':
                    return $arr['cover'];
                    break;
                case 'gallery':
                    return $arr['gallery'];
                    break;
                default:
                    return [];
                    break;
            }
        }
    }

    public function items($parameters)
    {
        $keyword = Arr::get($parameters, 'keyword');
        $isActive = Arr::get($parameters, 'status');
        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = Admin::with('Role');
        if ($keyword) {
            $query = $query->where('name', 'LIKE', '%' . trim($keyword) . '%');
        }
        if ($isActive) {
            $query = $query->where('isActive',$isActive);
        }
        // $query = $query->orderBy('sort', $sort);
        $query = $query->where('id','>', 1);
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
            '0' => ['url' => "javascript:void(0)", 'name' => "Administrator", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "User", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder_controller.index", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'navs' => $navs,
            'items' => $items,
        ]);
    }

    public function add(Request $request)
    {

        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "Administrator", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "User", "last" => 0],
            '2' => ['url' => "$this->segment/$this->folder/add", 'name' => "Add", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder_controller.add", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'navs' => $navs,
            'roles' => RoleModel::where(['isActive' => 'Y'])->get(),
        ]);
    }

    public function edit(Request $request, $id)
    {
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "Administrator", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "User", "last" => 0],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "Edit", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder_controller.edit", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'navs' => $navs,
            'roles' => RoleModel::where(['isActive' => 'Y'])->get(),
            'row' => Admin::find($id),
        ]);
    }

    public function view(Request $request, $id)
    {
        return view("$this->prefix.pages.$this->folder_controller.view", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'roles' => RoleModel::where(['isActive' => 'Y'])->get(),
            'row' => Admin::find($id),
        ]);
    }


    public function status($id = null)
    {
        $data = Admin::find($id);
        $data->status = ($data->status == 'inactive') ? 'active' : 'inactive';
        if ($data->save()) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function destroy(Request $request)
    {
        if ($request->id == 1) {
            return response()->json(false);
        } else {
            $datas = Admin::find(explode(',', $request->id));
            if (@$datas) {
                foreach ($datas as $data) {
                    $query = Admin::destroy($data->id);
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
            $check = $this->check_user($request, $id, $request->email);
            if ($check != "yes") {
                return view("$this->prefix.alert.alert", [
                    'url' => "$this->segment/$this->folder",
                    'title' => "เกิดข้อผิดพลาด",
                    'text' => "$check->text ",
                ]);
                return false;
            }
            if ($id == null) {
                $data = new Admin();
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
                $data->password = bcrypt($request->password);
            } else {
                $data = Admin::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
                if ($request->resetpassword == "on") {
                    $data->password = bcrypt($request->password);
                }
            }
            $data->role = $request->role;
            $data->isActive = $request->isActive;
            $data->name = $request->name;
            $data->email = $request->email;
            $data->phone = $request->phone;
 

            if ($data->save()) {
                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Account saved successfully!',
                    'desc' => 'The Account has been saved successfully.',
                ]);
            } else {
                throw new \Exception("Failed to save the role.");
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

    public function check_user(Request $request, $id = null, $email)
    {
        if ($id == null) {
            $check = Admin::where('email', $email)->first();
            if ($check) {
                return view("$this->prefix.alert.alert", [
                    'url' => "$this->segment/$this->folder",
                    'title' => "เกิดข้อผิดพลาด",
                    'text' => "อีเมล์นี้มีอยู่ในระบบ ",
                ]);
                $check_true = "no";
            } else {
                $check_true = "yes";
            }
        } else {
            $check = Admin::where('email', $email)->where('id', '!=', $id)->first();
            if ($check) {
                return view("$this->prefix.alert.alert", [
                    'url' => "$this->segment/$this->folder",
                    'title' => "เกิดข้อผิดพลาด",
                    'text' => "อีเมล์นี้มีอยู่ในระบบ ",
                ]);
                $check_true = "no";
            } else {
                $check_true = "yes";
            }
        }
        return $check_true;
    }
}
