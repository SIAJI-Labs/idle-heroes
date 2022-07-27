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
                        ], [
                            'name' => 'Hero',
                            'route' => null,
                            'icon' => '<i class="fa-solid fa-shield-halved"></i>',
                            'active_key' => 'hero',
                            'submenu' => [
                                [
                                    'name' => 'Faction',
                                    'route' => route('adm.hero.faction.index'),
                                    'active_key' => 'hero',
                                    'active_sub' => 'faction',
                                ], [
                                    'name' => 'Class',
                                    'route' => route('adm.hero.class.index'),
                                    'active_key' => 'hero',
                                    'active_sub' => 'class',
                                ], [
                                    'name' => 'List',
                                    'route' => route('adm.hero.index'),
                                    'active_key' => 'hero',
                                    'active_sub' => 'list',
                                ], 
                            ],
                        ], 
                    ],
                ], [
                    'name' => 'Developer',
                    'description' => null,
                    'menu' => [
                        [
                            'name' => 'Log Viewer',
                            'route' => route('adm.log-viewer.index'),
                            'icon' => '<i class="fa-solid fa-laptop-code"></i>',
                            'active_key' => 'log-viewer',
                            'submenu' => null,
                        ],
                    ],
                ]
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
