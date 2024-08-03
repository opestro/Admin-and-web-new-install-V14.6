<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\GuestUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class GuestMiddleware
{

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!Auth::guard('customer')->check()) {
            if (!session('guest_id')) {
                $guestId = GuestUser::create([
                    'ip_address' => $request->ip(),
                    'created_at' => now(),
                ]);

                session()->put('guest_id', $guestId?->id);
            }
        }
        return $next($request);
    }
}
