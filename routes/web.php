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


Route::get('/', [AdController::class, 'index']);
Route::get('/my', [AdController::class, 'indexMyAds'])->middleware('auth');
Route::post('/', [AdController::class, 'store'])->middleware('auth');
Route::get('/create', [AdController::class, 'create'])->middleware('auth');
Route::get('/{id}', [AdController::class , 'show']);
Route::get('/{id}/edit', [AdController::class, 'edit'])->middleware('auth');
Route::get('/{id}/delete', [AdController::class, 'delete'])->middleware('auth');
Route::put('/{id}', [AdController::class, 'update'])->middleware('auth');
Route::delete('/{id}', [AdController::class, 'destroy'])->middleware('auth');











