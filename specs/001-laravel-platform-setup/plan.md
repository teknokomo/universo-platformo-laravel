# Implementation Plan: Initial Repository Foundation and Development Environment

**Branch**: `001-laravel-platform-setup` | **Date**: 2025-11-17 | **Spec**: [spec.md](./spec.md)
**Input**: Feature specification from `/specs/001-laravel-platform-setup/spec.md`

**Note**: This template is filled in by the `/speckit.plan` command. See `.specify/templates/commands/plan.md` for the execution workflow.

## Summary

Establish foundation repository structure for Universo Platformo Laravel enabling modular development with bilingual international collaboration and scalable platform growth. This includes setting up monorepo architecture with Composer workspace management and Repository Pattern, configuring Supabase database integration with Laravel Sanctum authentication, establishing GitHub process compliance with bilingual documentation standards, and creating base infrastructure packages following the three-tier entity pattern established in the constitution. The technical approach uses Laravel 11.x with PHP 8.2+, Laravel Inertia.js for SPA integration, Vue.js 3, and Vuetify 3.x (Material Design 3) for consistent design language.

## Technical Context

**Language/Version**: PHP 8.2+, Laravel 11.x  
**Primary Dependencies**: 
- Backend: Laravel Framework 11.x, Laravel Sanctum 4.x, Guzzle HTTP client 7.8+, supabase-community/supabase-php 1.x
- Frontend: Vue.js 3.4+ (via Laravel Inertia.js), Vuetify 3.4+ (Material Design), Vite 5.0+, Axios 1.7+
- Database: Supabase PostgreSQL via Laravel's native PostgreSQL driver
- UI: Vuetify 3.x (Material Design component library for Vue.js)

**Storage**: 
- Primary: Supabase/PostgreSQL for relational data via Eloquent ORM
- Cache: File-based cache for development, Redis for production
- Sessions: Database-backed sessions with Redis option for production

**Testing**: 
- Backend: PHPUnit 11.x (Laravel default), Laravel HTTP tests for API testing
- Frontend: Vite for build testing, browser-based integration tests
- API: Laravel HTTP tests, OpenAPI documentation validation

**Target Platform**: Web application (SPA) with RESTful API server  
**Project Type**: Monorepo with Composer path repositories managing multiple packages  
**Performance Goals**: 
- API response time: <200ms p95 for CRUD operations
- Frontend load time: <3s initial page load
- Database queries: <50ms p95 for indexed lookups

**Constraints**: 
- Supabase free tier: 500MB database, 2GB bandwidth/month, 50MB file storage
- Development environment: Must work on standard PHP development setup
- Build time: <2 minutes for full frontend build

**Scale/Scope**: 
- Initial setup: 2 infrastructure packages (universo-types-srv, universo-utils-srv)
- Documentation: ~15-20 documentation files (bilingual)
- Configuration: ~10 configuration files (.env, composer.json, vite.config, etc.)
- GitHub setup: ~20 labels, issue/PR templates

**Research Status**: ✅ All technical clarifications resolved in research.md (Updated 2025-11-17 with Laravel best practices from web search and Context7)

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

Verify compliance with `.specify/memory/constitution.md` core principles:

- [x] **Monorepo Package Architecture**: Repository will be organized as monorepo with packages/ directory. Initial setup includes infrastructure packages (universo-types-srv, universo-utils-srv) without frontend/backend suffix. Future feature packages will follow -frt/-srv separation pattern. Each package will have base/ directory for multiple implementation support.
- [x] **Bilingual Documentation**: All documentation will be created in English first, followed by Russian translation with identical structure and line count. Applies to README files, GitHub Issues, and package documentation.
- [x] **Database-First with Supabase**: Supabase will be configured as primary database. Laravel Sanctum will handle authentication with custom Supabase JWT validation middleware. Database abstraction through Laravel's Eloquent ORM ensures extensibility to other DBMS.
- [x] **Laravel Best Practices**: Following Laravel 11.x conventions: Eloquent ORM, service container, form request validation, resource controllers, API resources. Frontend using Inertia.js for SPA integration with Vue.js 3 and Vuetify 3.x (Material Design 3). Repository Pattern for maintainable, testable architecture with domain-driven package organization.
- [x] **Clean Architecture**: No legacy code replication. Establishing clean patterns from start. Will document three-tier entity pattern (Clusters/Domains/Resources) as reference for future features.
- [x] **GitHub Process**: Issue already exists for this work. Proper labels will be verified/created. PR will follow documented guidelines. All documentation follows i18n guidelines.

**Constitution Compliance Status**: ✅ PASSED - All principles will be met by this implementation plan.

No violations requiring justification.

## Project Structure

### Documentation (this feature)

```text
specs/[###-feature]/
├── plan.md              # This file (/speckit.plan command output)
├── research.md          # Phase 0 output (/speckit.plan command)
├── data-model.md        # Phase 1 output (/speckit.plan command)
├── quickstart.md        # Phase 1 output (/speckit.plan command)
├── contracts/           # Phase 1 output (/speckit.plan command)
└── tasks.md             # Phase 2 output (/speckit.tasks command - NOT created by /speckit.plan)
```

### Source Code (repository root)

```text
# Root-level Laravel application
/
├── app/                      # Laravel application code
├── bootstrap/                # Laravel bootstrap
├── config/                   # Laravel configuration
├── database/                 # Migrations, seeders, factories
├── public/                   # Web root (compiled assets)
│   └── build/               # Vite compiled frontend assets
├── resources/               # Raw frontend resources
│   ├── js/                  # Vue.js components and app code
│   ├── css/                 # Stylesheets
│   └── views/               # Blade templates (Inertia root)
├── routes/                  # Laravel routes
│   ├── web.php             # Web routes
│   ├── api.php             # API routes (versioned)
│   └── console.php         # Artisan commands
├── storage/                 # Laravel storage
├── tests/                   # PHPUnit tests
│   ├── Feature/
│   └── Unit/
│
# Monorepo packages structure
packages/
├── universo-types-srv/      # Shared PHP types (NO -frt/-srv suffix)
│   ├── base/
│   │   ├── src/
│   │   │   ├── Contracts/   # PHP interfaces
│   │   │   ├── Enums/       # PHP enumerations
│   │   │   └── DTOs/        # Data Transfer Objects
│   │   └── tests/
│   ├── composer.json
│   ├── README.md
│   └── README-RU.md
│
├── universo-utils-srv/      # Shared utilities (NO -frt/-srv suffix)
│   ├── base/
│   │   ├── src/
│   │   │   ├── Helpers/     # Helper functions
│   │   │   ├── Validators/  # Custom validators
│   │   │   └── Transformers/ # Data transformers
│   │   └── tests/
│   ├── composer.json
│   ├── README.md
│   └── README-RU.md
│
└── (future feature packages like clusters-frt, clusters-srv)

# Root configuration files
├── .env.example             # Environment template with Supabase config
├── composer.json            # Root Composer with workspace config
├── package.json             # NPM/Vite configuration
├── vite.config.js          # Vite bundler configuration
├── phpunit.xml             # PHPUnit configuration
├── .gitignore              # Excludes vendor/, node_modules/, build/
│
# Documentation
├── README.md               # English documentation
├── README-RU.md           # Russian documentation (ru suffix, not .ru.md)
├── ARCHITECTURE.md        # Architecture patterns
├── CONTRIBUTING.md        # Contribution guidelines (bilingual)
│
# GitHub configuration
├── .github/
│   ├── instructions/       # Process guidelines
│   │   ├── github-issues.md
│   │   ├── github-labels.md
│   │   ├── github-pr.md
│   │   └── i18n-docs.md
│   └── (labels configured in repository settings)
│
# Specification system
└── specs/
    └── 001-laravel-platform-setup/
        ├── spec.md
        ├── plan.md         # This file
        ├── research.md     # Phase 0 output
        ├── data-model.md   # Phase 1 output
        ├── quickstart.md   # Phase 1 output
        └── contracts/      # Phase 1 output
```

**Structure Decision**: This is a repository initialization feature that sets up the foundation for all future work. It is primarily backend/infrastructure focused with some frontend tooling setup. The main Laravel application resides at the root level (standard Laravel structure), while the monorepo packages/ directory will host modular feature packages. Initial packages (universo-types-srv, universo-utils-srv) are shared infrastructure without -frt/-srv suffixes per constitution. Future feature packages like clusters will follow the -frt/-srv separation pattern.

## Architectural Patterns from React Repository Analysis

Based on comprehensive analysis of universo-platformo-react (documented in REACT_PATTERN_ANALYSIS.md), the following architectural patterns and best practices have been incorporated into this implementation plan:

### Database Design Patterns
- **CASCADE Deletes**: Foreign keys will use ON DELETE CASCADE for parent-child relationships to maintain referential integrity
- **JSONB Metadata**: Flexible schema support using JSON/JSONB columns for extensible entity metadata
- **Junction Tables**: Many-to-many relationships will use junction tables with UNIQUE constraints to prevent duplicate associations
- **UUID Primary Keys**: All entities will use UUIDs for distributed system compatibility
- **Idempotent Operations**: Relationship creation operations designed to handle retries safely without creating duplicates

### Security & Authorization Patterns
- **Application-Level Authorization Guards**: Multi-tenant features will implement guards to enforce data isolation and prevent IDOR attacks
- **Rate Limiting**: All public API endpoints will implement rate limiting (Laravel throttle middleware) to prevent DoS attacks
- **Query Scopes**: Eloquent global scopes for automatic tenant filtering
- **Parameterized Queries**: Exclusive use of Eloquent ORM to prevent SQL injection attacks
- **Ownership Validation**: Authorization guards validate ownership/permissions before allowing resource access

### API Design Standards
- **Versioned URLs**: All API routes use versioning pattern (e.g., /api/v1/) for backward compatibility
- **Standardized Responses**: Consistent JSON format with success/error structure and appropriate HTTP status codes
- **API Resources**: Laravel API Resources transform models before client exposure, controlling field visibility
- **Error Handling**: Standardized error response format across all endpoints

### Build & Deployment Standards
- **Build Artifact Separation**: Clear separation between source code and build artifacts
- **PHP Autoloading**: Composer autoloading for PHP code (no compilation step)
- **Frontend Asset Compilation**: Vite compilation of Vue.js/CSS into public/build/
- **Version Control Exclusions**: Build artifacts (vendor/, node_modules/, dist/, public/build/) excluded via .gitignore

### Shared Infrastructure Pattern
- **universo-types-srv**: PHP interfaces, contracts, enums, and DTOs for type consistency across packages
- **universo-utils-srv**: Shared helper functions, validators, and data transformers for code reuse
- **Laravel Localization**: Centralized translation management (lang/en/, lang/ru/)

These patterns are documented in constitution v1.3.0 (Principles I, IV, V, VIII) and spec.md (FR-035 through FR-050).

## Complexity Tracking

> **Constitution Check Status**: ✅ PASSED - No violations to justify

All constitution principles are satisfied by this implementation plan. No additional complexity or deviations required.
