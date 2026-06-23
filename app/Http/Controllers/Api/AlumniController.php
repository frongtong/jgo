<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Backend\Alumni;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        $items = Alumni::with('banners')
            ->where('status', 'on')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'ดึงข้อมูลรุ่นพี่ศิษย์เก่าสำเร็จ',
            'results' => $items,
        ]);
    }

    public function show($id)
    {
        $item = Alumni::with('banners')
            ->where('status', 'on')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'ไม่พบข้อมูลรุ่นพี่ศิษย์เก่า',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'ดึงข้อมูลสำเร็จ',
            'results' => $item,
        ]);
    }
}
