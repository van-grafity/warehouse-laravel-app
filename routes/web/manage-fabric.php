<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [
        'auth',
        'can:fabric-stock-in.access',
    ],
    'controller' => App\Http\Controllers\FabricStockInController::class,
    'prefix' => 'fabric-stock-in',
    'as' => 'fabric-stock-in.',
], function () {
    Route::get('', 'index')->name('index');
    Route::get('dtable', 'dtable')->name('dtable');
    Route::get('dtable-roll-list', 'dtable_roll_list')->name('dtable-roll-list');
    Route::get('{packinglist}/detail', 'detail')->name('detail');
    Route::post('', 'store')->name('store');
});


Route::group([
    'middleware' => [
        'auth',
        'can:fabric-status.access',
    ],
    'controller' => App\Http\Controllers\FabricStatusController::class,
    'prefix' => 'fabric-status',
    'as' => 'fabric-status.',
], function () {
    Route::get('', 'index')->name('index');
    Route::get('dtable', 'dtable')->name('dtable');
    Route::get('dtable-roll-list', 'dtable_roll_list')->name('dtable-roll-list');

    Route::get('{packinglist}', 'show')->name('show');
    Route::get('{packinglist}/detail', 'detail')->name('detail');
    Route::get('{packinglist}/export', 'export')->name('export');

    Route::post('', 'store')->name('store');
    Route::delete('remove-roll', 'remove_roll')->name('remove-roll');
});


Route::group([
    'middleware' => [
        'auth',
        'can:fabric-request.access',
    ],
    'controller' => App\Http\Controllers\FabricRequestController::class,
    'prefix' => 'fabric-request',
    'as' => 'fabric-request.',
], function () {
    Route::get('', 'index')->name('index');
    Route::get('dtable', 'dtable')->name('dtable');
    Route::post('sync', 'sync')->name('sync');
    Route::get('dtable-roll-list', 'dtable_roll_list')->name('dtable-roll-list');
    Route::post('{fabric_request}', 'receive_form')->name('receive-form');
    Route::get('report','report')->name('report');
    Route::get('dtable-preview', 'dtable_preview')->name('dtable-preview');
    Route::get('print','print')->name('print');
    Route::get('{fabric_request}/issuance-note-full','issuance_note_full')->name('issuance-note-full');
    Route::get('{fabric_request}/issuance-note','issuance_note')->name('issuance-note');
    Route::get('{fabric_request}', 'show')->name('show');
    Route::get('{fabric_request}/detail', 'detail')->name('detail');
    Route::get('{fabric_request}/issue-fabric', 'issue_fabric')->name('issue-fabric');
    Route::post('{fabric_request}/issue-fabric', 'issue_fabric_store')->name('issue-fabric-store');
});