<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\VocabularyController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AlumniController;



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
        Route::get('/vocabulary', [VocabularyController::class, 'index']);
        Route::get('/video', [VideoController::class, 'index']);
        Route::get('/article', [ArticleController::class, 'index']);
        Route::get('/alumni', [AlumniController::class, 'index']);
        Route::get('/alumni/{id}', [AlumniController::class, 'show'])->whereNumber('id');
        Route::prefix('member')->group(function () {
            Route::post(
                '{memberId}/parents',
                [MemberController::class, 'createParent']
            )->whereNumber('memberId');

            Route::get(
                '{memberId}/parents',
                [MemberController::class, 'parents']
            )->whereNumber('memberId');

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
