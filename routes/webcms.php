<?php

use App\Http\Controllers\WebCms\AdminUserController;
use App\Http\Controllers\WebCms\BrandBlueprintController;
use App\Http\Controllers\WebCms\Auth\LoginController;
use App\Http\Controllers\WebCms\DashboardController;
use App\Http\Controllers\WebCms\MemberController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CMS Routes
|--------------------------------------------------------------------------
|
| These routes are all prefixed with "/webcms" and named "webcms.*". They are
| served to staff authenticated through the "admin" guard.
|
*/

Route::middleware('guest:admin')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth:admin')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('admin-users', AdminUserController::class)->except('show');
    Route::resource('members', MemberController::class)->except('show');
    Route::post('members/{member}/refresh-quota', [MemberController::class, 'refreshQuota'])
        ->name('members.refresh-quota');
    // The parameter is named to match the controller's $blueprint argument, so
    // implicit binding resolves it.
    Route::resource('brand-blueprints', BrandBlueprintController::class)
        ->only(['index', 'show', 'destroy'])
        ->parameters(['brand-blueprints' => 'blueprint']);

    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
});
