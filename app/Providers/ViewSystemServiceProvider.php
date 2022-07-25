<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewSystemServiceProvider extends ServiceProvider
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
        View::composer(['layouts.system.app', 'content.system.*'], function ($view) {
            // $appVersion = '0.0.1';
            $appVersion = env('APP_VERSION');

            $menu = [
                [
                    'name' => 'Navigation',
                    'description' => null,
                    'menu' => [
                        [
                            'name' => 'Dashboard',
                            'route' => route('s.index'),
                            'icon' => '<i class="fa-solid fa-house"></i>',
                            'active_key' => 'dashboard',
                            'submenu' => null,
                        ], [
                            'name' => 'Association',
                            'route' => route('s.association.index'),
                            'icon' => '<i class="fa-solid fa-house-flag"></i>',
                            'active_key' => 'association',
                            'submenu' => null,
                        ], [
                            'name' => 'Guild',
                            'route' => route('s.guild.index'),
                            'icon' => '<i class="fa-solid fa-house-user"></i>',
                            'active_key' => 'guild',
                            'submenu' => null,
                        ], 
                    ],
                ], [
                    'name' => 'Developer',
                    'description' => null,
                    'menu' => [
                        [
                            'name' => 'Log Viewer',
                            'route' => route('s.log-viewer.index'),
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
            ]);
        });
    }
}
