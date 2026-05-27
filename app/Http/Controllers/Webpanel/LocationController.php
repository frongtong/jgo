<?php

namespace App\Http\Controllers\Webpanel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Backend\Location; // อ้างอิงตามที่คุณระบุว่า Model อยู่ใน Backend
use Illuminate\Support\Arr;

class LocationController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'location'; // เปลี่ยนชื่อโฟลเดอร์ตามจริงของคุณ

       public function items($parameters)
    {
        $search = Arr::get($parameters, 'search');
        // $sort = Arr::get($parameters, 'sort', 'asc');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new Location;
        if ($search) {
            $query = $query->where('name', 'LIKE', '%' . trim($search) . '%');
        }
        // $query = $query->orderBy('sort', $sort);
        $query = $query->orderBy('id', 'desc');
        $results = $query->paginate($paginate);
        return $results;
    }
    public function index(Request $request)
    {
       $items = Location::query()->paginate(15);
        $items->pages = new Location();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();
        
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "จัดการสถานที่", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "จังหวัด/อำเภอ", "last" => 1],
        ];

        return view("$this->prefix.pages.$this->folder.index", [
            'segment' => $this->segment, 'prefix' => $this->prefix,
            'folder' => $this->folder, 'items' => $items, 'navs' => $navs
        ]);
    }

    public function add(Request $request)
    {
        // ดึงหมวดหลัก (จังหวัด) เพื่อใช้แสดงใน Dropdown
        $parents = Location::whereNull('parent_id')->get();
            $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "จัดการสถานที่", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "จังหวัด/อำเภอ", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.add", [
            'segment' => $this->segment, 'prefix' => $this->prefix,
            'folder' => $this->folder, 'parents' => $parents, 'navs' => $navs
        ]);
    }

    public function edit(Request $request, $id)
    {
        $data = Location::find($id);
        $parents = Location::whereNull('parent_id')->where('id', '!=', $id)->get();
         $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "จัดการสถานที่", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "จังหวัด/อำเภอ", "last" => 1],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment, 'prefix' => $this->prefix,
            'folder' => $this->folder, 'data' => $data, 'parents' => $parents, 'navs' => $navs
        ]);
    }

   public function store($request, $id = null)
    {
        try {
            DB::beginTransaction();
            if ($id == null) {
                $data = new Location();
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
            } else {
                $data = Location::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
            }
            $data->name = $request->name;
            $data->parent_id = $request->parent_id ?: null;

           

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
            LogsController::logInsert($error_line, $error_url, $error_log, $type_log);
            return view("$this->prefix.alert.alert", [
                'url' => $error_url,
                'title' => "ไม่สามารถทำรายการได้",
                'text' => "กรุณาทำรายการใหม่อีกครั้ง !",
                'icon' => 'error'
            ]);
        }
    }
    public function insert(Request $request) { return $this->store($request); }
    public function update(Request $request, $id) { return $this->store($request, $id); }

    public function destroy(Request $request)
    {
        $item = Location::find($request->id);
        if ($item) {
            $item->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}