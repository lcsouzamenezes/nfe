<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->input('token');
        if (!$token) {
            $token = $request->header('token');
        }

        if (!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }

        try {
            $credentials = JWT::decode($token, ('7Fsxc2A865V6'), ['HS256']);
        } catch (ExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], 400);
        } catch (Exception $e) {
            dd($e);
            return response()->json([
                'error' => 'An error while decoding token.'
            ], 400);
        }

        $request->auth = $credentials->data;

        return $next($request);
    }
}
