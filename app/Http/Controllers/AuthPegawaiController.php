<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthPegawaiController extends Controller
{
    public function loginPegawai(Request $request)
    {
        $credentials = $request->only('id_pegawai', 'password');

        if (Auth::guard('pegawai')->attempt($credentials)) {
            $guard = Auth::guard('pegawai')->user();
            $token = $guard->createToken('API Token')->accessToken;
            return response()->json([
                'message' => 'Authenticated222',
                'user' => $guard,
                'token_type' => 'Bearer',
                'token' => $token
            ]);
        }

        return response()->json(['error' => 'Invalid credentials.'], 401);
    }

    public function logoutPegawai(Request $request)
    {
        $guard = Auth::guard('pegawai')->user();
        $request->$guard->token()->revoke();
        return response()->json(['message' => 'Logged out successfully.']);
    }
}
