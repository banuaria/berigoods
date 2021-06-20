<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function(){
    Route::get('/profile','API\UserController@profile');
   
});
Route::get('xendit/va/list','API\Payment\XenditPayment@getListVA');
Route::post('xendit/va/invoice','API\Payment\XenditPayment@createVa');
Route::get('midtrans/va/list','API\Payment\PaymentMidtrans@payment');

Route::resource('/product','API\ProductController');
Route::post('register','API\UserController@register');
Route::post('login','API\UserController@login');
// Route::post('midtrans/va/create','API\Payment\PaymentMidtrans@virtualaccount');
// Route::get('/xendit/va/lists','api\Payment\XenditController@getListVa');
