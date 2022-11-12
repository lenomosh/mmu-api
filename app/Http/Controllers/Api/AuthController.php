<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $request->authenticate();
        $token = $request->user()->createToken('Laravel Password Grant Client')->accessToken;

        return response()->json([
            'token' => $token,
            'user' => Auth::user()->only(['name', 'email'])
        ]);

    }

    public function loginWithGoogle(Request $request)
    {
        $client = new  Google_Client(['client_id' => config('services.google.client_id')]);

        $payload = $client->verifyIdToken($request->get('credential'));

        if (!$payload) {
            return response()->json(['message' => "Invalid token"], 422);
        }

        $user = User::query()->where('google_id', $payload['sub'])->first();
        if (!$user) {

            $user = User::query()->create([
                'first_name' => $payload['given_name'],
                'last_name' => $payload['family_name'],
                'picture' => $payload['picture'],
                'email' => $payload['email'],
                'google_id' => $payload['sub']
            ]);
        }
        return response()->json([
            'token' => $user->createToken("Google Auth Token")->accessToken,
            'user' => $user->only(['name', 'first_name', 'email', 'picture', 'first_name'])
        ]);

    }
}
