<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Dusk\DuskServiceProvider;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        view()->composer('*', function($view) {
            if (Auth::check()) {
                if (Auth::user()->role == 'healthcarepro') {
                    $countNewNotifications = 0;
                    foreach (Auth::user()->caregivers as $caregiver) {
                        $countNewNotifications += count($caregiver->notificationsCreated->where('viewed', 0));
                    }
                    $view->with('countNewNotifications', $countNewNotifications);
                }
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }
}
