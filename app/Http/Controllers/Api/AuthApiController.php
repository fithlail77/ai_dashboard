<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthApiController extends Controller
{
    /**
     * Endpoint Login API untuk Aplikasi Mobile Android.
     * Menggunakan Laravel Sanctum Bearer Token.
     */
    public function login(Request $request): JsonResponse
    {
        // 1. Sanitasi dan Validasi Input
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $email = Str::lower(trim(strip_tags((string) $request->input('email'))));
        $password = (string) $request->input('password');
        $deviceName = strip_tags((string) $request->input('device_name', 'Android Mobile App'));
        $throttleKey = Str::transliterate($email.'|'.$request->ip());

        // 2. Proteksi Brute-Force Rate Limiting (Maksimal 5 percobaan per menit)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return response()->json([
                'status' => 'error',
                'message' => "Terlalu banyak percobaan login. Silakan tunggu {$seconds} detik lagi.",
            ], 429);
        }

        // 3. Pencarian User secara aman (Parameterized Query Eloquent)
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            return response()->json([
                'status' => 'error',
                'message' => 'Kredensial email atau kata sandi tidak cocok.',
            ], 401);
        }

        // 4. Verifikasi status akun aktif
        if (! $user->is_active) {
            RateLimiter::hit($throttleKey, 60);

            return response()->json([
                'status' => 'error',
                'message' => 'Akun dinonaktifkan. Silakan hubungi IT administrator PT GUM.',
            ], 403);
        }

        // 5. Update metadata login dan bersihkan rate limiter
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);
        RateLimiter::clear($throttleKey);

        // 6. Buat Token Sanctum untuk Android
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'data' => [
                'token_type' => 'Bearer',
                'access_token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'phone' => $user->phone,
                    'last_login_at' => $user->last_login_at?->toIso8601String(),
                ],
            ],
        ], 200);
    }

    /**
     * Endpoint Profil User saat ini.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'phone' => $user->phone,
                    'last_login_at' => $user->last_login_at?->toIso8601String(),
                ],
            ],
        ], 200);
    }

    /**
     * Endpoint Logout API (Mencabut Token Bearer).
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil. Token telah dicabut.',
        ], 200);
    }
}
