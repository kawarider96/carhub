<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class EnsureUserIsActive
{
    use ApiResponse;

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Ha az account nem aktív → 423 Locked
        if (!$user->is_active) {
            return $this->error('A felhasználói fiók zárolva van', 423);
        }

        return $next($request);
    }
}
