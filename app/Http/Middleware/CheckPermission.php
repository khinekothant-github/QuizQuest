<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::user();

        if (!$user || !$user->hasPermission($permission)) {
            if ($request->is('api/*')) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
            return redirect()->route('403');
        }

        return $next($request);
    }
}
