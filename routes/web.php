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
    
    Route::resource('user', App\Http\Controllers\UserController::class);
    Route::get('user-dtable', [App\Http\Controllers\UserController::class,'dtable'])->name('user.dtable');
    Route::get('user/{user}/reset-password', [App\Http\Controllers\UserController::class,'reset_password'])->name('user.reset-password');
    Route::get('user/{user}/restore', [App\Http\Controllers\UserController::class,'restore'])->name('user.restore');
    
    Route::resource('color', App\Http\Controllers\ColorController::class);
    Route::get('color-dtable', [App\Http\Controllers\ColorController::class,'dtable'])->name('color.dtable');
    
    Route::resource('supplier', App\Http\Controllers\SupplierController::class);
    Route::get('supplier-dtable', [App\Http\Controllers\SupplierController::class,'dtable'])->name('supplier.dtable');

    Route::resource('department', App\Http\Controllers\DepartmentController::class);
    Route::get('department-dtable', [App\Http\Controllers\DepartmentController::class,'dtable'])->name('department.dtable');
    
    Route::resource('permission-category', App\Http\Controllers\PermissionCategoryController::class);
    Route::get('permission-category-dtable', [App\Http\Controllers\PermissionCategoryController::class,'dtable'])->name('permission-category.dtable');
});