<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class IsCustomer
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
        if($request->user() && $request->user()->type == 'C'){
        
            return $next($request);
        }

        throw new AccessDeniedHttpException('Unauthorized.');
    }
}
