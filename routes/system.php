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
    'as' => 's.'
], function(){
    // Dashboard
    Route::get('/', \App\Http\Controllers\System\DashboardController::class)->name('index');

    // Association
    Route::resource('association', \App\Http\Controllers\System\AssociationController::class)->only([
        'index', 'store', 'update', 'show'
    ]);

    // Log Viewer
    Route::get('log-viewer', \App\Http\Controllers\System\LogViewerController::class)->name('log-viewer.index');
});