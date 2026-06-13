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
        Route::get('/forget-password', 'AuthController@forget_password')->name('admin.forget_password');
        Route::post('/forget-password', 'AuthController@request_forget_password')->name('admin.request_forget_password');
        Route::get('/set-password/{token?}', 'AuthController@set_password')->name('admin.set_password');
        Route::post('/set-password/{token?}', 'AuthController@save_password')->name('admin.save_password');
    });

Route::middleware('guest:admin')->group(function () {
    Route::get('/email-success/{admin}', [\App\Http\Controllers\Admin\Auth\AuthController::class, 'email_success'])->name('admin.email_success');
    Route::post('/email-resend/{admin}', [\App\Http\Controllers\Admin\Auth\AuthController::class, 'email_resend'])->name('admin.email_resend');
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
    Route::resource('admins', \App\Http\Controllers\Admin\AdminController::class);
    Route::get('/admins/{admin}/resend-email', [\App\Http\Controllers\Admin\AdminController::class, 'resend_email'])->name('admins.email.resend');
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::post('/categories/update-order', [\App\Http\Controllers\Admin\CategoryController::class, 'updateOrder'])->name('categories.update_order');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('shipping_methods', \App\Http\Controllers\Admin\ShippingMethodController::class);
    Route::resource('pages', \App\Http\Controllers\Admin\PageController::class)->except(['show']);

    // Contact messages
    Route::resource('contact-messages', \App\Http\Controllers\Admin\ContactMessageController::class)->only(['index', 'show', 'destroy']);

    // Web (in-app) notifications
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'readAll'])->name('notifications.read_all');
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'read'])->name('notifications.read');

    // Settings
    Route::get('/settings/notifications', [\App\Http\Controllers\Admin\NotificationSettingController::class, 'index'])->name('settings.notifications');
    Route::post('/settings/notifications', [\App\Http\Controllers\Admin\NotificationSettingController::class, 'update'])->name('settings.notifications.update');
    Route::get('featured-products', [\App\Http\Controllers\Admin\FeaturedProductController::class, 'index'])->name('featured-products.index');
    Route::put('featured-products/{product}', [\App\Http\Controllers\Admin\FeaturedProductController::class, 'toggle'])->name('featured-products.toggle');
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
    Route::get('/users/{user}/email_resend', [\App\Http\Controllers\Admin\UserController::class,'resend_email'])->name('users.email.resend');
    Route::get('/users/{user}/verify_toggle', [\App\Http\Controllers\Admin\UserController::class,'verification_toggle'])
        ->name('users.verification_toggle');


});
