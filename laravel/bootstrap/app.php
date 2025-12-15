<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias middleware: for API we do NOT want redirects to a web 'login' route.
        $middleware->alias([
            'auth' => App\Http\Middleware\Authenticate::class,
        ]);
        // Groupe WEB (sessions, cookies, CSRF)
        $middleware->web([
            Illuminate\Cookie\Middleware\EncryptCookies::class,
            Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            Illuminate\Session\Middleware\StartSession::class,
            Illuminate\View\Middleware\ShareErrorsFromSession::class,
            Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Groupe API (stateful pour Sanctum SPA + bindings)
        $middleware->api([
            Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Force 401 JSON for API when unauthenticated, never redirect to a web route
        $exceptions->render(function (Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
            return null; // let Laravel handle non-API as usual
        });
    })->create();
