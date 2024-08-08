<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [
        'auth',
        'can:invoice-chart.access',
    ],
    'controller' => App\Http\Controllers\AnalysisController::class,
    'prefix' => 'invoice-chart',
    'as' => 'invoice-chart.',
], function () {
    Route::get('','index')->name('index');
});