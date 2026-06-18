<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

use App\Http\Controllers\Webpanel\LogsController;

use App\Models\Backend\Article; 
use App\Models\Backend\ArticleCategory1; 
use App\Models\Backend\ArticleCategory2;

class ArticleController extends Controller
{
      public function index(Request $request)
    {
        try {

            $data = Article::with([
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
