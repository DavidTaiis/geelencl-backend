<?php

namespace App\Providers;

use App\Models\Menu;
use Auth;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       
        if (config('app.env') == 'production') {
            \URL::forceScheme('https');
        }
        Passport::routes();
        Blade::withoutDoubleEncoding();
        Paginator::useBootstrapThree();
        view()->composer('layouts.main', function ($view) {
            $user = Auth::user();
            $menu = new Menu();
            $menu = $menu->buildMenu($user);
            $view->with('menu', $menu);
        });
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        Passport::ignoreMigrations();
    }
}
