<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json([
                'error' => 'Invalid Credentials'
            ]);
        }
        $user = Auth::user();

        Auth::login($user);

        $token = $user->createToken('Auth_token')->plainTextToken;

        return response()->json([
            'success' => 'Logged in Successfully',
            'token' => $token,
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'success' => 'Logged Out Successfully'
        ]);
    }
}
