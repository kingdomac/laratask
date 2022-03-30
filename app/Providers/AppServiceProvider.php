<?php

namespace App\Providers;

use App\Models\Issue;
use App\Models\Project;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.aside-menu', function ($view) {
            $countNewIssues =  Issue::select('id')
                ->when(auth()->user() && auth()->user()->isAdmin, function ($q) {
                    $q->where('user_id', auth()->user()->id);
                })
                ->where('is_new', true)
                ->count();
            $view->with('countNewIssues', $countNewIssues);
        });

        View::composer('layouts.navigation', function ($view) {
            $notifications = auth()->user()->notifications;
            $view->with('notifications', $notifications);
        });
    }
}
