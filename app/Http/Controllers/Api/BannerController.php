<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Backend\Home; // แบนเนอร์หลัก
use App\Models\Backend\BannerSub; // แบนเนอร์รอง
use Illuminate\Http\JsonResponse;

class BannerController extends Controller
{
    public function index(): JsonResponse
    {
        // ดึงข้อมูลแบนเนอร์หลัก 
        $mainBanners = Home::where('status','=','on')->orderBy('sort', 'asc')->get();

        // ดึงข้อมูลแบนเนอร์รอง
        $subBanners = BannerSub::get();

        $mainBanners->each(function ($item) {
        $item->full_url = asset($item->img_bg);
        });

        $subBanners->each(function ($item) {
            $item->full_url = asset($item->image);
        });

        return response()->json([
            'success' => true,
            'data' => [
                'main_banners' => $mainBanners,
                'sub_banners' => $subBanners,
            ]
        ]);
    }
}