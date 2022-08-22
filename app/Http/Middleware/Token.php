<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Token
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
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        if (!$token)
            return response()->json([
                'error' => true,
                'data' => "Envie um token"
            ], 400);

        if ($token != env("API_KEY"))
            return response()->json([
                'error' => true,
                'data' => "Token inválido"
            ], 400);

        return $next($request);
    }
}
