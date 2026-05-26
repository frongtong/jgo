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
use App\Models\Backend\News_old;
use App\Models\Backend\News_old_image;
use App\Models\Backend\News_old_url;

use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Backend\NewsCategory;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;



class NewsoldController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'news_old';
    public function items($parameters)
    {
        $search = Arr::get($parameters, 'search');
        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new news_old;
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
        $items->pages = new news_old();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();

        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "กิจกรรมที่กำลังจะมาถึง", "last" => 0],
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

        $category = NewsCategory::all();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "กิจกรรมที่กำลังจะมาถึง", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เนื้อหา", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/add", 'name' => "Add", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.add", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'category' => $category
        ]);
    }

    public function edit(Request $request, $id)
    {
        $data = news_old::find($id);
        $category = NewsCategory::all();
        $files = News_old_image::where('news_old_id', $id)->orderBy('order')->get();
        $refs = News_old_url::where('id_news_old', $id)->get();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "กิจกรรมที่กำลังจะมาถึง", "last" => 0],
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
            'files' => $files,
            'refs' => $refs,
            'id' => $id
        ]);
    }
    public function updateOrder(Request $request): mixed
    {
        // Retrieve ordered IDs from the request
        $orderedIds = $request->input('orderedIds');

        // Check if orderedIds is an array
        if (is_array($orderedIds)) {
            try {
                // Loop through each item and update the order
                foreach ($orderedIds as $item) {
                    News_old_image::where('id', $item['id'])->update(['order' => $item['order']]);
                }

                // Return a success response
                return response()->json(['success' => true, 'message' => 'Order updated successfully']);
            } catch (\Exception $e) {
                // Return an error response if something goes wrong
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
        }

        // Return a response if orderedIds is not an array or if something else is wrong
        return response()->json(['success' => false, 'error' => 'Invalid data format'], 400);
    }

    public function destroy(Request $request)
    {

        if ($request->id == null) {
            return response()->json(false);
        } else {
            $datas = news_old::find(explode(',', $request->id));
            if (@$datas) {
                foreach ($datas as $data) {
                    $query = news_old::destroy($data->id);
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
        $files = News_old_image::whereIn('id', $ids)->get();
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

            $deleted = News_old_image::destroy($file->id);
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
            // dd($request->all());
            DB::beginTransaction();
            $allowedFileTypesVDO = ['jpg', 'jpeg', 'png', 'gif'];
            $allowedFileTypes = ['jpg', 'jpeg', 'png'];

            // Validate the file type
            // $validator = Validator::make($request->all(), [
            //     'logo_image' => 'nullable|file|mimes:' . implode(',', $allowedFileTypes),
            //     'video' => 'file|mimes:' . implode(',', $allowedFileTypesVDO)
            // ]);
            // $error_url = url()->current();

            // if ($validator->fails()) {
            //     return view("$this->prefix.alert.alert", [
            //         'url' => $error_url,
            //         'title' => "ไม่สามารถทำรายการได้",
            //         'text' => "กรุณาทำรายการใหม่อีกครั้ง !",
            //         'icon' => 'error'
            //     ]);
            // }
            if ($id == null) {
                $data = new News_old();
                $data->created_at = now();
            } else {
                $data = News_old::find($id);
            }
            $data->updated_at = now();
            $data->title_th = $request->title_th;
            $data->title_en = $request->title_en;
            $data->description_th = $request->description_th;
            $data->description_en = $request->description_en;
            $data->start = $request->start;
            $data->end = $request->end;
            $data->status = '1';
            $data->news_category_id = $request->category;

            

            $path = "upload/newsnew";
            if ($request->file('logo_image')) {
                $filecover = $request->file('logo_image');
                $image = 'logo_image-' . time() . '.' . $filecover->getClientOriginalExtension();
                $filecover->move(public_path($path), $image);
                $data->logo_image = $path . '/' . $image;
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
                    if($request->file('image')){
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
            if ($request->has('path')) {
                $filePaths = $request->file('path', []);
                $fileIds = $request->input('news_old_id', []);
                if ($id != null) {
                    $maxOrder = News_old_image::where('news_old_id', $id)
                        ->max('order');
                    $x = $maxOrder + 1;
                } else {
                    $x = 1;
                }
                foreach ($filePaths as $index => $filePath) {
                    $fileId = $fileIds[$index] ?? null; // Get the file ID or null if not present
            
                    if ($fileId) {
                        // Update existing file
                        $fileRecord = News_old_image::find($fileId);
            
                        if ($fileRecord) {
                            if ($filePath && $filePath instanceof \Illuminate\Http\UploadedFile) {
                                // Generate a unique file name and move the file
                                $uniqueFileName = 'newsnew-' . time() . '-' . $index . '.' . $filePath->getClientOriginalExtension();
                                $filePath->move(public_path($path), $uniqueFileName);
            
                                // Update the file path in the database
                                $fileRecord->image = $path . '/' . $uniqueFileName;
                            }
            
                            $fileRecord->save(); // Save the updated file record
                        }
                    } else {
                        // Add new file
                        if ($filePath && $filePath instanceof \Illuminate\Http\UploadedFile) {
                            $newFileRecord = new News_old_image();
                            $newFileRecord->News_old_id = $data->id;
                            $newFileRecord->order = $x;

                            // Generate a unique file name and move the file
                            $uniqueFileName = 'newsnew-' . time() . '-' . $index . '.' . $filePath->getClientOriginalExtension();
                            $filePath->move(public_path($path), $uniqueFileName);
            
                            // Save the new file path in the database
                            $newFileRecord->image = $path . '/' . $uniqueFileName;
                            $newFileRecord->save();
                        }
                    }
                    $x++;

                }
            }

            
            $urlRef = is_array($request->url) ? $request->url : [];
            $loop_count_ref = count($urlRef);
            if($request->url){
                for ($x = 0; $x < $loop_count_ref; $x++) {
                    $refId = isset($request->id_refinnovation[$x]) ? $request->id_refinnovation[$x] : null;


                    $check_ref = News_old_url::find($refId);

                    if ($check_ref != null) {
                        $RefInnovation = $check_ref;
                    } else {
                        $RefInnovation = new News_old_url();
                    }

                    $RefInnovation->id_news_old = $data->id;
                    $RefInnovation->url = $request->url[$x];
                    $RefInnovation->text_ref = $request->text_ref[$x] ?? '';

                    $RefInnovation->save();
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
            $item = news_old::find($request->id);
            $item->status = $request->status;
            $item->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
    public function destroy_image(Request $request)
    {


            $deleted = News_old_image::destroy($request->id);

        return response()->json('true');
    }

    public function destroy_ref(Request $request)
    {

        if ($request->id == null) {
            return response()->json(false);
        }

        $ids = explode(',', $request->id);
        $refs = News_old_url::whereIn('id', $ids)->get();
        $success = true;
        foreach ($refs as $ref) {
            $deleted = News_old_url::destroy($ref->id);
            if (!$deleted) {
                $success = false;
            }
        }

        return response()->json($success);
    }
}
