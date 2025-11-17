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
| UI Library | Inertia.js + Vue.js 3 + Vuetify 3.x | Material Design 3 with Laravel best practices |
| Authentication | Laravel Sanctum + Supabase JWT validation | Recommended for SPAs, simpler than Passport |
| Monorepo Structure | Composer Path Repositories + Repository Pattern | Standard Composer pattern with DDD organization |
| Package Architecture | Modular with DDD principles | Domain-driven, testable, maintainable |
| API Design | URI Path Versioning (/api/v1/) | Industry standard, clear versioning |
| Rate Limiting | Laravel throttle middleware + Redis | 60 req/min authenticated, 30 req/min guests |
| Dev Cache | File-based (Redis for production) | Simpler developer onboarding |
| GitHub Labels | Manual with documentation | Clear and sufficient for one-time setup |
| Russian Files | -RU.md suffix (not .ru.md) | Constitution specification compliance |
| Architectural Patterns | Adopt all patterns from React analysis | Proven patterns with security, scalability benefits |
| Laravel Integration | Vite + Vuetify Plugin + Composition API | Modern frontend tooling with type safety |

## Dependencies Added

**PHP/Composer**:
- `supabase-community/supabase-php`: ^1.0 (Supabase Auth integration)
- `laravel/sanctum`: ^4.0 (included in Laravel 11, verify version)

**NPM**:
- `vuetify`: ^3.4.0 (Material Design 3 components)
- `@mdi/font`: ^7.0.0 (Material Design Icons for Vuetify)
- `sass`: ^1.69.0 (Vuetify requires Sass)
- `vite-plugin-vuetify`: latest (Vite optimization for Vuetify)

**Development Tools** (Optional, Recommended):
- PHP-CS-Fixer or Laravel Pint: Code style enforcement
- PHPStan or Larastan: Static analysis
- Pest or PHPUnit: Testing framework (PHPUnit included in Laravel 11)

## Next Steps

Phase 1 tasks:
1. Generate data-model.md with entity definitions for repository structure
2. Generate contracts/ with API specifications for any setup endpoints
3. Generate quickstart.md with detailed setup instructions
4. Update agent context with technology decisions

---

### 9. Laravel Monorepo Best Practices and Patterns

**Question**: What are the current best practices for Laravel 11 monorepo structure with Composer workspaces?

**Research Findings** (from web search 2025-11-17):

**Key Best Practices Identified**:

1. **Domain-Driven Organization**:
   - Organize packages by domain, feature, or service (not just technical function)
   - Each package has own `composer.json` with dependencies and autoload config
   - Use Composer workspaces to coordinate installs and updates at repo level

2. **Repository Pattern for Maintainability**:
   - Controllers remain slim; business/domain logic lives in repositories/services
   - Each package exposes repositories via interfaces bound in service providers
   - Enhances testability by mocking repositories
   - Decouples data layer from business logic

3. **Separation of Concerns**:
   - Backend (Laravel) and frontend packages can be separated
   - Shared libraries (domain models, utilities) reused by Laravel apps within monorepo
   - Use PSR-4 autoloading for each package

4. **CI/CD Optimization**:
   - Use selective builds/tests for affected packages to avoid monorepo CI bottlenecks
   - Enforce coding standards (PHP-CS-Fixer, ECS, Rector) at repo or package level
   - Use dependency graphs to isolate changes

**Decision**: Adopt **Repository Pattern with Domain-Driven Package Organization**

**Rationale**:
- Aligns with Laravel best practices and clean architecture principles
- Repository pattern provides testable, decoupled architecture
- Domain-driven organization supports the feature-based package structure (clusters, metaverses, etc.)
- Enables isolated development and testing per package
- Facilitates future extraction of packages to separate repositories

**Implementation Notes**:
- Each feature package will use Repository pattern for data access
- Repositories bound via service providers in each package
- Interface-based contracts in universo-types-srv package
- Domain logic separated from controllers
- Document repository pattern usage in ARCHITECTURE.md

**References**:
- [Laranext Monorepo Example](https://github.com/isaacdarcilla/laranext-monorepo/)
- [Hosting PHP Packages in Monorepo - LogRocket](https://blog.logrocket.com/hosting-all-your-php-packages-together-in-a-monorepo/)
- [Repository Pattern in Laravel Best Practices - WP Web Infotech](https://wpwebinfotech.com/blog/repository-pattern-in-laravel-project/)
- [Laravel Best Practices: Repository Pattern - Shkodenko](https://www.shkodenko.com/laravel-best-practices-repository-pattern-for-clean-and-scalable-code/)

---

### 10. Laravel API Design Patterns and Versioning Strategy

**Question**: What are the current best practices for Laravel REST API design, versioning, and rate limiting in 2025?

**Research Findings** (from web search 2025-11-17):

**API Design Best Practices**:

1. **Standard HTTP Methods**: Map CRUD to HTTP verbs (GET, POST, PUT/PATCH, DELETE)
2. **Consistent Naming**: Plural nouns for resources (e.g., `/api/users`), nested routes for relationships
3. **API Resources**: Use Laravel's `JsonResource` for consistent JSON outputs
4. **Pagination**: Always paginate large results; Laravel's built-in pagination includes meta info
5. **Input Validation**: Centralize in controllers or API request classes
6. **Error Handling**: Meaningful HTTP status codes and structured error responses

**Versioning Best Practices**:

1. **Version From Day One**: Even for new APIs to avoid breaking changes
2. **URI Path Versioning** (Recommended): `/api/v1/resource` - most common and clear
   - Easy to route and organize directories by version
   - Example: `app/Http/Controllers/Api/V1`
3. **Header Versioning**: Pass version in custom header (e.g., `X-API-Version: 1.0`)
   - More complex but useful for microservices
4. **Deprecation Policy**: Publish timelines, use HTTP `Sunset` header to inform clients

**Rate Limiting Best Practices**:

1. **Laravel Middleware**: Use `throttle` middleware (e.g., `throttle:60,1` for 60 req/min)
2. **Layered Limits**: Different limits per route, user, or token
   - Higher limits for authenticated users
   - Stricter for guests
3. **Custom Rate Limiters**: Implement for user roles or premium tiers
4. **Storage Backend**: Redis recommended for production, in-memory acceptable for dev
5. **Monitor & Log**: Track rate limit hits and abuse attempts

**Decision**: Adopt **URI Path Versioning with Layered Rate Limiting**

**Rationale**:
- URI versioning is industry standard and most transparent
- Layered rate limiting provides security without blocking legitimate users
- Laravel's built-in throttle middleware handles most use cases
- Redis storage enables distributed rate limiting in production
- Aligns with constitution Principle IV requirements

**Implementation Notes**:
- All API routes under `/api/v1/` from start
- Rate limiting: 60 req/min for authenticated, 30 req/min for guests
- Use Laravel's `RateLimiter` facade in AppServiceProvider
- API Resources for all model transformations
- Document versioning strategy in ARCHITECTURE.md
- Use Redis for rate limiting storage in production

**References**:
- [8 Laravel RESTful APIs Best Practices - Benjamin Crozat](https://benjamincrozat.com/laravel-restful-api-best-practices)
- [Laravel 12 API Versioning Best Practices - Kritim Yantra](https://kritimyantra.com/blogs/laravel-12-api-versioning-best-practices-for-long-term-projects)
- [API Versioning in Laravel 11 - Treblle API Academy](https://apiacademy.treblle.com/laravel-api-course/api-versioning)
- [10 Must-Know REST API Best Practices - Web App Rater](https://webapprater.com/web-app-development/building-rest-apis-in-laravel.html)

---

### 11. Laravel + Supabase Authentication Integration

**Question**: What is the best approach for integrating Supabase authentication with Laravel using Sanctum or Passport?

**Research Findings** (from web search 2025-11-17):

**Sanctum vs Passport for Supabase Integration**:

**Laravel Sanctum**:
- **Best for**: SPAs & mobile apps talking to their own backend
- **Features**: Personal access tokens (PATs), cookie/session friendly, basic scopes/abilities
- **Complexity**: Lightweight, simple setup
- **Use Case**: First-party applications (like our Inertia.js SPA)
- **Supabase Integration**: Custom middleware to validate Supabase JWT tokens

**Laravel Passport**:
- **Best for**: Third-party integrations, OAuth2 flows
- **Features**: Full OAuth2 implementation, client/secret support, refresh tokens
- **Complexity**: More setup overhead and operational complexity
- **Use Case**: When external apps need delegated access
- **Supabase Integration**: Overkill for typical Supabase auth flows

**Integration Approach**:

1. **Frontend**: Supabase handles user authentication (sign-in, sign-up)
2. **Backend**: Laravel validates Supabase-signed JWT tokens
3. **Trust Boundary**: API endpoints verify incoming JWT tokens before processing
4. **Hybrid Option**: Use Sanctum for Laravel tokens + custom middleware for Supabase tokens

**Security Best Practices**:
- Validate JWT signature using Supabase's public keys
- Check token expiration and revocation
- Use HTTPS everywhere
- Implement granular route middleware permissions
- Apply rate limiting, input validation, RBAC

**Decision**: **Laravel Sanctum with Custom Supabase JWT Validation Middleware** (confirmed from Research Task 3)

**Rationale**:
- Sanctum is Laravel's recommended solution for SPAs (matches Inertia.js architecture)
- Custom middleware validates Supabase JWTs while maintaining Laravel patterns
- Simpler than Passport for our first-party application use case
- Allows hybrid auth: Sanctum tokens for API, Supabase for user management
- Better alignment with Laravel ecosystem and conventions

**Implementation Notes**:
- Install Laravel Sanctum (included in Laravel 11)
- Create custom middleware to validate Supabase JWT tokens
- Use `auth:sanctum` guard for API routes
- Sync Supabase users to Laravel database for local permissions/roles
- Document authentication flow in quickstart.md
- Use supabase-community/supabase-php for Auth integration

**References**:
- [Laravel Passport vs Sanctum Guide - Web App Rater](https://webapprater.com/web-app-development/laravel-passport-vs-sanctum.html)
- [Laravel API Authentication - Server Avatar](https://serveravatar.com/laravel-api-authentication/)
- [Laravel Sanctum vs Passport Strategy 2025 - Abbacus Tech](https://www.abbacustechnologies.com/laravel-sanctum-vs-passport-authentication-strategy-for-2025/)
- [Building Secure APIs with Laravel Sanctum - Stalk Techie](https://stalktechie.com/post/building-secure-restful-apis-with-laravel-sanctum)

---

### 12. Laravel Package Development and Modular Architecture

**Question**: What are the best practices for Laravel package development with modular architecture and Domain-Driven Design?

**Research Findings** (from web search 2025-11-17):

**Modular Architecture Benefits**:
- **Separation of Concerns**: Each module handles distinct domain/business feature
- **Team Independence**: Multiple teams work on separate modules without conflicts
- **Scalability**: Add, rewrite, or replace features in isolation
- **Isolated Testing**: Modules tested independently

**Typical Modular Structure**:
```
src/
└── Modules/
    ├── Orders/
    │   ├── Controllers/
    │   ├── Models/
    │   ├── Services/
    │   ├── Routes/
    │   └── Tests/
    ├── Inventory/
    └── Billing/
```

**Domain-Driven Design (DDD) Concepts for Laravel**:

1. **Domain Layer**: Pure business logic, independent of framework
   - Entities and Value Objects
   - Aggregates (groups of entities)
   - Domain Events
   
2. **Application Layer**: Orchestrates use cases
   - Use Cases
   - DTOs (Data Transfer Objects)

3. **Infrastructure Layer**: Framework-specific implementations
   - Eloquent Models
   - External Services

4. **Interfaces Layer**: Entry points
   - HTTP Controllers
   - Console Commands

**DDD Folder Structure Example**:
```
app/
├── Domain/
│   ├── Invoice/
│   │   ├── Entities/
│   │   ├── ValueObjects/
│   │   ├── Repositories/
│   │   ├── Services/
│   │   └── Events/
│   └── ...
├── Application/
│   ├── UseCases/
│   └── DTOs/
├── Infrastructure/
│   ├── EloquentModels/
│   └── ExternalServices/
└── Interfaces/
    ├── Controllers/
    └── CLI/
```

**Package Development Best Practices**:

1. **Service Providers**: Bootstrap package—register bindings, events, routes
2. **Configurability**: Sensible defaults with published config files
3. **Testing**: Modular, isolated, automated tests
4. **Documentation**: Clear architecture, installation, usage docs
5. **Decoupling**: Avoid tight coupling with Laravel-specific classes

**Decision**: **Adopt Modular Architecture with DDD Principles for Feature Packages**

**Rationale**:
- Aligns with constitution's package-based structure (clusters-frt, clusters-srv)
- Each feature domain can be developed, tested, and deployed independently
- Repository pattern (from Research Task 9) fits naturally with DDD
- Supports the three-tier entity pattern (Clusters/Domains/Resources)
- Enables future extraction to separate repositories
- Maintains Laravel best practices

**Implementation Approach**:

1. **Start Simple**: Begin with Laravel default structure
2. **Evolve to Modular**: Refactor as complexity grows
3. **Clear Boundaries**: Define bounded contexts for each domain/module
4. **Ubiquitous Language**: Consistent terminology across team
5. **Service Providers**: Each package has its own service provider

**Implementation Notes**:
- Infrastructure packages (universo-types-srv, universo-utils-srv) follow package best practices
- Feature packages (clusters, metaverses) use modular structure within base/ directory
- Each package has service provider for registration
- DTOs and contracts in universo-types-srv
- Shared services and helpers in universo-utils-srv
- Document package development guidelines in CONTRIBUTING.md

**References**:
- [Building Modular Systems in Laravel - Sevalla](https://sevalla.com/blog/building-modular-systems-laravel/)
- [Laravel DDD Principles - MageComp](https://magecomp.com/blog/laravel-ddd-domain-driven-design-principles/)
- [Domain Driven Design with Laravel 9 - HiBit](https://www.hibit.dev/posts/43/domain-driven-design-with-laravel-9)
- [Laravel Clean Architecture DDD CQRS - GitHub](https://github.com/shahghasiadil/laravel-clean-architecture-ddd-cqrs)
- [3 Crucial Laravel Architecture Best Practices - Benjamin Crozat](https://benjamincrozat.com/laravel-architecture-best-practices)

---

### 13. Laravel + Inertia.js + Vue 3 + Vuetify Integration Best Practices

**Question**: What are the best practices for integrating Laravel with Inertia.js, Vue 3, and Vuetify (Material Design)?

**Research Findings** (from web search and Context7 2025-11-17):

**Project Setup & Structure**:

1. **Starter Kits**: Reference repositories available:
   - [laravel-viltify](https://github.com/MtDalPizzol/laravel-viltify) - Laravel + Vite + Inertia + Vuetify
   - [laravel-inertiajs-vuetify-starterkit](https://github.com/akfaiz/laravel-inertiajs-vuetify-starterkit)
   - These solve common integration pain points (Vite config, Vuetify theme, CSS conflicts)

2. **Framework Integration Best Practices**:
   - Define layouts in Vue, not Blade, for true SPA navigation
   - Use named slots or composition API for dynamic layouts
   - Separate entry points for different user types if needed
   - Register Vuetify in main app file with all components/directives

**Vuetify 3 + Material Design 3 Setup**:

```javascript
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({ components, directives })

createInertiaApp({
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(vuetify)
      .mount(el)
  }
})
```

**Material Design 3 Features** (Vuetify 3+):
- Dynamic theming and color tokens
- Dark mode switching via theme tools
- MD3's typographic scale and component simplification
- Improved accessibility (ARIA support, better contrast)
- Built-in accessibility tools for WCAG compliance

**Developer Experience Best Practices**:

1. **TypeScript**: Use Vue 3's type-checking and composition API
2. **Linting/Formatting**: ESLint + Prettier for consistent code quality
3. **Component Structure**: Write reusable, composable components
4. **Vite Plugin**: Use `vite-plugin-vuetify` for proper optimization

**Performance & Build**:
- Code splitting through Vite and Vue's async component import
- Load only necessary resources per page/view
- Environment variables: Separate client-side from server-side

**Laravel Conventions** (from Context7):
- Use Laravel's starter kit layouts (AuthLayout, AppLayout)
- shadcn-vue components can be published for additional UI elements
- ZiggyVue plugin for Laravel route helpers in Vue
- Project structure in `resources/js/`:
  - `components/` - Reusable Vue components
  - `composables/` - Vue composables/hooks
  - `layouts/` - Application layouts
  - `pages/` - Page components
  - `lib/` - Utility functions

**Decision**: **Adopt Laravel + Inertia.js + Vue 3 + Vuetify 3 Stack** (confirmed from Research Task 2)

**Rationale**:
- Vuetify 3 provides comprehensive Material Design components
- Inertia.js is Laravel's recommended SPA approach (simpler than separate API)
- Vue.js 3 already configured in package.json
- Maintains consistency with Material Design principles
- Active community and documentation
- Easier migration path for Laravel developers than React

**Implementation Notes**:
- Install Vuetify 3: `npm install vuetify@^3.0.0`
- Install MDI icons: `npm install @mdi/font@^7.0.0`
- Install Sass: `npm install sass@^1.69.0` (Vuetify requirement)
- Configure Vite with `vite-plugin-vuetify`
- Set up Inertia.js layout components with Vuetify theme
- Implement dynamic theming (light/dark mode)
- Use composition API for component logic
- Document component patterns in ARCHITECTURE.md

**References**:
- [Ultimate Setup: Laravel 11 + Inertia.js + Vite + Vue 3 + Vuetify 3 - Dev Nightly](https://devnightly.com/ultimate-setup-laravel-11-with-inertia-js-vite-vue-3-and-vuetify-3/)
- [Material Design 3: Next Generation Interfaces - Vuetify Blog](https://store.vuetifyjs.com/blogs/vuetify-blog/material-design-3-how-to-adapt-to-the-next-generation-of-interfaces)
- [Enhancing Laravel and Inertia.js with TypeScript - Laravel.io](https://laravel.io/articles/enhancing-laravel-and-inertiajs-with-typescript-and-vue-3s-composition-api-to-build-a-powerful-spa)
- [Laravel 12 and Vue 3 Ultimate Starter Guide - DEV Community](https://dev.to/robin-ivi/laravel-12-and-vue-3-ultimate-starter-guide-3bmd)

---

## References

- Laravel Documentation: https://laravel.com/docs/11.x
- Inertia.js Documentation: https://inertiajs.com/
- Vuetify Documentation: https://vuetifyjs.com/
- Supabase PHP Client: https://github.com/supabase-community/supabase-php
- Laravel Sanctum: https://laravel.com/docs/11.x/sanctum
- Composer Documentation: https://getcomposer.org/doc/
- Constitution v1.3.0: .specify/memory/constitution.md
- Context7 Laravel 11.x Documentation: /websites/laravel-11.x
- Context7 Inertia.js Documentation: /llmstxt/inertiajs-llms-full.txt
