<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/email/success/{user}', [\App\Http\Controllers\User\Auth\AuthController::class, 'email_success'])->name('email.success');
// Resend verification link
Route::post('/email/verification-notification/{user}', [\App\Http\Controllers\User\Auth\AuthController::class, 'email_resend'])
    ->middleware(['throttle:6,1'])->name('verification.send');

//// Resend verification link
//Route::post('/email/verification-notification', function (Request $request) {
//    $request->user()->sendEmailVerificationNotification();
//    return back()->with('message', 'Verification link sent!');
//})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware('guest')
    ->namespace('App\Http\Controllers\User\Auth')
    ->group(function () {
//        Route::get('/','AuthController@login_page');

        Route::get('/login', 'AuthController@login_page')->name('login');
        Route::post('/login', 'AuthController@login')->name('auth');

        Route::get('/registration', 'AuthController@registration_page')->name('registration');
        Route::post('/registration', 'AuthController@registration')->name('register');

        Route::get('/email_verify/{token?}', 'AuthController@email_verify')->name('email_verify');
        Route::get('/email/verify', 'AuthController@email_not_verify')->name('verification.notice');

        Route::get('/set-password/{token?}', 'AuthController@set_password')->name('set_password');
        Route::post('/set-password/{token?}', 'AuthController@save_password')->name('save_password');

        Route::get('/forget-password', 'AuthController@forget_password')->name('forget_password');
        Route::post('/forget-password', 'AuthController@request_forget_password')->name('request_forget_password');
    });

Route::get('/home', function () {
    return "Welcome to dashboard. Your email is verified!";
})->middleware(['auth', 'verified'])->name('user.home');
Route::get('/email/verify', [\App\Http\Controllers\User\Auth\AuthController::class, 'email_not_verify'])->name('verification.notice');

Route::middleware('auth')->group(function () {
    Route::get('/logout', [\App\Http\Controllers\User\Auth\AuthController::class, 'logout'])->name('logout');
});
