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

// Route::get('/', function () {
//     return view('welcome');
// });

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
    
    Route::get('/', [App\Http\Controllers\HomeController::class,'index'])->name('home.index');


    Route::get('profile', [App\Http\Controllers\UserController::class,'profile'])->name('profile.index');
    Route::post('profile/change_password', [App\Http\Controllers\UserController::class,'change_password'])->name('profile.change-password');
    
    // ## Master Data
    Route::resource('color', App\Http\Controllers\ColorController::class);
    Route::get('color-dtable', [App\Http\Controllers\ColorController::class,'dtable'])->name('color.dtable');
    
    Route::resource('supplier', App\Http\Controllers\SupplierController::class);
    Route::get('supplier-dtable', [App\Http\Controllers\SupplierController::class,'dtable'])->name('supplier.dtable');

    Route::resource('department', App\Http\Controllers\DepartmentController::class);
    Route::get('department-dtable', [App\Http\Controllers\DepartmentController::class,'dtable'])->name('department.dtable');

    Route::resource('location-row', App\Http\Controllers\LocationRowController::class);
    Route::get('location-row-dtable', [App\Http\Controllers\LocationRowController::class,'dtable'])->name('location-row.dtable');

    Route::resource('location', App\Http\Controllers\LocationController::class);
    Route::get('location-dtable', [App\Http\Controllers\LocationController::class,'dtable'])->name('location.dtable');

    Route::resource('rack', App\Http\Controllers\RackController::class);
    Route::get('rack-dtable', [App\Http\Controllers\RackController::class,'dtable'])->name('rack.dtable');
    Route::get('rack-barcode', [App\Http\Controllers\RackController::class,'rack_barcode'])->name('rack.barcode');

    
    Route::resource('invoice', App\Http\Controllers\InvoiceController::class);
    Route::get('invoice-dtable', [App\Http\Controllers\InvoiceController::class,'dtable'])->name('invoice.dtable');
    
    Route::prefix('packinglist')->name('packinglist.')->group(function () {
        Route::get('dtable', [App\Http\Controllers\PackinglistController::class,'dtable'])->name('dtable');
        Route::get('{packinglist}/detail', [App\Http\Controllers\PackinglistController::class,'detail'])->name('detail');
        Route::post('import', [App\Http\Controllers\PackinglistController::class,'import'])->name('import');
        Route::get('{packinglist}/information-card', [App\Http\Controllers\PackinglistController::class,'information_card'])->name('information-card');
    });
    Route::resource('packinglist', App\Http\Controllers\PackinglistController::class);
    
    Route::prefix('fabric-roll')->name('fabric-roll.')->group(function () {
        Route::get('dtable', [App\Http\Controllers\FabricRollController::class,'dtable'])->name('dtable');
        Route::delete('mass_delete', [App\Http\Controllers\FabricRollController::class,'mass_delete'])->name('mass_delete');
    });
    Route::resource('fabric-roll', App\Http\Controllers\FabricRollController::class);
    
    
    Route::prefix('fabric-offloading')->name('fabric-offloading.')->group(function () {
        Route::get('dtable', [App\Http\Controllers\FabricOffloadingController::class,'dtable'])->name('dtable');
        Route::get('dtable-roll-list', [App\Http\Controllers\FabricOffloadingController::class,'dtable_roll_list'])->name('dtable-roll-list');
        Route::get('{packinglist_id}/detail', [App\Http\Controllers\FabricOffloadingController::class,'detail'])->name('detail');
    });
    Route::resource('fabric-offloading', App\Http\Controllers\FabricOffloadingController::class);


    Route::prefix('fabric-stock-in')->name('fabric-stock-in.')->group(function () {
        Route::get('dtable', [App\Http\Controllers\FabricStockInController::class,'dtable'])->name('dtable');
        Route::get('dtable-roll-list', [App\Http\Controllers\FabricStockInController::class,'dtable_roll_list'])->name('dtable-roll-list');
        Route::get('{packinglist_id}/detail', [App\Http\Controllers\FabricStockInController::class,'detail'])->name('detail');
    });
    Route::resource('fabric-stock-in', App\Http\Controllers\FabricStockInController::class);

    Route::prefix('fabric-request')->name('fabric-request.')->group(function () {
        Route::get('dtable', [App\Http\Controllers\FabricRequestController::class,'dtable'])->name('dtable');
        Route::get('{packinglist_id}/detail', [App\Http\Controllers\FabricRequestController::class,'detail'])->name('detail');
        Route::post('sync', [App\Http\Controllers\FabricRequestController::class,'sync'])->name('sync');
    });
    Route::resource('fabric-request', App\Http\Controllers\FabricRequestController::class);

    
    Route::prefix('fabric-status')->name('fabric-status.')->group(function () {
        Route::get('dtable', [App\Http\Controllers\FabricStatusController::class,'dtable'])->name('dtable');
    });
    Route::resource('fabric-status', App\Http\Controllers\FabricStatusController::class);
});

Route::group([
    'middleware' => [
        'auth',
        'can:user-menu',
    ],
    'controller' => App\Http\Controllers\FetchSelectController::class,
    'prefix' => 'fetch-select',
    'as' => 'fetch-select.',
],function() {
    Route::get('', 'index')->name('index');
    Route::get('invoice', 'select_invoice')->name('invoice');
    Route::get('color', 'select_color')->name('color');
    Route::get('rack', 'select_rack')->name('rack');
});