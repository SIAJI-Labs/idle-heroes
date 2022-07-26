<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewAdminServiceProvider extends ServiceProvider
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
        View::composer(['content.adm.*'], function ($view) {
            // $appVersion = '0.0.1';
            $appVersion = env('APP_VERSION');
            $wlogout_route = route('adm.logout');

            $menu = [
                [
                    'name' => 'Navigation',
                    'description' => null,
                    'menu' => [
                        [
                            'name' => 'Dashboard',
                            'route' => route('adm.index'),
                            'icon' => '<i class="fa-solid fa-house"></i>',
                            'active_key' => 'dashboard',
                            'submenu' => null,
                        ],
                    ],
                ],
            ];

            $topMenu = [
                [
                    'name' => 'My Profile',
                    'icon' => '<i class="bi bi-person tw__mr-2"></i>',
                    'route' => null,
                ],
            ];

            $view->with([
                'provAppVersion' => $appVersion,
                'provMenu' => $menu,
                'provTopMenu' => $topMenu,
                'wlogout_route' => $wlogout_route
            ]);
        });
    }
}
