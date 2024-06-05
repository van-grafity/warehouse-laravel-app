<?php

use Illuminate\Support\Facades\Route;

// ## Route for Developer Role
Route::group([
    'middleware' => [
        'auth',
        'can:developer-menu',
    ]
], function () {
    Route::resource('permission-category', App\Http\Controllers\PermissionCategoryController::class);
    Route::get('permission-category-dtable', [App\Http\Controllers\PermissionCategoryController::class, 'dtable'])->name('permission-category.dtable');

    Route::resource('permission', App\Http\Controllers\PermissionController::class);
    Route::get('permission-dtable', [App\Http\Controllers\PermissionController::class, 'dtable'])->name('permission.dtable');

    Route::resource('role', App\Http\Controllers\RoleController::class);
    Route::get('role-dtable', [App\Http\Controllers\RoleController::class, 'dtable'])->name('role.dtable');
    Route::get('role/{role}/manage-permission', [App\Http\Controllers\RoleController::class, 'manage_permission'])->name('role.manage-permission');
    Route::post('role/{role}/manage-permission', [App\Http\Controllers\RoleController::class, 'manage_permission_update'])->name('role.manage-permission-update');
});


// ## Route for Admin Role
Route::group([
    'middleware' => [
        'auth',
        'can:admin-menu',
    ]
], function () {
    Route::resource('user', App\Http\Controllers\UserController::class);
    Route::get('user-dtable', [App\Http\Controllers\UserController::class, 'dtable'])->name('user.dtable');
    Route::get('user/{user}/reset-password', [App\Http\Controllers\UserController::class, 'reset_password'])->name('user.reset-password');
    Route::get('user/{user}/restore', [App\Http\Controllers\UserController::class, 'restore'])->name('user.restore');
});