<?php

namespace App\Conta\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class Autenticacao
{
    public function usuario(Request $request, $token)
    {
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

        return response()->json($credentials, 200);
    }
}
