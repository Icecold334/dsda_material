<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectSudin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $sudin_id = auth()->user()->sudin_id;

            if ($sudin_id) {
                // hanya set sudin_id ke request input jika create/update
                $request->merge(['sudin_id' => $sudin_id]);
            }
        }

        return $next($request);
    }
}
