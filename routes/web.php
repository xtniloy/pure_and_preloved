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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [\App\Http\Controllers\Public\HomeController::class, 'index'])->name('home');
// Route::get('/product', [\App\Http\Controllers\Public\HomeController::class, 'product'])->name('product'); // Replaced by dynamic route


//-------------------------------------------------------------

Route::get('/email/success/{user}', [\App\Http\Controllers\User\Auth\AuthController::class, 'email_success'])->name('email.success');
// Resend verification link
Route::post('/email/verification-notification/{user}', [\App\Http\Controllers\User\Auth\AuthController::class, 'email_resend'])
    ->middleware(['throttle:6,1'])->name('verification.send');

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

Route::get('/dashboard', [\App\Http\Controllers\User\Auth\AuthController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('user.dashboard');
Route::get('/email/verify', [\App\Http\Controllers\User\Auth\AuthController::class, 'email_not_verify'])->name('verification.notice');

Route::middleware('auth')->group(function () {
    Route::get('/logout', [\App\Http\Controllers\User\Auth\AuthController::class, 'logout'])->name('logout');
    Route::get('/account/profile', [\App\Http\Controllers\User\Auth\AuthController::class, 'profile'])->name('account.profile');
    Route::post('/account/profile', [\App\Http\Controllers\User\Auth\AuthController::class, 'updateProfile'])->name('account.profile.update');
    Route::post('/account/delete', [\App\Http\Controllers\User\Auth\AuthController::class, 'deleteAccount'])->name('account.delete');
});

// Dynamic Product Route - Must be last to avoid conflicts with other 3-segment routes
Route::get('/product/{gender}/{category}/{product}', [\App\Http\Controllers\Public\ProductController::class, 'show'])->name('product.show');

Route::get('/cart', [\App\Http\Controllers\Public\HomeController::class, 'cart'])->name('cart.index');
Route::post('/cart/add', [\App\Http\Controllers\Public\HomeController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [\App\Http\Controllers\Public\HomeController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove/{product}', [\App\Http\Controllers\Public\HomeController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/clear', [\App\Http\Controllers\Public\HomeController::class, 'clearCart'])->name('cart.clear');

Route::get('/wishlist', [\App\Http\Controllers\Public\HomeController::class, 'wishlist'])->name('wishlist.index');
Route::post('/wishlist/add', [\App\Http\Controllers\Public\HomeController::class, 'addToWishlist'])->name('wishlist.add');
Route::post('/wishlist/remove/{product}', [\App\Http\Controllers\Public\HomeController::class, 'removeFromWishlist'])->name('wishlist.remove');

Route::get('/checkout', [\App\Http\Controllers\Public\HomeController::class, 'checkout'])->name('checkout.index');
Route::post('/checkout', [\App\Http\Controllers\Public\HomeController::class, 'placeOrder'])->name('checkout.place');
