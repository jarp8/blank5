<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\authentications\LoginController;
use App\Http\Controllers\authentications\LogoutController;

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
  // Guest routes
  Route::middleware('guest')->group(function () {
    // Login
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
  });

  // Auth routes
  Route::middleware('auth')->group(function () {
    Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

    Route::middleware(['permission', 'permission.gate'])->group(function () {
      // Dashboard
      Route::resource('dashboard', DashboardController::class)->only(['index']);

      // Roles
      Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
      Route::post('roles/{role}/permissions', [RoleController::class, 'storePermissions'])->name('roles.storepermissions');
      Route::resource('roles', RoleController::class);

      // Users
      Route::get('users/{user}/permissions', [UserController::class, 'permissions'])->name('users.permissions');
      Route::post('users/{user}/permissions', [UserController::class, 'storePermissions'])->name('users.storepermissions');
      Route::resource('users', UserController::class);
    });
  });
});
