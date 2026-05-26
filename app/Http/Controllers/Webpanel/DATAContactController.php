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
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Backend\DetailContact;
use App\Models\Backend\DATAContact;
use App\Models\CustomerRequest;

class DATAContactController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'data-contact';
    public function items($parameters)
    {
        $search = Arr::get($parameters, 'search');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new DATAContact;
        if ($search) {
            $query = $query->where('name', 'LIKE', '%' . trim($search) . '%');
            $query = $query->where('email', 'LIKE', '%' . trim($search) . '%');
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
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "กำหนดชื่อและอีเมล", "last" => 1],
        ];
        return view("$this->prefix.pages.data-contact.index", [
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
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "กำหนดชื่อและอีเมล", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/add", 'name' => "Add", "last" => 2],
        ];

        return view('back-end.pages.data-contact.add', [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs
        ]);
    }



    public function edit(Request $request, $id)
    {
        $data = DATAContact::find($id);
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "ติดต่อเรา", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "กำหนดชื่อและอีเมล", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.data-contact.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'data' => $data,
        ]);
    }
    public function destroy(Request $request)
    {
        if ($request->id == null) {
            return response()->json(false);
        }

        $ids = explode(',', $request->id);
        $deleted = DATAContact::destroy($ids);

        // Return true if any records were deleted, otherwise return false
        return response()->json(true);
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
                $data = new DATAContact();
                $data->created_at = now();
            } else {
                $data = DATAContact::find($id);
            }
            $data->updated_at = now();
            $data->name_th = $request->name_th;
            $data->name_en = $request->name_en;
            $data->email = $request->email;


            $data->save();



            DB::commit();
            return view("$this->prefix.alert.success", ['url' => url("$this->segment/data-contact")]);
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
}
