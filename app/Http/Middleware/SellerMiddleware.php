<?php

namespace App\Http\Middleware;

use Closure;
use App\Utils\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
        $user = DB::table('user_offer')
            ->where('expire', '>', Carbon::now())
            ->where('user_id', auth('seller')->id())
            ->first();
        if (auth('seller')->check() && auth('seller')->user()->status == 'approved' && $user) {
            return $next($request);
        }
        auth()->guard('seller')->logout();

        return redirect()->route('vendor.auth.login');
    }
}
