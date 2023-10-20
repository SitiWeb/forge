<?php

namespace App\Providers;
use App\Models\NavigationItem;
use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
    //     $navItems = NavigationItem::all();
        
    //     view()->share('navItems', $navItems);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            $navItems = NavigationItem::all();
            $view->with('navItems', $navItems);
        });
    }
}
