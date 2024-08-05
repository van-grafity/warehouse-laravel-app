<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [
        'auth',
        'can:fabric-status-report.access',
    ],
    'controller' => App\Http\Controllers\FabricStatusReportController::class,
    'prefix' => 'fabric-status-report',
    'as' => 'fabric-status-report.',
], function () {
    Route::get('', 'index')->name('index');
    Route::get('dtable-preview', 'dtable_preview')->name('dtable-preview');
    Route::get('print','print')->name('print');
    Route::get('export-excel','export_excel')->name('export-excel');
});