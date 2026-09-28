<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Backend\LearningCenterBanner;
use Illuminate\Http\JsonResponse;

class LearningCenterController extends Controller
{
    public function banner(): JsonResponse
    {
        $banner = LearningCenterBanner::where('status', 'on')
            ->latest('id')
            ->first();

        $data = $banner ? [
            'id' => $banner->id,
            'image_url' => $banner->image_url,
            'full_url' => $banner->image_url ? asset($banner->image_url) : null,
            'status' => $banner->status,
            'created_at' => $banner->created_at,
            'updated_at' => $banner->updated_at,
        ] : null;

        return response()->json([
            'status' => true,
            'success' => true,
            'data' => $data,
        ]);
    }
}
