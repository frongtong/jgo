<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\BannerController;


Route::prefix('member')->group(function () {

    Route::post(
        'register',
        [MemberController::class, 'register']
    );

    Route::post(
        'login',
        [MemberController::class, 'login']
    );
    Route::middleware('auth:sanctum')
    ->group(function () {

    
        Route::get('/banners', [BannerController::class, 'index']);
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