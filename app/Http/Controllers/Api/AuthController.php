<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {

        Log::info('🚀 Requête reçue depuis JS', $request->all());
    dd('Authenticating user...');
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => 'Authentification réussie',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Identifiants incorrects',
        ], 401);
    }
}
