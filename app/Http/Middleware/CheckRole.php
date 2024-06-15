<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            // Если пользователь не аутентифицирован
            return redirect('/login');
        }
        $user = Auth::user();
        if ($user->role === null || !in_array($user->role, $roles)) {
            return redirect('/')->with('error', 'У вас нет доступа к этой странице.');
        }
        return $next($request);
    }
}
