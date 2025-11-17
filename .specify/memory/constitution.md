<!--
SYNC IMPACT REPORT
==================
Version Change: 1.2.0 → 1.3.0
Updated: 2025-11-17

MINOR VERSION UPDATE - Added shared infrastructure, API standards, security patterns, and build standards

Changes in 1.3.0:
- ADDED: Principle I - Shared infrastructure packages requirement (universo-types-srv, universo-utils-srv)
- ADDED: Principle IV - API design standards (versioning, rate limiting, standardized responses)
- ADDED: Principle IV - Authorization & security patterns (guards, IDOR prevention, SQL injection)
- ENHANCED: Principle V - Database design patterns (CASCADE, JSONB, junction tables, idempotency)
- ENHANCED: Principle V - Security patterns (authorization guards, rate limiting, query scopes)
- ADDED: Principle VIII - Build and Deployment Standards (NEW principle)
- IMPROVED: Comprehensive documentation of architectural patterns from React repository analysis

Changes in 1.2.0:
- ENHANCED: Principle VII - Added explicit monitoring process and frequency
- ENHANCED: Principle VII - Added documentation requirements for discovered features
- ENHANCED: Principle V - Expanded three-tier pattern with detailed example (Clusters/Domains/Resources)
- ENHANCED: Principle V - Added variation details (2-tier, 4-5 tier hierarchies)
- ENHANCED: Principle V - Added consistent elements across pattern adaptations
- IMPROVED: Clarified relationship between reference repository monitoring and implementation

Changes in 1.1.0:
- FIXED: Technology Stack - Changed from PNPM to Composer as primary package manager
- FIXED: Clarified Laravel + Inertia.js + React architecture
- ADDED: Principle VII - Reference Implementation Alignment with universo-platformo-react
- EXPANDED: Principle V - Added three-tier entity pattern guidance
- ADDED: Repository Initialization section to Development Workflow
- ADDED: Exclusions section documenting what NOT to create
- IMPROVED: Testing strategy aligned with actual tech stack
- IMPROVED: Clearer separation of PHP and JavaScript tooling

Principles Updated:
- I. Monorepo Package Architecture (FIXED 1.1.0, ENHANCED 1.3.0 - shared packages)
- IV. Laravel Full-Stack with React Frontend (ENHANCED 1.3.0 - API standards, security)
- V. Clean Architecture & Incremental Development (EXPANDED 1.1.0, ENHANCED 1.2.0, ENHANCED 1.3.0 - database & security patterns)
- VII. Reference Implementation Alignment (NEW 1.1.0, ENHANCED 1.2.0)
- VIII. Build and Deployment Standards (NEW 1.3.0)

Templates Status:
✅ plan-template.md - Reviewed, constitution check section aligned
✅ spec-template.md - Reviewed, requirements align with principles
✅ tasks-template.md - Reviewed, task categorization supports principles

Deep Check Report: .specify/memory/constitution-deep-check-report.md
-->

# Universo Platformo Laravel Constitution

## Core Principles

### I. Monorepo Package Architecture

**MUST** organize codebase as a monorepo with Composer managing PHP packages and workspaces. Packages MUST reside in `packages/` directory. When functionality requires both frontend and backend, they MUST be separated into distinct packages (e.g., `packages/clusters-frt` and `packages/clusters-srv`). Each package MUST contain a root `base/` directory to accommodate future multiple implementations. Frontend packages with React components MAY use NPM or PNPM for JavaScript dependencies separately from PHP dependencies.

**Shared Infrastructure Packages**: Repository MUST include shared infrastructure packages without -frt/-srv suffix for code used across multiple features. Required shared packages include:
- **universo-types-srv**: PHP interfaces, contracts, enums, and DTOs for type consistency
- **universo-utils-srv**: Helper functions, validators, data transformers for code reuse

MAY include additional shared packages as needed (e.g., universo-api-client for frontend HTTP client wrapper with authentication).

**Rationale**: This structure enables independent development and deployment of features while maintaining the flexibility to support multiple technology stack implementations within the same conceptual package. Composer is the standard for PHP/Laravel projects, while NPM/PNPM handles JavaScript asset compilation when needed. Shared infrastructure packages prevent code duplication and ensure consistency across the platform.

### II. Bilingual Documentation (English/Russian)

**MUST** create all documentation in both English and Russian. English is the primary standard and MUST be created first. Russian documentation MUST be an exact translation with identical structure, content, and line count. This applies to README files (e.g., `README.md` and `README-RU.md`) and all GitHub Issues.

**Rationale**: Supports bilingual development team and user base while maintaining documentation consistency through a clear primary-secondary relationship.

### III. Database-First with Supabase

**MUST** use Supabase as the primary database solution. Authentication MUST use Passport.js with Supabase connector. Database schema design MUST be extensible to support other DBMS in the future, avoiding Supabase-specific patterns that prevent migration.

**Rationale**: Supabase provides rapid development capabilities while maintaining PostgreSQL compatibility. Extensibility design prevents vendor lock-in.

### IV. Laravel Full-Stack with React Frontend

**MUST** follow Laravel framework conventions and PHP best practices for backend implementation. This includes: Eloquent ORM for database operations, Laravel's service container for dependency injection, form request validation, resource controllers, and API resources. Frontend **MUST** use Laravel with Inertia.js to integrate React components. User interface **MUST** use Material UI (MUI library) for consistent design language. This creates a modern single-page application experience while maintaining Laravel's server-side routing and controllers.

**API Design Standards**: All API endpoints MUST follow RESTful conventions with versioned URLs (e.g., /api/v1/resources). Responses MUST use standardized JSON format with success/error structure and appropriate HTTP status codes. Rate limiting MUST be implemented on all public endpoints using Laravel's throttle middleware (Redis storage recommended for production, in-memory acceptable for development). API resources MUST transform Eloquent models before returning to clients, controlling field exposure and adding computed properties.

**Authorization & Security**: Multi-tenant features MUST implement application-level authorization guards to enforce data isolation between tenants. Guards MUST prevent IDOR (Insecure Direct Object Reference) attacks by validating ownership/permissions before allowing access to resources. This can be implemented using Laravel middleware, policy classes, or Eloquent global scopes. All database queries MUST use Eloquent ORM's parameterized queries to prevent SQL injection attacks.

**Rationale**: Leverage Laravel's mature ecosystem for backend while using React + MUI for rich, interactive user interfaces. Inertia.js bridges these technologies without the complexity of a separate API layer. Explicit API standards prevent inconsistencies. Application-level authorization provides defense-in-depth security beyond database-level Row Level Security.

### V. Clean Architecture & Incremental Development

**MUST NOT** replicate legacy code or incomplete implementations from reference projects, particularly Flowise components still present in universo-platformo-react. Each feature MUST be implemented cleanly using established patterns. 

Development MUST follow this incremental approach:

1. **Base Entity Pattern**: Implement the three-tier entity structure starting with Clusters feature
   - **Example: Clusters / Domains / Resources**
     - **Clusters** (Top-level): Primary container/aggregate organizing related domains
     - **Domains** (Middle-level): Grouping within a cluster, categorizing resources
     - **Resources** (Bottom-level): Individual items belonging to a domain
   - Each tier has standard CRUD operations, relationships, and data model
   - Implementation includes both frontend (clusters-frt) and backend (clusters-srv) packages
   - Each entity has: unique identifier (UUID), name, description, timestamps, relationships
   - **Database Design Patterns**:
     - Foreign keys with CASCADE delete for parent-child relationships (deleting cluster removes all domains and resources)
     - JSON/JSONB columns for flexible metadata schemas (e.g., resource metadata field)
     - Junction tables with UNIQUE constraints for many-to-many relationships (prevents duplicate associations)
     - Idempotent operations for relationship creation (safe retry behavior without duplicates)
   - **Security Patterns**:
     - Application-level authorization guards for data isolation (cluster-level tenant isolation)
     - Rate limiting on API endpoints using Laravel throttle middleware (prevents DoS attacks)
     - Eloquent query scopes for automatic tenant filtering
     - IDOR attack prevention through ownership validation in policies

2. **Pattern Adaptation**: Copy and adapt the base pattern for similar features
   - **Metaverses Feature**: Metaverses / Sections / Entities (identical structure, different names)
   - **Variations by tier count**:
     - **Two tiers**: Some features need only parent-child relationship
     - **Four-five tiers**: Complex features like Uniks may need deeper hierarchies
   - **Consistent elements across adaptations**:
     - Standard CRUD operations (Create, Read, Update, Delete)
     - Parent-child relationships with foreign keys and CASCADE behavior
     - Authorization and access control patterns (guards, policies, scopes)
     - Bilingual UI labels and documentation (English + Russian)
     - API versioning and standardized response format

3. **Specialized Extensions**: Add feature-specific functionality on top of the base pattern
   - Node graph systems (Spaces / Canvases)
   - LangChain integration for AI workflows
   - UPDL-nodes for custom logic
   - Real-time collaboration features

**Rationale**: Building on proven patterns accelerates development while maintaining code quality. Clean implementation prevents technical debt accumulation. The three-tier pattern provides a flexible foundation that scales across diverse feature requirements.

### VI. GitHub Process Compliance

**MUST** follow established GitHub workflows: create Issues per `.github/instructions/github-issues.md`, apply labels per `.github/instructions/github-labels.md`, create Pull Requests per `.github/instructions/github-pr.md`. Issues MUST be created before implementing specifications. Documentation updates MUST follow i18n guidelines in `.github/instructions/i18n-docs.md`.

**Rationale**: Consistent process ensures traceability, proper project tracking, and team coordination.

### VII. Reference Implementation Alignment

**MUST** use universo-platformo-react (https://github.com/teknokomo/universo-platformo-react) as the conceptual reference for feature design and architecture. This repository demonstrates the general concept of Universo Platformo across different technology stacks. However, **MUST NOT** replicate legacy code, particularly Flowise components that remain partially integrated in the React implementation. 

Development **MUST**:
- Conduct careful, step-by-step, meticulous analysis of the React repository before implementing any feature
- Monitor the React repository continuously as work progresses and implement new features that appear using the Laravel stack
- Maintain feature parity by regularly reviewing the React implementation for updates and additions
- Design each package with the expectation that multiple implementations may exist across different technology stacks
- Avoid architectural shortcuts or incomplete refactorings present in the reference implementation

**Monitoring Process**: As work progresses, team **MUST** establish a process to:
- Regularly check the React repository for new commits and features (weekly or bi-weekly)
- Document discovered features in Issues before implementation
- Prioritize feature implementations based on project roadmap
- Ensure Laravel implementation follows the conceptual pattern while using Laravel best practices

**IMPORTANT**: The React version is still under active development and contains legacy code scheduled for removal. Extract the conceptual patterns and feature requirements, not the specific implementation details or technical debt.

**Rationale**: Maintains consistency and feature parity across Universo Platformo implementations while preventing propagation of technical debt. Enables learning from the reference implementation's successes while avoiding its incomplete refactoring efforts.

### VIII. Build and Deployment Standards

**MUST** maintain clear separation between source code and build artifacts. Backend PHP code uses Composer autoloading without compilation step. Frontend assets MUST be compiled via Vite (Laravel default) into public/ directory. Build artifacts (vendor/, node_modules/, dist/, public/build/) MUST be excluded from version control via .gitignore to prevent repository bloat and ensure consistent builds across environments.

**MUST** follow Laravel asset conventions: raw source files in resources/js/ and resources/css/, compiled output in public/build/. Package-specific build outputs MUST be organized in package dist/ directories and excluded from repository. Frontend packages using React components MUST use Vite for bundling with proper code splitting and lazy loading.

**MUST** document build requirements clearly: PHP version, Composer version, Node.js version, NPM/PNPM version. Development environment setup MUST be reproducible through documented steps or Docker configuration. Production builds MUST be optimized (minified JavaScript/CSS, optimized images, cached routes/views).

**Rationale**: Prevents repository bloat from committing generated files. Ensures consistent builds across development, staging, and production environments. Follows Laravel framework expectations for asset handling. Enables efficient CI/CD pipelines with cacheable build artifacts.

## Technology Stack Requirements

**Backend Framework**: Laravel (latest stable) with PHP 8.2+  
**Backend Package Manager**: Composer for PHP dependencies and workspace management  
**Frontend Framework**: React with Laravel Inertia.js for SPA integration  
**Frontend Library**: Material UI (MUI) for component design  
**Frontend Build Tools**: Vite (Laravel default) for asset compilation  
**Frontend Package Manager**: NPM or PNPM for JavaScript dependencies (separate from PHP)  
**Database**: Supabase (PostgreSQL-based) with extensibility for other DBMS  
**Authentication**: Passport.js with Supabase connector (or Laravel Sanctum + Supabase)  
**Testing Backend**: PHPUnit or Pest for PHP/Laravel testing  
**Testing Frontend**: Jest and React Testing Library for React component testing  
**Version Control**: Git with feature branch workflow

## Development Workflow

**Repository Initialization**: Before feature development begins, the repository MUST be properly initialized:
- Create comprehensive bilingual README files (README.md in English, README-RU.md in Russian)
- Establish GitHub labels according to `.github/instructions/github-labels.md`
- Set up base project structure following Laravel conventions
- Configure Composer workspaces for monorepo package management
- Initialize frontend build tooling (Vite, Inertia.js)

**Issue-First Development**: Before implementing any specification, an Issue MUST be created in the repository with proper English/Russian bilingual content and appropriate labels.

**Documentation-First**: Create English documentation first, validate completeness, then create Russian translation with identical structure and line count.

**Specification Process**: Follow `.specify/` templates for plans, specifications, and tasks. Each feature MUST have complete documentation before implementation begins.

**Code Review**: All changes MUST go through Pull Request review. PRs MUST reference related Issues and include both implementation and documentation updates.

## Exclusions

The following **MUST NOT** be created in this repository:

**Documentation Directory**: Do NOT create a `docs/` directory. Project documentation will be maintained in a separate dedicated repository (docs.universo.pro domain) to separate documentation lifecycle from code development.

**AI Agent Configuration Files**: Do NOT create additional AI agent configuration files beyond the existing `.github/agents/` directory. Users will create custom agent configurations as needed for their specific workflows. The existing agent files were established as part of the initial repository setup and should not be modified or extended without explicit user request.

**Rationale**: Separating documentation enables independent versioning and publishing workflows. Limiting AI agent file creation preserves user control over development automation tooling and prevents configuration sprawl.

## Governance

This constitution supersedes all other development practices and patterns. All Pull Requests and code reviews MUST verify compliance with these principles.

**Amendment Process**: Constitution amendments require documented justification, approval, and migration plan for affected code. Version MUST increment according to semantic versioning:
- MAJOR: Backward-incompatible governance changes or principle removals/redefinitions
- MINOR: New principles added or materially expanded guidance
- PATCH: Clarifications, wording fixes, non-semantic refinements

**Complexity Justification**: Any complexity that violates these principles MUST be explicitly justified in design documents. Simpler alternatives MUST be documented and their rejection explained.

**Compliance Review**: Constitution compliance is verified during specification review, implementation review, and before merging to main branch.

**Version**: 1.3.0 | **Ratified**: 2025-11-16 | **Last Amended**: 2025-11-17
