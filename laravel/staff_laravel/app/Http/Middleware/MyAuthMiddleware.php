<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Illuminate\Support\Str;

class MyAuthMiddleware
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
        $token = '';

        if ($request->header('jwt')) {
            $token = $request->header('jwt');
            $request->jwt = $token;
        } elseif ($request->jwt) {
            $token = $request->jwt;
        } else {
            return response()->json([
                'status' => 'error',
                'res' => 'jwt not found in header'
            ], 401);
        }

        JWTAuth::setToken($token);
        
        try {
            JWTAuth::getPayload();
        } catch (TokenExpiredException $e) {
            return response()->json([
                'status' => 'error',
                'res' => 'Token expired'
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'status' => 'error',
                'res' => 'Invalid token'
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'error',
                'res' => 'Token not provided'
            ], 401);
        } catch (TokenBlacklistedException $e) {
            return response()->json([
                'status' => 'error',
                'res' => 'Token blacklisted'
            ], 401);
        }

        return $next($request);
    }
}