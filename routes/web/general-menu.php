<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [
        'auth',
        'can:invoice.access',
    ],
    'controller' => App\Http\Controllers\InvoiceController::class,
    'prefix' => 'invoice',
    'as' => 'invoice.',
], function () {
    Route::get('', 'index')->name('index');
    Route::get('dtable', 'dtable')->name('dtable');
    Route::get('{invoice}', 'show')->name('show');
    Route::post('', 'store')->name('store');
    Route::put('{invoice}', 'update')->name('update');
    Route::delete('{invoice}', 'destroy')->name('destroy');
});


Route::group([
    'middleware' => [
        'auth',
        'can:packinglist.access',
    ],
    'controller' => App\Http\Controllers\PackinglistController::class,
    'prefix' => 'packinglist',
    'as' => 'packinglist.',
], function () {
    Route::get('', 'index')->name('index');
    Route::get('dtable', 'dtable')->name('dtable');
    Route::get('print-qrcode', 'print_qrcode')->name('print-qrcode');
    Route::post('import', 'import')->name('import');



    Route::get('{packinglist}', 'show')->name('show');
    Route::get('{packinglist}/detail', 'detail')->name('detail');
    Route::get('{packinglist}/information-card', 'information_card')->name('information-card');

    Route::post('', 'store')->name('store');
    Route::put('{packinglist}', 'update')->name('update');
    Route::delete('{packinglist}', 'destroy')->name('destroy');
});


Route::group([
    'middleware' => [
        'auth',
    ],
    'controller' => App\Http\Controllers\FabricRollController::class,
    'prefix' => 'fabric-roll',
    'as' => 'fabric-roll.',
], function () {
    Route::delete('mass-delete', 'mass_delete')->name('mass-delete');
    Route::get('dtable', 'dtable')->name('dtable');
    Route::get('{packinglist}', 'show')->name('show');
    Route::post('', 'store')->name('store');
    Route::put('{packinglist}', 'update')->name('update');
    Route::delete('{packinglist}', 'destroy')->name('destroy');
});