<?php

namespace App\Http\Controllers\Webpanel;

use App\Models\Backend\Brand_link;
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
use App\Models\Backend\Brand;
// use App\Models\Brand;

use Intervention\Image\ImageManagerStatic as Image;

class BrandController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'brand';
    public function items($parameters)
    {
        $search = Arr::get($parameters, 'search');
        $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new Brand;
        if ($search) {
            $query = $query->where('name_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->where('name_en', 'LIKE', '%' . trim($search) . '%');
        }
        $query = $query->orderBy('sort', $sort);
        // $query = $query->orderBy('id', 'desc');
        $results = $query->paginate($paginate);
        return $results;
    }

    public function index(Request $request)
    {
        $items = $this->items($request->all());
        $items->pages = new Brand();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();

        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "Administrator", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "แบรนด์", "last" => 1],
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
            '0' => ['url' => "javascript:void(0)", 'name' => "Administrator", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "แบรนด์", "last" => 1],
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
        $data = Brand::with('brand_link')->find($id);
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "Administrator", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เกี่ยวกับเรา", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'data' => $data
        ]);
    }
    public function destroy(Request $request)
    {
        
            $datas = Brand::find(explode(',', $request->id));
            if (@$datas) {
                foreach ($datas as $data) {
                    $query = Brand::destroy($data->id);
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
            // dd($request->all());
            if ($id == null) {
                $data = new Brand();
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
            } else {
                $data = Brand::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
            }
            $data->name_en = $request->name_en;
            $data->name_th = $request->name_th;
            $data->color   = $request->color;
            $data->description_en = $request->description_en;
            $data->description_th = $request->description_th;
            $path = "upload/Brand";

            if ($fileimage = $request->file('logo')) {
                if ($data->logo) {
                    $oldImagePath = public_path($path . '/' . $data->logo);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $image = 'logo-' . time() . '.' . $fileimage->getClientOriginalExtension();
                $fileimage->move(public_path($path), $image);
                $data->logo_image = $image;
            }
            if ($fileimage = $request->file('example')) {
                if ($data->example) {
                    $oldImagePath = public_path($path . '/' . $data->example);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $image = 'example-' . time() . '.' . $fileimage->getClientOriginalExtension();
                $fileimage->move(public_path($path), $image);
                $data->example_image = $image;
            }
            $data->save();
            if ($request->urls && $request->hasFile('images')) {
                $files = $request->file("images");
                $ids = $request->id_link; // Retrieve the id_link array from the request
            
                foreach ($files as $index => $file) {
                    $fileId = $ids[$index] ?? null; // Get the corresponding id_link or null if not present
            
                    $imgName = $path . '/link-img-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path($path), $imgName);
            
                    if ($fileId) {
                        // Update existing record
                        $Brand_link = Brand_link::find($fileId);
                        if ($Brand_link) {
                            $Brand_link->url = $request->urls[$index];
                            $Brand_link->image = $imgName;
                            $Brand_link->save();
                        }
                    } else {
                        // Create a new record
                        $Brand_link = new Brand_link();
                        $Brand_link->created_at = date('Y-m-d H:i:s');
                        $Brand_link->updated_at = date('Y-m-d H:i:s');
                        $Brand_link->url = $request->urls[$index];
                        $Brand_link->brand_id = $data->id;
                        $Brand_link->image = $imgName;
                        $Brand_link->save();
                    }
                }
            }
            if ($data->save()) {
                DB::commit();
                return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder")]);
            } else {
                return view("$this->prefix.alert.error", ['url' => url("$this->segment/$this->folder")]);
            }
        } catch (\Exception $e) {
            DB::rollback();
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
    public function destroy_url(Request $request)
    {


        $deleted = Brand_link::destroy($request->id);

        return response()->json('true');
    }
    public function updateSortOrder(Request $request)
    {
        $order = $request->order;

        foreach ($order as $item) {
            Brand::where('id', $item['id'])->update(['sort' => $item['sort']]);
        }

        return response()->json(['success' => true]);
    }
    public function updateStatus(Request $request)
    {
        try {
            $item = Brand::find($request->id);
            $item->status = $request->status;
            $item->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }

}