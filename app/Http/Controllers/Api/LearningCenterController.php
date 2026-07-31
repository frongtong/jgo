<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Backend\LearningCenterBanner;
use Illuminate\Http\JsonResponse;

class LearningCenterController extends Controller
{
    public function banner(): JsonResponse
    {
        $banner = LearningCenterBanner::where('status', 'on')->first();

        if ($banner && $banner->image_url) {
            $banner->full_url = asset($banner->image_url);
        }

        return response()->json([
            'success' => true,
            'data' => $banner,
        ]);
    }
}
