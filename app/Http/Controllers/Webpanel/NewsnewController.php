<?php

namespace App\Http\Controllers\Webpanel;

use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Webpanel\LogsController;
use App\Helpers\Helper;
use Illuminate\Support\Arr;
use App\Models\Backend\News_new;
use App\Models\Backend\News_old;
use App\Models\Backend\News_new_url;

use App\Models\Backend\News_new_image;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Backend\NewsCategory;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Mail\NewsEmail;
use App\Imports\ImportsEmailCustomer;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\EmailCustomer;
use App\Models\Backend\LogEmail_News;


class NewsnewController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'news_new';
    private $Host;
    private $Username;
    private $Password;
    private $Port;
    private $EmailFrom;
    private $NameFrom;

    public function __construct(
        $Host = null,
        $Username = null,
        $Password = null,
        $Port = null,
        $EmailFrom = null,
        $NameFrom = null,
    ) {

        $this->setHost($Host ?? env('MAIL_HOST'));
        $this->setUsername($Username ?? env('MAIL_USERNAME'));
        $this->setPassword($Password ?? env('MAIL_PASSWORD'));
        $this->setPort($Port ?? env('MAIL_PORT'));
        $this->setMailFrom($EmailFrom ?? env('MAIL_FROM_ADDRESS'));
        $this->setNameFrom($NameFrom ?? env('MAIL_FROM_NAME'));
    }
    public function setNameFrom($NameFrom)
    {
        $this->NameFrom = $NameFrom;
    }
    public function setMailFrom($EmailFrom)
    {
        $this->EmailFrom = $EmailFrom;
    }
    public function setHost($Host)
    {
        $this->Host = $Host;
    }
    public function setUsername($Username)
    {
        $this->Username = $Username;
    }
    public function setPassword($Password)
    {
        $this->Password = $Password;
    }
    public function setPort($Port)
    {
        $this->Port = $Port;
    }


    public function items($parameters)
    {
        $search = Arr::get($parameters, 'search');
        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new News_new;
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
        $items->pages = new News_new();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();

        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ข่าวสารและกิจกรรม", "last" => 0],
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
            '0' => ['url' => "javascript:void(0)", 'name' => "ข่าวสารและกิจกรรม", "last" => 0],
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
        $data = News_new::find($id);
        $category = NewsCategory::all();
        $files = News_new_image::where('news_new_id', $id)->orderBy('order')->get();
        $refs = News_new_url::where('id_news_new', $id)->get();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ข่าวสารและกิจกรรม", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เนื้อหา", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'row' => $data,
            'refs' => $refs,
            'category' => $category,
            'files' => $files,
            'id' => $id
        ]);
    }
    public function updateOrder(Request $request)
        {
            // รับ orderedIds จาก request
            $orderedIds = $request->input('orderedIds');

            // ตรวจสอบว่าเป็น array จริงหรือไม่
            if (!is_array($orderedIds)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid data format'
                ], 400);
            }

            try {
                foreach ($orderedIds as $item) {
                    News_new_image::where('id', $item['id'])
                        ->update(['order' => $item['order']]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Order updated successfully'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage()
                ], 500);
            }
        }


    public function destroy(Request $request)
    {

        if ($request->id == null) {
            return response()->json(false);
        } else {
            $datas = News_new::find(explode(',', $request->id));
            if (@$datas) {
                foreach ($datas as $data) {
                    $query = News_new::destroy($data->id);
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
                $data = new News_new();
                $data->created_at = now();
            } else {
                $data = News_new::find($id);
            }
            $data->updated_at = now();
            $data->title_th = $request->title_th;
            $data->title_en = $request->title_en;
            $data->description_th = $request->description_th;
            $data->description_en = $request->description_en;
            $data->start = $request->start;
            $data->end = $request->end;
            $data->date_start_show = $request->date_start_show;
            $data->date_end_show = Carbon::parse($request->date_start_show)->addDays(30);
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

            if ($request->has('path')) {
                $filePaths = $request->file('path', []);
                $fileIds = $request->input('news_new_id', []);
                if ($id != null) {
                    $maxOrder = News_new_image::where('news_new_id', $id)
                        ->max('order');
                    $x = $maxOrder + 1;
                } else {
                    $x = 1;
                }

                foreach ($filePaths as $index => $filePath) {
                    $fileId = $fileIds[$index] ?? null; // Get the file ID or null if not present

                    if ($fileId) {
                        // Update existing file
                        $fileRecord = News_new_image::find($fileId);

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
                            $newFileRecord = new News_new_image();
                            $newFileRecord->news_new_id = $data->id;
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

            for ($x = 0; $x < $loop_count_ref; $x++) {
                $refId = isset($request->id_refinnovation[$x]) ? $request->id_refinnovation[$x] : null;


                $check_ref = News_new_url::find($refId);

                if ($check_ref != null) {
                    $RefInnovation = $check_ref;
                } else {
                    $RefInnovation = new News_new_url();
                }

                $RefInnovation->id_news_new = $data->id;
                $RefInnovation->url = $request->url[$x];
                $RefInnovation->text_ref = $request->text_ref[$x] ?? '';
                $RefInnovation->date = $request->date_ref[$x];
                $RefInnovation->save();
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
            $item = News_new::find($request->id);
            $item->status = $request->status;
            $item->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
    public function destroy_image(Request $request)
    {


        $deleted = News_new_image::destroy($request->id);

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
    public function destroy_ref(Request $request)
    {

        if ($request->id == null) {
            return response()->json(false);
        }

        $ids = explode(',', $request->id);
        $refs = News_new_url::whereIn('id', $ids)->get();
        $success = true;
        foreach ($refs as $ref) {
            $deleted = News_new_url::destroy($ref->id);
            if (!$deleted) {
                $success = false;
            }
        }

        return response()->json($success);
    }

    public function handle()
    {
        $now = now();
        $emailsToSend = LogEmail_News::where('set_date_time', '<=', $now)
            ->where('status', 'pending')
            ->get();

        DB::beginTransaction();

        try {
            foreach ($emailsToSend as $logEmail) {
                try {
                    $email = trim($logEmail->email_user);
                
                    $mail = new PHPMailer(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->isSMTP();
                    $mail->Host       = $this->Host;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $this->Username;
                    $mail->Password   = $this->Password;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = $this->Port;

                    $mail->setFrom($this->EmailFrom, 'Inteqc  Pet Care News Center');
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = 'ข่าวสารจากบริษัท  บริษัท อินเทคค์ โกลบอล จำกัด';

                    // ข้อมูลข่าว
                    $data = $logEmail;
                    $news = News_new::find($data->id_news_new);

                    if ($news->type_banner == "image") {
                        $banner_url =  url($news->video);
                    } else {
                        $banner_url =  url($news->cover);
                    }


                    $title = Session::get("lang") == "th" ? $news->title_th : $news->title_en;
                    $desc = Session::get("lang") == "th" ? $news->description_th : $news->description_en;
                    $desc = Str::limit(html_entity_decode(strip_tags($desc)), 800);
                    $lang = Session::get('lang', 'th');
                    $detail_url = url($lang. '/news-detail/' .$news->id);

                    $body = '
                    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f5f5; padding:20px;">
                        <tr>
                            <td align="center">
                                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:10px; padding:20px; font-family:sans-serif; color:#000000;">
                                    <tr>
                                        <td align="center" style="font-size:24px; font-weight:bold;">' . $title . '</td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top:20px;">
                                            <img src="' . $banner_url . '" alt="News Image" width="100%" style="max-width:100%; border-radius:5px;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:15px 0; font-size:16px; line-height:1.5;">' . $desc . '</td>
                                    </tr>
                                    <tr>
                                        <td><a href="' . $detail_url . '" style="color:#A21D21; font-weight:bold;">อ่านเพิ่มเติม</a></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top:20px; font-size:14px;">
                                            <strong>ขอแสดงความนับถือ<br> บริษัท อินเทคค์ โกลบอล จำกัด</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top:30px; background-color:#a52a2a; color:#ffffff; text-align:center; border-radius:5px;">
                                            <h2 style="margin:10px 0;"> INTEQC GLOBAL PET CARE</h2>
                                            <p style="color:#ffffff;margin:0;">77/12 Moo 2 Rama 2 Rd., Nakhok, Mueang Samut Sakhon, </p>
                                            <p style="color:#ffffff;margin:0;">Samutsakhon, 74000 THAILAND</p>
                                            <p style="color:#ffffff; margin:0; font-size:12px;">
                                            If you would not like to receive this email, please send email to 
                                            <a href="mailto:inteqcglobal@inteqc.com" style="color:#ffffff !important; text-decoration:none;">
                                                inteqcglobal@inteqc.com</a>
                                            </p>
                                            <p style="color:#ffffff;margin:10px 0; font-size:12px;">Want to change how you receive these emails?<br>
                                            You can <a href="' . url('/unsubscribe-innovation?email=' . urlencode($email)) . '" style="color:#ffffff; text-decoration:underline;">unsubscribe</a></p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>';

                    $mail->Body = $body;
                    $mail->send();

                    // อัปเดตสถานะสำเร็จ
                    $logEmail->update(['status' => 'sent']);
                } catch (Exception $e) {
                    Log::error('Email send error to ' . $logEmail->email_user . ': ' . $e->getMessage());
                    $logEmail->update(['status' => 'failed']);
                }
            }

            DB::commit();
            return 'success';
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error processing email logs: " . $e->getMessage());
            return 'fail';
        }
    }
    public function itemshistory($parameters)
    {
        $search = Arr::get($parameters, 'keyword');
        $status = Arr::get($parameters, 'status');

        // $sort = Arr::get($parameters, 'sort', 'asc');
        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new LogEmail_News;
        $query = LogEmail_News::with(['news_new']);

        if ($search) {
            $query = $query->where('name_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->where('name_en', 'LIKE', '%' . trim($search) . '%');
        }
        // $query = $query->orderBy('sort', $sort);
        $query = $query->orderBy('id', 'desc');
        $results = $query->paginate($paginate);
        return $results;
        if ($status) {
            $query->where('logemail_news.status', '=', trim($status));
        }

        $results = $query->paginate($paginate);
        return $results;
    }
    public function indexhistory(Request $request)
    {
        $items = $this->itemshistory($request->all());
        $items->pages = new \stdClass();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();

        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ข่าวสารและกิจกรรม", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "ประวัติส่งบทความ", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.history", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'data' => $items,
            'navs' => $navs,
        ]);
    }
    public function detailhistory(Request $request, $id)
    {

        $keyword = LogEmail_News::find($id);
        if ($keyword->type_news == 0) {
            $news = News_new::where('id', $keyword->id_news_new)->first();
        } else {
            $news = News_old::where('id', $keyword->id_news_old)->first();
        }
        
        $data = DB::table('logemail_news')
        ->leftJoin('email_customer', 'email_customer.email', '=', 'logemail_news.email_user')
        ->select(
            'logemail_news.*',
            'email_customer.*', // Include email_customer fields
        )
        ->whereDate('logemail_news.created_at', \Carbon\Carbon::parse($keyword->created_at)->toDateString())
        ->get();        
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "กิจกรรมสัมพันธุ์", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "ประวัติส่งบทความ", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.detailhistory", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'data' => $data,
            'navs' => $navs,
            'keyword'=>$keyword,
            'news'=>$news,
        ]);
    }
    public function destroyhistory(Request $request, $id)
    {
        if (!$id) {
            return response()->json(false); 
        }

        $keyword = LogEmail_news::find($id);

        if (!$keyword) {
            return response()->json(false);  
        }

        try {
            DB::beginTransaction(); 
            if ($keyword->type_news == 0) {
            $logEmails = DB::table('logemail_news')
                ->where('id_news_new', $keyword->id_news_new)
                ->whereDate('created_at', \Carbon\Carbon::parse($keyword->created_at)->toDateString())
                ->get();             
            } else {
                $logEmails = DB::table('logemail_news')
                ->where('id_news_old', $keyword->id_news_old)
                ->whereDate('created_at', \Carbon\Carbon::parse($keyword->created_at)->toDateString())
                ->get(); 
                        }

            foreach ($logEmails as $logEmail) {
                LogEmail_news::destroy($logEmail->id);
            }

            DB::commit();
            return response()->json(true);  
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e);  
        }
    }

        public function email(Request $request)
    {
        $users = EmailCustomer::all();
        $categorys = NewsCategory::all();
        $news_news = News_new::all();


        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "กิจกรรมสัมพันธ์", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "ส่งอีเมล", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.email", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'users' => $users,
            'categorys' => $categorys,
            'news_news' => $news_news
        ]);
    }
   public function sendemail(Request $request)
    {
        $email_users = $request->user;

        foreach ($email_users as $email) {
            // สร้าง log
            $logEmail = new LogEmail_News();
            $logEmail->email_user = $email;
            $logEmail->status = $request->status;
            $logEmail->set_date_time = $request->datetime;
            $logEmail->created_by = Auth::guard('admin')->user()->name;
            $logEmail->created_at = now();
            $logEmail->updated_at = now();
            $logEmail->news_type = $request->type_news;

            if ($request->type_news == 0) {
                $logEmail->id_news_new = $request->news_new;
            } else {
                $logEmail->id_news_old = $request->news_new;
            }

            $logEmail->save();

       
            if ($request->status == "sent") {
                $mail = new PHPMailer(true);
                try {
                   
                    $mail = new PHPMailer(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->isSMTP();
                    $mail->Host       = $this->Host;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $this->Username;
                    $mail->Password   = $this->Password;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = $this->Port;

                    $mail->setFrom($this->EmailFrom, 'Inteqc  Pet Care News Center');
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = 'ข่าวสารจาก บริษัท อินเทคค์ โกลบอล จำกัด';

                    // 🔁 เงื่อนไขตาม news_type
                    $data = $logEmail;
                  
                        $news = News_new::find($data->id_news_new);
                   

                    // ✅ รูปภาพ banner ตาม type_banner
                    if ($news->type_banner == "image") {
                        $banner_url =  url($news->video);
                    } else {
                        $banner_url =  url($news->cover);
                    }


                    // ✅ ข้อมูลเนื้อหา
                    $title = Session::get("lang") == "th" ? $news->title_th : $news->title_en;
                    $desc = Session::get("lang") == "th" ? $news->description_th : $news->description_en;
                    $desc = Str::limit(html_entity_decode(strip_tags($desc)), 800);
                    $detail_url = url(Session::get('lang') . '/news-detail/' .$news->id);

                    // ✅ สร้าง HTML body
                    $body = '
                    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f5f5; padding:20px;">
                        <tr>
                            <td align="center">
                                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:10px; padding:20px; font-family:sans-serif; color:#000000;">
                                    <tr>
                                        <td align="center" style="font-size:24px; font-weight:bold;">
                                            ' . $title . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top:20px;">
                                            <img src="' . $banner_url . '" alt="News Image" width="100%" style="max-width:100%; border-radius:5px;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:15px 0; font-size:16px; line-height:1.5;">
                                            ' . $desc . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="' . $detail_url . '" style="color:#A21D21; font-weight:bold;">อ่านเพิ่มเติม</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top:20px; font-size:14px;">
                                            <strong>ขอแสดงความนับถือ<br>บริษัท อินเทคค์ โกลบอล จำกัด</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top:30px; background-color:#a52a2a; color:#ffffff; text-align:center; border-radius:5px;">
                                            <h2 style="margin:10px 0;">Inteqc  Pet Care</h2>
                                            <p style="color:#ffffff;margin:0;">77/12 Moo 2 Rama 2 Rd., Nakhok, Mueang Samut Sakhon, </p>
                                            <p style="color:#ffffff;margin:0;">Samutsakhon, 74000 THAILAND</p>
                                            <p style="color:#ffffff; margin:0; font-size:12px;">
                                            If you would not like to receive this email, please send email to 
                                            <a href="mailto:inteqcglobal@inteqc.com" style="color:#ffffff !important; text-decoration:none;">
                                                inteqcglobal@inteqc.com</a>
                                            </p>
                                            <p style="color:#ffffff;margin:10px 0; font-size:12px;">Want to change how you receive these emails?<br>
                                            You can <a href="' . url('/unsubscribe-innovation?email=' . urlencode($email)) . '" style="color:#ffffff; text-decoration:underline;">unsubscribe</a></p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>';

                    $mail->Body = $body;
                    $mail->send();

                } catch (Exception $e) {
                    \Log::error('Email send error to ' . $email . ': ' . $e->getMessage());
                }
            }
        }

        return response()->json(['status' => 200]);
    }

    public function get_description_new(Request $request, $id)
    {

        $data = News_new::find($id);

        return $data;
    }
    public function get_description_old(Request $request, $id)
    {

        $data = News_old::find($id);

        return $data;
    }
    public function getall_description_new(Request $request)
    {

        $data = News_new::all();

        return $data;
    }
    public function excelfileimport(Request $request)
    {



        Excel::import(new ImportsEmailCustomer, $request->file('email_customer_excel'));
        return redirect()->back()->with('success', 'Employees imported successfully.');
    }
     public function create_modal(Request $request)
    {



        return view("$this->prefix.pages.$this->folder.modal-create", [

            'segment' => $this->segment,

            'prefix' => $this->prefix,

            'folder' => $this->folder,

        ]);
    }
    public function edit_modal(Request $request, $id)

    {



        $data = EmailCustomer::find($id);

        return view("$this->prefix.pages.$this->folder.modal-edit", [

            'segment' => $this->segment,

            'prefix' => $this->prefix,

            'folder' => $this->folder,

            'row' => $data,

        ]);
    }



    public function insert_modal(Request $request, $id = null)

    {

        return $this->store_modal($request, $id = null);
    }

    public function update_modal(Request $request, $id)

    {

        return $this->store_modal($request, $id);
    }

    public function store_modal($request, $id = null)

    {

        try {

            DB::beginTransaction();



            // Determine if the request is for a single entry or multiple entries

            $prefixThais = $request->input('PrefixThai') ? (array) $request->input('PrefixThai') : [];

            $firstNames = $request->input('FirstNameThai') ? (array) $request->input('FirstNameThai') : [];

            $lastNames = $request->input('LastNameThai') ? (array) $request->input('LastNameThai') : [];

            $emails = $request->input('Email') ? (array) $request->input('Email') : [];



            // If the form was filled out for a single entry, make it an array

            if (empty($firstNames) && !empty($request->input('FirstNameThai'))) {

                $prefixThais[] = $request->input('PrefixThai');

                $firstNames[] = $request->input('FirstNameThai');

                $lastNames[] = $request->input('LastNameThai');

                $emails[] = $request->input('Email');
            }



            // Iterate over the incoming data

            $count = count($prefixThais);



            if ($count === 0) {
                return view("$this->prefix.alert.alert", [
                    'url' =>("$this->segment/$this->folder/email"),
                    'title' => "ไม่สามารถทำรายได้",
                    'text' => "กรุณากรอกข้อมูลให้ครบทุกช่อง!",
                    'icon' => 'error'
                ]);
            }





            for ($i = 0; $i < $count; $i++) {

                if ($id === null) {

                    $data = new EmailCustomer();

                    $data->created_at = now();

                    $data->updated_at = now();
                } else {

                    $data = EmailCustomer::find($id);

                    $data->updated_at = now();
                }

                $data->PrefixThai = $prefixThais[$i] ?? null;

                $data->FirstNameThai = $firstNames[$i] ?? null;

                $data->LastNameThai = $lastNames[$i] ?? null;

                $data->Email = $emails[$i] ?? null;

                $data->save();
            }



            DB::commit();

            return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder/email")]);
        } catch (\Exception $e) {

            DB::rollback();

            dd($e);

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
    public function destroy_email(Request $request)

    {



        if ($request->ids == null) {

            return response()->json(false);
        } else {

            $datas = EmailCustomer::find($request->ids);

            if (@$datas) {

                foreach ($datas as $data) {

                    $query = EmailCustomer::destroy($data->id);
                }
            }



            if (@$query) {

                return response()->json(true);
            } else {

                return response()->json(false);
            }
        }
    }
 }
