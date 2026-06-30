<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Backend\StudyFurther;
use Illuminate\Http\Request;

class StudyFurtherController extends Controller
{
    public function index(Request $request)
    {
        try {
            $limit = (int) $request->get('limit', 3);
            $limit = $limit > 0 ? min($limit, 50) : 3;

            $items = StudyFurther::where('status', 'on')
                ->orderByDesc('sort_order')
                ->orderByDesc('id')
                ->limit($limit)
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'ดึงข้อมูลโอกาสเรียนต่อสำเร็จ',
                'results' => $items,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'เกิดข้อผิดพลาด',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $item = StudyFurther::where('status', 'on')->find($id);

            if (!$item) {
                return response()->json([
                    'status' => false,
                    'message' => 'ไม่พบข้อมูลโอกาสเรียนต่อ',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'ดึงข้อมูลสำเร็จ',
                'results' => $item,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'เกิดข้อผิดพลาด',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }
}
