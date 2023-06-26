<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Controller function for Login
     */
    public function login(Request $request)
    {
        /**
         * Validation input for Login form
         */
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        /**
         * mengambil data pengguna berdasarkan email
         */
        $user = User::where('email', $request->email)->first();

        // Memeriksa apakah pengguna ada dan passwordnya cocok
    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Email atau kata sandi yang diberikan tidak benar.'],
        ])->status(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

        /**
         * Membuat token JWT untuk pengguna
         */
        $accessToken = $user->createToken($request->email)->plainTextToken;
        $user->token = $accessToken;

        return response()->json([
            'email' => $user->email,
            'accessToken' => $accessToken,
            'refresh_token' => $user->refresh_token,
            $user
        ]);
    }

    /**
     * Function Controller for Register form
     */
    public function register(Request $request)
    {
        // Validasi Input untuk Register
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Pengecekan apakah email sudah ada pada database
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'Email sudah terdaftar',
            ], 409);
        }

        // Membuat user baru
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        // Generate refresh token
        $refreshToken = Str::random(32);
        $user->refresh_token = $refreshToken;

        $user->save();

        return response()->json([
            'message' => 'Registrasi berhasil',
            'data' => $user,
            'refresh_token' => $refreshToken,
        ], 201);
    }


    /**
     * Function Controller for Update Token
     */
    public function updateToken(Request $request)
    {
        // Validasi input untuk pembaruan token
        $request->validate([
            'token' => 'required',
        ]);

        // Mengambil pengguna berdasarkan token refresh yang diberikan
        $user = User::where('refresh_token', $request->token)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'token' => ['Token refresh tidak valid.'],
            ]);
        }

        // Membuat token JWT baru untuk pengguna
        $newAccessToken = $user->createToken($user->email)->plainTextToken;
        $newRefreshToken = Str::random(32); // Mengenerate refresh token baru

        // Mengupdate refresh token pada pengguna
        $user->refresh_token = $newRefreshToken;
        $user->save();

        return response()->json([
            'accessToken' => $newAccessToken,
            'refreshToken' => $newRefreshToken,
            'user' => $user
        ]);
    }

    /**
     * Function for Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Successfully logout!',
        ]);
    }

}
