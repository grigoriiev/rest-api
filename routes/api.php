<?php

use App\Http\Controllers\api\AuthorController;
use App\Http\Controllers\api\MagazineController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/author/list',[AuthorController::class,'list']);

Route::post('/author/add',[AuthorController::class,'add']);

Route::put('/author/update',[AuthorController::class,'update']);

Route::delete('/author/delete',[AuthorController::class,'delete']);

Route::get('/magazine/list',[MagazineController::class,'list']);

Route::post(' /magazine/add',[MagazineController::class,'add']);

Route::put(' /magazine/update',[MagazineController::class,'update']);

Route::delete(' /magazine/delete',[MagazineController::class,'delete']);

