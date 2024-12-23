<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware('guest:admin')
    ->namespace('App\Http\Controllers\Admin\Auth')
    ->group(function () {
        Route::get('/login', 'AuthController@index')->name('admin.login');
        Route::post('/login', 'AuthController@login')->name('admin.auth');
    });

Route::middleware('auth:super_admin')->group(function () {
    Route::get('/logout',[\App\Http\Controllers\Admin\Auth\AuthController::class,'logout'])->name('admin.logout');
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class,'index'])->name('admin.dashboard');
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class,'index'])->name('admin.home');
});

