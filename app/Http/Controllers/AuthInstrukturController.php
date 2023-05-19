<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthInstrukturController extends Controller
{
    public function loginInstruktur(Request $request)
    {
        $credentials = $request->only('ID_INSTRUKTUR', 'password');

        if (Auth::guard('instruktur')->attempt($credentials)) {
            $guard = Auth::guard('instruktur')->user();
            $token = $guard->createToken('API Token')->accessToken;
            return response()->json([
                'message' => 'Authenticated333',
                'data' => $guard,
                'token_type' => 'Bearer',
                'token' => $token,
                'kode' => 3
            ]);
        }

        return response()->json(['error' => 'Invalid credentials.'], 401);
    }

    public function logoutInstruktur(Request $request)
    {
        $guard = Auth::guard('instruktur')->user();
        $request->$guard->token()->revoke();
        return response()->json(['message' => 'Logged out successfully.']);
    }
}
