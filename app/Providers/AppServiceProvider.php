<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Backend\Category1; // Example model
use App\Models\Backend\Brand; // Example model
use App\Models\Backend\Menu; // Example model

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            // Query your data here
            $categories = Category1::where('status','on')->get();
            // $brand = Brand::get();
            $menu = Menu::get();
            // Share the data with the view
            $view->with('categories', $categories)->with('menu', $menu);
            // $view->with('topcategories', $categories)->with('topbrands', $brand)->with('menu', $menu);
        });    
    }
}
