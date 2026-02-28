<?php

namespace Universo\Start\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Universo\Start\Services\SupabaseAuthService;

/**
 * AuthController - Supabase authentication proxy controller
 *
 * Provides API endpoints for sign-in, sign-up, sign-out, and user retrieval.
 * All Supabase credentials are kept server-side; the frontend receives only
 * the access/refresh tokens it needs.
 */
class AuthController extends Controller
{
    public function __construct(private readonly SupabaseAuthService $auth)
    {
    }

    /**
     * POST /api/v1/auth/login
     * Sign in with email and password.
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $result = $this->auth->signInWithPassword(
            $request->string('email')->toString(),
            $request->string('password')->toString()
        );

        if ($result['error']) {
            return response()->json(['error' => $result['error']], 401);
        }

        $data = $result['data'];

        // Store tokens in session for server-side auth checks
        session([
            'supabase_access_token'  => $data['access_token'],
            'supabase_refresh_token' => $data['refresh_token'],
            'supabase_user'          => $data['user'],
        ]);

        return response()->json([
            'user'          => $data['user'],
            'authenticated' => true,
        ]);
    }

    /**
     * POST /api/v1/auth/register
     * Register a new user with email and password.
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $result = $this->auth->signUp(
            $request->string('email')->toString(),
            $request->string('password')->toString()
        );

        if ($result['error']) {
            return response()->json(['error' => $result['error']], 422);
        }

        $data = $result['data'];
        $accessToken = $data['access_token'] ?? null;

        if ($accessToken) {
            session([
                'supabase_access_token'  => $accessToken,
                'supabase_refresh_token' => $data['refresh_token'] ?? null,
                'supabase_user'          => $data['user'] ?? null,
            ]);
        }

        return response()->json([
            'user'          => $data['user'] ?? null,
            'authenticated' => $accessToken !== null,
            'message'       => 'Registration successful. Please check your email if confirmation is required.',
        ], 201);
    }

    /**
     * POST /api/v1/auth/logout
     * Sign out the current user.
     */
    public function logout(Request $request): JsonResponse
    {
        $accessToken = session('supabase_access_token')
            ?? $request->bearerToken();

        if ($accessToken) {
            $this->auth->signOut($accessToken);
        }

        session()->forget(['supabase_access_token', 'supabase_refresh_token', 'supabase_user']);

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * GET /api/v1/auth/user
     * Return the currently authenticated user.
     */
    public function user(Request $request): JsonResponse
    {
        $accessToken = session('supabase_access_token')
            ?? $request->bearerToken();

        if (! $accessToken) {
            return response()->json(['user' => null, 'authenticated' => false]);
        }

        $result = $this->auth->getUser($accessToken);

        if ($result['error']) {
            // Try to refresh the token before giving up
            $refreshToken = session('supabase_refresh_token');
            if ($refreshToken) {
                $refreshResult = $this->auth->refreshToken($refreshToken);
                if (! $refreshResult['error']) {
                    $data = $refreshResult['data'];
                    session([
                        'supabase_access_token'  => $data['access_token'],
                        'supabase_refresh_token' => $data['refresh_token'],
                        'supabase_user'          => $data['user'],
                    ]);

                    return response()->json([
                        'user'          => $data['user'],
                        'authenticated' => true,
                        'access_token'  => $data['access_token'],
                    ]);
                }
            }

            session()->forget(['supabase_access_token', 'supabase_refresh_token', 'supabase_user']);

            return response()->json(['user' => null, 'authenticated' => false]);
        }

        return response()->json([
            'user'          => $result['data'],
            'authenticated' => true,
        ]);
    }

    /**
     * POST /api/v1/auth/refresh
     * Refresh the current access token.
     */
    public function refresh(Request $request): JsonResponse
    {
        $refreshToken = session('supabase_refresh_token')
            ?? $request->string('refresh_token')->toString();

        if (! $refreshToken) {
            return response()->json(['error' => 'No refresh token available'], 401);
        }

        $result = $this->auth->refreshToken($refreshToken);

        if ($result['error']) {
            session()->forget(['supabase_access_token', 'supabase_refresh_token', 'supabase_user']);

            return response()->json(['error' => $result['error']], 401);
        }

        $data = $result['data'];
        session([
            'supabase_access_token'  => $data['access_token'],
            'supabase_refresh_token' => $data['refresh_token'],
            'supabase_user'          => $data['user'],
        ]);

        return response()->json([
            'user'          => $data['user'],
            'authenticated' => true,
        ]);
    }
}
