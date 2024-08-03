<?php

namespace App\Http\Middleware;

use App\Models\GuestUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('customer')->check()) {
            if(!session('guest_id')){
                $guest_id = GuestUser::insertGetId([
                    'ip_address' => $request->ip(),
                    'created_at' => now(),
                ]);

                session()->put('guest_id', $guest_id);
            }
        }
        return $next($request);
    }
}
