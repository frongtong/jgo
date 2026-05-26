<?php

namespace App\Http\Controllers\Webpanel;

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

class ProfileController extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $controller = 'profile';
    protected $folder_controller = 'profile';
    protected $folder = 'profile';
    protected $name_page = "ข้อมูลส่วนตัว";

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

    public function edit(Request $request)
    {
        return view("$this->prefix.pages.$this->folder_controller.edit", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'row' => Admin::find(Auth::guard('admin')->id()),
        ]);
    }

    //==== Function Insert Update Delete Status Sort & Others ====
    public function update(Request $request)
    {
        return $this->store($request);
    }
    public function store($request)
    {
        try {
            DB::beginTransaction();
            $id = Auth::guard('admin')->id();
            $data = Admin::find($id);
            $data->updated_at = date('Y-m-d H:i:s');
            if($request->resetpassword == "on")
            {
                $data->password = bcrypt($request->password);
            }
            $data->name_th = $request->name_th;
            $data->name_en = $request->name_en;
            // $data->email = $request->email;
            $data->title = $request->title;
            $data->idcard = $request->idcard;
            $data->birthdate = $request->birthdate;
            $data->phone = $request->phone;

            // Image upload
            $filename = 'admin_' . date('dmY-His');
            $file = $request->image;
            if ($file)
            {
                $lg = Image::make($file->getRealPath());
                $ext = explode("/", $lg->mime())[1];
                $size = $this->imageSize();
                $lg->resize($size['cover']['md']['x'], $size['cover']['md']['y'])->stream();
                $newLG = 'upload/admin/' . $filename . '.' . $ext;
                $store = Storage::disk('public')->put($newLG, $lg);
                if($store)
                {
                    $data->image = $newLG;
                }
            }

            if ($data->save()) {
                DB::commit();
                return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder")]);
            } else {
                return view("$this->prefix.alert.error", ['url' => url("$this->segment/$this->folder")]);
            }
        } catch (\Exception $e) {
            $error_log = $e->getMessage();
            $error_line = $e->getLine();
            $type_log = 'backend';
            $error_url = url()->current();
            LogsController::logInsert($error_line,$error_url,$error_log,$type_log);
            return view("$this->prefix.alert.alert", [
                'url' => $error_url,
                'title' => "ไม่สามารถทำรายการได้",
                'text' => "กรุณาทำรายการใหม่อีกครั้ง !",
                'icon' => 'error'
            ]);
        }
    }
}
