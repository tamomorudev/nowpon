<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store\StoreLoginController;
use App\Http\Controllers\Store\StoreRegisterController;
use App\Http\Controllers\Store\StoreHomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::get('/', function () {
    return view('welcome');
});
*/
Route::get('/', [App\Http\Controllers\TopController::class, 'index'])->name('top');
Route::get('site/list', [App\Http\Controllers\SiteController::class, 'list'])->name('site_list');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/account', [App\Http\Controllers\AccountController::class, 'index'])->name('account');

Route::group(['prefix' => 'store'], function () {
    // 登録
    Route::get('register', [StoreRegisterController::class, 'create'])
        ->name('store.register');

    Route::post('register', [StoreRegisterController::class, 'store']);

    // ログイン
    Route::get('login', [StoreLoginController::class, 'showLoginPage'])
        ->name('store.login');

    Route::post('login', [StoreLoginController::class, 'login']);

    // 以下ストアユーザー認証必須のルーティング
    Route::middleware(['auth:store_user'])->group(function () {
        
        // ダッシュボード
        Route::get('/', 'App\Http\Controllers\Store\StoreHomeController@index')->name('store.home');
        Route::get('/home', 'App\Http\Controllers\Store\StoreHomeController@index')->name('store.home2');

        // 店舗
        Route::get('/shop', 'App\Http\Controllers\Store\StoreShopController@index')->name('store.shop.index');
        Route::get('/shop/create', 'App\Http\Controllers\Store\StoreShopController@create')->name('store.shop.create');
        Route::get('/shop/menu', 'App\Http\Controllers\Store\StoreShopController@menuCreate')->name('store.shop.menu');
        // クーポン
        Route::get('/coupon', 'App\Http\Controllers\Store\StoreCouponController@index')->name('store.coupon');
        Route::get('/coupon/create', 'App\Http\Controllers\Store\StoreCouponController@create')->name('store.coupon.create');
        
    });
});
