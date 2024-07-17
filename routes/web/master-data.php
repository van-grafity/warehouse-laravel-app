<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [
        'auth',
        'can:color.access',
    ],
    'controller' => App\Http\Controllers\ColorController::class,
    'prefix' => 'color',
    'as' => 'color.',
], function () {
    Route::get('', 'index')->name('index');
    Route::get('dtable', 'dtable')->name('dtable');
    Route::get('{color}', 'show')->name('show');
    Route::post('', 'store')->name('store');
    Route::put('{color}', 'update')->name('update');
    Route::delete('{color}', 'destroy')->name('destroy');
});


Route::group([
    'middleware' => [
        'auth',
        'can:supplier.access',
    ],
    'controller' => App\Http\Controllers\SupplierController::class,
    'prefix' => 'supplier',
    'as' => 'supplier.',
], function () {
    Route::get('', 'index')->name('index');
    Route::get('dtable', 'dtable')->name('dtable');
    Route::get('{supplier}', 'show')->name('show');
    Route::post('', 'store')->name('store');
    Route::put('{supplier}', 'update')->name('update');
    Route::delete('{supplier}', 'destroy')->name('destroy');
});


Route::group([
    'middleware' => [
        'auth',
        'can:department.access',
    ],
    'controller' => App\Http\Controllers\DepartmentController::class,
    'prefix' => 'department',
    'as' => 'department.',
], function () {
    Route::get('', 'index')->name('index');
    Route::get('dtable', 'dtable')->name('dtable');
    Route::get('{department}', 'show')->name('show');
    Route::post('', 'store')->name('store');
    Route::put('{department}', 'update')->name('update');
    Route::delete('{department}', 'destroy')->name('destroy');
});


Route::group([
    'middleware' => [
        'auth',
        'can:location-row.access',
    ],
    'controller' => App\Http\Controllers\LocationRowController::class,
    'prefix' => 'location-row',
    'as' => 'location-row.',
], function () {
    Route::get('', 'index')->name('index');
    Route::get('dtable', 'dtable')->name('dtable');
    Route::get('{location_row}', 'show')->name('show');
    Route::post('', 'store')->name('store');
    Route::put('{location_row}', 'update')->name('update');
    Route::delete('{location_row}', 'destroy')->name('destroy');
});

Route::group([
    'middleware' => [
        'auth',
        'can:location.access',
    ],
    'controller' => App\Http\Controllers\LocationController::class,
    'prefix' => 'location',
    'as' => 'location.',
], function () {
    Route::get('', 'index')->name('index');
    Route::get('dtable', 'dtable')->name('dtable');
    Route::get('{location}', 'show')->name('show');
    Route::post('', 'store')->name('store');
    Route::put('{location}', 'update')->name('update');
    Route::delete('{location}', 'destroy')->name('destroy');
});

Route::group([
    'middleware' => [
        'auth',
        'can:rack.access',
    ],
    'controller' => App\Http\Controllers\RackController::class,
    'prefix' => 'rack',
    'as' => 'rack.',
], function () {
    Route::get('', 'index')->name('index');
    Route::get('dtable', 'dtable')->name('dtable');
    Route::get('print-barcode', 'print_barcode')->name('print-barcode');
    
    Route::get('{rack}', 'show')->name('show');
    Route::post('', 'store')->name('store');
    Route::put('{rack}', 'update')->name('update');
    Route::delete('{rack}', 'destroy')->name('destroy');
});
