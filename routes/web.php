<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BrandBlueprintController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

// Generating a blueprint requires an account; a generated one stays reachable by
// its link so it can be shared.
Route::middleware('auth')->group(function () {
    Route::get('create', [BrandBlueprintController::class, 'create'])->name('blueprint.create');
    Route::post('create', [BrandBlueprintController::class, 'store'])
        ->middleware('throttle:20,1')
        ->name('blueprint.store');
});

Route::get('blueprint/{blueprint}', [BrandBlueprintController::class, 'show'])->name('blueprint.show');

Route::get('contact', [ContactController::class, 'create'])->name('contact');
Route::post('contact', [ContactController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('contact.store');

Route::get('privacy', LegalController::class)->defaults('document', 'privacy')->name('privacy');
Route::get('terms', LegalController::class)->defaults('document', 'terms')->name('terms');

/*
|--------------------------------------------------------------------------
| Member Authentication
|--------------------------------------------------------------------------
|
| Members sign in through the default "web" guard.
|
*/

Route::middleware('guest')->group(function () {
    // Self-registration is off for now; staff create members in the CMS. The
    // routes stay named so links resolve, but 404 while the flag is off.
    Route::middleware('feature:member_registration')->group(function () {
        Route::get('register', [RegisterController::class, 'create'])->name('register');
        Route::post('register', [RegisterController::class, 'store'])->name('register.store');
    });

    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
});
