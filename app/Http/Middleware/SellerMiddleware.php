<?php

namespace App\Http\Middleware;

use Closure;
use App\Utils\Helpers;
use Illuminate\Support\Facades\Auth;

class SellerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth('seller')->check() && auth('seller')->user()->status == 'approved') {
            return $next($request);
        }
        auth()->guard('seller')->logout();

        return redirect()->route('vendor.auth.login');
    }
}
