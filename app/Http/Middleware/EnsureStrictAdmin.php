<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;

class EnsureStrictAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $roleName = $request->user() ? Role::where('role_id', $request->user()->role_id)->value('role_name') : null;
        
        if (strtolower($roleName) !== 'admin') {
            abort(403, 'Unauthorized access to restricted admin module.');
        }
        
        return $next($request);
    }
}
