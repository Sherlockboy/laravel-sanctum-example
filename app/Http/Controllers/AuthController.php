<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'message' => 'Successfully logged in!',
                'code' => 200,
                'user' => auth()->user(),
                'token' => auth()->user()->createToken('auth-token')
            ]);
        }

        return response()->json([
            'message' => 'Failure! User not found.',
            'code' => 404
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password'])
        ]);

        auth()->login($user);

        return response()->json([
            'message' => 'Successfully registered!',
            'code' => 201,
            'user' => $user,
            'token' => $user->createToken('auth-token')
        ]);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();  
        
        return response()->json([
            'message' => 'Successfully logged out!',
            'code' => 200
        ]);
    }
}
