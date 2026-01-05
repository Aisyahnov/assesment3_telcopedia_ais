<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'nim'  => 'required|string|unique:users,nim',
            'password' => 'required|string|min:3'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'nim' => $fields['nim'],
            'password' => Hash::make($fields['password']),
        ]);

        $token = $user->createToken('telcopedia_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'nim' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('nim', $fields['nim'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json(['message' => 'NIM atau Password Salah!'], 401);
        }

        $token = $user->createToken('telcopedia_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Token dihapus, logout berhasil!'], 200);
    }
}