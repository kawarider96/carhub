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

    $exceptions->render(function (Throwable $e, $request) {
        // Speciális kezelés: AuthenticationException
        if ($e instanceof AuthenticationException) {

            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Nincs bejelentkezve',
                ], 401);
            }

            return redirect()->guest(route('/'));
        }

        // alapértelmezett render
        return null; 
    });
    })->create();
