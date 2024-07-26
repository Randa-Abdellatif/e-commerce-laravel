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

Route::get('test', function(){
    return 'Randa';
});

//Route::view('/users', 'users');
Route::view('/users', 'users', ['name' => 'Randa Abdellatif']);

Route::get('/users/{id}', function (string $id) {
    return 'User '.$id;
});

Route::get('/user/{name?}', function (string $name = 'john') {
    return $name;
});
