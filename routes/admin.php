<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'as' => 'adm.'
], function(){
    // Auth
    Route::group([
        'namespace' => 'App\\Http\\Controllers\\Admin',
    ], function () {
        Auth::routes([
            'register' => false,
            'reset' => false,
        ]);
    });

    Route::group([
        'middleware' => ['auth:admin']
    ], function(){
        // Dashboard
        Route::get('/', \App\Http\Controllers\Admin\DashboardController::class)->name('index');

        // Hero
        Route::group([
            'prefix' => 'hero',
            'as' => 'hero.'
        ], function(){
            // Faction
            Route::resource('faction', \App\Http\Controllers\Admin\HeroFactionController::class);
            // Class
            Route::resource('class', \App\Http\Controllers\Admin\HeroClassController::class);
        });
        Route::resource('hero', \App\Http\Controllers\Admin\HeroController::class);

        // Game Mode
        Route::group([
            'prefix' => 'game-mode',
            'as' => 'game-mode.'
        ], function(){
            // Periode
            Route::resource('period', \App\Http\Controllers\Admin\PeriodController::class);
        });

        // Log Viewer
        Route::get('log-viewer', \App\Http\Controllers\Admin\LogViewerController::class)->name('log-viewer.index');

        // JSON
        Route::group([
            'prefix' => 'json',
            'as' => 'json.'
        ], function(){
            // Hero
            Route::group([
                'prefix' => 'hero',
                'as' => 'hero.'
            ], function(){
                // Faction
                Route::get('faction', [\App\Http\Controllers\Admin\HeroFactionController::class, 'jsonList'])->name('faction.list');
                // Class
                Route::get('class', [\App\Http\Controllers\Admin\HeroClassController::class, 'jsonList'])->name('class.list');
            });
            Route::get('hero', [\App\Http\Controllers\Admin\HeroController::class, 'jsonList'])->name('hero.list');

            // Game Mode
            Route::group([
                'prefix' => 'game-mode',
                'as' => 'game-mode.'
            ], function(){
                // Periode
                Route::get('period', [\App\Http\Controllers\Admin\PeriodController::class, 'jsonList'])->name('period.list');
            });
        });
    });
});