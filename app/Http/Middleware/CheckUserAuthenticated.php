<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // بررسی اینکه آیا کاربر ورود کرده است یا خیر
        if (!Auth::check()) {
            // اگر کاربر ورود نکرده باشد، به صفحه لاگین هدایت می‌شود
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        return $next($request);
    }
}
