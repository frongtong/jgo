<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

use App\Http\Controllers\Webpanel\LogsController;

use App\Models\Backend\Video; 
use App\Models\Backend\VideoCategory1; 
use App\Models\Backend\VideoCategory2;

class VideoController extends Controller
{
      public function index(Request $request)
    {
        try {

            $data = Video::with([
                'mainCategory',
                'subCategory'
            ])
            ->where('status', 'on')
            ->latest()
            ->get();

            return response()->json([
                'status' => true,
                'message' => 'ดึงข้อมูลสำเร็จ',
                'results' => $data
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => 'เกิดข้อผิดพลาด',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);

        }
    }
}
