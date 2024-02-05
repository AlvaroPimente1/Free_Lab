<?php

namespace App\Http\Middleware;

use Closure;

class SessionExpired
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
        if (!auth()->check()) {
            return redirect()->route('login')->with('expired', 'A sessão expirou, faça o login novamente.');
        }
        return $next($request);
    }
}
