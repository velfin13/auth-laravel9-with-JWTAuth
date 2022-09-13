<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (Exception $er) {
            if ($er instanceof TokenInvalidException) {
                return response()->json([
                    "status" => "token invalaid"
                ], 403);
            }

            if ($er instanceof TokenExpiredException) {
                return response()->json([
                    "status" => "token expired"
                ], 403);
            }

            return response()->json([
                "status" => "token not found"
            ], 403);
        }
        return $next($request);
    }
}
