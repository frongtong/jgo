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
use App\Models\Backend\Question;
// use App\Models\question;

use Intervention\Image\ImageManagerStatic as Image;

class QuestionController extends Controller
{
    protected $segment = 'webpanel';
    protected $prefix = 'back-end';
    protected $folder = 'question';
    public function items($parameters)
    {
        $keyword = Arr::get($parameters, 'keyword');
        $status = Arr::get($parameters, 'status');
        $paginate = Arr::get($parameters, 'total', 15);
        $query = new Question;
        if ($keyword) {
            $query = $query->where('head_th', 'LIKE', '%' . trim($keyword) . '%');
            $query = $query->orWhere('head_en', 'LIKE', '%' . trim($keyword) . '%');
        }
        if ($status) {
            $query = $query->where('status',$status);
        }
        $query = $query->orderBy('sort', 'asc');
        $results = $query->paginate($paginate);
        return $results;
    }

    public function index(Request $request)
    {
        $items = $this->items($request->all());
        $items->pages = new Question();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "คำถามที่พบบ่อย", "last" => 0],
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
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "คำถามที่พบบ่อย", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เนื้อหา", "last" => 1],
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
        $data = Question::find($id);
        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "คำถามที่พบบ่อย", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "เนื้อหา", "last" => 1],
            '2' => ['url' => "$this->segment/$this->folder/edit/$id", 'name' => "Edit", "last" => 2],
        ];
        return view("$this->prefix.pages.$this->folder.edit", [
            'segment' => $this->segment,
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'navs' => $navs,
            'id' => $id,
            'data' => $data
        ]);
    }

    public function destroy(Request $request)
    {
        $datas = Question::find(explode(',', $request->id));
        if (@$datas) {
            foreach ($datas as $data) {
                Question::where('sort', '>', $data->sort)
                    ->update([
                        'sort' => DB::raw('`sort` - 1'),
                    ]);
                $query = Question::destroy($data->id);
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
            if ($id == null) {
                $sort = Question::max('sort') + 1;
                $data = new Question();

                $data->sort = $sort;
                $data->created_at = date('Y-m-d H:i:s');
                $data->updated_at = date('Y-m-d H:i:s');
            } else {
                $data = Question::find($id);
                $data->updated_at = date('Y-m-d H:i:s');
            }
            $data->head_th = $request->head_th;
            $data->head_en = $request->head_en;
            $data->description_en = $request->description_en;
            $data->description_th = $request->description_th;
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

    public function updateStatus(Request $request)
    {
        try {
            $item = Question::find($request->id);
            $item->status = $request->status;
            $item->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }

    // Sort หลิว
    public function updateSortOrder(Request $request)
    {
        $order = $request->order;

        foreach ($order as $item) {
            Question::where('id', $item['id'])->update(['sort' => $item['sort']]);
        }

        return response()->json(['success' => true]);
    }

    // public function updateRowOrder(Request $request, $id = null)
    // {
    //     $id = $request->input('id');
    //     $old_sort = (int) $request->input('old_sort');
    //     $new_sort = (int) $request->input('new_sort');

    //     $question = Question::find($id); 
 
    //     if ($old_sort > $new_sort) {
    //         Question::where('sort', '>=', $new_sort)
    //             ->where('sort', '<', $old_sort)
    //             ->update([
    //                'sort' => DB::raw('`sort` + 1'), 
    //             ]);
    //     } else {
    //         Question::where('sort', '<=', $new_sort)
    //             ->where('sort', '>', $old_sort)
    //             ->update([
    //                 'sort' => DB::raw('`sort` - 1'), 
    //             ]);
    //     }

    //     $question->sort = $new_sort;
    //     $question->save(); 
       
    //     return response()->json(['success' => true]);
    // }
}
