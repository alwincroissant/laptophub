<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $roleName = $user
            ? Role::where('role_id', $user->role_id)->value('role_name')
            : null;

        if (! $user || ! in_array($roleName, ['Admin', 'InventoryManager'])) {
            return redirect()->route('index')->with('error', 'Unauthorized access to admin dashboard.');
        }

        return $next($request);
    }
}
