<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        $token = $request->header('X-API-TOKEN');

        if ($token !== env('API_TOKEN')) {

            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);

        }

        return $next($request);
    }
}
