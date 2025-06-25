<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            return redirect()->route('filament.auth.pages.dashboard');
        }

        // jika url terdapat /auth maka redirect ke '/'
        // if (str_contains($request->url(), '/auth')) {
        //     // return redirect()->route('filament./.pages.dashboard');
        //     return redirect()->to('/');
        // }

        return $next($request);
    }
}
