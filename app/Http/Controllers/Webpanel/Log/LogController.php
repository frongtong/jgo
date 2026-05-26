<?php

namespace App\Http\Controllers\Webpanel\Log;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Functions\MenuControl;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Webpanel\LogsController;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\DB;

use App\Models\Authuse\Admin;
use App\Models\Backend\RoleModel;
use App\Models\Backend\LogsModel;
use App\Models\Backend\Logs_listModel;

class LogController extends Controller
{
    protected $prefix = 'back-end';
    protected $segment = 'webpanel';
    protected $controller = 'user';
    protected $folder_controller = 'log';
    protected $folder = 'log';
    protected $name_page = "รายการผู้ดูแล";

    public function items($parameters)
    {
        $search = Arr::get($parameters, 'search');
        $paginate = Arr::get($parameters, 'total', 15);

        $date = date('Y-m-d');
        if (@$parameters['due_date'] != null) {
            $date = $parameters['due_date'];
        }
        $log = LogsModel::where('date',$date)->first();
        if($log){
            $query = Logs_listModel::where('log_id',$log->id);
            $query = $query->orderBy('id', 'DESC');
        }else{
            $query = Logs_listModel::where('log_id',0);
        }
        $results = $query->paginate($paginate);
        return $results;
    }

    public function index(Request $request)
    {
        $items = $this->items($request->all());
        $items->pages = new \stdClass();
        $items->pages->start = ($items->perPage() * $items->currentPage()) - $items->perPage();

        $navs = [
            '0' => ['url' => "javascript:void(0)", 'name' => "Administrator", "last" => 0],
            '1' => ['url' => "$this->segment/$this->folder", 'name' => "User", "last" => 1],
        ];

        $due_date = date('Y-m-d');
        if($request->due_date){
            $due_date = date('Y-m-d',strtotime($request->due_date));
        }

        return view("$this->prefix.pages.$this->folder_controller.index", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'navs' => $navs,
            'items' => $items,
            'due_date'=>$due_date,
        ]);
    }

    public function show_log(Request $request)
    {

        $data = Logs_listModel::find($request->id);
        return view("$this->prefix.pages.$this->folder.show-log", [
            'prefix' => $this->prefix,
            'folder' => $this->folder,
            'segment' => $this->segment,
            'name_page' => $this->name_page,
            'data' => $data,
        ]);
    }


}
