<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'name' => 'required|min:6',
                'role' => 'in:admin,operator'
            ]);

            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Registrasi berhasil',
                'data' => $user
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal registrasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string'
            ]);

            $user = User::where('email', $data['email'])->first();

            if (!$user || !Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email atau password salah'
                ], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login berhasil',
                'token' => $token,
                'user' => $user
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal login: ' . $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logout berhasil'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal logout: ' . $e->getMessage()
            ], 500);
        }
    }
}
