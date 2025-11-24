# API Contracts: Initial Repository Foundation

**Feature**: 001-laravel-platform-setup  
**Date**: 2025-11-17  
**Version**: 1.0.0

## Overview

This document defines API contracts for the initial repository setup. While this feature is primarily infrastructure-focused, it establishes the API patterns that all future features must follow.

## API Standards

All APIs in Universo Platformo Laravel MUST follow these standards:

### Versioning
- **Pattern**: `/api/v{version}/{resource}`
- **Example**: `/api/v1/clusters`
- **Current Version**: v1
- **Version in URL**: Required for all API endpoints

### Authentication
- **Method**: Laravel Sanctum token-based authentication
- **Header**: `Authorization: Bearer {token}`
- **Endpoints**: Most endpoints require authentication
- **Public Endpoints**: Explicitly marked as public in documentation

### Rate Limiting
- **Limit**: 60 requests per minute per IP address
- **Header**: `X-RateLimit-Limit`, `X-RateLimit-Remaining`
- **Response**: 429 Too Many Requests when exceeded
- **Retry Header**: `Retry-After` in seconds

### Response Format

**Success Response**:
```json
{
  "data": {
    // Resource data or array of resources
  },
  "meta": {
    "timestamp": "2025-11-17T06:30:00Z"
  }
}
```

**Paginated Response**:
```json
{
  "data": [
    // Array of resources
  ],
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 10,
    "path": "/api/v1/resource",
    "per_page": 15,
    "to": 15,
    "total": 150
  },
  "links": {
    "first": "/api/v1/resource?page=1",
    "last": "/api/v1/resource?page=10",
    "prev": null,
    "next": "/api/v1/resource?page=2"
  }
}
```

**Error Response**:
```json
{
  "error": {
    "message": "Human-readable error message",
    "code": "ERROR_CODE",
    "status": 400
  },
  "errors": {
    "field_name": [
      "Validation error message 1",
      "Validation error message 2"
    ]
  }
}
```

### HTTP Status Codes

- **200 OK**: Successful GET, PUT, PATCH
- **201 Created**: Successful POST creating resource
- **204 No Content**: Successful DELETE
- **400 Bad Request**: Invalid request data
- **401 Unauthorized**: Missing or invalid authentication
- **403 Forbidden**: Authenticated but not authorized
- **404 Not Found**: Resource doesn't exist
- **422 Unprocessable Entity**: Validation failed
- **429 Too Many Requests**: Rate limit exceeded
- **500 Internal Server Error**: Server error

---

## Health Check Endpoint

### GET /api/health

**Description**: Check API health and availability. Public endpoint, no authentication required.

**Authentication**: None

**Rate Limit**: 10 requests per minute per IP

**Request**:
```http
GET /api/health HTTP/1.1
Host: api.universo.pro
Accept: application/json
```

**Response 200 OK**:
```json
{
  "data": {
    "status": "healthy",
    "version": "1.0.0",
    "timestamp": "2025-11-17T06:30:00Z",
    "services": {
      "database": "connected",
      "cache": "connected"
    }
  }
}
```

**Response 503 Service Unavailable** (when unhealthy):
```json
{
  "error": {
    "message": "Service temporarily unavailable",
    "code": "SERVICE_UNAVAILABLE",
    "status": 503
  },
  "data": {
    "status": "unhealthy",
    "services": {
      "database": "disconnected",
      "cache": "connected"
    }
  }
}
```

**Use Cases**:
- Load balancer health checks
- Monitoring system probes
- Development environment verification
- CI/CD pipeline validation

---

## Version Endpoint

### GET /api/version

**Description**: Get API version information. Public endpoint.

**Authentication**: None

**Rate Limit**: 10 requests per minute per IP

**Request**:
```http
GET /api/version HTTP/1.1
Host: api.universo.pro
Accept: application/json
```

**Response 200 OK**:
```json
{
  "data": {
    "api_version": "1.0.0",
    "laravel_version": "11.0.0",
    "php_version": "8.2.0",
    "environment": "production"
  }
}
```

**Use Cases**:
- Client version compatibility checks
- Debug information for support
- Monitoring version deployment

---

## Future API Patterns (Reference)

### Standard CRUD Operations

For future feature entities (e.g., Clusters, Domains, Resources), follow this pattern:

#### List Resources
```http
GET /api/v1/{resources}
```

**Query Parameters**:
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 15, max: 100)
- `sort`: Sort field (default: created_at)
- `order`: Sort direction (asc/desc, default: desc)
- `filter[field]`: Filter by field value

**Example**:
```http
GET /api/v1/clusters?page=2&per_page=20&sort=name&order=asc&filter[user_id]=123
```

#### Get Single Resource
```http
GET /api/v1/{resources}/{id}
```

**Example**:
```http
GET /api/v1/clusters/550e8400-e29b-41d4-a716-446655440000
```

#### Create Resource
```http
POST /api/v1/{resources}
Content-Type: application/json

{
  "name": "Resource Name",
  "description": "Resource description",
  // ... other fields
}
```

**Response**: 201 Created with resource in response body

#### Update Resource
```http
PUT /api/v1/{resources}/{id}
Content-Type: application/json

{
  "name": "Updated Name",
  // ... fields to update
}
```

**Response**: 200 OK with updated resource

#### Partial Update
```http
PATCH /api/v1/{resources}/{id}
Content-Type: application/json

{
  "name": "Updated Name"
}
```

**Response**: 200 OK with updated resource

#### Delete Resource
```http
DELETE /api/v1/{resources}/{id}
```

**Response**: 204 No Content

### Nested Resources

For hierarchical relationships (e.g., Cluster → Domain → Resource):

```http
GET /api/v1/clusters/{cluster_id}/domains
GET /api/v1/clusters/{cluster_id}/domains/{domain_id}
POST /api/v1/clusters/{cluster_id}/domains
GET /api/v1/domains/{domain_id}/resources
```

### Relationship Management

**Create Association** (Idempotent):
```http
POST /api/v1/clusters/{id}/domains/{domain_id}
```

**Response**: 200 OK (whether newly created or already exists)

**Remove Association**:
```http
DELETE /api/v1/clusters/{id}/domains/{domain_id}
```

**Response**: 204 No Content

---

## Error Codes

Standard error codes to be used across all endpoints:

| Code | Description |
|------|-------------|
| `VALIDATION_ERROR` | Request validation failed |
| `UNAUTHORIZED` | Authentication required or invalid |
| `FORBIDDEN` | Insufficient permissions |
| `NOT_FOUND` | Resource not found |
| `RATE_LIMIT_EXCEEDED` | Too many requests |
| `SERVER_ERROR` | Internal server error |
| `SERVICE_UNAVAILABLE` | Service temporarily unavailable |
| `DATABASE_ERROR` | Database operation failed |
| `CONFLICT` | Resource conflict (e.g., duplicate) |

---

## OpenAPI Specification

Future features SHOULD include OpenAPI 3.0 specification files in the contracts directory:

```yaml
# Example: contracts/clusters-api.yaml
openapi: 3.0.0
info:
  title: Clusters API
  version: 1.0.0
paths:
  /api/v1/clusters:
    get:
      summary: List clusters
      # ... full OpenAPI specification
```

**Tools**:
- Validation: Spectral, OpenAPI Validator
- Documentation: Swagger UI, Redoc
- Client Generation: OpenAPI Generator

---

## Security Headers

All API responses MUST include security headers:

```http
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000; includeSubDomains
Content-Security-Policy: default-src 'self'
```

---

## CORS Configuration

For frontend SPA access:

```http
Access-Control-Allow-Origin: https://app.universo.pro
Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS
Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With
Access-Control-Max-Age: 86400
```

**Development**: Allow localhost origins
**Production**: Whitelist specific domains

---

## Implementation Checklist

For each new API endpoint, ensure:

- [ ] Versioned URL (/api/v1/...)
- [ ] Authentication middleware (except public endpoints)
- [ ] Rate limiting configured
- [ ] Form Request validation
- [ ] API Resource transformation
- [ ] Consistent error responses
- [ ] Authorization guards (policies)
- [ ] OpenAPI documentation
- [ ] Integration tests
- [ ] Bilingual error messages

---

## Testing Examples

### PHPUnit Integration Test

```php
<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HealthCheckTest extends TestCase
{
    public function test_health_check_returns_success()
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'status',
                         'version',
                         'timestamp',
                         'services'
                     ]
                 ]);
    }
}
```

---

## Summary

This contract document establishes:

1. **API Standards**: Versioning, authentication, rate limiting, response format
2. **Health Endpoints**: System monitoring and version checking
3. **CRUD Patterns**: Reference implementation for future features
4. **Error Handling**: Standard error codes and responses
5. **Security**: Headers and CORS configuration
6. **Testing**: Integration test patterns

All future features MUST follow these API standards for consistency across the platform.
