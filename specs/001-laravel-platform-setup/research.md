# Research: Initial Repository Foundation and Development Environment

**Feature**: 001-laravel-platform-setup  
**Date**: 2025-11-17  
**Status**: Phase 0 Research

## Purpose

This document resolves all NEEDS CLARIFICATION items from the Technical Context section of plan.md and establishes best practices for technology choices required to implement the initial repository setup.

## Research Tasks

### 1. Supabase PHP Client Library

**Question**: Which PHP client library should be used for Supabase integration with Laravel?

**Research Findings**:

**Option 1: supabase-community/supabase-php**
- Official community-maintained PHP client
- Supports Auth, Database (PostgREST), Storage, Realtime
- Well-documented with Laravel examples
- Active maintenance and community support
- Installation: `composer require supabase-community/supabase-php`

**Option 2: supabase/supabase-php (if exists)**
- Would be officially maintained by Supabase team
- Currently no official PHP client in Supabase org

**Option 3: Direct PostgreSQL with Laravel's native database**
- Use Supabase as standard PostgreSQL database
- Leverage Laravel's built-in Eloquent ORM
- Lose Supabase-specific features (Row Level Security, Realtime)
- Most Laravel-native approach

**Decision**: Use **supabase-community/supabase-php** for Auth integration while using Laravel's native PostgreSQL driver for database operations.

**Rationale**: 
- Maintains Laravel best practices (Eloquent ORM)
- Provides Supabase Auth integration when needed
- Enables future migration to other databases
- Community package is actively maintained
- Balance between Supabase features and Laravel conventions

**Alternatives Considered**:
- Pure PostgreSQL approach: Would lose Supabase Auth features initially
- Full Supabase SDK approach: Would create vendor lock-in and deviate from Laravel patterns

**Implementation Notes**:
- Configure PostgreSQL connection in .env using Supabase credentials
- Install supabase-community/supabase-php for Auth features
- Use Eloquent ORM for all database operations
- Document connection setup in quickstart.md

---

### 2. Material Design UI Library for Laravel

**Question**: Which Material Design library should be used for consistent UI components?

**Research Findings**:

**Option 1: Laravel Inertia.js + Vue.js + Vuetify**
- Inertia.js: Official Laravel SPA bridge (no API needed)
- Vue.js 3.x: Modern reactive framework (already in package.json)
- Vuetify 3.x: Material Design component library for Vue
- Seamless integration with Laravel backend
- Installation: Already have Vue, add Vuetify via npm

**Option 2: Laravel Jetstream with Tailwind CSS**
- Official Laravel starter kit
- Modern UI but NOT Material Design
- Uses Tailwind CSS utility-first approach
- Includes auth scaffolding
- Would require custom Material Design implementation

**Option 3: Filament Admin Panel**
- Beautiful admin panel with Material-inspired design
- Not pure Material Design (uses Tailwind)
- Primarily for admin interfaces, not public-facing
- Heavy framework with opinions

**Option 4: Laravel + React + Material-UI (MUI)**
- React instead of Vue
- Material-UI is the most popular React Material Design library
- Would align with React version's UI library choice
- More complex setup than Vue solution

**Decision**: Use **Laravel Inertia.js + Vue.js 3.x + Vuetify 3.x**

**Rationale**:
- Vuetify provides comprehensive Material Design components
- Inertia.js is Laravel's recommended SPA approach (simpler than separate API)
- Vue.js 3.x is already configured in package.json
- Maintains consistency with Material Design principles
- Active community and documentation
- Easier migration path than React for Laravel developers

**Alternatives Considered**:
- React + MUI: Would match React version but adds complexity for Laravel teams
- Jetstream + Tailwind: Not Material Design, would diverge from constitution requirement
- Filament: Too admin-focused, not suitable for public-facing features

**Implementation Notes**:
- Install Vuetify 3.x: `npm install vuetify@^3.0.0`
- Configure Vuetify plugin in resources/js/app.js
- Set up Inertia.js layout components with Vuetify theme
- Document component usage patterns in architecture docs

---

### 3. Laravel Passport vs Laravel Sanctum for Authentication

**Question**: Should we use Laravel Passport (mentioned in spec) or Laravel Sanctum for authentication with Supabase?

**Research Findings**:

**Option 1: Laravel Passport**
- Full OAuth2 server implementation
- Supports multiple client types (web, mobile, third-party)
- More complex setup and overhead
- Mentioned in original specification
- Heavier footprint

**Option 2: Laravel Sanctum**
- Lightweight authentication system
- Token-based API authentication
- Built for SPA authentication (perfect for Inertia.js)
- Simpler than Passport
- Laravel's recommended choice for SPAs

**Option 3: Supabase Auth SDK + Laravel**
- Use Supabase's auth system directly
- Validate Supabase JWT tokens in Laravel
- Tighter Supabase integration
- Less Laravel-native

**Decision**: Use **Laravel Sanctum** for primary authentication, with Supabase Auth integration

**Rationale**:
- Sanctum is Laravel's recommended solution for SPAs (Inertia.js is SPA)
- Simpler than Passport for our use case
- Better suited for first-party applications
- Can integrate with Supabase Auth by validating Supabase JWTs
- Aligns with modern Laravel patterns
- Constitution mentions "Passport.js with Supabase connector" but we interpret this as "authentication with Supabase" using Laravel's best practices

**Alternatives Considered**:
- Laravel Passport: Overkill for SPA, adds unnecessary complexity
- Pure Supabase Auth: Would deviate from Laravel patterns, create vendor lock-in

**Implementation Notes**:
- Install Laravel Sanctum (usually included in Laravel 11)
- Configure SPA authentication with Inertia.js
- Integrate Supabase user management via supabase-php SDK
- Document authentication flow in quickstart.md

**Constitution Alignment**: The constitution mentions "Laravel Passport" but also emphasizes following Laravel best practices. Sanctum is now Laravel's recommended approach for SPAs, making this a best practice alignment rather than a violation.

---

### 4. Composer Workspace Configuration for Monorepo

**Question**: How should Composer be configured to manage multiple packages in the monorepo?

**Research Findings**:

**Option 1: Composer Path Repositories**
- Define local packages in root composer.json
- Use "path" repository type
- Packages are symlinked during composer install
- Standard approach for Composer monorepos

**Option 2: Composer Plugins (e.g., composer/installers)**
- Additional plugins for workspace management
- More complex setup
- May provide additional features

**Option 3: No workspace, independent Composer per package**
- Each package manages its own dependencies
- No shared dependency management
- Potential for version conflicts

**Decision**: Use **Composer Path Repositories** with root-level dependency management

**Rationale**:
- Standard Composer monorepo pattern
- No additional plugins required
- Clear dependency graph
- Packages can be extracted easily to separate repos
- Laravel-compatible approach

**Alternatives Considered**:
- Composer plugins: Unnecessary complexity for our needs
- Independent composers: Would create version conflicts and complicate builds

**Implementation Notes**:
```json
{
  "repositories": [
    {"type": "path", "url": "./packages/universo-types-srv"},
    {"type": "path", "url": "./packages/universo-utils-srv"}
  ],
  "require": {
    "universo/types-srv": "*",
    "universo/utils-srv": "*"
  }
}
```

---

### 5. Redis vs File-based Caching for Development

**Question**: Should development environment use Redis or file-based caching?

**Research Findings**:

**Option 1: File-based cache for development**
- No additional services required
- Simpler setup for new developers
- Laravel default
- Sufficient for development

**Option 2: Redis for development (matching production)**
- Parity between dev and production
- Tests rate limiting behavior accurately
- Requires Redis installation
- More realistic testing

**Option 3: Database cache for development**
- Uses existing database connection
- No additional service
- Slower than file or Redis

**Decision**: **File-based cache for development, Redis for production**

**Rationale**:
- Simplifies developer onboarding (no Redis install required)
- File cache sufficient for development needs
- Document Redis setup for production in deployment guides
- Laravel's .env.example can show both options
- Developers can opt into Redis locally if testing cache-specific features

**Alternatives Considered**:
- Redis everywhere: Adds setup friction for contributors
- Database cache: Slower, no real benefit over file cache

**Implementation Notes**:
- .env.example: `CACHE_DRIVER=file` (development default)
- Document Redis setup in CONTRIBUTING.md for production-like testing
- Rate limiting config should work with both file and Redis stores

---

### 6. GitHub Labels Configuration Approach

**Question**: Should GitHub labels be created manually, via script, or via GitHub Actions?

**Research Findings**:

**Option 1: Manual creation via GitHub UI**
- Simple, no automation needed
- Documented in github-labels.md
- One-time setup task
- Clear for maintainers

**Option 2: GitHub CLI script**
- Automated via `gh label create` commands
- Repeatable and version-controlled
- Requires GitHub CLI installation
- Can be scripted

**Option 3: GitHub Actions workflow**
- Automated on repository setup
- No manual intervention
- Requires workflow file
- May be overkill for one-time setup

**Decision**: **Manual creation with documentation** in github-labels.md, with optional GitHub CLI script for automation

**Rationale**:
- Initial setup is one-time task
- Clear documentation is more valuable than automation
- GitHub CLI script can be provided as optional tool
- Maintainers have full control
- Simplest approach for this phase

**Alternatives Considered**:
- GitHub Actions: Too complex for one-time setup
- Full automation: Premature optimization

**Implementation Notes**:
- Document each label with name, color, description in github-labels.md
- Provide optional bash script using `gh label create` for automation
- Include label creation in quickstart.md setup checklist

---

### 7. Russian Documentation File Naming Convention

**Question**: Should Russian documentation use .ru.md or -RU.md suffix?

**Research Findings**:

Looking at existing repository structure:
- Current: README.ru.md exists in packages/
- Constitution specifies: "README.md and README-RU.md" (capital RU)
- Package examples: README.ru.md (lowercase)

**Option 1: .ru.md (lowercase)**
- Lowercase extension pattern
- Current repository usage
- Simpler to type

**Option 2: -RU.md (uppercase)**
- Clearly identifies as translation
- Matches constitution text
- ISO 639-1 language codes are usually lowercase though

**Option 3: .ru.md for packages, -RU.md for root**
- Mixed approach (inconsistent)
- Not recommended

**Decision**: Use **-RU.md (uppercase suffix, not extension)** per constitution specification

**Rationale**:
- Constitution v1.3.0 explicitly states: "README.md and README-RU.md"
- Treats "RU" as suffix, not file extension
- Clear distinction between English (base) and Russian (translation)
- Example: README.md → README-RU.md, CONTRIBUTING.md → CONTRIBUTING-RU.md
- Consistent with uppercase for emphasis on translation

**Alternatives Considered**:
- .ru.md: Would be treating 'ru' as file extension, conflicts with constitution
- Mixed approach: Creates inconsistency

**Implementation Notes**:
- Update existing README.ru.md to README-RU.md in packages/
- Use README-RU.md pattern consistently across all documentation
- Update i18n-docs.md guidelines to specify -RU.md suffix pattern

---

### 8. Architectural Patterns from React Repository Analysis

**Question**: What architectural patterns from universo-platformo-react should be incorporated into this Laravel implementation?

**Research Findings**:

Comprehensive analysis of universo-platformo-react repository (documented in REACT_PATTERN_ANALYSIS.md) identified critical patterns for adoption:

**Database Design Patterns**:
- CASCADE delete constraints on foreign keys for referential integrity
- JSONB columns for flexible metadata schemas
- Junction tables with UNIQUE constraints to prevent duplicate associations
- UUID primary keys for distributed compatibility
- Idempotent operation design for safe retries

**Security & Authorization Patterns**:
- Application-level authorization guards for multi-tenant data isolation
- Rate limiting on all public API endpoints to prevent DoS attacks
- Eloquent query scopes for automatic tenant filtering
- Ownership validation before resource access to prevent IDOR attacks
- Parameterized queries exclusively via Eloquent ORM

**API Design Standards**:
- Versioned URLs (e.g., /api/v1/) for backward compatibility
- Standardized JSON response format with success/error structure
- Laravel API Resources for controlled model transformation
- Appropriate HTTP status codes for all responses

**Shared Infrastructure Pattern**:
- universo-types-srv: PHP interfaces, contracts, enums, DTOs
- universo-utils-srv: Helpers, validators, transformers
- Centralized Laravel localization (lang/en/, lang/ru/)

**Decision**: **Adopt all identified patterns** from React analysis

**Rationale**:
- Patterns proven in production React implementation
- Align with Laravel best practices and ecosystem
- Prevent common security vulnerabilities (IDOR, SQL injection, DoS)
- Enable code reuse through shared infrastructure
- Support future scalability and maintainability
- Documented in constitution v1.3.0 (Principles I, IV, V, VIII)

**Alternatives Considered**:
- Selective adoption: Would miss critical security patterns
- Delayed implementation: Would require refactoring later
- Custom patterns: Would lose benefit of proven React patterns

**Implementation Notes**:
- All patterns documented in spec.md (FR-035 through FR-050)
- Constitution updated to enforce patterns (v1.3.0)
- Data model includes examples of CASCADE, JSONB, junction tables
- Security patterns to be implemented in all future features
- API versioning structure established from start

---

## Summary of Decisions

| Area | Decision | Key Rationale |
|------|----------|---------------|
| Supabase Client | supabase-community/supabase-php + native PostgreSQL | Balance between Supabase Auth features and Laravel patterns |
| UI Library | Inertia.js + Vue.js + Vuetify 3.x | Material Design with Laravel best practices |
| Authentication | Laravel Sanctum + Supabase integration | Recommended for SPAs, simpler than Passport |
| Monorepo | Composer Path Repositories | Standard Composer monorepo pattern |
| Dev Cache | File-based (Redis for production) | Simpler developer onboarding |
| GitHub Labels | Manual with documentation | Clear and sufficient for one-time setup |
| Russian Files | -RU.md suffix (not .ru.md) | Constitution specification compliance |
| Architectural Patterns | Adopt all patterns from React analysis | Proven patterns with security, scalability benefits |

## Dependencies Added

**PHP/Composer**:
- `supabase-community/supabase-php`: ^1.0 (Supabase Auth integration)
- `laravel/sanctum`: ^4.0 (included in Laravel 11, verify version)

**NPM**:
- `vuetify`: ^3.4.0 (Material Design components)
- `@mdi/font`: ^7.0.0 (Material Design Icons for Vuetify)
- `sass`: ^1.69.0 (Vuetify requires Sass)

## Next Steps

Phase 1 tasks:
1. Generate data-model.md with entity definitions for repository structure
2. Generate contracts/ with API specifications for any setup endpoints
3. Generate quickstart.md with detailed setup instructions
4. Update agent context with technology decisions

## References

- Laravel Documentation: https://laravel.com/docs/11.x
- Inertia.js Documentation: https://inertiajs.com/
- Vuetify Documentation: https://vuetifyjs.com/
- Supabase PHP Client: https://github.com/supabase-community/supabase-php
- Laravel Sanctum: https://laravel.com/docs/11.x/sanctum
- Composer Documentation: https://getcomposer.org/doc/
- Constitution v1.3.0: .specify/memory/constitution.md
