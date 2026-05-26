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

class AboutushomeController extends Controller
{ 
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'aboutus/home';
    public function edit(Request $request)
    {
        $data = Aboutus::where('type','=','3')->first();
      

        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "เกี่ยวกับเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "ข้อมูลหน้าHome", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/edit", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'row' => $data
        ]);
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

            $data->head_en = $request->head_en;
            $data->head_th = $request->head_th;

            $data->head_th_1 = $request->head_th_1;
            $data->th_1 = $request->th_1;
            $data->head_en_1 = $request->head_en_1;
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
                $img_name = $path . '/home' . '-' . time() . '.' . $file_img->getClientOriginalExtension();
                $save_img = $file_img->move(public_path($path), $img_name);
                $data->img = $img_name;
            }
            if ($request->file("img_1")) {
                $file_img1 = $request->file("img_1");
                // if ($data->img) {
                //     $oldImage = public_path($data->img);
                //     if (file_exists($oldImage)) {
                //         unlink($oldImage);
                //     }
                // }
                $img_name1 = $path . '/home1' . '-' . time() . '.' . $file_img1->getClientOriginalExtension();
                $save_img1 = $file_img1->move(public_path($path), $img_name1);
                $data->img_1 = $img_name1;
            }
            $data->save();
        
           


            DB::commit();
            return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder/edit")]);
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
}
