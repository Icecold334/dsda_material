<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSudin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Super Admin (sudin_id null) bebas
        if ($user && $user->sudin_id === null) {
            return $next($request);
        }

        // Jika route memiliki parameter "sudin_id"
        if ($request->route('sudin_id')) {
            if ($request->route('sudin_id') != $user->sudin_id) {
                abort(403, "Akses Sudin tidak diizinkan.");
            }
        }

        return $next($request);
    }
}
