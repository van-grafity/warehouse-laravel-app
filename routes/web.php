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

// ## General Route for Default User
Route::group([
    'middleware' => [
        'auth',
    ]
], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home.index');


    Route::get('profile', [App\Http\Controllers\UserController::class, 'profile'])->name('profile.index');
    Route::post('profile/change_password', [App\Http\Controllers\UserController::class, 'change_password'])->name('profile.change-password');
});

Route::group([
    'middleware' => [
        'auth',
    ],
    'controller' => App\Http\Controllers\FetchSelectController::class,
    'prefix' => 'fetch-select',
    'as' => 'fetch-select.',
], function () {
    Route::get('', 'index')->name('index');
    Route::get('invoice', 'select_invoice')->name('invoice');
    Route::get('color', 'select_color')->name('color');
    Route::get('rack', 'select_rack')->name('rack');
});



// ## import route from external file
require __DIR__ . '/web/by-role.php';
require __DIR__ . '/web/master-data.php';
require __DIR__ . '/web/general-menu.php';
require __DIR__ . '/web/manage-fabric.php';
require __DIR__ . '/web/manage-rack.php';
// ## ======================================



