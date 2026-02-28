<?php

namespace Universo\Start\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /**
     * The auth user endpoint returns unauthenticated state when no session exists.
     */
    public function test_user_endpoint_returns_unauthenticated_without_session(): void
    {
        $response = $this->getJson('/api/v1/auth/user');

        $response->assertStatus(200)
            ->assertJson([
                'user'          => null,
                'authenticated' => false,
            ]);
    }

    /**
     * The auth login endpoint requires email and password.
     */
    public function test_login_requires_email_and_password(): void
    {
        $response = $this->postJson('/api/v1/auth/login', []);

        $response->assertStatus(422)
            ->assertJsonStructure(['error']);
    }

    /**
     * The auth register endpoint requires email and password.
     */
    public function test_register_requires_email_and_password(): void
    {
        $response = $this->postJson('/api/v1/auth/register', []);

        $response->assertStatus(422)
            ->assertJsonStructure(['error']);
    }

    /**
     * The auth logout endpoint clears the session.
     */
    public function test_logout_succeeds_without_active_session(): void
    {
        $response = $this->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);
    }

    /**
     * Login response must not contain raw Supabase tokens.
     * The frontend never needs to see raw tokens — auth is session-based.
     */
    public function test_login_response_does_not_contain_raw_tokens(): void
    {
        // When credentials are wrong, the service returns an error — just verify structure
        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        // Either 401 (bad credentials, proxied from Supabase) or if Supabase is unreachable 401
        // In both cases: no access_token or refresh_token should be in the response
        $this->assertArrayNotHasKey('access_token', $response->json());
        $this->assertArrayNotHasKey('refresh_token', $response->json());
    }

    /**
     * Register response must not contain raw Supabase tokens.
     */
    public function test_register_response_does_not_contain_raw_tokens(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'email'    => 'test@example.com',
            'password' => 'password123',
        ]);

        // 422 (validation pass, but Supabase unavailable/error) or 201
        // In both cases: no raw tokens should be exposed
        $this->assertArrayNotHasKey('access_token', $response->json());
        $this->assertArrayNotHasKey('refresh_token', $response->json());
    }
}
