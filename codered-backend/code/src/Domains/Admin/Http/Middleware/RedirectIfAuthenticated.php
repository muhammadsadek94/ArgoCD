<?php

namespace App\Domains\Admin\Http\Middleware;

use Closure;
use Constants;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->guard('admin')->check()) {
            return redirect(Constants::ADMIN_BASE_URL . '/dashboard');
        }

        return $next($request);
    }
}
