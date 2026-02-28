# start-srv — Start Pages Backend Package

Backend package for Universo Platformo start pages. Provides Supabase authentication proxy and start page routing via the Laravel backend.

## Overview

This package handles:

- **Supabase authentication proxy** — sign-in, sign-up, sign-out, token refresh, user session
- **Session management** — stores Supabase tokens in Laravel sessions (server-side)
- **API routes** — RESTful endpoints consumed by the `start-frt` frontend package

## Package Structure

```
start-srv/
├── base/                          # Core implementation
│   ├── src/
│   │   ├── Controllers/
│   │   │   └── AuthController.php # Auth API endpoints
│   │   ├── Services/
│   │   │   └── SupabaseAuthService.php # Supabase REST API client
│   │   └── Providers/
│   │       └── StartServiceProvider.php # Package registration
│   ├── routes/
│   │   └── api.php                # API route definitions
│   ├── tests/
│   │   └── Feature/
│   │       └── AuthControllerTest.php
│   └── composer.json
├── README.md
└── README-RU.md
```

## API Endpoints

All routes are prefixed with `/api/v1/auth`.

| Method | Path | Description |
|--------|------|-------------|
| `POST` | `/api/v1/auth/login` | Sign in with email and password |
| `POST` | `/api/v1/auth/register` | Register a new account |
| `POST` | `/api/v1/auth/logout` | Sign out current session |
| `GET` | `/api/v1/auth/user` | Get current authenticated user |
| `POST` | `/api/v1/auth/refresh` | Refresh the access token |

### Request/Response Examples

**POST /api/v1/auth/login**

```json
// Request
{
    "email": "user@example.com",
    "password": "secret123"
}

// Response 200
{
    "user": { "id": "...", "email": "user@example.com" },
    "access_token": "...",
    "refresh_token": "...",
    "expires_in": 3600
}

// Response 401 (invalid credentials)
{
    "error": "Invalid login credentials"
}
```

**GET /api/v1/auth/user**

```json
// Response 200 (authenticated)
{
    "user": { "id": "...", "email": "user@example.com" },
    "authenticated": true
}

// Response 200 (not authenticated)
{
    "user": null,
    "authenticated": false
}
```

## Configuration

Add Supabase credentials to your `.env` file:

```env
SUPABASE_URL=https://your-project.supabase.co
SUPABASE_KEY=your-supabase-anon-key
SUPABASE_SERVICE_KEY=your-supabase-service-key
```

## Registration

The package is registered automatically via Laravel's package discovery. It is declared in root `composer.json`:

```json
{
    "repositories": [
        { "type": "path", "url": "packages/start-srv/base" }
    ],
    "require": {
        "universo/start-srv": "@dev"
    }
}
```

## Authentication Flow

1. **Frontend** sends credentials to `/api/v1/auth/login`
2. **AuthController** forwards them to Supabase REST API via `SupabaseAuthService`
3. **Supabase** returns access token, refresh token, and user data
4. **AuthController** stores tokens in the Laravel session and returns them to the frontend
5. **Frontend** can call `/api/v1/auth/user` at any time to check authentication status
6. If the access token has expired, the backend automatically tries to refresh it using the stored refresh token

## Testing

```bash
php artisan test --filter AuthControllerTest
```
