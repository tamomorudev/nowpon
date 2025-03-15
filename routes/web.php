<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store\StoreLoginController;
use App\Http\Controllers\Store\StoreRegisterController;
use App\Http\Controllers\Store\StoreHomeController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminRegisterController;
use App\Http\Controllers\Admin\AdminHomeController;

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
        Route::match(['get', 'post'], '/shop/create', 'App\Http\Controllers\Store\StoreShopController@create')->name('store.shop.create');
        Route::get('/account', 'App\Http\Controllers\Store\StoreShopController@account')->name('store.account.index');
        Route::match(['get', 'post'], '/account/create', 'App\Http\Controllers\Store\StoreShopController@accountCreate')->name('store.account.create');
        // クーポン
        Route::get('/coupon', 'App\Http\Controllers\Store\StoreCouponController@index')->name('store.coupon');
        Route::match(['get', 'post'], '/coupon/create', 'App\Http\Controllers\Store\StoreCouponController@create')->name('store.coupon.create');
        Route::match(['get', 'post'], '/coupon/edit', 'App\Http\Controllers\Store\StoreCouponController@edit')->name('store.coupon.edit');
        // マスタ
        Route::match(['get', 'post'], '/cource', 'App\Http\Controllers\Store\StoreShopController@courceCreate')->name('store.shop.cource');
        //Route::get('/cource', 'App\Http\Controllers\Store\StoreShopController@courceCreate')->name('store.shop.cource');
        
    });
});

Route::group(['prefix' => 'admin'], function () {
    // 登録
    Route::get('register', [AdminRegisterController::class, 'create'])
        ->name('admin.register');

    Route::post('register', [AdminRegisterController::class, 'store']);

    // ログイン
    Route::get('login', [AdminLoginController::class, 'showLoginPage'])
        ->name('admin.login');

    Route::post('login', [AdminLoginController::class, 'login']);

    // 以下adminユーザー認証必須のルーティング
    Route::middleware(['auth:admin_user'])->group(function () {
        
        // ダッシュボード
        Route::get('/', 'App\Http\Controllers\Admin\AdminHomeController@index')->name('admin.home');
        Route::get('/home', 'App\Http\Controllers\Admin\AdminHomeController@index')->name('admin.home2');

        // 店舗
        Route::get('/shop', 'App\Http\Controllers\Admin\AdminShopController@index')->name('admin.shop.index');
        Route::get('/account', 'App\Http\Controllers\Admin\AdminShopController@account')->name('admin.account.index');

        // クーポン
        Route::get('/coupon', 'App\Http\Controllers\Admin\AdminCouponController@index')->name('admin.coupon');
        // マスタ
        Route::match(['get', 'post'], '/images', 'App\Http\Controllers\Admin\AdminMasterController@images')->name('admin.master.images');
        //Route::get('/cource', 'App\Http\Controllers\Store\StoreShopController@courceCreate')->name('store.shop.cource');
    });
});
