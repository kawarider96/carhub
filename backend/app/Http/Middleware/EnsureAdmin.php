<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class EnsureAdmin
{
    use ApiResponse;

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            return $this->error('Nincs jogosultságod ehhez a művelethez', 403);
        }

        return $next($request);
    }
}
