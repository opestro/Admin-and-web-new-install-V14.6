<?php

namespace App\Http\Middleware;

use App\Utils\Helpers;
use App\Traits\ActivationClass;
use Brian2694\Toastr\Facades\Toastr;
use Closure;
use http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ActivationCheckMiddleware
{
    use ActivationClass;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('login/'.getWebConfig(name: 'admin_login_url'))) {
            $response = $this->actch();
            $data = json_decode($response->getContent(), true);
            if (!$data['active']) {
                return Redirect::away(base64_decode('aHR0cHM6Ly82YW10ZWNoLmNvbS9zb2Z0d2FyZS1hY3RpdmF0aW9u'))->send();
            }
        }
        return $next($request);
    }
}
