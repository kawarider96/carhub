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
        
        // 🔥 GLOBAL DEBUG LOGGER – minden kivételt naplóz
    $exceptions->report(function (Throwable $e) {
        \Log::error('💀 GLOBAL EXCEPTION', [
            'error' => $e->getMessage(),
            'type'  => get_class($e),
            'file'  => $e->getFile(),
            'line'  => $e->getLine(),
            'trace' => collect($e->getTrace())->take(10),
        ]);
    });

    // 🔥 GLOBAL RENDER DEBUGGER – minden request tartalmát is logoljuk
    $exceptions->render(function (Throwable $e, $request) {
        \Log::error('🛰 RENDER EXCEPTION – REQUEST DUMP', [
            'url'     => $request->fullUrl(),
            'method'  => $request->method(),
            'all'     => $request->all(),
            'files'   => $request->allFiles(),
            'error'   => $e->getMessage(),
            'type'    => get_class($e),
        ]);

        // Speciális kezelés: AuthenticationException
        if ($e instanceof AuthenticationException) {

            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Nincs bejelentkezve',
                ], 401);
            }

            return redirect()->guest(route('login'));
        }

        // alapértelmezett render
        return null; 
    });
    })->create();
