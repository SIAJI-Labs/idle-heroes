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

    // Game Mode
    Route::group([
        'prefix' => 'game-mode',
        'as' => 'game-mode.'
    ], function(){
        // Guild War
        Route::group([
            'prefix' => 'guild-war',
            'as' => 'guild-war.'
        ], function(){
            // Participation
            Route::resource('participation', \App\Http\Controllers\System\GameMode\GuildWarParticipationController::class);
        });
        Route::resource('guild-war', \App\Http\Controllers\System\GameMode\GuildWarController::class);
        // Star Expedition
        Route::group([
            'prefix' => 'star-expedition',
            'as' => 'star-expedition.'
        ], function(){
            // Participation
            Route::resource('participation', \App\Http\Controllers\System\GameMode\StarExpeditionParticipationController::class);
        });
        Route::resource('star-expedition', \App\Http\Controllers\System\GameMode\StarExpeditionController::class);
    });

    // JSON
    Route::group([
        'prefix' => 'json',
        'as' => 'json.'
    ], function(){
        // Association
        Route::get('association', [\App\Http\Controllers\System\AssociationController::class, 'jsonList'])->name('association.list');
        // Guild
        Route::group([
            'prefix' => 'guild',
            'as' => 'guild.'
        ], function(){
            // Member
            Route::get('member', [\App\Http\Controllers\System\GuildMemberController::class, 'jsonList'])->name('member.list');
        });
        Route::get('guild', [\App\Http\Controllers\System\GuildController::class, 'jsonList'])->name('guild.list');
        // Player
        Route::get('player', [\App\Http\Controllers\System\PlayerController::class, 'jsonList'])->name('player.list');

        // Game Mode
        Route::group([
            'prefix' => 'game-mode',
            'as' => 'game-mode.'
        ], function(){
            // Guild War
            Route::group([
                'prefix' => 'guild-war',
                'as' => 'guild-war.'
            ], function(){
                // Participant
                Route::get('participant', [\App\Http\Controllers\System\GameMode\GuildWarParticipationController::class, 'jsonList'])->name('participant.list');
            });
            Route::get('guild-war', [\App\Http\Controllers\System\GameMode\GuildWarController::class, 'jsonList'])->name('guild-war.list');

            // Star Expedition
            Route::group([
                'prefix' => 'star-expedition',
                'as' => 'star-expedition.'
            ], function(){
                // Participant
                Route::get('participant', [\App\Http\Controllers\System\GameMode\StarExpeditionParticipationController::class, 'jsonList'])->name('participant.list');
            });
            Route::get('star-expedition', [\App\Http\Controllers\System\GameMode\StarExpeditionController::class, 'jsonList'])->name('star-expedition.list');

            // Period
            Route::get('period', [\App\Http\Controllers\System\GameMode\PeriodController::class, 'jsonList'])->name('period.list');
        });
    });
});