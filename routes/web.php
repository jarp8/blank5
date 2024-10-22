<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\LoginController;
use App\Http\Controllers\authentications\LogoutController;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\HomeController;

// Guest routes
Route::middleware('guest')->group(function () {
  // Login
  Route::get('login', [LoginController::class, 'index'])->name('login');
  Route::post('login', [LoginController::class, 'store']);
});

// Auth routes
Route::middleware('auth')->group(function () {
  Route::resource('home', HomeController::class)->only(['index']);

  Route::post('logout', [LogoutController::class, 'logout'])->name('logout');
});

// // Main Page Route
// Route::get('/', [HomePage::class, 'index'])->name('pages-home');
// Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');

// // locale
// Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
// Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// // authentication
// Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
// Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
