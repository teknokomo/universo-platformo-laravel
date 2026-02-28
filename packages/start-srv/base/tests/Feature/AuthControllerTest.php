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
}
