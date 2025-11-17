# Feature Specification: Initial Repository Foundation and Development Environment

**Feature Branch**: `001-laravel-platform-setup`  
**Created**: 2025-11-16  
**Status**: Draft  
**Input**: User description: "Establish foundation repository structure enabling modular development, bilingual international collaboration, and scalable platform growth following proven architectural patterns"

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Repository Structure Verification (Priority: P1)

A developer clones the repository and immediately understands the project structure, its purpose, and how to contribute. They can navigate the monorepo, identify packages, and understand the Laravel-based architecture.

**Why this priority**: Foundation for all development work - without proper structure and documentation, no other feature can be built effectively.

**Independent Test**: Can be fully tested by cloning the repository, reading the README files (English and Russian), and verifying all directory structures exist as documented. Success means a developer can understand the project in under 10 minutes.

**Acceptance Scenarios**:

1. **Given** an empty repository, **When** the setup is complete, **Then** the repository contains a clear monorepo structure with packages/ directory, README files in both languages, and proper .gitignore configuration
2. **Given** a developer reads the main README, **When** they follow the structure documentation, **Then** they can locate the packages directory and understand the base/ folder convention for each package
3. **Given** bilingual documentation exists, **When** comparing English and Russian versions, **Then** both versions have identical structure and line count with accurate translations
4. **Given** architecture documentation exists, **When** a developer reads about entity patterns, **Then** they can understand the three-tier pattern (Clusters/Domains/Resources) and its variations in under 5 minutes
5. **Given** documentation describes the monitoring process, **When** team members review it, **Then** they understand the schedule and process for tracking React repository updates

---

### User Story 2 - Package Management Setup (Priority: P2)

A developer needs to add a new package or dependency to the project. They can use Composer for PHP dependencies and understand how packages are organized in the monorepo structure following the frontend/backend separation pattern (e.g., clusters-frt, clusters-srv).

**Why this priority**: Enables modular development and allows developers to create new features as separate packages, following the Universo Platformo React pattern.

**Independent Test**: Can be tested by creating a test package, adding dependencies via Composer, and verifying the package structure follows the documented conventions. Success means creating a new package takes under 5 minutes with clear templates.

**Acceptance Scenarios**:

1. **Given** the monorepo is initialized, **When** a developer creates a new package in packages/ directory, **Then** the package follows the naming convention (feature-frt or feature-srv) and contains a base/ subdirectory
2. **Given** a package requires PHP dependencies, **When** the developer runs Composer commands, **Then** dependencies are installed correctly and isolated to that package
3. **Given** packages need to reference each other, **When** using Composer's path repository feature, **Then** inter-package dependencies work correctly

---

### User Story 3 - GitHub Labels and Issue Configuration (Priority: P2)

A project maintainer or contributor needs to create Issues following project conventions. They can access predefined labels, understand labeling guidelines, and create Issues with bilingual content (English with Russian in spoiler tags).

**Why this priority**: Establishes workflow standards early, ensuring consistent project management and enabling international team collaboration.

**Independent Test**: Can be tested by creating a sample Issue following the guidelines, verifying all required labels exist in the repository, and confirming the bilingual format matches the template. Success means creating a properly formatted Issue takes under 3 minutes.

**Acceptance Scenarios**:

1. **Given** the repository is configured, **When** viewing available labels, **Then** all required labels exist including type labels (feature, bug, enhancement), area labels (frontend, backend, platformo), and project-specific labels
2. **Given** a contributor creates an Issue, **When** following the github-issues.md guidelines, **Then** the Issue contains English text in the main body and Russian translation in a properly formatted spoiler tag with `<summary>In Russian</summary>`
3. **Given** multiple team members from different language backgrounds, **When** they read Issues, **Then** they can access content in their preferred language (English or Russian)

---

### User Story 4 - Database Integration Preparation (Priority: P3)

A developer needs to integrate database functionality using Supabase as the primary database. They can find configuration examples, understand the abstraction layer that allows future support for other databases, and implement basic CRUD operations.

**Why this priority**: Enables data persistence for features but can be implemented incrementally as features require database access.

**Independent Test**: Can be tested by configuring Supabase credentials, establishing a connection, and performing basic database operations. Success means connecting to Supabase and executing a test query in under 10 minutes.

**Acceptance Scenarios**:

1. **Given** Supabase credentials are configured, **When** the application initializes, **Then** a database connection is established successfully
2. **Given** database operations are implemented, **When** using Laravel's database abstraction, **Then** the code remains database-agnostic enough to support future migration to other databases
3. **Given** configuration files exist, **When** a developer reviews them, **Then** clear comments indicate where and how to configure Supabase connection details

---

### User Story 5 - Authentication Framework Setup (Priority: P3)

A developer needs to implement user authentication. They can use Laravel Passport with Supabase integration, follow documented patterns, and implement secure authentication flows.

**Why this priority**: Required for user-facing features but can be implemented when first feature requiring authentication is developed.

**Independent Test**: Can be tested by configuring Laravel Passport, implementing a basic authentication flow, and verifying token generation. Success means having a working authentication scaffold that can be extended by feature-specific implementations.

**Acceptance Scenarios**:

1. **Given** Laravel Passport is installed, **When** configured with Supabase connection, **Then** authentication tokens can be generated and validated
2. **Given** authentication endpoints exist, **When** a client makes authentication requests, **Then** secure tokens are issued following OAuth2 standards
3. **Given** documentation exists, **When** a developer reads authentication setup guides, **Then** they understand how to extend authentication for specific features

---

### User Story 6 - UI Component Library Integration (Priority: P3)

A frontend developer needs to build user interfaces. They can access a configured Material Design component library for Laravel (such as Filament, Laravel Livewire with Alpine.js, or Blade components styled with Tailwind CSS), follow documented patterns, and create consistent UI components.

**Why this priority**: Required for building user interfaces but can be set up when first UI feature is developed. The specific library choice may depend on feature requirements.

**Independent Test**: Can be tested by creating a sample UI component using the chosen library, verifying styling consistency, and confirming components render correctly. Success means creating a basic styled component in under 15 minutes.

**Acceptance Scenarios**:

1. **Given** a UI component library is configured, **When** a developer creates a new component, **Then** the component follows Material Design principles and matches the project's design system
2. **Given** multiple packages need UI components, **When** using the shared component library, **Then** all packages have access to consistent styling and component patterns
3. **Given** the React version uses Material UI, **When** implementing Laravel UI, **Then** visual consistency is maintained across different technology implementations

---

### Edge Cases

- What happens when a developer tries to create a package without following the naming convention (missing -frt or -srv suffix)?
- How does the system handle missing or incomplete bilingual documentation (English without Russian translation)?
- What happens when Supabase credentials are incorrect or the service is unavailable?
- How does the system handle conflicting dependency versions between packages in the monorepo?
- What happens when a contributor creates an Issue without using the required spoiler tag format for Russian translation?
- How does the system behave when trying to install packages on systems without Composer or with incompatible PHP versions?
- What happens when developers reference the React implementation and try to directly port React-specific patterns that don't apply to Laravel?
- What happens when deleting a parent entity (e.g., Cluster) that has children (e.g., Domains and Resources)? How is CASCADE behavior communicated to users?
- How does the system handle duplicate relationship creation attempts (e.g., adding same resource to same domain twice)?
- What happens when rate limit is exceeded for a specific IP address? What error message and HTTP status are returned?
- How does the system prevent cross-tenant data access when user attempts to access resource in another user's cluster?
- What happens when metadata JSON schema evolves over time (e.g., adding new required fields)? How are existing records migrated?
- How does the system handle missing translations in one language when the other is complete? What fallback behavior is used?
- What happens when frontend build fails due to syntax errors or missing dependencies? How is error communicated to developer?
- How does the system behave when multiple API versions (/api/v1/, /api/v2/) need to coexist during migration period?
- What happens when idempotent operation receives conflicting data (e.g., same IDs but different metadata)?
- How does the system handle authorization guard failures when middleware chain is interrupted?

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: Repository MUST contain a monorepo structure with a packages/ directory at the root level
- **FR-002**: Each package MUST follow the naming convention: feature-name-frt for frontend packages and feature-name-srv for backend packages
- **FR-003**: Each package MUST contain a base/ subdirectory at its root to support future multiple implementations
- **FR-004**: Repository MUST include README.md (English) and README.ru.md (Russian) files at the root level with identical structure and line count
- **FR-005**: Russian documentation MUST be a complete translation of English documentation, not a summary, maintaining identical structure
- **FR-006**: Repository MUST use Composer for PHP dependency management at both root and package levels
- **FR-007**: Repository MUST include .gitignore configured for PHP/Laravel projects, excluding vendor/, node_modules/, and environment files
- **FR-008**: GitHub repository MUST have predefined labels including: bug, feature, enhancement, documentation, frontend, backend, platformo, mmoomm, web, repository, releases, i18n, publication, architecture, multiplayer, colyseus
- **FR-009**: GitHub Issues MUST follow the bilingual format with English as main content and Russian in `<details><summary>In Russian</summary>` spoiler tags
- **FR-010**: Repository MUST include configuration for Supabase as the primary database with connection settings in .env.example
- **FR-011**: Repository MUST be configured to use Laravel Passport for OAuth2 authentication
- **FR-012**: Repository MUST include instructions for Supabase integration in documentation
- **FR-013**: Repository MUST avoid including a docs/ directory (documentation hosted separately at docs.universo.pro)
- **FR-014**: Repository MUST NOT include AI agent configuration files in .github/agents/ (user creates these separately)
- **FR-015**: All README files and primary documentation MUST be available in both English and Russian with identical content structure
- **FR-016**: Repository MUST include package.json for frontend build tools and asset management even though backend is PHP
- **FR-017**: Frontend packages MUST be configured to work with a UI component library following Material Design principles
- **FR-018**: Repository structure MUST support three-tier entity patterns (e.g., Clusters/Domains/Resources) commonly used across features with clear examples in architecture documentation
- **FR-019**: Documentation MUST reference the React implementation at https://github.com/teknokomo/universo-platformo-react as a conceptual guide while noting it's partially implemented with legacy code
- **FR-020**: Documentation MUST include process for monitoring the React repository (weekly or bi-weekly) for new features and implementing them using Laravel stack
- **FR-021**: Repository MUST include clear contribution guidelines in both English and Russian
- **FR-022**: Package structure MUST support isolated development where packages can be extracted to separate repositories in the future
- **FR-023**: Repository MUST include examples of proper GitHub Issue creation following .github/instructions/github-issues.md
- **FR-024**: Repository MUST include examples of proper GitHub PR creation following .github/instructions/github-pr.md
- **FR-025**: Repository MUST include guidelines for maintaining bilingual documentation following .github/instructions/i18n-docs.md
- **FR-026**: Repository MUST use Laravel best practices for file organization, routing, and middleware configuration
- **FR-027**: Database configuration MUST be abstracted enough to support future integration with databases beyond Supabase
- **FR-028**: Repository MUST include composer.json at root level with workspace configuration for managing multiple packages
- **FR-029**: Each package MUST have its own composer.json file for isolated dependency management
- **FR-030**: Repository MUST include environment variable templates (.env.example) with all required configuration options documented
- **FR-031**: Repository MUST NOT replicate poor implementations from the React version but should use Laravel best practices
- **FR-032**: Architecture documentation MUST include detailed three-tier entity pattern with Clusters/Domains/Resources as the reference implementation
- **FR-033**: Architecture documentation MUST describe pattern variations including two-tier, four-tier, and five-tier entity hierarchies
- **FR-034**: Architecture documentation MUST define standard CRUD operations, relationships, and data models for entity patterns
- **FR-035**: Repository MUST include shared infrastructure packages: universo-types-srv (PHP interfaces, contracts, enums), universo-utils-srv (helpers, validators, transformers)
- **FR-036**: All database foreign keys MUST use CASCADE delete where parent-child relationship exists to maintain referential integrity
- **FR-037**: Resources with flexible schemas MUST use JSON/JSONB columns for metadata storage to support extensible data models
- **FR-038**: Junction tables for many-to-many relationships MUST have UNIQUE constraints on relationship pairs to prevent duplicate associations
- **FR-039**: API endpoints MUST implement rate limiting to prevent DoS attacks, with reasonable limits per IP address
- **FR-040**: Multi-tenant features MUST implement application-level authorization guards to enforce data isolation between tenants
- **FR-041**: Idempotent operations MUST be used for relationship creation to ensure safe retry behavior without creating duplicates
- **FR-042**: API responses MUST follow standardized JSON format with consistent success/error structure and appropriate HTTP status codes
- **FR-043**: API routes MUST use versioning (e.g., /api/v1/) to support future backward-compatible changes
- **FR-044**: Translation workflow MUST include validation of bilingual completeness to ensure 100% coverage in English and Russian
- **FR-045**: Build artifacts (vendor/, node_modules/, dist/, public/build/) MUST be excluded via .gitignore to prevent repository bloat
- **FR-046**: Frontend build output MUST be organized in public/ directory per Laravel conventions, with compiled assets in public/build/
- **FR-047**: Each package MUST have bilingual README files (README.md and README-RU.md) with identical structure, not just root-level documentation
- **FR-048**: Rate limiting MUST use appropriate storage backend (Redis recommended for production, in-memory acceptable for development)
- **FR-049**: Database queries MUST use parameterized queries (Eloquent ORM) to prevent SQL injection attacks
- **FR-050**: Authorization guards MUST prevent IDOR (Insecure Direct Object Reference) attacks by validating ownership before allowing resource access

### Key Entities

- **Package**: A self-contained module in the packages/ directory, either frontend (-frt) or backend (-srv), containing a base/ subdirectory with its implementation. Packages can have dependencies on other packages and can be extracted to separate repositories.

- **Feature Module**: A logical grouping of related packages (e.g., clusters feature includes clusters-frt and clusters-srv) that work together to provide specific functionality. May follow patterns like three-tier entities (primary/secondary/tertiary).

- **Documentation File**: Bilingual documentation consisting of an English version (.md) and Russian version (.ru.md or in spoiler tags for Issues) with identical structure and line count. Used for README files, contribution guides, and Issue templates.

- **GitHub Label**: A categorization tag used for Issues and Pull Requests, following defined categories: type (feature/bug/enhancement/documentation), area (frontend/backend/platformo/mmoomm), and technical scope (i18n/publication/architecture/multiplayer).

- **Database Connection**: Configuration for connecting to Supabase (primary) or other databases (future support), defined in environment variables and abstracted through Laravel's database layer.

- **Authentication Token**: OAuth2 token generated by Laravel Passport, used for authenticating users through Supabase, following security best practices for token storage and validation.

- **Three-Tier Entity Pattern**: Common data structure pattern used across features consisting of:
  - **Primary Entity** (e.g., Cluster, Metaverse, Unik): Top-level aggregate organizing related secondary entities
  - **Secondary Entity** (e.g., Domain, Section, Category): Middle-level grouping within a primary entity
  - **Tertiary Entity** (e.g., Resource, Entity, Item): Bottom-level items belonging to a secondary entity
  - Pattern includes standard CRUD operations, parent-child relationships, and bilingual UI elements
  - Variations exist: two-tier (parent-child only), four-tier, or five-tier hierarchies depending on feature complexity

- **Repository Monitoring Process**: Ongoing process to track the React implementation (universo-platformo-react) for new features, updates, and architectural patterns. Process includes:
  - Regular review schedule (weekly or bi-weekly)
  - Documentation of discovered features as GitHub Issues
  - Prioritization for Laravel implementation
  - Extraction of conceptual patterns while avoiding legacy code

- **Shared Infrastructure Package**: Utility packages without -frt/-srv suffix containing shared code (types, utilities, internationalization) used across multiple feature packages. Examples: universo-types-srv (PHP interfaces, contracts, enums for type consistency), universo-utils-srv (helpers, validators, transformers for code reuse), Laravel localization files (lang/en/, lang/ru/ for translation management).

- **Junction Table**: Database table representing many-to-many relationship between entities, with CASCADE delete constraints to remove relationships when parent is deleted, and UNIQUE constraint on relationship pairs to prevent duplicates. Used for flexible associations like resource-cluster, resource-domain, domain-cluster. Example: resources_domains table links resources to domains with columns (id, resource_id, domain_id, created_at) and unique constraint on (resource_id, domain_id).

- **Authorization Guard**: Application-level middleware component that enforces data isolation and prevents cross-tenant access (IDOR attacks). Validates that authenticated user has permission to access specific cluster/domain/resource before allowing operation. Implemented as Laravel middleware or Eloquent global scopes. Example: ClusterGuard checks user_id matches cluster.user_id before allowing read/write operations.

- **Idempotent Operation**: API operation that produces same result when called multiple times with same parameters, critical for reliability in distributed systems. For relationship creation: checks if relationship already exists before creating, returns success either way. Prevents duplicate entries when network requests are retried. Example: POST /clusters/{id}/domains/{domainId} creates association if missing, returns 200 OK if already exists.

- **Rate Limiter**: Security component that restricts number of requests from single IP address within time window to prevent DoS attacks and resource exhaustion. Configuration includes window duration (e.g., 15 minutes), maximum requests (e.g., 100), and storage backend (Redis for production, in-memory for development). Returns 429 Too Many Requests when limit exceeded. Applied to all public API endpoints.

- **API Resource**: Laravel class that transforms Eloquent models into JSON responses with controlled field exposure and computed properties. Provides consistent response format across API, hides internal fields, and adds API-specific fields like links or computed values. Example: ClusterResource transforms Cluster model into JSON with id, name, description, domains_count, and timestamps.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: A developer can clone the repository and understand its purpose, structure, and contribution guidelines within 10 minutes by reading documentation
- **SC-002**: Repository structure enables module isolation where any module can be extracted to a separate repository with less than 2 hours of migration work
- **SC-003**: Developers can create a new module following documented conventions in under 5 minutes
- **SC-004**: All primary documentation is available in both English and Russian with 100% translation coverage and identical structure
- **SC-005**: Project issues created following the guidelines are properly formatted with bilingual content, measurable by 100% having both language versions
- **SC-006**: Database connection can be established and verified with a test query in under 10 minutes following documentation
- **SC-007**: Developer can install all dependencies and run the project in a development environment within 15 minutes on a properly configured machine
- **SC-008**: All required project labels exist in the repository and match documented specifications
- **SC-009**: Repository structure supports common hierarchical data patterns (three-tier entities) with documented examples
- **SC-010**: Authentication framework can be configured and tested with a basic secure authentication flow in under 20 minutes
- **SC-011**: Dependency management works correctly at both repository root and module levels without conflicts
- **SC-012**: New developers can contribute their first documentation update within 30 minutes of cloning the repository by following bilingual documentation guidelines
- **SC-013**: Project maintainers can verify adherence to platform best practices by reviewing 100% of setup files against standard patterns
- **SC-014**: Architecture documentation clearly describes the three-tier entity pattern with concrete Clusters/Domains/Resources example that developers can reference in under 5 minutes
- **SC-015**: Team can identify new features in the React repository and create corresponding implementation Issues within the monitoring cycle (weekly or bi-weekly reviews)
- **SC-016**: Developers can understand and apply entity pattern variations (two-tier, four-tier, five-tier) to new features by reading pattern documentation in under 10 minutes
- **SC-017**: Shared infrastructure packages (universo-types-srv, universo-utils-srv) are created and used by at least 2 feature packages, reducing code duplication
- **SC-018**: Database relationships with CASCADE delete work correctly, measurable by deleting parent entity and verifying all children are automatically removed
- **SC-019**: Authorization guards prevent cross-tenant data access, measurable by attempting unauthorized access to another tenant's resource returns 403 Forbidden
- **SC-020**: Rate limiting prevents DoS attacks, measurable by exceeding configured limit returns 429 Too Many Requests status with appropriate retry-after header
- **SC-021**: Translation completeness is 100% for both English and Russian across all user-facing strings, measurable by automated validation script
- **SC-022**: API versioning allows multiple versions to coexist, measurable by /api/v1/ and /api/v2/ (future) routing independently without conflicts
- **SC-023**: Idempotent operations handle retries safely, measurable by calling same relationship creation endpoint twice produces identical database state
- **SC-024**: Build artifacts are properly excluded from repository, measurable by vendor/, node_modules/, dist/, public/build/ not appearing in git status
- **SC-025**: All packages have bilingual READMEs with identical structure, measurable by automated line count comparison between README.md and README-RU.md

### Additional Context

**Assumptions**:
- Developers have basic familiarity with server-side web application development
- Developers have required tools installed on their development systems (will be documented in setup guide)
- The team has access to cloud database services for data operations
- The project will follow semantic versioning for releases
- User interface components will follow Material Design principles
- The modular repository approach is acceptable for initial development
- Project repository has admin access for configuration
- Development team includes members comfortable with both English and Russian languages for documentation verification

**Dependencies**:
- Reference implementation repository (universo-platformo-react) serves as conceptual guide for architecture patterns
- Ongoing access to React repository for monitoring new features and updates
- Cloud database service availability for data operations
- Repository platform access for configuration management
- Standard authentication libraries and patterns
- Team capacity for weekly or bi-weekly review of reference implementation

**Out of Scope**:
- Detailed analysis of React repository structure and features (separate task to be created)
- Migration of specific features from reference implementation (covered in future specifications)
- Complete authentication user interface (only framework setup)
- Full database schema design (implemented per-feature)
- Legacy code cleanup from reference version (handled in that repository)
- Specific feature implementations like Clusters, Metaverses, Uniks (covered in separate specifications)
- Implementation of node graph systems, LangChain integration, UPDL-nodes (future feature specifications)
- Production deployment configuration (development setup only)
- Performance optimization (handled per-feature)
- Comprehensive testing infrastructure (basic setup only, expanded per-feature)

**Risks**:
- Database-specific code may require refactoring when supporting additional providers
- Module interdependencies may complicate extraction to separate repositories
- Bilingual documentation maintenance may create overhead for rapid development cycles
- Direct porting of patterns from reference implementation may not align with platform best practices
