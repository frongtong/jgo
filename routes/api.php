<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\BannerController;

/*
|--------------------------------------------------------------------------
| Member Auth
|--------------------------------------------------------------------------
*/
Route::get('/banners', [BannerController::class, 'index']);
Route::prefix('member')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Auth
    |--------------------------------------------------------------------------
    */

    Route::post(
        'register',
        [MemberController::class, 'register']
    );

    Route::post(
        'login',
        [MemberController::class, 'login']
    );

    /*
    |--------------------------------------------------------------------------
    | Sanctum Protected
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        Route::post(
            'profile',
            [MemberController::class, 'profile']
        );

        /*
        |--------------------------------------------------------------------------
        | Logout
        |--------------------------------------------------------------------------
        */

        Route::post(
            'logout',
            [MemberController::class, 'logout']
        );

    });

});