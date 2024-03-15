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

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::resource('home', App\Http\Controllers\HomeController::class);
    Route::resource('color', App\Http\Controllers\ColorController::class);
    Route::get('color-dtable', [App\Http\Controllers\ColorController::class,'dtable'])->name('color.dtable');
});

Route::get('color-oke', [App\Http\Controllers\ColorController::class,'index']);