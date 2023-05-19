<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthMemberController extends Controller
{
    public function loginMember(Request $request)
    {
        $credentials = $request->only('NO_MEMBER', 'password');

        if (Auth::guard('member')->attempt($credentials)) {
            $guard = Auth::guard('member')->user();
            $token = $guard->createToken('API Token')->accessToken;
            return response()->json([
                'message' => 'Authenticated111',
                'data' => $guard,
                'token_type' => 'Bearer',
                'token' => $token,
                'kode' => 1
            ]);
        }

        return response()->json(['error' => 'Invalid credentials.'], 401);
    }

    public function logoutMember(Request $request)
    {
        $guard = Auth::guard('member')->user();
        $request->$guard->token()->revoke();
        return response()->json(['message' => 'Logged out successfully.']);
    }
}
