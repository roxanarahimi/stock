<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Token
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request['api_key'] != 'tzbC4oXXFlLSmccTww3xOhTQDpHg8tugfeQ24SF') {
            return response(['message' => 'access denied!'], 500);
        }else{
            return $next($request);;
        }
    }
}
