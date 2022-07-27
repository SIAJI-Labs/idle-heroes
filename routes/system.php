<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| System Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'as' => 's.',
    'middleware' => ['auth']
], function(){
    // Dashboard
    Route::get('/', \App\Http\Controllers\System\DashboardController::class)->name('index');

    // Association
    Route::resource('association', \App\Http\Controllers\System\AssociationController::class)->only([
        'index', 'store', 'update', 'show'
    ]);

    // Guild
    Route::group([
        'prefix' => 'guild',
        'as' => 'guild.'
    ], function(){
        // Player
        Route::resource('player', \App\Http\Controllers\System\GuildPlayerController::class)->only([
            'store'
        ]);
    });
    Route::resource('guild', \App\Http\Controllers\System\GuildController::class)->only([
        'index', 'store', 'update', 'show'
    ]);
    // Player
    Route::resource('player', \App\Http\Controllers\System\PlayerController::class)->only([
        'index', 'store', 'update', 'show'
    ]);

    // JSON
    Route::group([
        'prefix' => 'json',
        'as' => 'json.'
    ], function(){
        // Association
        Route::get('association', [\App\Http\Controllers\System\AssociationController::class, 'jsonList'])->name('association.list');
        // Guild
        Route::get('guild', [\App\Http\Controllers\System\GuildController::class, 'jsonList'])->name('guild.list');
        // Player
        Route::get('player', [\App\Http\Controllers\System\PlayerController::class, 'jsonList'])->name('player.list');
    });
});