<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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
        if (Auth::check()) {
            $lastActivity = session('lastActivityTime');
            if ($lastActivity && (time() - $lastActivity) > config('session.lifetime') * 60) {
                Auth::logout();
                return redirect()->route('login')->with('expired', 'A sessão expirou por inatividade. Faça o login novamente.');
            }
            session(['lastActivityTime' => time()]);
        }

        if (!Auth::check()) {
            return redirect()->route('login')->with('expired', 'A sessão expirou, faça o login novamente.');
        }

        return $next($request);
    }
}
