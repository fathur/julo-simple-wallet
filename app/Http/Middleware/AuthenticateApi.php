<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (is_null($request->header('Authorization'))) {
            throw new \Exception('Need authentication.');
        }

        $user = auth()->user();

        if (is_null($user)) {
            throw new \Exception('Unauthorized.');
        }

        return $next($request);
    }
}
