<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store\StoreLoginController;
use App\Http\Controllers\Store\StoreRegisterController;
use App\Http\Controllers\Store\StoreHomeController;
use App\Http\Controllers\Store\StoreShopController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminRegisterController;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\AccountController;

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
Route::get('site/cart', [App\Http\Controllers\SiteController::class, 'cart'])->name('cart');
Route::get('site/checkout', [App\Http\Controllers\SiteController::class, 'checkout'])->name('checkout');
Route::match(['GET','POST'], 'site/couponlist', [App\Http\Controllers\SiteController::class, 'couponlist'])->name('couponlist');
Route::get('site/coupondetail', [App\Http\Controllers\SiteController::class, 'coupondetail'])->name('coupondetail');
// 利用規約
Route::get('site/terms', [App\Http\Controllers\SiteController::class, 'terms'])->name('terms');
// プライバシーポリシー
Route::get('site/privacypolicy', [App\Http\Controllers\SiteController::class, 'privacypolicy'])->name('privacypolicy');
// お問い合わせ
Route::get('site/contact', [App\Http\Controllers\SiteController::class, 'contact'])->name('contact');
//駅取得
Route::post('/check_station', [StoreShopController::class, 'checkStation'])->name('check_station');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');   // 画面表示
    Route::put('/account', [AccountController::class, 'update'])->name('account.update'); // 更新
});

Route::group(['prefix' => 'store'], function () {
    // 登録
    Route::get('register', [StoreRegisterController::class, 'create'])
        ->name('store.register');

    Route::post('register', [StoreRegisterController::class, 'store']);

    // ログイン
    Route::get('login', [StoreLoginController::class, 'showLoginPage'])
        ->name('store.login');

    Route::post('login', [StoreLoginController::class, 'login']);
    Route::post('logout', [StoreLoginController::class, 'logout'])->name('store.logout');

    // 以下ストアユーザー認証必須のルーティング
    Route::middleware(['auth:store_user'])->group(function () {

        // ダッシュボード
        Route::get('/', 'App\Http\Controllers\Store\StoreHomeController@index')->name('store.home');
        Route::get('/home', 'App\Http\Controllers\Store\StoreHomeController@index')->name('store.home2');
        // 店舗
        Route::get('/shop', 'App\Http\Controllers\Store\StoreShopController@index')->name('store.shop.index');
        Route::match(['get', 'post'], '/shop/create', 'App\Http\Controllers\Store\StoreShopController@create')->name('store.shop.create');
        Route::match(['get', 'post'], '/shop/edit', 'App\Http\Controllers\Store\StoreShopController@edit')->name('store.shop.edit');
        Route::get('/account', 'App\Http\Controllers\Store\StoreShopController@account')->name('store.account.index');
        Route::match(['get', 'post'], '/account/create', 'App\Http\Controllers\Store\StoreShopController@accountCreate')->name('store.account.create');
        // クーポン
        Route::get('/coupon', 'App\Http\Controllers\Store\StoreCouponController@index')->name('store.coupon');
        Route::match(['get', 'post'], '/coupon/create', 'App\Http\Controllers\Store\StoreCouponController@create')->name('store.coupon.create');
        Route::match(['get', 'post'], '/coupon/edit', 'App\Http\Controllers\Store\StoreCouponController@edit')->name('store.coupon.edit');
        Route::post('/coupon/delete', 'App\Http\Controllers\Store\StoreCouponController@delete')->name('store.coupon.delete');
        // マスタ
        Route::match(['get', 'post'], '/cource', 'App\Http\Controllers\Store\StoreShopController@courceCreate')->name('store.shop.cource');
        //Route::get('/cource', 'App\Http\Controllers\Store\StoreShopController@courceCreate')->name('store.shop.cource');

    });

    Route::post('/shop/check_station', 'App\Http\Controllers\Store\StoreShopController@checkStation')->name('store.shop.check_station');
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
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    // 以下adminユーザー認証必須のルーティング
    Route::middleware(['auth:admin_user'])->group(function () {

        // ダッシュボード
        Route::get('/', 'App\Http\Controllers\Admin\AdminHomeController@index')->name('admin.home');
        Route::get('/home', 'App\Http\Controllers\Admin\AdminHomeController@index')->name('admin.home2');

        // 店舗
        Route::get('/shop', 'App\Http\Controllers\Admin\AdminShopController@index')->name('admin.shop.index');
        Route::get('/account', 'App\Http\Controllers\Admin\AdminShopController@account')->name('admin.account.index');

        // お知らせ
        Route::get('/information', 'App\Http\Controllers\Admin\AdminInformationController@index')->name('admin.information.index');
        Route::match(['get', 'post'], '/information/create', 'App\Http\Controllers\Admin\AdminInformationController@create')->name('admin.information.create');
        Route::match(['get', 'post'], '/information/edit', 'App\Http\Controllers\Admin\AdminInformationController@edit')->name('admin.information.edit');
        Route::post('/information/delete', 'App\Http\Controllers\Admin\AdminInformationController@delete')->name('admin.information.delete');

        // クーポン
        Route::get('/coupon', 'App\Http\Controllers\Admin\AdminCouponController@index')->name('admin.coupon');
        // 特集
        Route::get('/special_future', 'App\Http\Controllers\Admin\AdminSpecialFutureController@index')->name('admin.special_future');
        Route::match(['get', 'post'], '/special_future/create', 'App\Http\Controllers\Admin\AdminSpecialFutureController@create')->name('admin.special_future.create');
        Route::match(['get', 'post'], '/special_future/edit', 'App\Http\Controllers\Admin\AdminSpecialFutureController@edit')->name('admin.special_future.edit');
        Route::post('/special_future/delete', 'App\Http\Controllers\Admin\AdminSpecialFutureController@delete')->name('admin.special_future.delete');
        // マスタ
        Route::match(['get', 'post'], '/images', 'App\Http\Controllers\Admin\AdminMasterController@images')->name('admin.master.images');
        Route::post('/images_delete', 'App\Http\Controllers\Admin\AdminMasterController@images_delete')->name('admin.master.images_delete');
        //Route::get('/cource', 'App\Http\Controllers\Store\StoreShopController@courceCreate')->name('store.shop.cource');
    });
});
