<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HSTS
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
        $response = $next($request);

        if (!$request->secure() && config('app.env') != 'local') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubdomains');
        }

        return $response;
    }
}
