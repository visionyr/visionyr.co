<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (): void {
            Route::middleware('web')
                ->prefix('webcms')
                ->name('webcms.')
                ->group(base_path('routes/webcms.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'feature' => \App\Http\Middleware\EnsureFeatureIsEnabled::class,
        ]);

        $middleware->redirectGuestsTo(
            fn (Request $request) => $request->is('webcms', 'webcms/*')
                ? route('webcms.login')
                : route('login'),
        );

        $middleware->redirectUsersTo(
            fn (Request $request) => $request->is('webcms', 'webcms/*')
                ? route('webcms.dashboard')
                : route('dashboard'),
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
