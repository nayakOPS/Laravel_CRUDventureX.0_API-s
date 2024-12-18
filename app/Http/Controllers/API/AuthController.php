<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Register a new user
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User registered successfully. Please Log in to get your access token',
        ], 201);
    }

    // Log in and generate token
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token
        ], 200);
    }

    // Log out user (revoke token)
    public function logout(Request $request)
    {
         // Revoke all tokens..
         $request -> user() -> tokens() -> delete();

         // Revoke the current token
         $request -> user() -> currentAccessToken() -> delete();

         return response()->json([
             'message' => 'You have been successfully logged out'
         ],200);
    }
}
