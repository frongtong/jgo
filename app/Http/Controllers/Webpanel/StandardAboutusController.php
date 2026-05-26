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

class StandardAboutusController extends Controller

{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'aboutus/standard';
    public function items($parameters)
    {
        $search = Arr::get($parameters, 'keyword');
        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = Aboutus::where('type','=','2');
        if ($search) {
            $query = $query->where('head_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->orwhere('head_en', 'LIKE', '%' . trim($search) . '%');
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
            '0' => ['url' => "javascript:void(0)", 'name' => "เกี่ยวกับเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "มาตรฐาน และการรับรอง", "last" => 1],
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
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "มาตรฐาน และการรับรอง", "last" => 1],
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
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "มาตรฐาน และการรับรอง", "last" => 1],
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
    public function destroy_logo(Request $request)
    {

        if ($request->id == null) {
            return response()->json(false);
        }

        $ids = explode(',', $request->id);
        $files = LogoAboutus::whereIn('id', $ids)->get();

        if ($files->isEmpty()) {
            return response()->json(false);
        }

        $success = true;
        foreach ($files as $file) {
            $filePath = public_path($file->logo);
            // if (file_exists($filePath)) {
            //     unlink($filePath);
            // }

            $deleted = LogoAboutus::destroy($file->id);
            if (!$deleted) {
                $success = false;
            }
        }

        return response()->json($success);
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

            $data->head_en = $request->head_en;
            $data->head_th = $request->head_th;

            $data->head_th_1 = $request->head_th_1;
            $data->th_1 = $request->th_1;
            $data->head_en_1 = $request->head_en_1;
            $data->en_1 = $request->en_1;
            $data->type = "2";
            $path = 'upload/aboutus/second';
            if ($request->file("img")) {
                $file_img = $request->file("img");
                // if ($data->img) {
                //     $oldImage = public_path($data->img);
                //     if (file_exists($oldImage)) {
                //         unlink($oldImage);
                //     }
                // }
                $img_name = $path . '/standard' . '-' . time() . '.' . $file_img->getClientOriginalExtension();
                $save_img = $file_img->move(public_path($path), $img_name);
                $data->img = $img_name;
            }
            $data->save();
            $fileIds = $request->input('id_logo', []);
            $head_logo_ths = $request->input('head_logo_th', []);
            $detail_logo_ths = $request->input('detail_logo_th', []);
            $head_logo_ens = $request->input('head_logo_en', []);
            $detail_logo_ens = $request->input('detail_logo_en', []);
            $logos = $request->file('logo', []);

            foreach ($head_logo_ths as $index => $head_logo_th) {
                $fileId = $fileIds[$index] ?? null;
                $detail_logo_th = $detail_logo_ths[$index] ?? null;
                $head_logo_en = $head_logo_ens[$index] ?? null;
                $detail_logo_en = $detail_logo_ens[$index] ?? null;
                $logo = $logos[$index] ?? null;

                if ($fileId) {
                    $fileRecord = LogoAboutus::find($fileId);
                    if ($fileRecord) {
                        // Update existing file
                        $fileRecord->head_logo_th = $head_logo_th;
                        $fileRecord->detail_logo_th = $detail_logo_th;
                        $fileRecord->head_logo_en = $head_logo_en;
                        $fileRecord->detail_logo_en = $detail_logo_en;

                        if ($logo && $logo instanceof \Illuminate\Http\UploadedFile) {
                            // Handle file upload
                            // if (file_exists(public_path($fileRecord->logo))) {
                            //     unlink(public_path($fileRecord->logo));
                            // }

                            $uniqueFileName = "logo-" . time() . '-' . $index . '.' . $logo->getClientOriginalExtension();
                            $logo->move(public_path($path), $uniqueFileName);

                            $fileRecord->logo = $path . '/' . $uniqueFileName;
                        }

                        $fileRecord->save();
                    }
                } else {
                    // Add new file
                    if ($logo && $logo instanceof \Illuminate\Http\UploadedFile) {
                        $newFileRecord = new LogoAboutus();
                        $newFileRecord->id_aboutus = $data->id;
                        $newFileRecord->head_logo_th = $head_logo_th;
                        $newFileRecord->detail_logo_th = $detail_logo_th;
                        $newFileRecord->head_logo_en = $head_logo_en;
                        $newFileRecord->detail_logo_en = $detail_logo_en;

                        $uniqueFileName = "logo-" . time() . '-' . $index . '.' . $logo->getClientOriginalExtension();
                        $logo->move(public_path($path), $uniqueFileName);

                        $newFileRecord->logo = $path . '/' . $uniqueFileName;
                        $newFileRecord->save();
                    }
                }
            }


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
}

