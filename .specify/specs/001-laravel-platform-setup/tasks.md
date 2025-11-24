# Tasks: Initial Repository Foundation and Development Environment

**Input**: Design documents from `.specify/specs/001-laravel-platform-setup/`
**Prerequisites**: plan.md ✓, spec.md ✓, research.md ✓, data-model.md ✓, contracts/ ✓, quickstart.md ✓

**Tests**: Tests are NOT explicitly requested in this feature specification. Tasks focus on setup and infrastructure.

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)
- Include exact file paths in descriptions

## Path Conventions

- **Monorepo (Universo Platformo Laravel)**: `packages/[feature]-frt/base/`, `packages/[feature]-srv/base/`
- **Frontend packages**: `packages/[feature]-frt/base/resources/`, `packages/[feature]-frt/base/tests/`
- **Backend packages**: `packages/[feature]-srv/base/app/`, `packages/[feature]-srv/base/tests/`
- **Root Laravel app**: `app/`, `config/`, `database/`, `resources/`, `routes/`, `tests/`
- Each package includes bilingual README.md and README-RU.md

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Project initialization and basic structure validation

- [ ] T001 Verify Laravel 11.x installation and core dependencies in composer.json
- [ ] T002 Verify Node.js/NPM dependencies and Vite configuration in package.json
- [ ] T003 [P] Verify .env.example contains all required Supabase configuration variables
- [ ] T004 [P] Verify .gitignore excludes vendor/, node_modules/, public/build/, .env files
- [ ] T005 [P] Verify PHPUnit configuration in phpunit.xml

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core infrastructure that MUST be complete before ANY user story can be implemented

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [ ] T006 Create base directory structure: packages/ at repository root
- [ ] T007 Configure Composer workspace for monorepo in root composer.json with path repositories
- [ ] T008 [P] Configure Supabase database connection settings in config/database.php for PostgreSQL
- [ ] T009 [P] Install and configure Laravel Sanctum in config/sanctum.php
- [ ] T010 [P] Configure API rate limiting middleware in app/Http/Kernel.php (60 requests/minute)
- [ ] T011 [P] Create API versioning structure in routes/api.php with /api/v1/ prefix
- [ ] T012 [P] Configure CORS settings in config/cors.php for API access
- [ ] T013 Setup error handling with standardized JSON response format in app/Exceptions/Handler.php

**Checkpoint**: Foundation ready - user story implementation can now begin in parallel

---

## Phase 3: User Story 1 - Repository Structure Verification (Priority: P1) 🎯 MVP

**Goal**: Developers can clone the repository, understand its structure, and navigate the monorepo architecture in under 10 minutes

**Independent Test**: Clone repository, read README files (English and Russian), verify all directory structures exist as documented, confirm understanding in under 10 minutes

### Documentation for User Story 1

- [ ] T014 [P] [US1] Update README.md with monorepo structure documentation, package conventions, and progressive feature roadmap (Auth → Clusters → Metaverses → Uniks → Spaces/Canvases → Node Systems including LangChain and UPDL)
- [ ] T015 [P] [US1] Create README-RU.md with identical structure to README.md (Russian translation)
- [ ] T016 [P] [US1] Update ARCHITECTURE.md with three-tier entity pattern documentation (Clusters/Domains/Resources) and fix frontend framework examples from React to Vue.js 3 + Vuetify 3 + Inertia.js per specification
- [ ] T017 [P] [US1] Update ARCHITECTURE.md with entity pattern variations documentation (two-tier, four-tier, five-tier)
- [ ] T018 [P] [US1] Update ARCHITECTURE.md to document React repository monitoring process (weekly/bi-weekly schedule) for tracking new features to implement in Laravel
- [ ] T019 [US1] Create CONTRIBUTING.md with bilingual contribution guidelines (English primary)
- [ ] T020 [US1] Create CONTRIBUTING-RU.md with Russian translation matching CONTRIBUTING.md structure

### Package Structure Templates for User Story 1

- [ ] T021 [US1] Create packages/universo-types-srv/ directory with base/ subdirectory structure
- [ ] T022 [US1] Create packages/universo-types-srv/composer.json with namespace configuration
- [ ] T023 [US1] Create packages/universo-types-srv/base/src/Contracts/ directory for PHP interfaces
- [ ] T024 [P] [US1] Create packages/universo-types-srv/base/src/Enums/ directory for PHP enumerations
- [ ] T025 [P] [US1] Create packages/universo-types-srv/base/src/DTOs/ directory for Data Transfer Objects
- [ ] T026 [US1] Create packages/universo-types-srv/README.md documenting package purpose and usage
- [ ] T027 [US1] Create packages/universo-types-srv/README-RU.md with Russian translation

- [ ] T028 [US1] Create packages/universo-utils-srv/ directory with base/ subdirectory structure
- [ ] T029 [US1] Create packages/universo-utils-srv/composer.json with namespace configuration
- [ ] T030 [US1] Create packages/universo-utils-srv/base/src/Helpers/ directory for helper functions
- [ ] T031 [P] [US1] Create packages/universo-utils-srv/base/src/Validators/ directory for custom validators
- [ ] T032 [P] [US1] Create packages/universo-utils-srv/base/src/Transformers/ directory for data transformers
- [ ] T033 [US1] Create packages/universo-utils-srv/README.md documenting package purpose and usage
- [ ] T034 [US1] Create packages/universo-utils-srv/README-RU.md with Russian translation

**Checkpoint**: At this point, User Story 1 should be fully functional and testable independently - repository structure is clear and documented

---

## Phase 4: User Story 2 - Package Management Setup (Priority: P2)

**Goal**: Developers can add new packages or dependencies, understanding Composer workspace configuration and package organization in under 5 minutes

**Independent Test**: Create a test package following conventions, add dependencies via Composer, verify package structure matches documented patterns

### Package Management Implementation for User Story 2

- [ ] T035 [P] [US2] Configure root composer.json with repositories array pointing to packages/*/
- [ ] T036 [P] [US2] Add universo-types-srv to root composer.json require section as path repository
- [ ] T037 [P] [US2] Add universo-utils-srv to root composer.json require section as path repository
- [ ] T038 [US2] Run composer update to register workspace packages
- [ ] T039 [P] [US2] Add autoload configuration in root composer.json for packages namespace
- [ ] T040 [P] [US2] Create package template documentation in .github/instructions/package-creation.md
- [ ] T041 [US2] Create .github/instructions/package-creation-ru.md with Russian translation
- [ ] T042 [P] [US2] Document inter-package dependency patterns in package-creation.md
- [ ] T043 [US2] Add package naming conventions documentation (feature-frt, feature-srv patterns)
- [ ] T044 [US2] Add examples for creating frontend (-frt) and backend (-srv) packages

**Checkpoint**: At this point, User Stories 1 AND 2 should both work independently - packages can be created and managed

---

## Phase 5: User Story 3 - GitHub Labels and Issue Configuration (Priority: P2)

**Goal**: Contributors can create Issues with proper labels and bilingual content following project conventions in under 3 minutes

**Independent Test**: Create sample Issue following guidelines, verify all required labels exist, confirm bilingual format matches template

### GitHub Configuration for User Story 3

- [ ] T045 [P] [US3] Create GitHub label: bug (color: d73a4a, description: Something isn't working)
- [ ] T046 [P] [US3] Create GitHub label: feature (color: 0075ca, description: New feature or request)
- [ ] T047 [P] [US3] Create GitHub label: enhancement (color: a2eeef, description: Enhancement to existing functionality)
- [ ] T048 [P] [US3] Create GitHub label: documentation (color: 0075ca, description: Documentation improvements)
- [ ] T049 [P] [US3] Create GitHub label: frontend (color: 1d76db, description: Frontend/UI related)
- [ ] T050 [P] [US3] Create GitHub label: backend (color: 5319e7, description: Backend/API related)
- [ ] T051 [P] [US3] Create GitHub label: platformo (color: fbca04, description: Universo Platformo core)
- [ ] T052 [P] [US3] Create GitHub label: mmoomm (color: fbca04, description: Universo MMOOMM related)
- [ ] T053 [P] [US3] Create GitHub label: web (color: c5def5, description: Web application)
- [ ] T054 [P] [US3] Create GitHub label: repository (color: ededed, description: Repository maintenance)
- [ ] T055 [P] [US3] Create GitHub label: releases (color: 0e8a16, description: Release management)
- [ ] T056 [P] [US3] Create GitHub label: i18n (color: fef2c0, description: Internationalization)
- [ ] T057 [P] [US3] Create GitHub label: publication (color: d4c5f9, description: Publications and content)
- [ ] T058 [P] [US3] Create GitHub label: architecture (color: 5319e7, description: Architecture decisions)
- [ ] T059 [P] [US3] Create GitHub label: multiplayer (color: 0075ca, description: Multiplayer/collaborative features)
- [ ] T060 [P] [US3] Create GitHub label: colyseus (color: 0075ca, description: Colyseus multiplayer)

### Issue Templates for User Story 3

- [ ] T061 [US3] Verify .github/instructions/github-issues.md contains bilingual format guidelines
- [ ] T062 [US3] Verify .github/instructions/github-labels.md contains all label definitions
- [ ] T063 [US3] Verify .github/instructions/github-pr.md contains PR creation guidelines
- [ ] T064 [US3] Verify .github/instructions/i18n-docs.md contains bilingual documentation rules
- [ ] T065 [US3] Create sample Issue demonstrating bilingual format with spoiler tags for Russian content
- [ ] T066 [US3] Document label usage guidelines in .github/instructions/github-labels.md

**Checkpoint**: All user stories 1, 2, and 3 should now be independently functional - GitHub workflow is established

---

## Phase 6: User Story 4 - Database Integration Preparation (Priority: P3)

**Goal**: Developers can configure Supabase credentials, establish database connection, and execute test queries in under 10 minutes

**Independent Test**: Configure Supabase credentials, establish connection, perform basic database operation, verify connection succeeds

### Database Configuration for User Story 4

- [ ] T067 [P] [US4] Add Supabase connection variables to .env.example (DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- [ ] T068 [P] [US4] Document Supabase configuration in quickstart.md (obtaining credentials from Supabase dashboard)
- [ ] T069 [US4] Create database/migrations/2024_01_01_000000_create_base_tables.php for future entity tables
- [ ] T070 [P] [US4] Install supabase-community/supabase-php package via Composer
- [ ] T071 [P] [US4] Create config/supabase.php configuration file for Supabase client settings
- [ ] T072 [US4] Add Supabase service provider in app/Providers/SupabaseServiceProvider.php
- [ ] T073 [US4] Register SupabaseServiceProvider in bootstrap/providers.php
- [ ] T074 [P] [US4] Create app/Services/DatabaseTestService.php for connection verification
- [ ] T075 [US4] Create Artisan command app/Console/Commands/TestDatabaseConnection.php
- [ ] T076 [US4] Document database testing procedure in quickstart.md
- [ ] T077 [P] [US4] Add database abstraction notes in ARCHITECTURE.md for future multi-database support
- [ ] T078 [US4] Document CASCADE delete patterns in ARCHITECTURE.md
- [ ] T079 [US4] Document JSONB metadata usage patterns in ARCHITECTURE.md
- [ ] T080 [US4] Document junction table patterns with UNIQUE constraints in ARCHITECTURE.md

**Checkpoint**: User Story 4 complete - database integration is ready for feature development

---

## Phase 7: User Story 5 - Authentication Framework Setup (Priority: P3)

**Goal**: Developers can implement authentication flows using Laravel Sanctum with Supabase JWT validation in under 20 minutes

**Independent Test**: Configure Laravel Sanctum, implement basic authentication flow with Supabase JWT validation, verify token generation works

### Authentication Setup for User Story 5

- [ ] T081 [P] [US5] Publish Laravel Sanctum configuration: php artisan vendor:publish --tag=sanctum-config
- [ ] T082 [P] [US5] Publish Laravel Sanctum migrations: php artisan vendor:publish --tag=sanctum-migrations
- [ ] T083 [US5] Run Sanctum migrations: php artisan migrate
- [ ] T084 [P] [US5] Create app/Http/Middleware/SupabaseJWTMiddleware.php for JWT validation
- [ ] T085 [US5] Register SupabaseJWTMiddleware in app/Http/Kernel.php
- [ ] T086 [P] [US5] Create authentication routes in routes/api.php (/api/v1/auth/login, /api/v1/auth/register, /api/v1/auth/logout)
- [ ] T087 [US5] Create app/Http/Controllers/Api/V1/AuthController.php with login/register/logout methods
- [ ] T088 [P] [US5] Create app/Http/Requests/LoginRequest.php for login validation
- [ ] T089 [P] [US5] Create app/Http/Requests/RegisterRequest.php for registration validation
- [ ] T090 [US5] Add HasApiTokens trait to app/Models/User.php
- [ ] T091 [P] [US5] Create app/Services/AuthService.php for authentication logic
- [ ] T092 [US5] Document authentication setup in quickstart.md
- [ ] T093 [P] [US5] Document authorization guard patterns in ARCHITECTURE.md for multi-tenant features
- [ ] T094 [US5] Document rate limiting for authentication endpoints in ARCHITECTURE.md
- [ ] T095 [US5] Create example authentication flow in quickstart.md

**Checkpoint**: User Story 5 complete - authentication framework is ready for feature-specific implementations

---

## Phase 8: User Story 6 - UI Component Library Integration (Priority: P3)

**Goal**: Frontend developers can create Material Design UI components using configured library in under 15 minutes

**Independent Test**: Create sample UI component using library, verify styling consistency, confirm component renders correctly

### UI Library Configuration for User Story 6

- [ ] T096 [P] [US6] Install Inertia.js server-side: composer require inertiajs/inertia-laravel
- [ ] T097 [P] [US6] Publish Inertia middleware: php artisan inertia:middleware
- [ ] T098 [US6] Add Inertia middleware to web middleware group in app/Http/Kernel.php
- [ ] T099 [P] [US6] Install Inertia.js client-side: npm install @inertiajs/vue3
- [ ] T100 [P] [US6] Install Vue.js 3: npm install vue@next
- [ ] T101 [P] [US6] Install Vuetify 3: npm install vuetify@^3.4.0
- [ ] T102 [P] [US6] Install Vuetify dependencies: npm install @mdi/font
- [ ] T103 [US6] Create resources/js/app.js with Vue and Inertia setup
- [ ] T104 [US6] Create resources/js/plugins/vuetify.js for Vuetify configuration
- [ ] T105 [P] [US6] Create resources/views/app.blade.php as Inertia root template
- [ ] T106 [US6] Configure Vite in vite.config.js to build Vue/Inertia assets
- [ ] T107 [P] [US6] Create example Vue component in resources/js/Pages/Welcome.vue
- [ ] T108 [US6] Create example route in routes/web.php using Inertia
- [ ] T109 [P] [US6] Document Inertia setup in quickstart.md
- [ ] T110 [P] [US6] Document Vuetify component usage in quickstart.md
- [ ] T111 [US6] Document Material Design consistency guidelines in ARCHITECTURE.md
- [ ] T112 [US6] Add frontend build instructions to quickstart.md (npm run dev, npm run build)

**Checkpoint**: All user stories complete - full development environment is operational

---

## Phase 9: Polish & Cross-Cutting Concerns

**Purpose**: Improvements that affect multiple user stories and final validation

- [ ] T113 [P] Update all documentation with correct file paths and examples
- [ ] T114 [P] Create API response helper in app/Http/Helpers/ApiResponse.php
- [ ] T115 [P] Create API resource base class in app/Http/Resources/BaseResource.php
- [ ] T116 [P] Document API standards from contracts/api-standards.md in ARCHITECTURE.md
- [ ] T117 [P] Add idempotent operation patterns documentation in ARCHITECTURE.md
- [ ] T118 [P] Document build artifact exclusion patterns in .gitignore documentation
- [ ] T119 [P] Create translation structure: resources/lang/en/ and resources/lang/ru/
- [ ] T120 [P] Add example translation files: resources/lang/en/common.php and resources/lang/ru/common.php
- [ ] T121 Verify all bilingual documentation has identical structure (line count validation)
- [ ] T122 Run composer install to verify all dependencies resolve correctly
- [ ] T123 Run npm install to verify frontend dependencies install correctly
- [ ] T124 Run php artisan config:cache to verify configuration is valid
- [ ] T125 Run npm run build to verify frontend builds successfully
- [ ] T126 Validate quickstart.md procedures by following them step-by-step
- [ ] T127 Create PROJECT_SETUP_SUMMARY.md documenting completed setup and next steps
- [ ] T128 Verify root README.md has current status and progressive feature roadmap (completed by T014)

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies - can start immediately (validation only)
- **Foundational (Phase 2)**: Depends on Setup completion - BLOCKS all user stories
- **User Stories (Phase 3-8)**: All depend on Foundational phase completion
  - User Story 1 (P1) - MVP priority: Can start after Phase 2
  - User Story 2 (P2): Can start after Phase 2
  - User Story 3 (P2): Can start after Phase 2
  - User Story 4 (P3): Can start after Phase 2
  - User Story 5 (P3): Can start after Phase 2
  - User Story 6 (P3): Can start after Phase 2
- **Polish (Phase 9)**: Depends on all desired user stories being complete

### User Story Dependencies

- **User Story 1 (P1) - Repository Structure**: Can start after Foundational (Phase 2) - No dependencies on other stories
- **User Story 2 (P2) - Package Management**: Depends on US1 for package structure - integrates with but independently testable
- **User Story 3 (P2) - GitHub Labels**: Can start after Foundational (Phase 2) - No dependencies on other stories
- **User Story 4 (P3) - Database Integration**: Can start after Foundational (Phase 2) - No dependencies on other stories
- **User Story 5 (P3) - Authentication**: Depends on US4 for database - integrates with but independently testable
- **User Story 6 (P3) - UI Components**: Can start after Foundational (Phase 2) - No dependencies on other stories

### Within Each User Story

- Documentation tasks can often run in parallel (marked [P])
- Package creation follows structure: directory → composer.json → source directories → README files
- Configuration tasks follow: installation → configuration file → registration/integration
- Stories complete when all tasks done and independently testable

### Parallel Opportunities

- All Setup validation tasks (T001-T005) can run in parallel
- Many Foundational tasks (T008-T012) can run in parallel after T006-T007 complete
- Documentation tasks within each story marked [P] can run in parallel
- GitHub label creation tasks (T045-T060) can all run in parallel
- Once Foundational phase completes, multiple user stories can be worked on in parallel by different team members

---

## Parallel Example: User Story 1 (Repository Structure)

```bash
# Launch all documentation creation together:
Task T014: "Update README.md with monorepo structure documentation"
Task T015: "Create README-RU.md with Russian translation"
Task T016: "Create ARCHITECTURE.md with three-tier entity pattern"
Task T017: "Add entity pattern variations documentation in ARCHITECTURE.md"

# Launch package directory creation together:
Task T023: "Create packages/universo-types-srv/base/src/Contracts/ directory"
Task T024: "Create packages/universo-types-srv/base/src/Enums/ directory"
Task T025: "Create packages/universo-types-srv/base/src/DTOs/ directory"
```

---

## Parallel Example: User Story 3 (GitHub Labels)

```bash
# Launch all label creation together (16 labels can be created simultaneously):
Task T045: Create bug label
Task T046: Create feature label
Task T047: Create enhancement label
Task T048: Create documentation label
Task T049: Create frontend label
Task T050: Create backend label
# ... all 16 labels in parallel
```

---

## Implementation Strategy

### MVP First (User Story 1 Only)

1. Complete Phase 1: Setup validation (T001-T005)
2. Complete Phase 2: Foundational infrastructure (T006-T013) - CRITICAL
3. Complete Phase 3: User Story 1 - Repository Structure (T014-T034)
4. **STOP and VALIDATE**: Test User Story 1 independently
   - Clone repository to fresh directory
   - Verify README comprehension in under 10 minutes
   - Validate all package structures exist
   - Confirm bilingual documentation completeness
5. Deploy/demo if ready

### Incremental Delivery

1. **Foundation** (Phases 1-2): Core infrastructure ready
2. **MVP** (Phase 3): User Story 1 → Repository structure documented and validated
3. **Iteration 1** (Phase 4): Add User Story 2 → Package management operational
4. **Iteration 2** (Phase 5): Add User Story 3 → GitHub workflow established
5. **Iteration 3** (Phase 6): Add User Story 4 → Database integration ready
6. **Iteration 4** (Phase 7): Add User Story 5 → Authentication framework ready
7. **Iteration 5** (Phase 8): Add User Story 6 → UI library integrated
8. **Polish** (Phase 9): Cross-cutting concerns and validation

Each iteration adds independent value without breaking previous functionality.

### Parallel Team Strategy

With multiple developers:

1. **Together**: Complete Setup + Foundational (Phases 1-2)
2. **Once Foundational complete**:
   - **Developer A (Priority)**: User Story 1 (Repository Structure) - MVP
   - **Developer B**: User Story 3 (GitHub Labels) - Independent
   - **Developer C**: User Story 4 (Database Integration) - Independent
3. **Sequential dependencies**:
   - Developer A completes US1 → then starts US2 (depends on US1)
   - Developer C completes US4 → then starts US5 (depends on US4)
   - Developer B completes US3 → then starts US6 (independent)
4. Stories complete and integrate independently

---

## Summary Statistics

**Total Tasks**: 128 tasks across 9 phases

**Task Distribution by User Story**:
- Setup (Phase 1): 5 tasks (validation)
- Foundational (Phase 2): 8 tasks (blocking prerequisites)
- User Story 1 (P1): 21 tasks (repository structure - MVP)
- User Story 2 (P2): 10 tasks (package management)
- User Story 3 (P2): 22 tasks (GitHub configuration)
- User Story 4 (P3): 14 tasks (database integration)
- User Story 5 (P3): 15 tasks (authentication)
- User Story 6 (P3): 17 tasks (UI components)
- Polish (Phase 9): 16 tasks (cross-cutting)

**Parallel Opportunities**: 85 tasks marked [P] can run in parallel within their phase/story

**MVP Scope**: Phases 1-3 (34 tasks) = Functional repository with clear structure and documentation

**Independent Test Criteria**:
- US1: Clone and understand repository in <10 minutes
- US2: Create new package in <5 minutes
- US3: Create proper Issue in <3 minutes
- US4: Connect to database in <10 minutes
- US5: Implement auth flow in <20 minutes
- US6: Create UI component in <15 minutes

---

## Notes

- [P] tasks = different files or independent operations, no dependencies within same phase
- [Story] label maps task to specific user story for traceability
- Each user story should be independently completable and testable
- Tests are NOT included as they were not explicitly requested in the specification
- Commit after each logical group of tasks or at phase checkpoints
- Stop at any checkpoint to validate story independently
- Avoid: vague tasks, same file conflicts, cross-story dependencies that break independence
- Focus on Laravel best practices rather than replicating React implementation patterns

---

## Future Feature Specifications (Progressive Implementation Roadmap)

This feature (001-laravel-platform-setup) establishes the foundation. The following features should be implemented as separate feature specifications in priority order:

### Phase 1: Core Features (Priority: P1)
1. **002-authentication-system**: Complete authentication pages and user management (login, register, profile, password reset)
2. **003-clusters-feature**: Clusters package (clusters-frt, clusters-srv) implementing three-tier pattern with Clusters → Domains → Resources

### Phase 2: Extended Features (Priority: P2)
3. **004-metaverses-feature**: Metaverses package for 3D/VR world management (metaverses-frt, metaverses-srv)
4. **005-uniks-feature**: Uniks package for unified knowledge/item system (uniks-frt, uniks-srv)

### Phase 3: Advanced Features (Priority: P3)
5. **006-spaces-canvases**: Spaces and Canvases system for visual workflow editing
6. **007-node-system-base**: Base node system infrastructure for visual programming
7. **008-langchain-nodes**: LangChain integration nodes package (langchain-nodes-srv, langchain-nodes-frt)
8. **009-updl-nodes**: UPDL (Universo Platform Description Language) nodes package (updl-nodes-srv, updl-nodes-frt)

### Phase 4: Platform Features (Priority: P4)
9. **010-publication-system**: Application publication and deployment system
10. **011-multiplayer-colyseus**: Real-time multiplayer features using Colyseus (multiplayer-colyseus-srv, multiplayer-colyseus-frt)

### Implementation Notes:
- Each feature should follow the same specification process: spec.md → plan.md → research.md → data-model.md → contracts/ → tasks.md
- All features MUST use the modular package structure (feature-frt / feature-srv pattern)
- Monitor [universo-platformo-react](https://github.com/teknokomo/universo-platformo-react) for architectural patterns, but avoid copying legacy Flowise monolithic structures
- Each feature package should be independently testable and potentially extractable to its own repository
- Frontend packages use Vue.js 3 + Vuetify 3 + Inertia.js (NOT React)
- Backend packages use Laravel 11+ with Repository pattern and Domain-Driven Design principles

### React Repository Package Reference:
- React packages structure: https://github.com/teknokomo/universo-platformo-react/tree/main/packages
- Flowise components (legacy, needs optimization): https://github.com/teknokomo/universo-platformo-react/tree/main/packages/flowise-components
- UPDL nodes reference: https://github.com/teknokomo/universo-platformo-react/tree/main/packages/updl/base
