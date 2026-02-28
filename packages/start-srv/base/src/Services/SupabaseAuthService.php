<?php

namespace Universo\Start\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

/**
 * SupabaseAuthService - Supabase authentication proxy service
 *
 * Handles all communication with Supabase Auth REST API on behalf of the
 * Laravel backend, keeping Supabase credentials server-side only.
 */
class SupabaseAuthService
{
    private Client $client;
    private string $supabaseUrl;
    private string $supabaseKey;

    public function __construct()
    {
        $this->supabaseUrl = rtrim(config('services.supabase.url', ''), '/');
        $this->supabaseKey = config('services.supabase.key', '');

        $this->client = new Client([
            'base_uri' => $this->supabaseUrl . '/auth/v1/',
            'headers' => [
                'apikey'       => $this->supabaseKey,
                'Content-Type' => 'application/json',
            ],
            'http_errors' => false,
            'timeout'     => 10,
        ]);
    }

    /**
     * Sign in with email and password.
     *
     * @param  string  $email
     * @param  string  $password
     * @return array{data: array|null, error: string|null}
     */
    public function signInWithPassword(string $email, string $password): array
    {
        try {
            $response = $this->client->post('token?grant_type=password', [
                'json' => [
                    'email'    => $email,
                    'password' => $password,
                ],
            ]);

            $body = json_decode((string) $response->getBody(), true);

            if ($response->getStatusCode() !== 200) {
                return ['data' => null, 'error' => $body['error_description'] ?? $body['msg'] ?? 'Authentication failed'];
            }

            return ['data' => $body, 'error' => null];
        } catch (\Throwable $e) {
            Log::error('[SupabaseAuthService] signInWithPassword failed', ['exception' => $e->getMessage()]);

            return ['data' => null, 'error' => 'Authentication service unavailable'];
        }
    }

    /**
     * Sign up with email and password.
     *
     * @param  string  $email
     * @param  string  $password
     * @return array{data: array|null, error: string|null}
     */
    public function signUp(string $email, string $password): array
    {
        try {
            $response = $this->client->post('signup', [
                'json' => [
                    'email'    => $email,
                    'password' => $password,
                ],
            ]);

            $body = json_decode((string) $response->getBody(), true);

            if (! in_array($response->getStatusCode(), [200, 201])) {
                return ['data' => null, 'error' => $body['error_description'] ?? $body['msg'] ?? 'Registration failed'];
            }

            return ['data' => $body, 'error' => null];
        } catch (\Throwable $e) {
            Log::error('[SupabaseAuthService] signUp failed', ['exception' => $e->getMessage()]);

            return ['data' => null, 'error' => 'Authentication service unavailable'];
        }
    }

    /**
     * Sign out the given access token.
     *
     * @param  string  $accessToken
     * @return array{success: bool, error: string|null}
     */
    public function signOut(string $accessToken): array
    {
        try {
            $response = $this->client->post('logout', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);

            if ($response->getStatusCode() !== 204) {
                $body = json_decode((string) $response->getBody(), true);

                return ['success' => false, 'error' => $body['msg'] ?? 'Sign out failed'];
            }

            return ['success' => true, 'error' => null];
        } catch (\Throwable $e) {
            Log::error('[SupabaseAuthService] signOut failed', ['exception' => $e->getMessage()]);

            return ['success' => false, 'error' => 'Authentication service unavailable'];
        }
    }

    /**
     * Get the current user via an access token.
     *
     * @param  string  $accessToken
     * @return array{data: array|null, error: string|null}
     */
    public function getUser(string $accessToken): array
    {
        try {
            $response = $this->client->get('user', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);

            $body = json_decode((string) $response->getBody(), true);

            if ($response->getStatusCode() !== 200) {
                return ['data' => null, 'error' => $body['msg'] ?? 'Failed to get user'];
            }

            return ['data' => $body, 'error' => null];
        } catch (\Throwable $e) {
            Log::error('[SupabaseAuthService] getUser failed', ['exception' => $e->getMessage()]);

            return ['data' => null, 'error' => 'Authentication service unavailable'];
        }
    }

    /**
     * Refresh an access token using a refresh token.
     *
     * @param  string  $refreshToken
     * @return array{data: array|null, error: string|null}
     */
    public function refreshToken(string $refreshToken): array
    {
        try {
            $response = $this->client->post('token?grant_type=refresh_token', [
                'json' => [
                    'refresh_token' => $refreshToken,
                ],
            ]);

            $body = json_decode((string) $response->getBody(), true);

            if ($response->getStatusCode() !== 200) {
                return ['data' => null, 'error' => $body['error_description'] ?? $body['msg'] ?? 'Token refresh failed'];
            }

            return ['data' => $body, 'error' => null];
        } catch (\Throwable $e) {
            Log::error('[SupabaseAuthService] refreshToken failed', ['exception' => $e->getMessage()]);

            return ['data' => null, 'error' => 'Authentication service unavailable'];
        }
    }
}
