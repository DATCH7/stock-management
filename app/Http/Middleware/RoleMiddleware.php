<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role->role_name;

        if (!in_array($userRole, $roles)) {
            abort(403, 'Access denied');
        }

        return $next($request);
    }
}
