<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webpanel as Webpanel;
use App\Http\Controllers\Functions as Functions;
use Illuminate\Http\Request;

Route::get('webpanel/login', [Webpanel\AuthController::class, 'getLogin']);
Route::post('webpanel/login', [Webpanel\AuthController::class, 'postLogin']);
Route::get('webpanel/logout', [Webpanel\AuthController::class, 'logOut']);
Route::get('/handle', [Webpanel\NewsnewController::class, 'handle'])->where(['id' => '[0-9]+']);
Route::get(
    'api/location/city/{id}',
    [Webpanel\CompanyController::class, 'getCity']
);
Route::group(['middleware' => 'Admin'], function () {


    Route::post('/upload-image', function (Request $request) {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/uploads'), $filename);

            $url = asset('storage/uploads/' . $filename);
            return response()->json(['url' => $url]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    });

    Route::prefix('webpanel')->group(function () {
        Route::get('/', [Webpanel\DashboardController::class, 'index']);

        Route::prefix('menu')->group(function () {
            Route::get('/', [Webpanel\MenuController::class, 'index'])->name('webpanel.menu');
            Route::post('/update-status', [Webpanel\MenuController::class, 'updateStatus']);
            Route::get('/edit/{id}', [Webpanel\MenuController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\MenuController::class, 'update'])->name('webpanel.menu.update')->where(['id' => '[0-9]+']);
        });

        Route::prefix('home')->group(function () {
            Route::post('/update_row/{id}', [Webpanel\HomeController::class, 'updateRowOrder']);
            Route::get('/', [Webpanel\HomeController::class, 'index'])->name('webpanel.home');
            Route::get('/add', [Webpanel\HomeController::class, 'add']);
            Route::post('/add', [Webpanel\HomeController::class, 'insert']);
            Route::get('/edit/{id}', [Webpanel\HomeController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\HomeController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::get('/destroy/{id}', [Webpanel\HomeController::class, 'destroy'])->where(['id' => '[0-9]+']);
            Route::post('/update-status', [Webpanel\HomeController::class, 'updateStatus']);
            Route::post('/update-sort-order', [Webpanel\HomeController::class, 'updateSortOrder']);
            Route::post('/update_row/{id}', [Webpanel\HomeController::class, 'updateRowOrder']);
        });
        Route::prefix('member')->group(function () {
            Route::get('/', [Webpanel\MemberController::class, 'index'])->name('webpanel.member');
            Route::get('/add', [Webpanel\MemberController::class, 'add']);
            Route::post('/add', [Webpanel\MemberController::class, 'insert']);
            Route::post('/update-sort-order', [Webpanel\MemberController::class, 'updateSortOrder']);
            Route::post('/update-status', [Webpanel\MemberController::class, 'updateStatus']);
            Route::get('/view/{id}', [Webpanel\MemberController::class, 'view'])->where(['id' => '[0-9]+']);
            Route::get('/edit/{id}', [Webpanel\MemberController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\MemberController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}/parents', [Webpanel\MemberController::class, 'createParent'])->where(['id' => '[0-9]+']);
            Route::get('/destroy/{id}', [Webpanel\MemberController::class, 'destroy'])->where(['id' => '[0-9]+']);
            Route::post('/destroy/logo', [Webpanel\MemberController::class, 'destroy_logo'])->where(['id' => '[0-9]+']);
        });
        Route::prefix('company')->group(function () {
            Route::get('/', [Webpanel\CompanyController::class, 'index'])->name('webpanel.company');
            Route::get('/add', [Webpanel\CompanyController::class, 'add']);
            Route::post('/add', [Webpanel\CompanyController::class, 'insert']);
            Route::get('/edit/{id}', [Webpanel\CompanyController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\CompanyController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::get('/destroy/{id}', [Webpanel\CompanyController::class, 'destroy'])->where(['id' => '[0-9]+']);
            Route::post('/update-status', [Webpanel\CompanyController::class, 'updateStatus']);
        });


        Route::prefix('/bannersub')->group(function () {
            Route::get('/', [Webpanel\BannerSubController::class, 'index'])->name('webpanel.bannersub');
            Route::get('/add', [Webpanel\BannerSubController::class, 'add']);
            Route::post('/add', [Webpanel\BannerSubController::class, 'insert']);
            Route::get('/edit/{id}', [Webpanel\BannerSubController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\BannerSubController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::get('/destroy/{id}', [Webpanel\BannerSubController::class, 'destroy'])->where(['id' => '[0-9]+']);
            Route::post('/update-status', [Webpanel\BannerSubController::class, 'updateStatus']);
        });
        Route::prefix('brand')->group(function () {
            Route::get('/', [Webpanel\BrandController::class, 'index'])->name('webpanel.brand');
            Route::get('/add', [Webpanel\BrandController::class, 'add'])->name('webpanel.brand.add');
            Route::post('/add', [Webpanel\BrandController::class, 'insert'])->name('webpanel.brand.insert');
            Route::get('/edit/{id}', [Webpanel\BrandController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\BrandController::class, 'update'])->where(['id' => '[0-9]+'])->name('webpanel.brand.update');
            Route::get('/destroy/{id}', [Webpanel\BrandController::class, 'destroy'])->where(['id' => '[0-9]+']);
            Route::post('/destroy/url', [Webpanel\BrandController::class, 'destroy_url'])->where(['id' => '[0-9]+']);
            Route::post('/update-sort-order', [Webpanel\BrandController::class, 'updateSortOrder']); //หลิว
            Route::post('/update-status', [Webpanel\BrandController::class, 'updateStatus']);
        });
        Route::prefix('attribute')->group(function () {
            Route::get('/', [Webpanel\AttributeController::class, 'index'])->name('webpanel.attribute');
            Route::get('/add', [Webpanel\AttributeController::class, 'add'])->name('webpanel.attribute.add');
            Route::post('/add', [Webpanel\AttributeController::class, 'insert'])->name('webpanel.attribute.insert');
            Route::get('/edit/{id}', [Webpanel\AttributeController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\AttributeController::class, 'update'])->where(['id' => '[0-9]+'])->name('webpanel.attribute.update');
            Route::get('/destroy/{id}', [Webpanel\AttributeController::class, 'destroy'])->where(['id' => '[0-9]+']);
            Route::post('/destroy/url', [Webpanel\AttributeController::class, 'destroy_url'])->where(['id' => '[0-9]+']);
            Route::post('/update-status', [Webpanel\AttributeController::class, 'updateStatus']);
        });
        Route::prefix('category1')->group(function () {
            Route::get('/', [Webpanel\Category1Controller::class, 'index'])->name('webpanel.category1');
            Route::get('/add', [Webpanel\Category1Controller::class, 'add'])->name('webpanel.category1.add');
            Route::post('/add', [Webpanel\Category1Controller::class, 'insert']);
            Route::get('/edit/{id}', [Webpanel\Category1Controller::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\Category1Controller::class, 'update'])->where(['id' => '[0-9]+'])->name('webpanel.category1.update');
            Route::post('/destroy', [Webpanel\Category1Controller::class, 'destroy']);
            Route::post('/update-status', [Webpanel\Category1Controller::class, 'updateStatus']);
            Route::post('/update-sort-order', [Webpanel\Category1Controller::class, 'updateSortOrder']); //หลิว
        });

        Route::prefix('category2')->group(function () {
            Route::get('/get/{category1_id}', [Webpanel\Category2Controller::class, 'getCategory2']);
            Route::get('/{category1_id}', [Webpanel\Category2Controller::class, 'index'])->where(['category1_id' => '[0-9]+'])->name('webpanel.category2');
            Route::get('/add/{category1_id}', [Webpanel\Category2Controller::class, 'add'])->where('category1_id', '[0-9]+')->name('webpanel.category2.add');
            Route::post('/add/{category1_id}', [Webpanel\Category2Controller::class, 'insert'])->name('webpanel.category2.insert');
            Route::get('{category1_id}/edit/{id}', [Webpanel\Category2Controller::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('{category1_id}/edit/{id}', [Webpanel\Category2Controller::class, 'update'])->where(['id' => '[0-9]+'])->name('webpanel.category2.update');
            Route::post('{category1_id}/destroy', [Webpanel\Category2Controller::class, 'destroy']);
            Route::post('/{category1_id}/update-status', [Webpanel\Category2Controller::class, 'updateStatus']);
            Route::post('{category1_id}/update-sort-order', [Webpanel\Category2Controller::class, 'updateSortOrder']); //หลิว
        });
        Route::prefix('job')->group(function () {
            Route::get('/', [Webpanel\JobController::class, 'index'])->name('webpanel.job');
            Route::get('/add', [Webpanel\JobController::class, 'add'])->name('webpanel.job.add');
            Route::post('/add', [Webpanel\JobController::class, 'insert']);
            Route::post('/edit/{id}', [Webpanel\JobController::class, 'update'])->where(['id' => '[0-9]+'])->name('webpanel.job.update');
            Route::get('/edit/{id}', [Webpanel\JobController::class, 'edit'])->where(['id' => '[0-9]+']);

            Route::post('/destroy', [Webpanel\JobController::class, 'destroy']);
            Route::post('/update-status', [Webpanel\JobController::class, 'updateStatus']);
        });
        Route::prefix('jobapplication')->group(function () {
            Route::get('/', [Webpanel\JobApplicationController::class, 'index'])->name('webpanel.jobapplication');
            Route::get('/export', [Webpanel\JobApplicationController::class, 'export'])->name('webpanel.jobapplication.export');
            Route::post('/edit/{id}', [Webpanel\JobApplicationController::class, 'update'])->where(['id' => '[0-9]+'])->name('webpanel.job_application.update');
            Route::get('/edit/{id}', [Webpanel\JobApplicationController::class, 'edit'])->where(['id' => '[0-9]+']);

            Route::post('/destroy', [Webpanel\JobApplicationController::class, 'destroy']);
        });
        Route::prefix('general-notifications')->group(function () {
            Route::get('/', [Webpanel\GeneralNotificationController::class, 'index'])->name('webpanel.general-notifications');
            Route::get('/add', [Webpanel\GeneralNotificationController::class, 'add'])->name('webpanel.general-notifications.add');
            Route::post('/add', [Webpanel\GeneralNotificationController::class, 'insert']);
            Route::get('/edit/{id}', [Webpanel\GeneralNotificationController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\GeneralNotificationController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::post('/destroy', [Webpanel\GeneralNotificationController::class, 'destroy']);
            Route::post('/update-status', [Webpanel\GeneralNotificationController::class, 'updateStatus']);
        });
        Route::prefix('learning-center')->group(function () {
            Route::get('/', [Webpanel\LearningCenterController::class, 'index'])->name('webpanel.learning-center');
            Route::post('/', [Webpanel\LearningCenterController::class, 'update']);
        });

        Route::prefix('location')->group(function () {
            // หน้าหลักรายการสถานที่ทั้งหมด
            Route::get('/', [Webpanel\LocationController::class, 'index'])->name('webpanel.location');

            // หน้าเพิ่มสถานที่ (จังหวัด/อำเภอ)
            Route::get('/add', [Webpanel\LocationController::class, 'add'])->name('webpanel.location.add');
            Route::post('/add', [Webpanel\LocationController::class, 'insert']);

            // หน้าแก้ไขสถานที่
            Route::get('/edit/{id}', [Webpanel\LocationController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\LocationController::class, 'update'])->where(['id' => '[0-9]+'])->name('webpanel.location.update');

            // การจัดการอื่นๆ
            Route::post('/destroy', [Webpanel\LocationController::class, 'destroy']);
            Route::post('/update-status', [Webpanel\LocationController::class, 'updateStatus']);
            Route::post('/update-sort-order', [Webpanel\LocationController::class, 'updateSortOrder']);
        });
        Route::prefix('vocategory1')->group(function () {
            Route::get('/', [Webpanel\VoCategory1Controller::class, 'index'])->name('webpanel.vocategory1');
            Route::get('/add', [Webpanel\VoCategory1Controller::class, 'add'])->name('webpanel.vocategory1.add');
            Route::post('/add', [Webpanel\VoCategory1Controller::class, 'insert']);
            Route::get('/edit/{id}', [Webpanel\VoCategory1Controller::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\VoCategory1Controller::class, 'update'])->where(['id' => '[0-9]+'])->name('webpanel.vocategory1.update');
            Route::post('/destroy', [Webpanel\VoCategory1Controller::class, 'destroy']);
            Route::post('/update-status', [Webpanel\VoCategory1Controller::class, 'updateStatus']);
            Route::post('/update-sort-order', [Webpanel\VoCategory1Controller::class, 'updateSortOrder']); //หลิว
        });

        Route::prefix('vocategory2')->group(function () {
            Route::get('/get/{category1_id}', [Webpanel\VoCategory2Controller::class, 'getCategory2']);
            Route::get('/{category1_id}', [Webpanel\VoCategory2Controller::class, 'index'])->where(['category1_id' => '[0-9]+'])->name('webpanel.vocategory2');
            Route::get('/add/{category1_id}', [Webpanel\VoCategory2Controller::class, 'add'])->where('category1_id', '[0-9]+')->name('webpanel.vocategory2.add');
            Route::post('/add/{category1_id}', [Webpanel\VoCategory2Controller::class, 'insert'])->name('webpanel.category2.insert');
            Route::get('{category1_id}/edit/{id}', [Webpanel\VoCategory2Controller::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('{category1_id}/edit/{id}', [Webpanel\VoCategory2Controller::class, 'update'])->where(['id' => '[0-9]+'])->name('webpanel.vocategory2.update');
            Route::post('{category1_id}/destroy', [Webpanel\VoCategory2Controller::class, 'destroy']);
            Route::post('/destroy/url', [Webpanel\VoCategory2Controller::class, 'destroy_url'])->where(['id' => '[0-9]+']);
            Route::post('/{category1_id}/update-status', [Webpanel\VoCategory2Controller::class, 'updateStatus']);
            Route::post('{category1_id}/update-sort-order', [Webpanel\VoCategory2Controller::class, 'updateSortOrder']); //หลิว
        });
        Route::prefix('vocabulary')->group(function () {
            Route::get('/', [Webpanel\VocabularyController::class, 'index'])->name('webpanel.vocabulary');
            Route::get('/add', [Webpanel\VocabularyController::class, 'add'])->name('webpanel.vocabulary.add');
            Route::post('/add', [Webpanel\VocabularyController::class, 'insert']);
            Route::post('/edit/{id}', [Webpanel\VocabularyController::class, 'update'])->where(['id' => '[0-9]+'])->name('webpanel.vocabulary.update');
            Route::get('/edit/{id}', [Webpanel\VocabularyController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::get('/subcategory/{id}', [Webpanel\VocabularyController::class, 'getSubCategory'])->where(['id' => '[0-9]+']);
            Route::post('/destroy', [Webpanel\VocabularyController::class, 'destroy']);
            Route::post('/update-status', [Webpanel\VocabularyController::class, 'updateStatus']);
            Route::post('/update-sort-order', [Webpanel\VocabularyController::class, 'updateSortOrder']); //หลิว

            Route::get('/{vocabulary}/items', [Webpanel\VocabularyItemController::class, 'index'])->name('webpanel.vocabulary.items');
            Route::get('/{vocabulary}/items/add', [Webpanel\VocabularyItemController::class, 'create'])->name('webpanel.vocabulary.items.add');
            Route::post('/{vocabulary}/items/add', [Webpanel\VocabularyItemController::class, 'store'])->name('webpanel.vocabulary.items.store');
            Route::get('/{vocabulary}/items/{vocabularyItem}/edit', [Webpanel\VocabularyItemController::class, 'edit'])->name('webpanel.vocabulary.items.edit');
            Route::put('/{vocabulary}/items/{vocabularyItem}', [Webpanel\VocabularyItemController::class, 'update'])->name('webpanel.vocabulary.items.update');
            Route::delete('/{vocabulary}/items/{vocabularyItem}', [Webpanel\VocabularyItemController::class, 'destroy'])->name('webpanel.vocabulary.items.destroy');
            Route::patch('/{vocabulary}/items/{vocabularyItem}/status', [Webpanel\VocabularyItemController::class, 'updateStatus'])->name('webpanel.vocabulary.items.status');
        });
        Route::prefix('video')->group(function () {

            Route::get('/', [Webpanel\VideoController::class, 'index'])
                ->name('webpanel.video');

            Route::get('/add', [Webpanel\VideoController::class, 'add'])
                ->name('webpanel.video.add');

            Route::post('/add', [Webpanel\VideoController::class, 'insert']);

            Route::get('/edit/{id}', [Webpanel\VideoController::class, 'edit'])
                ->where(['id' => '[0-9]+']);

            Route::post('/edit/{id}', [Webpanel\VideoController::class, 'update'])
                ->where(['id' => '[0-9]+'])
                ->name('webpanel.video.update');

            Route::get('/subcategory/{id}', [Webpanel\VideoController::class, 'getSubCategory'])
                ->where(['id' => '[0-9]+']);

            Route::post('/destroy', [Webpanel\VideoController::class, 'destroy']);

            Route::post('/update-status', [Webpanel\VideoController::class, 'updateStatus']);

            Route::post('/update-sort-order', [Webpanel\VideoController::class, 'updateSortOrder']);

        });
        Route::prefix('videoctegory1')->group(function () {

            Route::get('/', [Webpanel\VideoCategory1Controller::class, 'index'])
                ->name('webpanel.videoctegory1');

            Route::get('/add', [Webpanel\VideoCategory1Controller::class, 'add'])
                ->name('webpanel.videocategory1.add');

            Route::post('/add', [Webpanel\VideoCategory1Controller::class, 'insert']);

            Route::get('/edit/{id}', [Webpanel\VideoCategory1Controller::class, 'edit'])
                ->where(['id' => '[0-9]+']);

            Route::post('/edit/{id}', [Webpanel\VideoCategory1Controller::class, 'update'])
                ->where(['id' => '[0-9]+'])
                ->name('webpanel.videocategory1.update');

            Route::post('/destroy', [Webpanel\VideoCategory1Controller::class, 'destroy']);

            Route::post('/update-status', [Webpanel\VideoCategory1Controller::class, 'updateStatus']);

            Route::post('/update-sort-order', [Webpanel\VideoCategory1Controller::class, 'updateSortOrder']);

        });
        Route::prefix('videoctegory2')->group(function () {
                    Route::get('/get/{category1_id}', [Webpanel\VideoCategory2Controller::class, 'getCategory2']);
                    Route::get('/{category1_id}', [Webpanel\VideoCategory2Controller::class, 'index'])->where(['category1_id' => '[0-9]+'])->name('webpanel.videoctegory2');
                    Route::get('/add/{category1_id}', [Webpanel\VideoCategory2Controller::class, 'add'])->where('category1_id', '[0-9]+')->name('webpanel.videoctegory2.add');
                    Route::post('/add/{category1_id}', [Webpanel\VideoCategory2Controller::class, 'insert'])->name('webpanel.category2.insert');
                    Route::get('{category1_id}/edit/{id}', [Webpanel\VideoCategory2Controller::class, 'edit'])->where(['id' => '[0-9]+']);
                    Route::post('{category1_id}/edit/{id}', [Webpanel\VideoCategory2Controller::class, 'update'])->where(['id' => '[0-9]+'])->name('webpanel.videoctegory2.update');
                    Route::post('{category1_id}/destroy', [Webpanel\VideoCategory2Controller::class, 'destroy']);
                    Route::post('/destroy/url', [Webpanel\VideoCategory2Controller::class, 'destroy_url'])->where(['id' => '[0-9]+']);
                    Route::post('/{category1_id}/update-status', [Webpanel\VideoCategory2Controller::class, 'updateStatus']);
                    Route::post('{category1_id}/update-sort-order', [Webpanel\VideoCategory2Controller::class, 'updateSortOrder']); //หลิว
        });
        Route::prefix('article')->group(function () {

            Route::get('/', [Webpanel\ArticleController::class, 'index'])
                ->name('webpanel.article');

            Route::get('/add', [Webpanel\ArticleController::class, 'add'])
                ->name('webpanel.article.add');

            Route::post('/add', [Webpanel\ArticleController::class, 'insert']);

            Route::get('/edit/{id}', [Webpanel\ArticleController::class, 'edit'])
                ->where(['id' => '[0-9]+']);

            Route::post('/edit/{id}', [Webpanel\ArticleController::class, 'update'])
                ->where(['id' => '[0-9]+'])
                ->name('webpanel.article.update');

            Route::get('/subcategory/{id}', [Webpanel\ArticleController::class, 'getSubCategory'])
                ->where(['id' => '[0-9]+']);

            Route::post('/destroy', [Webpanel\ArticleController::class, 'destroy']);

            Route::post('/update-status', [Webpanel\ArticleController::class, 'updateStatus']);

            Route::post('/update-sort-order', [Webpanel\ArticleController::class, 'updateSortOrder']);

        });

        Route::prefix('alumni')->group(function () {
            Route::get('/', [Webpanel\AlumniController::class, 'index'])->name('webpanel.alumni');
            Route::get('/add', [Webpanel\AlumniController::class, 'add'])->name('webpanel.alumni.add');
            Route::post('/add', [Webpanel\AlumniController::class, 'insert']);
            Route::get('/edit/{id}', [Webpanel\AlumniController::class, 'edit'])->whereNumber('id');
            Route::post('/edit/{id}', [Webpanel\AlumniController::class, 'update'])->whereNumber('id');
            Route::post('/destroy', [Webpanel\AlumniController::class, 'destroy']);
            Route::post('/update-status', [Webpanel\AlumniController::class, 'updateStatus']);
            Route::post('/update-sort-order', [Webpanel\AlumniController::class, 'updateSortOrder']);
        });
        
        Route::prefix('arcategory1')->group(function () {

            Route::get('/', [Webpanel\ArticleCategory1Controller::class, 'index'])
                ->name('webpanel.arcategory1');

            Route::get('/add', [Webpanel\ArticleCategory1Controller::class, 'add'])
                ->name('webpanel.arcategory1.add');

            Route::post('/add', [Webpanel\ArticleCategory1Controller::class, 'insert']);

            Route::get('/edit/{id}', [Webpanel\ArticleCategory1Controller::class, 'edit'])
                ->where(['id' => '[0-9]+']);

            Route::post('/edit/{id}', [Webpanel\ArticleCategory1Controller::class, 'update'])
                ->where(['id' => '[0-9]+'])
                ->name('webpanel.arcategory1.update');

            Route::post('/destroy', [Webpanel\ArticleCategory1Controller::class, 'destroy']);

            Route::post('/update-status', [Webpanel\ArticleCategory1Controller::class, 'updateStatus']);

            Route::post('/update-sort-order', [Webpanel\ArticleCategory1Controller::class, 'updateSortOrder']);

        });
        Route::prefix('arcategory2')->group(function () {
                    Route::get('/get/{category1_id}', [Webpanel\ArticleCategory2Controller::class, 'getCategory2']);
                    Route::get('/{category1_id}', [Webpanel\ArticleCategory2Controller::class, 'index'])->where(['category1_id' => '[0-9]+'])->name('webpanel.arcategory2');
                    Route::get('/add/{category1_id}', [Webpanel\ArticleCategory2Controller::class, 'add'])->where('category1_id', '[0-9]+')->name('webpanel.arcategory2.add');
                    Route::post('/add/{category1_id}', [Webpanel\ArticleCategory2Controller::class, 'insert'])->name('webpanel.category2.insert');
                    Route::get('{category1_id}/edit/{id}', [Webpanel\ArticleCategory2Controller::class, 'edit'])->where(['id' => '[0-9]+']);
                    Route::post('{category1_id}/edit/{id}', [Webpanel\ArticleCategory2Controller::class, 'update'])->where(['id' => '[0-9]+'])->name('webpanel.arcategory2.update');
                    Route::post('{category1_id}/destroy', [Webpanel\ArticleCategory2Controller::class, 'destroy']);
                    Route::post('/destroy/url', [Webpanel\ArticleCategory2Controller::class, 'destroy_url'])->where(['id' => '[0-9]+']);
                    Route::post('/{category1_id}/update-status', [Webpanel\ArticleCategory2Controller::class, 'updateStatus']);
                    Route::post('{category1_id}/update-sort-order', [Webpanel\ArticleCategory2Controller::class, 'updateSortOrder']); //หลิว
        });
               
         Route::prefix('studyfurther')->group(function () {

            Route::get('/', [Webpanel\StudyFurtherController::class, 'index'])
                ->name('webpanel.studyfurther');

            Route::get('/add', [Webpanel\StudyFurtherController::class, 'add'])
                ->name('webpanel.studyfurther.add');

            Route::post('/add', [Webpanel\StudyFurtherController::class, 'insert']);

            Route::get('/edit/{id}', [Webpanel\StudyFurtherController::class, 'edit'])
                ->where(['id' => '[0-9]+']);

            Route::post('/edit/{id}', [Webpanel\StudyFurtherController::class, 'update'])
                ->where(['id' => '[0-9]+'])
                ->name('webpanel.studyfurther.update');

            Route::get('/subcategory/{id}', [Webpanel\StudyFurtherController::class, 'getSubCategory'])
                ->where(['id' => '[0-9]+']);

            Route::post('/destroy', [Webpanel\StudyFurtherController::class, 'destroy']);

            Route::post('/update-status', [Webpanel\StudyFurtherController::class, 'updateStatus']);

            Route::post('/update-sort-order', [Webpanel\StudyFurtherController::class, 'updateSortOrder']);

        });

                Route::prefix('administrator')->group(function () {
                    Route::prefix('user')->group(function () {
                        Route::get('/', [Webpanel\Administrator\UserController::class, 'index']);
                        Route::get('/add', [Webpanel\Administrator\UserController::class, 'add']);
                        Route::post('/add', [Webpanel\Administrator\UserController::class, 'insert']);
                        Route::get('/edit/{id}', [Webpanel\Administrator\UserController::class, 'edit'])->where(['id' => '[0-9]+']);
                        Route::post('/edit/{id}', [Webpanel\Administrator\UserController::class, 'update'])->where(['id' => '[0-9]+']);
                        Route::get('/destroy/{id}', [Webpanel\Administrator\UserController::class, 'destroy'])->where(['id' => '[0-9]+']);
                        Route::get('/status/{id}', [Webpanel\Administrator\UserController::class, 'status'])->where(['id' => '[0-9]+']);
                    });

                    Route::prefix('permission')->group(function () {
                        Route::get('/', [Webpanel\Administrator\PermissionController::class, 'index']);
                        Route::get('/add', [Webpanel\Administrator\PermissionController::class, 'add']);
                        Route::post('/add', [Webpanel\Administrator\PermissionController::class, 'insert']);
                        Route::get('/edit/{id}', [Webpanel\Administrator\PermissionController::class, 'edit'])->where(['id' => '[0-9]+']);
                        Route::post('/edit/{id}', [Webpanel\Administrator\PermissionController::class, 'update'])->where(['id' => '[0-9]+']);
                        Route::get('/destroy/{id}', [Webpanel\Administrator\PermissionController::class, 'destroy'])->where(['id' => '[0-9]+']);
                        Route::get('/status/{id}', [Webpanel\Administrator\PermissionController::class, 'status'])->where(['id' => '[0-9]+']);
                    });
                });
                Route::prefix('home')->group(function () {
                    Route::prefix('company')->group(function () {
                        Route::get('/', [Webpanel\CompanyHomeController::class, 'index'])->name('home.company');
                        Route::get('/add', [Webpanel\CompanyHomeController::class, 'add']);
                        Route::post('/add', [Webpanel\CompanyHomeController::class, 'insert']);
                        Route::get('/edit/{id}', [Webpanel\CompanyHomeController::class, 'edit'])->where(['id' => '[0-9]+']);
                        Route::post('/edit/{id}', [Webpanel\CompanyHomeController::class, 'update'])->where(['id' => '[0-9]+']);
                        Route::get('/destroy/{id}', [Webpanel\CompanyHomeController::class, 'destroy'])->where(['id' => '[0-9]+']);
                    });
                });


                Route::prefix('analytics')->group(function () {
                    Route::get('/edit', [Webpanel\AnalyticsController::class, 'edit'])->name('webpanel.analytics');
                    Route::post('/edit/{id}', [Webpanel\AnalyticsController::class, 'update'])->where(['id' => '[0-9]+']);
                });
            });
        });
        
        
