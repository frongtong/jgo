<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

use App\Http\Controllers\Webpanel\LogsController;

use App\Models\Backend\Vocabulary;
use App\Models\Backend\VoCategory1;
use App\Models\Backend\VoCategory2;

class VocabularyController extends Controller
{
    public function index(Request $request)
    {
        try {

            $data = Vocabulary::with([
                'mainCategory',
                'subCategory'
            ])
            ->withCount([
                'items as items_count' => function ($query) {
                    $query->where('status', 'on');
                }
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

    public function show($id)
    {
        try {
            $data = Vocabulary::with([
                'mainCategory',
                'subCategory',
                'items' => function ($query) {
                    $query->where('status', 'on')
                        ->orderBy('sort_order')
                        ->orderBy('id');
                }
            ])
            ->where('status', 'on')
            ->find($id);

            if (!$data) {
                return response()->json([
                    'status' => false,
                    'message' => 'ไม่พบบทความคำศัพท์'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'ดึงข้อมูล Flash Card สำเร็จ',
                'results' => $data
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูล Flash Card',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
