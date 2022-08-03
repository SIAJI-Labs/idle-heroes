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
            $wlogout_route = route('logout');

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
                        ], [
                            'name' => 'Player',
                            'route' => route('s.player.index'),
                            'icon' => '<i class="fa-solid fa-users"></i>',
                            'active_key' => 'player',
                            'submenu' => null,
                        ], 
                    ],
                ], [
                    'name' => 'Game Mode',
                    'description' => null,
                    'menu' => [
                        [
                            'name' => 'Star Expedition',
                            'route' => route('s.game-mode.star-expedition.index'),
                            'icon' => '<i class="fa-brands fa-octopus-deploy"></i>',
                            'active_key' => 'star-expedition',
                            'submenu' => null
                        ], [
                            'name' => 'Guild War',
                            'route' => route('s.game-mode.guild-war.index'),
                            'icon' => '<i class="fa-brands fa-galactic-senate"></i>',
                            'active_key' => 'guild-war',
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
