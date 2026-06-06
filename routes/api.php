<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\JobController;



    Route::post(
        'register',
        [MemberController::class, 'register']
    );

    Route::post(
        'login',
        [MemberController::class, 'login']
    );
    
    Route::middleware([
            'auth:api_member'
        ])->group(function () {


    
        Route::get('/banners', [BannerController::class, 'index']);
        Route::get('/jobs', [JobController::class, 'index']);
        Route::prefix('member')->group(function () {
            Route::post(
                'profile',
                [MemberController::class, 'profile']
            );
            Route::post(
                'logout',
                [MemberController::class, 'logout']
            );

        });

    });