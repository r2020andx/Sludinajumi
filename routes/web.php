<?php

use App\Http\Controllers\AdController;
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
Auth::routes();

Route::get('/', function() {
    return redirect('/ads');
});

Route::get('/ads', [AdController::class, 'index']);
Route::get('/ads/my', [AdController::class, 'indexMyAds'])->middleware('auth');
Route::post('/ads', [AdController::class, 'store'])->middleware('auth');
Route::get('/ads/create', [AdController::class, 'create'])->middleware('auth');
Route::get('/ads/{id}', [AdController::class , 'show']);
Route::get('/ads/{id}/edit', [AdController::class, 'edit'])->middleware('auth');
Route::get('/ads/{id}/delete', [AdController::class, 'delete'])->middleware('auth');
Route::put('/ads/{id}', [AdController::class, 'update'])->middleware('auth');
Route::delete('/ads/{id}', [AdController::class, 'destroy'])->middleware('auth');











