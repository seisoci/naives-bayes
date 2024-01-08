<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
          Log::error($guard);
            if (Auth::guard($guard)->check()) {
              return match ($guard) {
                'web' => redirect(RouteServiceProvider::USER_HOME),
                'panel' => redirect(RouteServiceProvider::HOME),
                default => '/',
              };
            }
        }

        return $next($request);
    }
}
