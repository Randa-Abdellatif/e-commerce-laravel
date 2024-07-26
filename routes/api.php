<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});


//Route::middleware(['jwt.verify' ])->group(function (){})

//Category CRUD
Route::group(['prefix' => 'category'], function($router){
Route::controller(CategoryController::class)->group(function (){
    Route::get('/index','index')->middleware('is_admin');
    Route::get('/show/{id}','show')->middleware('is_admin');
    Route::post('/store','store')->middleware('is_admin');
    Route::put('/update/{id}','update')->middleware('is_admin');
    Route::delete('/delete/{id}','delete')->middleware('is_admin');
});
});

//Product CRUD
Route::group(['prefix' => 'products'], function($router){
Route::controller(ProductController::class)->group(function (){
    Route::get('/index','index')->middleware('auth');
    Route::get('/show/{id}','show')->middleware('auth');
    Route::post('/store','store')->middleware('is_admin');
    Route::put('/update/{id}','update')->middleware('is_admin');
    Route::delete('/destroy/{id}','destroy')->middleware('is_admin');
});
});

//Order CRUD
Route::group(['prefix' => 'orders'], function($router){
Route::controller(OrderController::class)->group(function(){
    Route::get('/index','index')->middleware('is_admin');
    Route::get('/show/{id}','show')->middleware('is_admin');
    Route::post('/store','store')->middleware('auth');
    Route::get('get_user_orders/{id}', 'get_user_orders')->middleware('auth');
    Route::post('change_order_status/{id}', 'change_order_status')->middleware('is_admin');
});
});




