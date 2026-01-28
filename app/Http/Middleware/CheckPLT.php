<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PositionDelegation;
use Symfony\Component\HttpFoundation\Response;

class CheckPLT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();
        $today = Carbon::today();

        // Ambil PLT yang aktif
        $delegations = PositionDelegation::where('user_id', $user->id)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->get();

        // Inject ke user data PLT aktif
        $user->active_delegations = $delegations;

        return $next($request);
    }
}
