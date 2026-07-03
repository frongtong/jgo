<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\VocabularyController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AlumniController;
use App\Http\Controllers\Api\StudyFurtherController;



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
        Route::prefix('jobs')->group(function () {
            Route::get('/filters', [JobController::class, 'filters']);
            Route::get('/', [JobController::class, 'index']);
            Route::post('/search', [JobController::class, 'index']);
            Route::post('/favorite', [JobController::class, 'favorite']);
            Route::get('/favorites', [JobController::class, 'favoriteList']);
            Route::delete('/favorite/{jobId}', [JobController::class, 'unfavorite'])->whereNumber('jobId');
            Route::post('/favorite/{jobId}/remove', [JobController::class, 'unfavorite'])->whereNumber('jobId');
            Route::post('/apply', [JobController::class, 'apply']);
            Route::get('/applications', [JobController::class, 'applications']);
        });
        Route::get('/vocabulary', [VocabularyController::class, 'index']);
        Route::get('/video', [VideoController::class, 'index']);
        Route::get('/article', [ArticleController::class, 'index']);
        Route::get('/alumni', [AlumniController::class, 'index']);
        Route::get('/alumni/{id}', [AlumniController::class, 'show'])->whereNumber('id');
        Route::get('/studyfurther', [StudyFurtherController::class, 'index']);
        Route::get('/studyfurther/{id}', [StudyFurtherController::class, 'show'])->whereNumber('id');
        Route::get('/notifications', [MemberController::class, 'notifications']);
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
            Route::put(
                'profile',
                [MemberController::class, 'updateProfile']
            );
            Route::post(
                'profile/update',
                [MemberController::class, 'updateApplication']
            );
            Route::put(
                'application',
                [MemberController::class, 'updateApplication']
            );
            Route::post(
                'application/update',
                [MemberController::class, 'updateApplication']
            );
            Route::put(
                'password',
                [MemberController::class, 'updatePassword']
            );
            Route::post(
                'password/update',
                [MemberController::class, 'updatePassword']
            );
            Route::post(
                'logout',
                [MemberController::class, 'logout']
            );

        });

    });
