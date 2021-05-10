<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials))
        {
            return response()->json([
                'message' => 'Successfully logged in!',
                'user' => auth()->user(),
                'token' => auth()->user()->createToken('auth-token')->plainTextToken
            ], 200);
        }

        return response()->json([
            'message' => 'Failure! User not found.'
        ], 404);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password'])
        ]);

        return response()->json([
            'message' => 'Successfully registered!',
            'user' => $user,
            'token' => $user->createToken('auth-token')->plainTextToken
        ], 201);
    }

    public function user()
    {
        return response()->json(auth()->user(), 200);
    }
    
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        
        return response()->json([
            'message' => 'Successfully logged out!'
        ], 200);
    }
}
