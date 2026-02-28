<?php

namespace Universo\Start\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;
use Universo\Start\Services\SupabaseAuthService;

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
     * Successful login response must not contain raw Supabase tokens.
     * Tokens stay in the server-side session only.
     */
    public function test_login_success_does_not_expose_raw_tokens(): void
    {
        $mock = Mockery::mock(SupabaseAuthService::class);
        $mock->shouldReceive('signInWithPassword')
            ->once()
            ->andReturn([
                'data' => [
                    'access_token'  => 'mock-access-token',
                    'refresh_token' => 'mock-refresh-token',
                    'expires_in'    => 3600,
                    'user'          => ['id' => 'user-1', 'email' => 'test@example.com'],
                ],
                'error' => null,
            ]);
        $this->app->instance(SupabaseAuthService::class, $mock);

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson(['authenticated' => true]);
        $this->assertArrayNotHasKey('access_token', $response->json());
        $this->assertArrayNotHasKey('refresh_token', $response->json());
    }

    /**
     * Successful register response must not contain raw Supabase tokens.
     */
    public function test_register_success_does_not_expose_raw_tokens(): void
    {
        $mock = Mockery::mock(SupabaseAuthService::class);
        $mock->shouldReceive('signUp')
            ->once()
            ->andReturn([
                'data' => [
                    'access_token'  => 'mock-access-token',
                    'refresh_token' => 'mock-refresh-token',
                    'user'          => ['id' => 'user-2', 'email' => 'new@example.com'],
                ],
                'error' => null,
            ]);
        $this->app->instance(SupabaseAuthService::class, $mock);

        $response = $this->postJson('/api/v1/auth/register', [
            'email'    => 'new@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJson(['authenticated' => true]);
        $this->assertArrayNotHasKey('access_token', $response->json());
        $this->assertArrayNotHasKey('refresh_token', $response->json());
    }

    /**
     * The /user endpoint after token refresh must not expose raw tokens.
     */
    public function test_user_endpoint_after_refresh_does_not_expose_raw_tokens(): void
    {
        // Simulate a session with an expired access token and a valid refresh token
        session([
            'supabase_access_token'  => 'expired-token',
            'supabase_refresh_token' => 'valid-refresh-token',
        ]);

        $mock = Mockery::mock(SupabaseAuthService::class);
        $mock->shouldReceive('getUser')
            ->once()
            ->with('expired-token')
            ->andReturn(['data' => null, 'error' => 'Token expired']);
        $mock->shouldReceive('refreshToken')
            ->once()
            ->with('valid-refresh-token')
            ->andReturn([
                'data' => [
                    'access_token'  => 'new-access-token',
                    'refresh_token' => 'new-refresh-token',
                    'user'          => ['id' => 'user-1', 'email' => 'test@example.com'],
                ],
                'error' => null,
            ]);
        $this->app->instance(SupabaseAuthService::class, $mock);

        $response = $this->getJson('/api/v1/auth/user');

        $response->assertStatus(200)
            ->assertJson(['authenticated' => true]);
        $this->assertArrayNotHasKey('access_token', $response->json());
        $this->assertArrayNotHasKey('refresh_token', $response->json());
    }
}
