<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register new user.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('NG9')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Register Successful',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    /**
     * Login.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('NG9')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login Successful',
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * Current authenticated user.
     */
    public function me(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'user' => Auth::user(),
        ]);
    }

    /**
     * Logout.
     */
    public function logout(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout Successful',
        ]);
    }
}
