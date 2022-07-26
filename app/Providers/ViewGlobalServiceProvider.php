<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewGlobalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['*'], function ($view) {
            // Previous Menu
            if (! \Session::has('previous_menu')) {
                \Session::put('previous_menu', route('s.index'));
            }
            $previousMenu = \Session::get('previous_menu');
            $previousUrl = url()->previous();
            if ($previousUrl) {
                if ($previousUrl !== $previousMenu && strpos($previousUrl, 'json') === false) {
                    $previousMenu = $previousUrl;
                    \Session::put('previous_menu', $previousMenu);
                }
            }

            // Ads
            $ads = true;
            $view->with([
                'provPreviousMenu' => $previousMenu,
                'provAds' => $ads,
            ]);
        });
    }
}
