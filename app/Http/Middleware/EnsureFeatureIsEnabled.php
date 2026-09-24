<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Hides a route behind a flag in config/features.php.
 *
 * The route stays registered so links and tests can reach it by name; when the
 * flag is off the route simply does not exist as far as a visitor is concerned.
 */
class EnsureFeatureIsEnabled
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        abort_unless(config("features.{$feature}"), 404);

        return $next($request);
    }
}
