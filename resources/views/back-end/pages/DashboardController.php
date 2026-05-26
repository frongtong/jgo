<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DateTime;
use Session;
use Response;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;

use Yajra\DataTables\Facades\DataTables;
use File;
use Folklore\Image\Facades\Image;
use Gloudemans\Shoppingcart\Facades\Cart;
use Auth;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Excel;
use App\Models\Backend\BookingRevenue;
use App\Models\Backend\BookingItem ;
use App\Models\Backend\Booking;

class DashboardController extends Controller
{
    // protected $prefix = '';
    // protected $segment = 'webpanel';
    // protected $controller = 'dashboard';
    // protected $folder = 'dashboard';

    protected $route_param_name = 'dashboard';
    protected $route_name = 'webpanel.dashboard';
    protected $view_path = 'dashboard';

    public function index(Request $request)
    {
         $salesData = BookingRevenue::selectRaw('MONTH(created_at) as month, SUM(total_income) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [];
        $totals = [];

        foreach($salesData as $data){
            $months[] = \Carbon\Carbon::create()->month($data->month)->format('M');
            $totals[] = (float) $data->total;
        }

       
        if(empty($months)){
            $months = ['Jan','Feb','Mar','Apr','May','Jun'];
            $totals = [15000, 12000, 18000, 20000, 17000, 19000];
        }
        $topProducts = BookingItem::select('product_id', 'name', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

       
        $labels = $topProducts->pluck('name')->toArray();
        $data = $topProducts->pluck('total_quantity')->toArray(); 
        $boats = Booking::select('product_id', 'products.name', DB::raw('COUNT(bookings.id) as total_sales'))
            ->join('products', 'bookings.product_id', '=', 'products.id')
            ->groupBy('product_id', 'products.name')
            ->orderByDesc('total_sales')
            ->limit(5) 
            ->get();

        
        $labels_book = $boats->pluck('name')->toArray();
        $data_book = $boats->pluck('total_sales')->toArray();

        $totalSales = BookingRevenue::sum('total_income'); 
        $totalBookings = Booking::count();
        $totalProductsSold = BookingItem::sum('quantity');


            return view($this->view_path,  compact('months','totals','labels','data','labels_book','data_book','totalSales','totalBookings','totalProductsSold'));
        }

   

    public static function uploadimage_text(Request $request)
    {

        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('uploads/texteditor/'), $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('uploads/texteditor/' . $fileName);
            $msg = "อัพโหลดรูปภาพสำเร็จ";
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
}
