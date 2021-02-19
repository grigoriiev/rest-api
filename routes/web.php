<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});


Route::get('/author/list',[AuthorController::class,'list']);

Route::post('/author/add',[AuthorController::class,'add']);

Route::post('/author/update',[AuthorController::class,'update']);

Route::post('/author/delete',[AuthorController::class,'delete']);

Route::get('/magazine/list',[MagazineController::class,'list']);

Route::post(' /magazine/add',[MagazineController::class,'add']);

Route::post(' /magazine/update',[MagazineController::class,'update']);

Route::post(' /magazine/delete',[MagazineController::class,'delete']);
