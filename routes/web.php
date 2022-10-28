<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
    return Inertia::render('Auth/Login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/products', function () {
        return Inertia::render('Products');
    })->name('products');

    Route::get('/user-created-orders', function () {
        return Inertia::render('UserCreatedOrders');
    })->name('user-created-orders');

    Route::post('/create-products', 'App\Http\Controllers\ProductController@create')->name('product-creation');
    Route::get('/get-products', 'App\Http\Controllers\ProductController@show')->name('product-list');
    Route::delete('/delete-products/{productId}', 'App\Http\Controllers\ProductController@delete')->name('product-delete');

    Route::post('/create-order', 'App\Http\Controllers\OrderController@createOrder')->name('create-order');
    Route::get('/get-user-orders', 'App\Http\Controllers\OrderController@getOrderByUser')->name('order-list-by-user');
    Route::post('/pay', 'App\Http\Controllers\OrderController@pay')->name('payment');
    Route::get('/get-all-orders', 'App\Http\Controllers\OrderController@getOrders')->name('order-list-by-user');
    Route::get('/transaction-history/{orderId}', 'App\Http\Controllers\OrderController@getTransactionHistory')->name('order-list-by-user');
});
