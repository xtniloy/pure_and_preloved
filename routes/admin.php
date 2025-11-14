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

Route::middleware('auth:admin')->group(function () {
    Route::get('/logout',[\App\Http\Controllers\Admin\Auth\AuthController::class,'logout'])->name('admin.logout');
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class,'index'])->name('admin.dashboard');
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class,'home'])->name('admin.home');
});

Route::middleware('auth:admin')->as('admin.')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class,'edit'])->name('profile.view');
    Route::post('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class,'update'])->name('profile');

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::get('/users/{user}/email_resend', [\App\Http\Controllers\Admin\UserController::class,'resend_email'])->name('users.email.resend');
    Route::get('/users/{user}/verify_toggle', [\App\Http\Controllers\Admin\UserController::class,'verification_toggle'])
        ->name('users.verification_toggle');


});

