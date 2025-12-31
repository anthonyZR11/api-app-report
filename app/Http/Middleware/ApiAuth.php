<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      if(!auth('sanctum')->check()) {
        return response()->json(['message' => 'Usuario no autenticado o token expirado'], 401);
      }

      auth('sanctum')->setUser(auth('sanctum')->user());

      return $next($request);
    }
}
