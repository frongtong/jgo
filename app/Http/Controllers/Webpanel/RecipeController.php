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
use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Backend\RecipecategoryModel;
use App\Models\Backend\RecipeModel;
use App\Models\Backend\RecipesdetailModel;
use App\Models\Backend\News_new_image;
use App\Models\Backend\Product;
use App\Models\Backend\RecipeproductModel;
use App\Models\Backend\RecipeurlModel;
class RecipeController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'recipe';
    public function items($parameters)
    {
        $search = Arr::get($parameters, 'search');
        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new RecipeModel;
        if ($search) {
            $query = $query->where('title_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->where('title_en', 'LIKE', '%' . trim($search) . '%');
        }
        // $query = $query->orderBy('sort', $sort);
        $query = $query->orderBy('id', 'desc');
        $results = $query->paginate($paginate);
        return $results;
    }

    public function index(Request $request)
    {
        $items = $this->items($request->all());
        $items->pages = new RecipeModel();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();

        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "สูตรอาหาร", "last" => 0],
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

        $category = RecipecategoryModel::all();
        $Product = Product::all();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "สูตรอาหาร", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เนื้อหา", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/add", 'name' => "Add", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.add", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'category' => $category,
            'Product' => $Product
        ]);
    }

    public function edit(Request $request, $id)
    {
        $data = RecipeModel::find($id);
        $category = RecipecategoryModel::all();
        $detail = RecipesdetailModel::where('recipe_id',$id)->get();
        $Recipeproduct = RecipeproductModel::where('recipe_id',$id)->get();
        $url = RecipeurlModel::where('recipe_id',$id)->get();
        $Product = Product::all();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "สูตรอาหาร", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เนื้อหา", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'row' => $data,
            'category' => $category,
            'detail' => $detail,
            'url' => $url,
            'Recipeproduct' => $Recipeproduct,
            'Product' => $Product,
            'id' => $id
        ]);
    }
   
    public function destroy(Request $request)
    {

        if ($request->id == null) {
            return response()->json(false);
        } else {
            $datas = RecipeModel::find(explode(',', $request->id));
            if (@$datas) {
                foreach ($datas as $data) {
                    $query = RecipeModel::destroy($data->id);
                 
                } 
            }

            if (@$query) {
                return response()->json(true);
            } else {
                return response()->json(false);
            }
        }
    }
    public function destroy_file(Request $request)
    {

        if ($request->id == null) {
            return response()->json(false);
        }

        $ids = explode(',', $request->id);
        $files = News_new_image::whereIn('id', $ids)->get();
        $path = "upload/newsnew";

        if ($files->isEmpty()) {
            return response()->json(false);
        }

        $success = true;
        foreach ($files as $file) {
            $filePath = public_path($path . '/' . $file->path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $deleted = News_new_image::destroy($file->id);
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


            $allowedFileTypesVDO = ['jpg', 'jpeg', 'png', 'gif'];
            $allowedFileTypes = ['jpg', 'jpeg', 'png'];

            if ($id == null) {
                $data = new RecipeModel();
                $data->created_at = now();
            } else {
                $data = RecipeModel::find($id);
            }
            $data->updated_at = now();
            $data->title_th = $request->title_th_;
            $data->title_en = $request->title_en_;
            $data->description_th = $request->description_th;
            $data->description_en = $request->description_en;
            $data->status = '1';
            $data->category = $request->category;

            $path = "upload/recipe";
            if ($request->file('banner')) {
                $filecover = $request->file('banner');
                $image = 'banner-' . time() . '.' . $filecover->getClientOriginalExtension();
                $filecover->move(public_path($path), $image);
                $data->banner_image = $path . '/' . $image;
            }
            if ($request->media_type == 'image' || $request->media_type == 'video' || $request->media_type == 'youtube') {
                // Delete existing files if the type has changed or new files are uploaded
                if (!is_null($id) && $data->type_banner !== $request->media_type) {
                    // Handle deleting old files if type has changed
                    if ($data->video && $request->media_type != 'video') {
                        $oldVideoPath = public_path($path . '/' . $data->video);
                        if (file_exists($oldVideoPath)) {
                            // unlink($oldVideoPath);
                            $data->video = null;
                        }
                    }
                    if ($data->cover && $request->media_type != $data->type_banner) {
                        $oldCoverPath = public_path($path . '/' . $data->cover);
                        if (file_exists($oldCoverPath)) {
                            // unlink($oldCoverPath);
                            $data->cover = null;
                        }
                    }
                }

                if ($request->media_type == 'video') {
                    $fileimage = $request->file('video');
                    $data->type_banner = 'video';
                    if ($request->file('cover-video')) {
                        $filecover = $request->file('cover-video');
                        $image = 'cover_image-' . time() . '.' . $filecover->getClientOriginalExtension();
                        $filecover->move(public_path($path), $image);
                        $data->cover = $path . '/' . $image;
                    }
                    if ($request->file('video')) {
                        $image1 = 'video-' . time() . '.' . $fileimage->getClientOriginalExtension();
                        $fileimage->move(public_path($path), $image1);
                        $data->video = $path . '/' . $image1;
                    }
                } else if ($request->media_type == 'image') {
                    if ($request->file('image')) {
                        $data->type_banner = 'image';
                        $fileimage = $request->file('image');
                        $image = 'video-' . time() . '.' . $fileimage->getClientOriginalExtension();
                        $fileimage->move(public_path($path), $image);
                        $data->video = $path . '/' . $image;  // Consider renaming this field if it will also store images
                        $data->cover = null;  // Assuming no cover image for simple images
                    }
                } else if ($request->media_type == 'youtube') {
                    $data->type_banner = 'youtube';
                    $data->video = $request->youtube_url;
                    if ($request->file('cover-youtube')) {
                        $filecover = $request->file('cover-youtube');
                        $image = 'cover_image-' . time() . '.' . $filecover->getClientOriginalExtension();
                        $filecover->move(public_path($path), $image);
                        $data->cover = $path . '/' . $image;
                    }
                }
            }
            $data->save();

            $fileIds = $request->input('id_details', []);
            $head_ths = $request->input('title_th', []);
            $detail_ths = $request->input('details_th', []);
            $head_ens = $request->input('title_en', []);
            $detail_ens = $request->input('details_en', []);
            $image_details = $request->file('image_details', []);

           
            foreach ($head_ths as $index => $head_th) {
                $fileId = $fileIds[$index] ?? null;
                $detail_th = $detail_ths[$index] ?? null;
                $head_en = $head_ens[$index] ?? null;
                $detail_en = $detail_ens[$index] ?? null;
                $image_detail = $image_details[$index] ?? null;

                if ($fileId) {
                    $fileRecord = RecipesdetailModel::find($fileId);
                    if ($fileRecord) {
                        
                        $fileRecord->title_th           = $head_th;
                        $fileRecord->description_th     = $detail_th;
                        $fileRecord->title_en           = $head_en;
                        $fileRecord->description_en     = $detail_en;

                        if ($image_detail && $image_detail instanceof \Illuminate\Http\UploadedFile) {
                       

                            $uniqueFileName = "image_details-" . time() . '-' . $index . '.' . $image_detail->getClientOriginalExtension();
                            $image_detail->move(public_path($path), $uniqueFileName);

                            $fileRecord->banner = $path . '/' . $uniqueFileName;
                        }

                        $fileRecord->save();
                    }
                } else {
                    
                    if ($image_detail && $image_detail instanceof \Illuminate\Http\UploadedFile) {
                        
                        $newFileRecord = new RecipesdetailModel();
                        $newFileRecord->recipe_id          = $data->id;
                        $newFileRecord->title_th           = $head_th;
                        $newFileRecord->description_th     = $detail_th;
                        $newFileRecord->title_en           = $head_en;
                        $newFileRecord->description_en     = $detail_en;
                        $uniqueFileName = "image_details-" . time() . '-' . $index . '.' . $image_detail->getClientOriginalExtension();
                        $image_detail->move(public_path($path), $uniqueFileName);

                        $newFileRecord->banner	 = $path . '/' . $uniqueFileName;
                     
                        $newFileRecord->save();
                    }
                }
            }
            $ProductIds = $request->input('id_Product', []);
            $Products = $request->input('Product', []);
            if(!empty($Products)){
                foreach ($Products as $index_Products => $Products) {
                    $ProductId = $ProductIds[$index_Products] ?? null;
                

                    if ($ProductId) {
                        $Recipeproduct = RecipeproductModel::find($ProductId);
                        if ($Recipeproduct) {
                            $Recipeproduct->product_id = $Products;
                            $Recipeproduct->save();
                        }
                    } else {
                        if (!empty($Products)) { // ตรวจสอบว่ามีค่าจริง ๆ ก่อนทำงาน
                            $newRecipeproduct = new RecipeproductModel();
                            $newRecipeproduct->recipe_id  = $data->id;
                            $newRecipeproduct->product_id = $Products;
                            $newRecipeproduct->save();
                        }
                    }
                }
            }
            $UrlIds = $request->input('id_url', []);
            $Urls = $request->input('url', []);
            if(!empty($Urls)){
                foreach ($Urls as $index => $Url) {
                    $UrlId = $UrlIds[$index] ?? null;
                
                    if (!empty($Url)) { // ตรวจสอบว่ามี URL จริง ๆ ก่อนทำงาน
                        if ($UrlId) {
                            $RecipeUrl = RecipeurlModel::find($UrlId);
                            if ($RecipeUrl) {
                                $RecipeUrl->url = $Url; // เปลี่ยนจาก `Url` เป็น `url` เพื่อให้ตรงกับฟิลด์ในฐานข้อมูล
                                $RecipeUrl->save();
                            }
                        } else {
                            $newRecipeUrl = new RecipeurlModel();
                            $newRecipeUrl->recipe_id = $data->id;
                            $newRecipeUrl->url = $Url;
                            $newRecipeUrl->save();
                        }
                    }
                }
            }
            

           

            DB::commit();

            return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder")]);
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();

            // Log the error for debugging
            $error_log = $e->getMessage();
            $error_line = $e->getLine();
            $type_log = 'backend';
            $error_url = url()->current();
            LogsController::logInsert($error_line, $error_url, $error_log, $type_log);

            return view("$this->prefix.alert.alert", [
                'url' => $error_url,
                'title' => "ไม่สามารถทำรายการได้....",
                'text' => "$e !",
                'icon' => 'error'
            ]);
        }
    }
    public function updateStatus(Request $request)
    {
        try {
            $item = RecipeModel::find($request->id);
            $item->status = $request->status;
            $item->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
    public function destroy_ref(Request $request)
    {
        $deleted = RecipeurlModel::destroy($request->id);
        return response()->json('true');
    }
    public function destroy_pro(Request $request)
    {
        $deleted = RecipeproductModel::destroy($request->id);
        return response()->json('true');
    }
    public function destroy_detail(Request $request)
    {
        $deleted = RecipesdetailModel::destroy($request->id);
        return response()->json('true');
    }
    public function upload(Request $request)
    {
        // Validate the file
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // max file size is 2MB
        ]);

        // Store the file in the 'uploads' directory (or specify your own directory)
        $path = $request->file('file')->store('uploads', 'public');

        // Return response
        return response()->json([
            'success' => true,
            'file_path' => $path,
            'message' => 'File uploaded successfully!'
        ]);
    }
}
