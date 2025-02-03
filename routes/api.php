<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\FormRecordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::controller(InfoController::class)->group(function () {
//    Route::prefix('info')->group(function () {
//        Route::get('/', 'index');
//        Route::get('/{id}', 'show');
//        Route::post('/', 'store');
//        Route::put('/{id}', 'update');
//        Route::delete('/{id}', 'delete');
//    });
//});
Route::resource('/info', InfoController::class);
Route::resource('/form', FormController::class);
Route::resource('/record', FormRecordController::class);

Route::get('/fix', [InfoController::class, 'fix']);
Route::get('/cache', [InfoController::class, 'cache']);
Route::get('/test', [InfoController::class, 'index2']);
Route::get('/sku', [InfoController::class, 'sku']);
Route::get('/factor', [InfoController::class, 'factor']);
Route::get('/test', [InfoController::class, 'index2']);

Route::put('/end/form/{form}', [FormController::class,'end']);
Route::get('/last/open/form', [FormController::class,'last']);
