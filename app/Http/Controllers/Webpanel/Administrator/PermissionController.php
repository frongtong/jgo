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

use App\Models\Backend\RoleModel;
use App\Models\Backend\Role_listModel;

class PermissionController extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $controller = 'permission';
    protected $folder_controller = 'administrator.permission';
    protected $folder = 'administrator/permission';
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
        $query = new RoleModel;
        if ($keyword) {
            $query = $query->where('name', 'LIKE', '%' . trim($keyword) . '%');
        }
        if ($isActive) {
            $query = $query->where('isActive', $isActive);
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
            '0' => ['url' => "javascript:void(0)", 'name' => "Administrator", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "Permission", "last" => 1],
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
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "Permission", "last" => 0],
            '2' => ['url' => "$this->segment/$this->folder/add", 'name' => "Add", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder_controller.add", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'navs' => $navs,
        ]);
    }

    public function edit(Request $request, $id)
    {
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "Administrator", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "Permission", "last" => 0],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "Edit", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder_controller.edit", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'navs' => $navs,
            'row' => RoleModel::find($id),
            'roles' => RoleModel::where(['isActive' => 'Y'])->get(),
            'menus' => \App\Models\Backend\MenuModel::where(['status' => 'on', 'position' => 'main'])->get(),
        ]);
    }

    public function status($id = null)
    {
        $data = RoleModel::find($id);
        $data->status = ($data->status == 'inactive') ? 'active' : 'inactive';
        if ($data->save()) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function destroy(Request $request)
    {
        $datas = RoleModel::find(explode(',', $request->id));
        if (@$datas) {
            foreach ($datas as $data) {
                $lists = Role_listModel::where('role_id', $data->id)->delete();

                $query = RoleModel::destroy($data->id);
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
  
            $data = $id ? RoleModel::find($id) : new RoleModel();
            $data->created_at = $id ? $data->created_at : now();
            $data->updated_at = now();
            $data->isActive = $request->isActive ? 'Y' : 'N';
            $data->name = $request->name;
            $data->detail = $request->detail;
        

            if ($data->save()) { 
                if ($request->menu_id) {
                 
                    foreach ($request->menu_id as $menu_id) {
                        $roleList = Role_listModel::firstOrNew([
                            'role_id' => $id,
                            'menu_id' => $menu_id,
                        ]);

                        $roleList->read = $request->input('read_' . $menu_id) == "on" ? "on" : "off";
                        $roleList->add = $request->input('add_' . $menu_id) == "on" ? "on" : "off";
                        $roleList->edit = $request->input('edit_' . $menu_id) == "on" ? "on" : "off";
                        $roleList->delete = $request->input('delete_' . $menu_id) == "on" ? "on" : "off";
                        $roleList->save();
                    }
                }

                DB::commit();

                return response()->json([
                    'status' => 200,
                    'message' => 'Role saved successfully!',
                    'desc' => 'The role has been saved and the permissions have been updated.',
                ]);
            } else {
                throw new \Exception("Failed to save the role.");
            }
        } catch (\Exception $e) {
            DB::rollback();
            $this->logError($e);
            return view("$this->prefix.alert.alert", [
                'url' => url()->current(),
                'title' => "ไม่สามารถทำรายการได้",
                'text' => "กรุณาทำรายการใหม่อีกครั้ง !",
                'icon' => 'error'
            ]);
        }
    }


    protected function logError($e)
    {
        $error_log = $e->getMessage();
        $error_line = $e->getLine();
        $error_url = url()->current();
        $type_log = 'backend';

        LogsController::logInsert($error_line, $error_url, $error_log, $type_log);
    }


    public function checkRole() {}
}
