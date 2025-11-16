<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Route middleware-k:
        $middleware->alias([
            'active' => \App\Http\Middleware\EnsureUserIsActive::class,
            'admin'  => \App\Http\Middleware\EnsureAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        
        $exceptions->render(function (AuthenticationException $e, $request) {
            // API vagy JSON kérések → 401
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Nincs bejelentkezve',
                ], 401);
            }

            // Web esetén továbbra is redirect
            return redirect()->guest(route('login'));
        });
    })->create();
