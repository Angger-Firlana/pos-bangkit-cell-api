<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // kalau belum login
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // kalau token user nggak punya salah satu role yg dibolehkan
        $allowed = false;
        foreach ($roles as $role) {
            if ($user->tokenCan($role)) {
                $allowed = true;
                break;
            }
        }

        if (!$allowed) {
            return response()->json(['message' => 'Forbidden - Role tidak sesuai'], 403);
        }

        return $next($request);
    }
}
