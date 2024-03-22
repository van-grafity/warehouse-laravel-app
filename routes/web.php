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

Route::group([
    'middleware' => [
        'auth',
        'can:developer-menu',
    ]
],function() {
    Route::resource('permission-category', App\Http\Controllers\PermissionCategoryController::class);
    Route::get('permission-category-dtable', [App\Http\Controllers\PermissionCategoryController::class,'dtable'])->name('permission-category.dtable');

    Route::resource('permission', App\Http\Controllers\PermissionController::class);
    Route::get('permission-dtable', [App\Http\Controllers\PermissionController::class,'dtable'])->name('permission.dtable');
    
    Route::resource('role', App\Http\Controllers\RoleController::class);
    Route::get('role-dtable', [App\Http\Controllers\RoleController::class,'dtable'])->name('role.dtable');
    Route::get('role/{role}/manage-permission', [App\Http\Controllers\RoleController::class,'manage_permission'])->name('role.manage-permission');
    Route::post('role/{role}/manage-permission', [App\Http\Controllers\RoleController::class,'manage_permission_update'])->name('role.manage-permission-update');
});

Route::group([
    'middleware' => [
        'auth',
        'can:admin-menu',
    ]
],function() {
    Route::resource('user', App\Http\Controllers\UserController::class);
    Route::get('user-dtable', [App\Http\Controllers\UserController::class,'dtable'])->name('user.dtable');
    Route::get('user/{user}/reset-password', [App\Http\Controllers\UserController::class,'reset_password'])->name('user.reset-password');
    Route::get('user/{user}/restore', [App\Http\Controllers\UserController::class,'restore'])->name('user.restore');
});


Route::group([
    'middleware' => [
        'auth',
        'can:user-menu',
    ]
],function() {
    Route::resource('home', App\Http\Controllers\HomeController::class);

    Route::get('profile', [App\Http\Controllers\UserController::class,'profile'])->name('profile.index');
    Route::post('profile/change_password', [App\Http\Controllers\UserController::class,'change_password'])->name('profile.change-password');
    
    // ## Master Data
    Route::resource('color', App\Http\Controllers\ColorController::class);
    Route::get('color-dtable', [App\Http\Controllers\ColorController::class,'dtable'])->name('color.dtable');
    
    Route::resource('supplier', App\Http\Controllers\SupplierController::class);
    Route::get('supplier-dtable', [App\Http\Controllers\SupplierController::class,'dtable'])->name('supplier.dtable');

    Route::resource('department', App\Http\Controllers\DepartmentController::class);
    Route::get('department-dtable', [App\Http\Controllers\DepartmentController::class,'dtable'])->name('department.dtable');

    Route::resource('location', App\Http\Controllers\LocationController::class);
    Route::get('location-dtable', [App\Http\Controllers\LocationController::class,'dtable'])->name('location.dtable');

    Route::resource('rack', App\Http\Controllers\RackController::class);
    Route::get('rack-dtable', [App\Http\Controllers\RackController::class,'dtable'])->name('rack.dtable');


    
    Route::resource('invoice', App\Http\Controllers\InvoiceController::class);
    Route::get('invoice-dtable', [App\Http\Controllers\InvoiceController::class,'dtable'])->name('invoice.dtable');
    
    Route::resource('packinglist', App\Http\Controllers\PackinglistController::class);
    Route::get('packinglist-dtable', [App\Http\Controllers\PackinglistController::class,'dtable'])->name('packinglist.dtable');
});