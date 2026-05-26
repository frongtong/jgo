<?php

namespace App\Http\Controllers\Webpanel;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Str;
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
use App\Models\Backend\Innovation;
use App\Models\Backend\FileInnovation;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Backend\InnovationCategory;
use App\Models\Backend\RefInnovation;
use App\Models\Backend\Innovation_image;
use App\Imports\ImportsEmailCustomer;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\EmailCustomer;
use App\Models\Backend\LogEmail_Innovation;
use App\Mail\InnovationEmail;



class InnovationController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'innovation';
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
        $search = Arr::get($parameters, 'keyword');
        $status = Arr::get($parameters, 'status');

        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = Innovation::leftJoin('innovation_category', 'innovation.category', '=', 'innovation_category.id')
            ->select('innovation.*', 'innovation_category.name_th as category_name_th', 'innovation_category.name_en as category_name_en');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title_th', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('title_en', 'LIKE', '%' . trim($search) . '%');
            });
        }

        if ($status) {
            $query->where('innovation.category', '=', $status);
        }
        // $query = $query->orderBy('sort', $sort);
        $query = $query->orderBy('id', 'desc');
        $results = $query->paginate($paginate);
        return $results;
    }


    public function get_Innovation(Request $request, $InnovationId)
    {

        $data = Innovation::where('category', $InnovationId)->get();

        return $data;
    }
    public function get_description(Request $request, $id)
    {

        $data = Innovation::find($id);

        return $data;
    }
    
    public function index(Request $request)
    {
        $items = $this->items($request->all());
        $items->pages = new \stdClass();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();

        $categorys = InnovationCategory::where('status', 'on')->get();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ศูนย์นวัตกรรม", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เนื้อหา", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.index", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'items' => $items,
            'navs' => $navs,
            'categorys' => $categorys
        ]);
    }

    public function add(Request $request)
    {

        $category = InnovationCategory::where('status', 'on')->get();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ศูนย์นวัตกรรม", "last" => 0],
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
        $data = Innovation::find($id);
        $category = InnovationCategory::where('status', 'on')->get();
        $files = Innovation_image::where('innovation_id', $id)->orderBy('order')->get();
   
        $refs = RefInnovation::where('id_innovation', $id)->get();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ศูนย์นวัตกรรม", "last" => 0],
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
            'refs' => $refs
        ]);
    }
   
    public function destroy(Request $request)
    {

        if ($request->id == null) {
            return response()->json(false);
        } else {
            $datas = Innovation::find(explode(',', $request->id));
            if (@$datas) {
                foreach ($datas as $data) {
                    $query = Innovation::destroy($data->id);
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
        $files = Innovation_image::whereIn('id', $ids)->get();

        if ($files->isEmpty()) {
            return response()->json(false);
        }

        $success = true;
        foreach ($files as $file) {
            $deleted = Innovation_image::destroy($file->id);
            if (!$deleted) {
                $success = false;
            }
        }

        return response()->json($success);
    }
    public function destroy_ref(Request $request)
    {

        if ($request->id == null) {
            return response()->json(false);
        }

        $ids = explode(',', $request->id);
        $refs = RefInnovation::whereIn('id', $ids)->get();
        $success = true;
        foreach ($refs as $ref) {
            $deleted = RefInnovation::destroy($ref->id);
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
        // dd($request);
        try {
            DB::beginTransaction();
            if ($id == null) {
                $data = new Innovation();
                $data->created_at = now();
            } else {
                $data = Innovation::find($id);
            }
            $data->updated_at = now();
            $data->category = $request->category;
            $data->date = $request->date;
            $data->title_th = $request->title_th;
            $data->title_en = $request->title_en;
            $data->description_th = $request->description_th;
            $data->description_en = $request->description_en;

            $path = "upload/innovation";
            if ($request->file('banner')) {
                $fileimage = $request->file('banner');
                $image = 'banner-' . time() . '.' . $fileimage->getClientOriginalExtension();
                $fileimage->move(public_path($path), $image);
                $data->banner = $image;
            }

            $data->save();

            
            if ($request->has('path')) {
                $filePaths = $request->file('path', []);
                $fileIds = $request->input('innovation_id', []);
                if ($id != null) {
                    $maxOrder = Innovation_image::where('innovation_id', $id)
                        ->max('order');
                    $x = $maxOrder + 1;
                } else {
                    $x = 1;
                }

                foreach ($filePaths as $index => $filePath) {
                    $fileId = $fileIds[$index] ?? null; // Get the file ID or null if not present

                    if ($fileId) {
                        // Update existing file
                        $fileRecord = Innovation_image::find($fileId);

                        if ($fileRecord) {
                            if ($filePath && $filePath instanceof \Illuminate\Http\UploadedFile) {
                                // Generate a unique file name and move the file
                                $uniqueFileName = 'Innovation-' . time() . '-' . $index . '.' . $filePath->getClientOriginalExtension();
                                $filePath->move(public_path($path), $uniqueFileName);

                                // Update the file path in the database
                                $fileRecord->image = $path . '/' . $uniqueFileName;
                            }

                            $fileRecord->save(); // Save the updated file record
                        }
                    } else {
                        // Add new file
                        if ($filePath && $filePath instanceof \Illuminate\Http\UploadedFile) {
                            $newFileRecord = new Innovation_image();
                            $newFileRecord->innovation_id = $data->id;
                            $newFileRecord->order = $x;
                            // Generate a unique file name and move the file
                            $uniqueFileName = 'Innovation-' . time() . '-' . $index . '.' . $filePath->getClientOriginalExtension();
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


                $check_ref = RefInnovation::find($refId);

                if ($check_ref != null) {
                    $RefInnovation = $check_ref;
                } else {
                    $RefInnovation = new RefInnovation();
                }

                $RefInnovation->id_innovation = $data->id;
                $RefInnovation->url = $request->url[$x];
                $RefInnovation->text_ref = $request->text_ref[$x] ?? '';
                $RefInnovation->date = $request->text_date[$x] ;
                $RefInnovation->save();
            }


            DB::commit();
            return view("$this->prefix.alert.success", ['url' => url("$this->segment/$this->folder")]);
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


    private function uploadfile($files, $path, $InnovationId, $id = null, $type,  $namefile = null)
    {
        try {
            DB::beginTransaction();


            if ($files) {
                foreach ($files as $index => $file) {
                    if ($id === null) {
                        $uploadfile = new FileInnovation();
                        $uploadfile->created_at = now();
                    } else {
                        $uploadfile = FileInnovation::find($id);

                        // If no single record is found or if $id returns a collection, create a new instance
                        if (!$uploadfile || $uploadfile instanceof \Illuminate\Database\Eloquent\Collection) {
                            $uploadfile = new FileInnovation();
                            $uploadfile->created_at = now();
                        } else {
                            $uploadfile->updated_at = now();
                        }
                    }

                    // Generate and save the new file
                    $fileName = $type . '-' . time() . '-' . $index . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path($path), $fileName);

                    // Set the attributes
                    $uploadfile->path = $fileName;
                    $uploadfile->type = $type;
                    $uploadfile->namefile = $namefile;
                    $uploadfile->id_innovation = $InnovationId;

                    // Save the file information to the database
                    $uploadfile->save();
                }
                DB::commit();
            }
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
        }
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

            // If no entries, handle accordingly
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
    public function excelfileimport(Request $request)

    {



        Excel::import(new ImportsEmailCustomer, $request->file('email_customer_excel'));



        return redirect()->back()->with('success', 'Employees imported successfully.');
    }
    public function handle()
    {
         $now = now();
        $emailsToSend = LogEmail_Innovation::where('set_date_time', '<=', $now)
            ->where('status', 'pending')
            ->get();

        DB::beginTransaction();

        try {
            foreach ($emailsToSend as $logEmail) {
                try {
                    $email = trim($logEmail->email_user);
                    $innovation = Innovation::find($logEmail->id_innovation);

                    if (!$innovation) {
                        Log::warning("Innovation not found for log ID {$logEmail->id}");
                        $logEmail->update(['status' => 'failed']);
                        continue;
                    }

                    $mail = new PHPMailer(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->isSMTP();
                    $mail->Host       = $this->Host;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $this->Username;
                    $mail->Password   = $this->Password;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = $this->Port;

                    $mail->setFrom($this->EmailFrom, 'Inteqc  Pet Care Innovation Center');
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = 'ข้อมูล ข่าวสารจากศูนย์นวัตกรรม บริษัท อินเทคค์ โกลบอล จำกัด';
                    if ($innovation->banner ) {
                        $banner_url =  url('public/upload/innovation/' .$innovation->banner);
                    } 

                    // ✅ ข้อมูลเนื้อหา
                    $title = Session::get("lang") == "th" ? $innovation->title_th : $innovation->title_en;
                    $desc = Session::get("lang") == "th" ? $innovation->description_th : $innovation->description_en;
                    $desc = Str::limit(html_entity_decode(strip_tags($desc)), 400);
                    $lang = Session::get('lang', 'th');
                     $detail_url = url( $lang  . '/innovation-detail/' .$innovation->id);


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

                    $logEmail->update(['status' => 'sent']);

                } catch (Exception $e) {
                    Log::error('Email send error to ' . $email . ': ' . $e->getMessage());
                    $logEmail->update(['status' => 'failed']);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error processing innovation email logs: " . $e->getMessage());
        }
    }
   public function itemshistory($parameters)
    {
        $search = Arr::get($parameters, 'keyword');
        $type = Arr::get($parameters, 'type');
        $status = Arr::get($parameters, 'status');
        $paginate = Arr::get($parameters, 'total', 15);

        $query = LogEmail_Innovation::leftJoin('innovation', 'innovation.id', '=', 'logemail_innovation.id_innovation')
            ->leftJoin('innovation_category', 'innovation.category', '=', 'innovation_category.id')
            ->select(
                DB::raw('MAX(logemail_innovation.id) as id'),
                DB::raw('MAX(logemail_innovation.created_by) as created_by'),
                DB::raw('MAX(logemail_innovation.status) as status'),
                DB::raw('MAX(logemail_innovation.created_at) as created_at'),
                DB::raw('MAX(innovation.id) as innovation_id'),
                DB::raw('MAX(innovation.title_th) as title_th'),
                DB::raw('MAX(innovation.title_en) as title_en'),
                DB::raw('MAX(innovation_category.name_th) as category_name_th'),
                DB::raw('MAX(innovation_category.name_en) as category_name_en')
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('innovation.title_th', 'LIKE', '%' . trim($search) . '%')
                ->orWhere('innovation.title_en', 'LIKE', '%' . trim($search) . '%')
                ->orWhere('innovation_category.name_th', 'LIKE', '%' . trim($search) . '%')
                ->orWhere('innovation_category.name_en', 'LIKE', '%' . trim($search) . '%')
                ->orWhere('logemail_innovation.created_by', 'LIKE', '%' . trim($search) . '%');
            });
        }

        if ($type) {
            $query->where('innovation.category', $type);
        }

        if ($status) {
            $query->where('logemail_innovation.status', trim($status));
        }

        // Group by innovation id และ วันที่ (เพื่อให้ได้เฉพาะ record ล่าสุดต่อ innovation)
        $query->groupBy('logemail_innovation.id_innovation', 'logemail_innovation.created_at');

        // สั่งเรียงลำดับโดยใช้ค่า MAX(id) ที่เราเลือกไว้ (alias ต้อง wrap ด้วย DB::raw)
        $query->orderBy(DB::raw('MAX(logemail_innovation.id)'), 'desc');

        return $query->paginate($paginate);
    }

    public function indexhistory(Request $request)
    {
        $items = $this->itemshistory($request->all());
        $items->pages = new \stdClass();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();

        
        $categorys = InnovationCategory::where('status', 'on')->get();

        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ศูนย์นวัตกรรม", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "ประวัติส่งบทความ", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.history", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'data' => $items,
            'navs' => $navs,
            'categorys' => $categorys
        ]);
    }
    public function detailhistory(Request $request, $id)
    {

        $keyword = LogEmail_Innovation::find($id);

        $data = DB::table('logemail_innovation')
            ->leftJoin('innovation', 'innovation.id', '=', 'logemail_innovation.id_innovation')
            ->leftJoin('innovation_category', 'innovation.category', '=', 'innovation_category.id')
            ->leftJoin('email_customer', 'email_customer.email', '=', 'logemail_innovation.email_user')
            ->select(
                'logemail_innovation.*',
                'email_customer.*',
                'innovation.title_th',
                'innovation.title_en',
                'innovation_category.name_th as category_name_th',
                'innovation_category.name_en as category_name_en'
            )
            ->where('logemail_innovation.id_innovation', $keyword->id_innovation)
            ->whereDate('logemail_innovation.created_at', \Carbon\Carbon::parse($keyword->created_at)->toDateString())

            ->get();

        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ศูนย์นวัตกรรม", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "ประวัติส่งบทความ", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.detailhistory", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'data' => $data,
            'navs' => $navs,
        ]);
    }
    public function destroyhistory(Request $request, $id)
    {
        if (!$id) {
            return response()->json(false);
        }

        $keyword = LogEmail_Innovation::find($id);

        if (!$keyword) {
            return response()->json(false);
        }

        try {
            DB::beginTransaction();
            $logEmails = DB::table('logemail_innovation')
                ->where('id_innovation', $keyword->id_innovation)
                ->whereDate('created_at', \Carbon\Carbon::parse($keyword->created_at)->toDateString())
                ->get();
            foreach ($logEmails as $logEmail) {
                LogEmail_Innovation::destroy($logEmail->id);
            }

            DB::commit();
            return response()->json(true);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(false);
        }
    }
   public function sendemail(Request $request)
    {
        $email_users = $request->user;
        $innovation = Innovation ::find($request->innovation);

        foreach ($email_users as $email) {
            // 1. บันทึก Log
            $logEmail = new LogEmail_Innovation();
            $logEmail->email_user = $email;
            $logEmail->id_innovation = $request->innovation;
            $logEmail->status = $request->status;
            $logEmail->set_date_time = $request->datetime;
            $logEmail->created_by = Auth::guard('admin')->user()->name;
            $logEmail->created_at = now();
            $logEmail->updated_at = now();
            $logEmail->save();

            // 2. ส่งอีเมลถ้าสถานะเป็น "sent"
            if ($request->status == "sent") {
                $mail = new PHPMailer(true);
                try {
                    $mail->CharSet = 'UTF-8';
                    $mail->isSMTP();
                    $mail->Host       = $this->Host;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $this->Username;
                    $mail->Password   = $this->Password;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = $this->Port;
                    $mail->setFrom($this->EmailFrom,'Inteqc  Pet Care Innovation Center');
                    $mail->addAddress(trim($email));

                    $mail->isHTML(true);
                    $mail->Subject = 'ข้อมูล ข่าวสารจากศูนย์นวัตกรรม บริษัท อินเทคค์ โกลบอล จำกัด';
                    if ($innovation->banner ) {
                        $banner_url =  url('public/upload/innovation/' .$innovation->banner);
                    } 

                    // ✅ ข้อมูลเนื้อหา
                    $title = Session::get("lang") == "th" ? $innovation->title_th : $innovation->title_en;
                    $desc = Session::get("lang") == "th" ? $innovation->description_th : $innovation->description_en;
                    $desc = Str::limit(html_entity_decode(strip_tags($desc)), 800);
                    $detail_url = url(Session::get('lang') . '/innovation-detail/' .$innovation->id);


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
                                                nteqcflourmill@inteqc.com</a>
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
    public function updateStatus(Request $request)
    {
        try {
            $item = Innovation::find($request->id);
            $item->status = $request->status;
            $item->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
     
  
  
    public function email(Request $request)
    {
        $categorys = InnovationCategory::where('status', 'on')->get();
        $innovations = Innovation::all();
        $users = EmailCustomer::all();

        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ศูนย์นวัตกรรม", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "ส่งบทความ", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.email", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'categorys' => $categorys,
            'innovations' => $innovations,
            'users' => $users
        ]);
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
