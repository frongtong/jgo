<?php

namespace App\Http\Controllers\Webpanel;
use PHPMailer\PHPMailer\PHPMailer;
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
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Backend\DetailContact;
use App\Models\Backend\Contact;
use App\Models\CustomerRequest;
use App\Models\Backend\Email_customer;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContactExport;
use App\Exports\EmailCustomerExport;

class ContactController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'contact';

//    public function updateRowOrder(Request $request)
// {
//     $request->validate([
//         'id' => 'required|integer',
//         'old_sort' => 'required|integer',
//         'new_sort' => 'required|integer',
//     ]);

//     $id = $request->input('id');
//     $oldSort = (int) $request->input('old_sort');
//     $newSort = (int) $request->input('new_sort');

//     $data = DetailContact::find($id);
//     if (!$data) {
//         return response()->json(['success' => false, 'message' => 'Record not found'], 404);
//     }

//     $idDetail = $data->id_contact;

//     DB::beginTransaction();
//     try {
//         if ($oldSort > $newSort) {
//             // เลื่อนไปด้านบน → ดัน record ที่อยู่ระหว่าง newSort ถึง oldSort ลง 1
//             DetailContact::where('id_contact', $idDetail)
//                 ->where('sort_order', '>=', $newSort)
//                 ->where('sort_order', '<', $oldSort)
//                 ->update([
//                     'sort_order' => DB::raw('sort_order + 1'),
//                 ]);
//         } else {
//             // เลื่อนไปด้านล่าง → ดัน record ที่อยู่ระหว่าง oldSort ถึง newSort ขึ้น 1
//             DetailContact::where('id_contact', $idDetail)
//                 ->where('sort_order', '<=', $newSort)
//                 ->where('sort_order', '>', $oldSort)
//                 ->update([
//                     'sort_order' => DB::raw('sort_order - 1'),
//                 ]);
//         }

//         // อัปเดต record ปัจจุบัน
//         $data->sort_order = $newSort;
//         $data->save();

//         DB::commit();

//         return response()->json(['success' => true]);
//     } catch (\Exception $e) {
//         DB::rollBack();
//         return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
//     }
// }


public function updateRowOrder(Request $request)

{

    try {

        DB::beginTransaction();



        $id1 = $request->input('id1');

        $id2 = $request->input('id2');



        $item1 = DetailContact::findOrFail($id1);

        $item2 = DetailContact::findOrFail($id2);



        // Swap order values

        $tempOrder = $item1->order;

        $item1->order = $item2->order;

        $item2->order = $tempOrder;



        $item1->save();

        $item2->save();



        DB::commit();

        return response()->json(['success' => true]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);

    }

}

    public function items($parameters)
    {
        $search = Arr::get($parameters, 'search');
        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new Contact;
        if ($search) {
            $query = $query->where('name_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->where('name_en', 'LIKE', '%' . trim($search) . '%');
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
            '0' => ['url' => "javascript:void(0)", 'name' => "ติดต่อเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เนื้อหา", "last" => 1],
        ];
        return view("$this->prefix.pages.contact.index", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'items' => $items,
            'navs' => $navs
        ]);
    }
    public function items_email($parameters)
    {
        $search = Arr::get($parameters, 'search');
        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new Email_customer;
        if ($search) {
            $query = $query->where('name_th', 'LIKE', '%' . trim($search) . '%');
            $query = $query->where('name_en', 'LIKE', '%' . trim($search) . '%');
        }
        // $query = $query->orderBy('sort', $sort);
        $query = $query->orderBy('id', 'asc');
        $results = $query->paginate($paginate);
        return $results;
    }

    public function index_email(Request $request)
    {
        $items = $this->items_email($request->all());
        $items->pages = new \stdClass();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();

        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ติดต่อเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "สมัครับข่าวสาร", "last" => 1],
        ];
        return view("$this->prefix.pages.contact.index_email", [
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
            '0' => ['url' => "javascript:void(0)", 'name' => "ติดต่อเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เนื้อหา", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/add", 'name' => "Add", "last" => 2],
        ];

        return view('back-end.pages.contact.add', [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs
        ]);
    }

    public function destroy_report(Request $request)
    {
        if ($request->id == null) {
            return response()->json(false);
        }

        $ids = explode(',', $request->id);
        $deleted = CustomerRequest::destroy($ids);

        // Return true if any records were deleted, otherwise return false
        return response()->json($deleted > 0);
    }


    public function items_report($parameters)
    {
        $search = Arr::get($parameters, 'search');
        $keyword = Arr::get($parameters, 'keyword');

        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new CustomerRequest;
        if ($keyword) {
            $query = $query->where('name', 'LIKE', '%' . trim($keyword) . '%');
            $query = $query->orWhere('type', 'LIKE', '%' . trim($keyword) . '%');
            $query = $query->orWhere('email', 'LIKE', '%' . trim($keyword) . '%');
        }
        // $query = $query->orderBy('sort', $sort);
        $query = $query->orderBy('id', 'desc');
        $results = $query->paginate($paginate);
        return $results;
    }

    public function report(Request $request)
    {
        $items = $this->items_report($request->all());
        $items->pages = new \stdClass();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ติดต่อเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "รายงาน", "last" => 1],
        ];

        return view('back-end.pages.contact.report', [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'items' => $items,
            'navs' => $navs
        ]);
    }
    public function detail(Request $request, $id)
    {
        $data = CustomerRequest::find($id);
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ติดต่อเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "รายงาน", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "รายละเอียด", "last" => 2],
        ];
        return view("$this->prefix.pages.contact.report_detail", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'data' => $data,
        ]);
    }
    public function edit(Request $request, $id)
    {


        $data = Contact::find($id);
        $details = DetailContact::where('id_contact', $id)->orderBy('order', 'asc')->get();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ติดต่อเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "แก้ไข", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.contact.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'data' => $data,
            'details' => $details
        ]);
    }
    public function destroy_file(Request $request)
    {
        if ($request->id == null) {
            return response()->json(false);
        } else {
            $datas = DetailContact::find(explode(',', $request->id));
            if (@$datas) {
                foreach ($datas as $data) {
                    DetailContact::where('id_contact', $data->id)
                        ->where('order', '>', $data->order)
                        ->update([
                            'order' => DB::raw('`order` - 1'),
                        ]);
                    $query = DetailContact::destroy($data->id);
                }
            }

            if (@$query) {
                return response()->json(true);
            } else {
                return response()->json(false);
            }
        }
    }
    public function destroy_qr(Request $request)
    {
        $contacts = Contact::find($request->id);
        if ($contacts) { 
            $contacts->qr = null;
            $contacts->save();
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }
    public function destroy(Request $request)
    {
        if ($request->id == null) {
            return response()->json(false);
        } else {
            $contactIds = explode(',', $request->id);
            $contacts = Contact::find($contactIds);

            if ($contacts) {
                foreach ($contacts as $contact) {
                    DetailContact::where('id_contact', $contact->id)->delete();
                    $contact->delete();
                }
                return response()->json(true);
            } else {
                return response()->json(false);
            }
        }
    }
    public function destroy_email(Request $request)
    {
        if ($request->id == null) {
            return response()->json(false);
        }

        $ids = explode(',', $request->id);
        $deleted = Email_customer::destroy($ids);

        // Return true if any records were deleted, otherwise return false
        return response()->json($deleted > 0);
        
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

                $data = new Contact();

                $data->created_at = now();

            } else {

                $data = Contact::find($id);

            }

            $data->updated_at = now();

            $data->title_th = $request->title_th;

            $data->title_en = $request->title_en;

            $data->address_th = $request->address_th1;

            $data->address_en = $request->address_en1;

            $data->phone = $request->phone1;

            $data->email = $request->email1;



            $path = "upload/Contact";

            if ($request->file('qr')) {

                $fileimage = $request->file('qr');

                // if ($data->qr) {

                //     $oldImagePath = public_path($path . '/' . $data->qr);

                //     if (file_exists($oldImagePath)) {

                //         unlink($oldImagePath);

                //     }

                // }

                $image = 'qr-' . time() . '.' . $fileimage->getClientOriginalExtension();

                $fileimage->move(public_path($path), $image);

                $data->qr = $image;

            }

            $data->save();



        foreach ($request->name_th as $key => $value) {
            if (isset($request->id_detailcontact[$key]) && $request->id_detailcontact[$key] != null) {
                // อัปเดตข้อมูลที่มีอยู่
                $detail = DetailContact::find($request->id_detailcontact[$key]);
            } else {
                // เพิ่มข้อมูลใหม่
                $detail = new DetailContact();
                $detail->created_at = now();

                // หา order สุดท้ายของ contact นี้ แล้วบวก 1
                $maxOrder = DetailContact::where('id_contact', $data->id)->max('order');
                $detail->order = $maxOrder ? $maxOrder + 1 : 1;
            }

            $detail->updated_at = now();
            $detail->id_contact = $data->id;
            $detail->name_th = $request->name_th[$key];
            $detail->name_en = $request->name_en[$key];
            $detail->address_th = $request->address_th[$key];
            $detail->address_en = $request->address_en[$key];
            $detail->phone = $request->phone[$key];
            $detail->phone_en = $request->phone_en[$key];
            $detail->phone_home = $request->phone2[$key];
            $detail->email = $request->email[$key];

            if (isset($request->qr_detail[$key]) && $request->file('qr_detail.' . $key)) {
                $fileqr_detail = $request->file('qr_detail.' . $key);
                $qr_detail = 'qr_detail-' . $key . '-' . time() . '.' . $fileqr_detail->getClientOriginalExtension();
                $fileqr_detail->move(public_path($path), $qr_detail);
                $detail->qr_detail = $qr_detail;
            }

            $detail->save();
        }






            DB::commit();

            return view("$this->prefix.alert.success", ['url' => url("$this->segment/contact")]);

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
    public function export()
    {
        return Excel::download(new ContactExport, 'contacts.xlsx');
    }
     public function export_email()
    {
        return Excel::download(new EmailCustomerExport, 'email_customers.xlsx');
    }
}
