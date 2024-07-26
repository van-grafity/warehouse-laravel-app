<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [
        'auth',
        'can:rack-location.access',
    ],
    'controller' => App\Http\Controllers\RackLocationController::class,
    'prefix' => 'rack-location',
    'as' => 'rack-location.',
], function () {
    Route::get('', 'index')->name('index');
    Route::get('dtable', 'dtable')->name('dtable');
    Route::get('dtable-roll-list', 'dtable_roll_list')->name('dtable-roll-list');
    Route::get('{rack_location}', 'detail')->name('detail');
    Route::get('{user}', 'show')->name('show');
    Route::post('', 'store')->name('store');
});

Route::group([
    'middleware' => [
        'auth',
        'can:location-status.access',
    ],
    'controller' => App\Http\Controllers\LocationStatusController::class,
    'prefix' => 'location-status',
    'as' => 'location-status.',
], function () {
    Route::get('', 'index')->name('index');
    Route::get('dtable', 'dtable')->name('dtable');
});