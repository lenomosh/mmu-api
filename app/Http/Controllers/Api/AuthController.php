<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $request->authenticate();
        $token = $request->user()->createToken('Laravel Password Grant Client')->accessToken;

        return response()->json(['token' => $token]);

    }
}
