<!--
SYNC IMPACT REPORT
==================
Version Change: [NEW] → 1.0.0
Created: 2025-11-16

Initial Constitution - Established core principles for Universo Platformo Laravel

Principles Established:
- I. Monorepo Package Architecture
- II. Bilingual Documentation (English/Russian)
- III. Database-First with Supabase
- IV. Laravel Best Practices
- V. Clean Architecture & Incremental Development
- VI. GitHub Process Compliance

Templates Status:
✅ plan-template.md - Reviewed, constitution check section aligned
✅ spec-template.md - Reviewed, requirements align with principles
✅ tasks-template.md - Reviewed, task categorization supports principles

Follow-up TODOs: None - all placeholders resolved
-->

# Universo Platformo Laravel Constitution

## Core Principles

### I. Monorepo Package Architecture

**MUST** organize codebase as a monorepo with PNPM workspace management. Packages MUST reside in `packages/` directory. When functionality requires both frontend and backend, they MUST be separated into distinct packages (e.g., `packages/clusters-frt` and `packages/clusters-srv`). Each package MUST contain a root `base/` directory to accommodate future multiple implementations.

**Rationale**: This structure enables independent development and deployment of features while maintaining the flexibility to support multiple technology stack implementations within the same conceptual package.

### II. Bilingual Documentation (English/Russian)

**MUST** create all documentation in both English and Russian. English is the primary standard and MUST be created first. Russian documentation MUST be an exact translation with identical structure, content, and line count. This applies to README files (e.g., `README.md` and `README-RU.md`) and all GitHub Issues.

**Rationale**: Supports bilingual development team and user base while maintaining documentation consistency through a clear primary-secondary relationship.

### III. Database-First with Supabase

**MUST** use Supabase as the primary database solution. Authentication MUST use Passport.js with Supabase connector. Database schema design MUST be extensible to support other DBMS in the future, avoiding Supabase-specific patterns that prevent migration.

**Rationale**: Supabase provides rapid development capabilities while maintaining PostgreSQL compatibility. Extensibility design prevents vendor lock-in.

### IV. Laravel Best Practices

**MUST** follow Laravel framework conventions and PHP best practices. This includes: Eloquent ORM for database operations, Laravel's service container for dependency injection, form request validation, resource controllers, and API resources. Front-end MUST use Material UI (MUI library) for consistent design language.

**Rationale**: Leverage Laravel's mature ecosystem and conventions for maintainable, scalable full-stack PHP applications.

### V. Clean Architecture & Incremental Development

**MUST NOT** replicate legacy code or incomplete implementations from reference projects. Each feature MUST be implemented cleanly using established patterns. Development MUST follow incremental approach: basic entity structure first (e.g., Clusters/Domains/Resources), then adapt pattern for similar features (e.g., Metaverses/Sections/Entities), finally add specialized functionality (e.g., Spaces/Canvases with node graphs).

**Rationale**: Building on proven patterns accelerates development while maintaining code quality. Clean implementation prevents technical debt accumulation.

### VI. GitHub Process Compliance

**MUST** follow established GitHub workflows: create Issues per `.github/instructions/github-issues.md`, apply labels per `.github/instructions/github-labels.md`, create Pull Requests per `.github/instructions/github-pr.md`. Issues MUST be created before implementing specifications. Documentation updates MUST follow i18n guidelines in `.github/instructions/i18n-docs.md`.

**Rationale**: Consistent process ensures traceability, proper project tracking, and team coordination.

## Technology Stack Requirements

**Framework**: Laravel (latest stable) with PHP 8.2+
**Package Manager**: PNPM for monorepo workspace management
**Database**: Supabase (PostgreSQL-based) with extensibility for other DBMS
**Authentication**: Passport.js with Supabase connector
**Frontend Library**: Material UI (MUI)
**Testing**: PHPUnit for backend, Jest/React Testing Library for frontend packages
**Version Control**: Git with feature branch workflow

## Development Workflow

**Issue-First Development**: Before implementing any specification, an Issue MUST be created in the repository with proper English/Russian bilingual content and appropriate labels.

**Documentation-First**: Create English documentation first, validate completeness, then create Russian translation with identical structure and line count.

**Specification Process**: Follow `.specify/` templates for plans, specifications, and tasks. Each feature MUST have complete documentation before implementation begins.

**Code Review**: All changes MUST go through Pull Request review. PRs MUST reference related Issues and include both implementation and documentation updates.

## Governance

This constitution supersedes all other development practices and patterns. All Pull Requests and code reviews MUST verify compliance with these principles.

**Amendment Process**: Constitution amendments require documented justification, approval, and migration plan for affected code. Version MUST increment according to semantic versioning:
- MAJOR: Backward-incompatible governance changes or principle removals/redefinitions
- MINOR: New principles added or materially expanded guidance
- PATCH: Clarifications, wording fixes, non-semantic refinements

**Complexity Justification**: Any complexity that violates these principles MUST be explicitly justified in design documents. Simpler alternatives MUST be documented and their rejection explained.

**Compliance Review**: Constitution compliance is verified during specification review, implementation review, and before merging to main branch.

**Version**: 1.0.0 | **Ratified**: 2025-11-16 | **Last Amended**: 2025-11-16
