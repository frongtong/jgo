<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Backend\Job;

class JobController extends Controller
{
    public function index(Request $request)
    {
        try {

            $jobs = Job::with([
                'company',
                'province',
            ])
            ->where('status', 'on')
            ->latest()
            ->get();

            return response()->json([
                'status' => true,
                'message' => 'ดึงข้อมูลสำเร็จ',
                'results' => $jobs
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