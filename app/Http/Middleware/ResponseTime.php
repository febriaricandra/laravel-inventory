<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);
        
        $response = $next($request);
        
        $end = microtime(true);
        $responseTime = ($end - $start) * 1000; // Convert to milliseconds
        
        $response->headers->set('X-Response-Time', $responseTime . 'ms');
        
        return $response;
    }
}
