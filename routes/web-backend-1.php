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
            Route::get('/edit/{id}', [Webpanel\MemberController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\MemberController::class, 'update'])->where(['id' => '[0-9]+']);
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
            Route::post('/destroy/url', [Webpanel\Category2Controller::class, 'destroy_url'])->where(['id' => '[0-9]+']);
            Route::post('/{category1_id}/update-status', [Webpanel\Category2Controller::class, 'updateStatus']);
            Route::post('{category1_id}/update-sort-order', [Webpanel\Category2Controller::class, 'updateSortOrder']); //หลิว
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
            
            // หากต้องการเพิ่ม Sort Order ในอนาคต
            Route::post('/update-sort-order', [Webpanel\LocationController::class, 'updateSortOrder']);
        });
        Route::prefix('product_detail')->group(function () {
            Route::get('/', [Webpanel\ProductDetailController::class, 'index'])->name('webpanel.product_detail');
            Route::get('/add', [Webpanel\ProductDetailController::class, 'add'])->name('webpanel.product_detail.add');
            Route::post('/add', [Webpanel\ProductDetailController::class, 'insert']);
            Route::get('/edit/{id}', [Webpanel\ProductDetailController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\ProductDetailController::class, 'update'])->where(['id' => '[0-9]+'])->name('webpanel.category1.update');
            Route::post('/destroy', [Webpanel\ProductDetailController::class, 'destroy']);
            Route::post('/update-status', [Webpanel\ProductDetailController::class, 'updateStatus']);
        });

        Route::prefix('product')->group(function () {
            Route::get('/', [Webpanel\ProductController::class, 'index'])->name('webpanel.product');
            Route::get('/add', [Webpanel\ProductController::class, 'add']);
            Route::post('/add', [Webpanel\ProductController::class, 'insert'])->name('webpanel.product.add');
            Route::get('/edit/{id}', [Webpanel\ProductController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\ProductController::class, 'update'])->where(['id' => '[0-9]+'])->name('webpanel.product.update');
            Route::get('/destroy/{id}', [Webpanel\ProductController::class, 'destroy'])->where(['id' => '[0-9]+']);
            Route::post('/update-status', [Webpanel\ProductController::class, 'updateStatus']);
            Route::post('/update-sort-order', [Webpanel\ProductController::class, 'updateSortOrder']); //หลิว
            Route::post('/update_row/{id}', [Webpanel\ProductController::class, 'updateRowOrder']);

            // ลบข้อมูลที่ append มา
            Route::post('/destroy/fileproduct', [Webpanel\ProductController::class, 'destroy_fileproduct'])->where(['id' => '[0-9]+']);
            Route::post('/destroy/fileproducticon', [Webpanel\ProductController::class, 'destroy_fileproduct_icon'])->where(['id' => '[0-9]+']);
            Route::post('/destroy/product_packing_size', [Webpanel\ProductController::class, 'destroy_product_packing_size'])->where(['id' => '[0-9]+']);
            Route::post('/destroy/product_detail', [Webpanel\ProductController::class, 'destroy_product_detail'])->where(['id' => '[0-9]+']);
            Route::post('/destroy/fileurl', [Webpanel\ProductController::class, 'destroy_fileurl'])->where(['id' => '[0-9]+']);
            Route::post('/destroy/fileurl', [Webpanel\ProductController::class, 'destroy_fileurl'])->where(['id' => '[0-9]+']);
            Route::post('/destroy/product_attribute', [Webpanel\ProductController::class, 'destroy_product_attribute'])->where(['id' => '[0-9]+']);
        });

        Route::prefix('service')->group(function () {
            Route::get('/', [Webpanel\ServiceController::class, 'index'])->name('webpanel.service');
            Route::get('/add', [Webpanel\ServiceController::class, 'add']);
            Route::post('/add', [Webpanel\ServiceController::class, 'insert']);
            Route::get('/edit/{id}', [Webpanel\ServiceController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\ServiceController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::get('/destroy/{id}', [Webpanel\ServiceController::class, 'destroy'])->where(['id' => '[0-9]+']);
            Route::post('/service/upload', [Webpanel\ServiceController::class, 'upload'])->name('service.upload');
            Route::post('/update-status', [Webpanel\ServiceController::class, 'updateStatus']);
        });

     

        Route::prefix('term')->group(function () {
            Route::get('/', [Webpanel\TermController::class, 'index'])->name('webpanel.term');
            Route::get('/edit/{id}', [Webpanel\TermController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\TermController::class, 'update'])->where(['id' => '[0-9]+'])->name('webpanel.term.update');
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

        Route::prefix('aboutus')->group(function () {
            Route::get('/', [Webpanel\AboutusController::class, 'index'])->name('webpanel.aboutus');
            Route::get('/add', [Webpanel\AboutusController::class, 'add']);
            Route::post('/add', [Webpanel\AboutusController::class, 'insert']);
            Route::post('/update-sort-order', [Webpanel\AboutusController::class, 'updateSortOrder']); //หลิว
            Route::post('/update-status', [Webpanel\AboutusController::class, 'updateStatus']);
            Route::get('/edit/{id}', [Webpanel\AboutusController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\AboutusController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::get('/destroy/{id}', [Webpanel\AboutusController::class, 'destroy'])->where(['id' => '[0-9]+']);
            Route::post('/destroy/logo', [Webpanel\AboutusController::class, 'destroy_logo'])->where(['id' => '[0-9]+']);

            Route::prefix('/banner')->group(function () {
                Route::get('/', [Webpanel\BannerAboutusController::class, 'index'])->name('webpanel.banner.aboutus');
                Route::post('/update_row/{id}', [Webpanel\BannerAboutusController::class, 'updateRowOrder']);
                Route::post('/update_status/{id}', [Webpanel\BannerAboutusController::class, 'status']);
                Route::get('/add', [Webpanel\BannerAboutusController::class, 'add']);
                Route::post('/add', [Webpanel\BannerAboutusController::class, 'insert']);
                Route::get('/edit/{id}', [Webpanel\BannerAboutusController::class, 'edit'])->where(['id' => '[0-9]+']);
                Route::post('/edit/{id}', [Webpanel\BannerAboutusController::class, 'update'])->where(['id' => '[0-9]+']);
                Route::get('/destroy/{id}', [Webpanel\BannerAboutusController::class, 'destroy'])->where(['id' => '[0-9]+']);
            });
            Route::prefix('/standard')->group(function () {
                Route::get('/', [Webpanel\StandardAboutusController::class, 'index'])->name('webpanel.aboutus.standard');
                Route::post('/update_row/{id}', [Webpanel\StandardAboutusController::class, 'updateRowOrder']);
                Route::post('/update_status/{id}', [Webpanel\StandardAboutusController::class, 'status']);
                Route::get('/add', [Webpanel\StandardAboutusController::class, 'add']);
                Route::post('/add', [Webpanel\StandardAboutusController::class, 'insert']);
                Route::get('/edit/{id}', [Webpanel\StandardAboutusController::class, 'edit'])->where(['id' => '[0-9]+']);
                Route::post('/edit/{id}', [Webpanel\StandardAboutusController::class, 'update'])->where(['id' => '[0-9]+']);
                Route::get('/destroy/{id}', [Webpanel\StandardAboutusController::class, 'destroy'])->where(['id' => '[0-9]+']);
          
            });
            Route::prefix('/home')->group(function () {
                Route::get('/edit', [Webpanel\AboutushomeController::class, 'edit'])->name('webpanel.aboutus.home');
                Route::post('/edit/{id}', [Webpanel\AboutushomeController::class, 'update'])->where(['id' => '[0-9]+']);
          
            });
            Route::prefix('/manager')->group(function () {
                Route::get('/', [Webpanel\ManageraboutsController::class, 'index'])->name('webpanel.aboutus.manager');
                Route::get('/add', [Webpanel\ManageraboutsController::class, 'add']);
                Route::post('/add', [Webpanel\ManageraboutsController::class, 'insert']);
                Route::get('/edit/{id}', [Webpanel\ManageraboutsController::class, 'edit'])->where(['id' => '[0-9]+']);
                Route::post('/edit/{id}', [Webpanel\ManageraboutsController::class, 'update'])->where(['id' => '[0-9]+']);
                Route::get('/destroy/{id}', [Webpanel\ManageraboutsController::class, 'destroy'])->where(['id' => '[0-9]+']);
                Route::post('/update-sort-order', [Webpanel\ManageraboutsController::class, 'updateSortOrder']); 
            });
        });

    
        Route::prefix('news_category')->group(function () {
            Route::get('/', [Webpanel\NewsCategoryController::class, 'index'])->name('news.category');
            Route::get('/add', [Webpanel\NewsCategoryController::class, 'add']);
            Route::post('/add', [Webpanel\NewsCategoryController::class, 'insert'])->name('news.category.add');
            Route::get('/edit/{id}', [Webpanel\NewsCategoryController::class, 'edit'])->name('news.category.update')->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\NewsCategoryController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::get('/destroy/{id}', [Webpanel\NewsCategoryController::class, 'destroy'])->where(['id' => '[0-9]+']);
        });

        Route::prefix('news_new')->group(function () {
            Route::get('/', [Webpanel\NewsnewController::class, 'index'])->name('webpanel.news_new');
            Route::get('/add', [Webpanel\NewsnewController::class, 'add']);
            Route::post('/add', [Webpanel\NewsnewController::class, 'insert'])->name('webpanel.news_new.add');
            Route::get('/edit/{id}', [Webpanel\NewsnewController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\NewsnewController::class, 'update'])->name('webpanel.news_new.update')->where(['id' => '[0-9]+']);
            Route::get('/destroy/{id}', [Webpanel\NewsnewController::class, 'destroy'])->where(['id' => '[0-9]+']);
            Route::post('/destroy/file', [Webpanel\NewsnewController::class, 'destroy_file'])->where(['id' => '[0-9]+']);
            Route::post('/destroy/ref', [Webpanel\NewsnewController::class, 'destroy_ref'])->where(['id' => '[0-9]+']);
            Route::post('/update-status', [Webpanel\NewsnewController::class, 'updateStatus']);
            Route::post('/destroy/image', [Webpanel\NewsnewController::class, 'destroy_image'])->where(['id' => '[0-9]+']);
            Route::post('/upload', [Webpanel\NewsnewController::class, 'upload'])->name('webpanel.news_new.upload');
            Route::get('/getnewsold/{id}', [Webpanel\NewsnewController::class,'get_description_old']);
            Route::get('/getnewsnew/{id}', [Webpanel\NewsnewController::class,'get_description_new']);
            Route::get('/getallnewsold', [Webpanel\NewsnewController::class,'getall_description_old']);
            Route::get('/getallnewsnew', [Webpanel\NewsnewController::class,'getall_description_new']);
            Route::get('/email', [Webpanel\NewsnewController::class, 'email'])->name('news_new.email');
            Route::post('/email/sendemail', [Webpanel\NewsnewController::class, 'sendemail']);
            Route::post('/addexcel', [Webpanel\NewsnewController::class, 'excelfileimport'])->name('import.excel');
            Route::get('/email/create-modal', [Webpanel\NewsnewController::class, 'create_modal']);
            Route::get('/email/edit-modal/{id}', [Webpanel\NewsnewController::class, 'edit_modal'])->where(['id' => '[0-9]+']);
            Route::post('/email/add', [Webpanel\NewsnewController::class, 'insert_modal'])->name('email.add');
            Route::post('/email/edit/{id}', [Webpanel\NewsnewController::class, 'update_modal'])->name('email.edit');
            Route::post('/email/destroy', [Webpanel\NewsnewController::class, 'destroy_email'])->where(['id' => '[0-9]+']);
      
            Route::post('/updateOrder', [Webpanel\NewsnewController::class, 'updateOrder'])->name('webpanel.news_new.updateOrder');

            Route::get('/history', [Webpanel\NewsnewController::class, 'indexhistory'])->name('news_new.history'); 
            Route::get('/history/detail/{id}', [Webpanel\NewsnewController::class, 'detailhistory']); 
            Route::get('/history/destroy/{id}', [Webpanel\NewsnewController::class, 'destroyhistory']);
        });

        Route::prefix('data-contact')->group(function () {
            Route::get('/', [Webpanel\DATAContactController::class, 'index'])->name('webpanel.data-contact');
            Route::get('/add', [Webpanel\DATAContactController::class, 'add']);
            Route::post('/add', [Webpanel\DATAContactController::class, 'insert']);
            Route::get('/edit/{id}', [Webpanel\DATAContactController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\DATAContactController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::get('/destroy/{id}', [Webpanel\DATAContactController::class, 'destroy'])->where(['id' => '[0-9]+']);
        });
        
        Route::prefix('contact')->group(function () {
            Route::post('/update_row', [Webpanel\ContactController::class, 'updateRowOrder'])->name('contact.update.row.order'); 
            Route::get('/report', [Webpanel\ContactController::class, 'report'])->name('contact.report');
            Route::post('/report/destroy', [Webpanel\ContactController::class, 'destroy_report']);
            Route::get('/export-contacts', [Webpanel\ContactController::class, 'export'])->name('contacts.export');
             Route::get('/export-contacts-email', [Webpanel\ContactController::class, 'export_email'])->name('contacts.export.email');
            Route::get('/', [Webpanel\ContactController::class, 'index'])->name('webpanel.contact');
            Route::get('/index_email', [Webpanel\ContactController::class, 'index_email'])->name('webpanel.contact.email');
            Route::post('/index_email/destroy', [Webpanel\ContactController::class, 'destroy_email'])->where(['id' => '[0-9]+']);
            Route::get('/add', [Webpanel\ContactController::class, 'add']);
            Route::post('/add', [Webpanel\ContactController::class, 'insert']);
            Route::get('/edit/{id}', [Webpanel\ContactController::class, 'edit'])->where(['id' => '[0-9]+']);
            Route::post('/edit/{id}', [Webpanel\ContactController::class, 'update'])->where(['id' => '[0-9]+']);
            Route::get('/destroy/{id}', [Webpanel\ContactController::class, 'destroy'])->where(['id' => '[0-9]+']);
            Route::post('/destroy/file', [Webpanel\ContactController::class, 'destroy_file'])->where(['id' => '[0-9]+']);
            Route::post('/destroy/qr', [Webpanel\ContactController::class, 'destroy_qr'])->where(['id' => '[0-9]+']);

            Route::prefix('url')->group(function () {
                Route::get('/category/index', [Webpanel\URLContactController::class, 'category_index']); 
                Route::get('/category/index/create-modal', [Webpanel\URLContactController::class, 'create_modal']);
                Route::get('/category/index/edit-modal/{id}', [Webpanel\URLContactController::class, 'edit_modal'])->where(['id' => '[0-9]+']);
                Route::get('/category/destroy/{id}', [Webpanel\URLContactController::class, 'category_destroy'])->where(['id' => '[0-9]+']);
                Route::post('/category/add', [Webpanel\URLContactController::class, 'category_store'])->name('category_store');
                Route::post('/category/edit/{id}', [Webpanel\URLContactController::class, 'category_store'])->name('category_edit');
                Route::get('/', [Webpanel\URLContactController::class, 'index'])->name('contact.url');
                Route::get('/add', [Webpanel\URLContactController::class, 'add']);
                Route::post('/add', [Webpanel\URLContactController::class, 'insert']);
                Route::get('/edit/{id}', [Webpanel\URLContactController::class, 'edit'])->where(['id' => '[0-9]+']);
                Route::post('/edit/{id}', [Webpanel\URLContactController::class, 'update'])->where(['id' => '[0-9]+']);
                Route::get('/destroy/{id}', [Webpanel\URLContactController::class, 'destroy'])->where(['id' => '[0-9]+']);
                Route::post('/destroy/file', [Webpanel\URLContactController::class, 'destroy_file'])->where(['id' => '[0-9]+']);
            });
        });
        Route::prefix('analytics')->group(function () {
                Route::get('/edit', [Webpanel\AnalyticsController::class, 'edit'])->name('webpanel.analytics');
                Route::post('/edit/{id}', [Webpanel\AnalyticsController::class, 'update'])->where(['id' => '[0-9]+']);
        });
    });

});
