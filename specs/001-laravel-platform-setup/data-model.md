# Data Model: Initial Repository Foundation

**Feature**: 001-laravel-platform-setup  
**Date**: 2025-11-17  
**Status**: Phase 1 Design

## Overview

This document defines the structural entities and relationships for the initial repository setup. While this feature doesn't introduce database entities directly, it establishes the foundational structure that will support future data models following the three-tier entity pattern.

## Conceptual Entities

### Repository Structure

The repository structure itself represents our primary organizational model:

#### Entity: Package

**Description**: A self-contained module in the packages/ directory providing specific functionality.

**Types**:
- **Infrastructure Package**: Shared utilities without -frt/-srv suffix (e.g., universo-types-srv, universo-utils-srv)
- **Feature Frontend Package**: Frontend-specific code with -frt suffix (future)
- **Feature Backend Package**: Backend-specific code with -srv suffix (future)

**Structure**:
```
package-name/
├── base/                    # Base implementation directory (required)
│   ├── src/                # Source code
│   │   └── [feature-specific]
│   ├── tests/              # Unit and feature tests
│   └── composer.json       # Package-specific dependencies
├── README.md               # English documentation
├── README-RU.md            # Russian documentation
└── composer.json           # Package manifest
```

**Validation Rules**:
- Package name MUST follow naming convention (feature-name-frt/srv or shared without suffix)
- MUST contain base/ subdirectory
- MUST include bilingual README files
- MUST have composer.json with proper namespace
- Frontend packages MAY include package.json for npm dependencies

**Relationships**:
- Packages can depend on other packages via Composer path repositories
- Infrastructure packages are dependencies for feature packages
- Packages are loosely coupled for future extraction to separate repositories

---

### Documentation Files

#### Entity: Bilingual Document

**Description**: Paired documentation files in English and Russian with identical structure.

**Structure**:
- Primary: `{name}.md` (English)
- Translation: `{name}-RU.md` (Russian)

**Types**:
- README files (package and root level)
- Contributing guides
- Architecture documentation
- Process documentation (GitHub instructions)

**Validation Rules**:
- English version MUST be created first
- Russian version MUST have identical structure and line count
- Russian version MUST be complete translation, not summary
- Both versions MUST be kept in sync during updates

**Relationships**:
- Each English document has exactly one Russian counterpart
- Documents reference each other (e.g., README links to CONTRIBUTING)

---

### GitHub Configuration

#### Entity: GitHub Label

**Description**: Repository labels for categorizing Issues and Pull Requests.

**Schema**:
```json
{
  "name": "string (unique)",
  "color": "hex color code",
  "description": "string",
  "category": "type|area|scope"
}
```

**Categories**:

**Type Labels**:
- `bug` - Something isn't working
- `feature` - New feature or request
- `enhancement` - Improvement to existing feature
- `documentation` - Documentation updates

**Area Labels**:
- `frontend` - Frontend-related changes
- `backend` - Backend-related changes
- `platformo` - Universo Platformo features
- `mmoomm` - Universo MMOOMM features
- `web` - Web platform
- `repository` - Repository structure/config

**Scope Labels**:
- `i18n` - Internationalization
- `publication` - Publishing/deployment
- `architecture` - Architectural changes
- `multiplayer` - Multiplayer features
- `colyseus` - Colyseus integration
- `releases` - Release management

**Validation Rules**:
- Name MUST be unique within repository
- Color MUST be valid 6-digit hex code
- Description SHOULD clearly explain label purpose
- Labels SHOULD follow documented categorization

**Usage**:
- Issues MUST have at least one type label
- Issues SHOULD have area and scope labels as applicable
- Labels guide project management and filtering

---

## Three-Tier Entity Pattern (Reference Model)

While not implemented in this feature, this section documents the pattern that future features MUST follow:

### Pattern Structure

```
Primary Entity (Tier 1)
  ├── has many → Secondary Entity (Tier 2)
  │                ├── has many → Tertiary Entity (Tier 3)
  │                └── belongs to → Primary Entity
  └── can access all tertiary entities through secondary entities
```

### Example: Clusters Pattern (Reference Implementation)

**Primary Entity: Cluster**
- Top-level container organizing related domains
- Fields: id (UUID), name, description, user_id, metadata (JSONB), timestamps
- Relationships: has many Domains, has many Resources (through Domains)
- Authorization: Enforces tenant isolation via user_id

**Secondary Entity: Domain**
- Middle-tier grouping within a cluster
- Fields: id (UUID), cluster_id, name, description, metadata (JSONB), timestamps
- Relationships: belongs to Cluster, has many Resources
- Cascade: DELETE cluster removes all domains

**Tertiary Entity: Resource**
- Bottom-tier individual items
- Fields: id (UUID), domain_id, name, description, type, metadata (JSONB), timestamps
- Relationships: belongs to Domain, belongs to Cluster (through Domain)
- Cascade: DELETE domain removes all resources

### Pattern Variations

**Two-Tier**: Primary → Tertiary (skips middle layer)
- Example: Category → Items
- Simpler features without need for intermediate grouping

**Four-Tier**: Primary → Secondary → Tertiary → Quaternary
- Example: Organization → Project → Module → Component
- Complex hierarchical features

**Five-Tier**: Primary → Secondary → Tertiary → Quaternary → Quinary
- Example: Uniks with deep nesting
- Rarely needed, evaluate if simpler structure possible

### Standard Elements Across Patterns

All entities in the pattern MUST include:
- **UUID Primary Key**: For global uniqueness and security
- **Timestamps**: created_at, updated_at for auditing
- **Soft Deletes**: deleted_at for data recovery (optional, feature-dependent)
- **Foreign Keys with CASCADE**: Maintain referential integrity
- **JSONB Metadata**: Flexible schema for feature-specific data
- **Bilingual Fields**: If storing user-facing text, support i18n

All entity APIs MUST implement:
- **CRUD Operations**: Create, Read, Update, Delete endpoints
- **Authorization Guards**: Validate tenant/user access
- **Rate Limiting**: Prevent abuse (60 requests/minute per IP)
- **Validation**: Laravel Form Requests with clear error messages
- **API Resources**: Consistent JSON response format
- **Versioned Routes**: /api/v1/entities pattern

---

## Configuration Entities

### Entity: Environment Configuration

**Description**: Environment variables defining application behavior.

**Key Configurations**:

**Application**:
- APP_NAME, APP_ENV, APP_KEY, APP_DEBUG, APP_URL

**Database** (Supabase):
- DB_CONNECTION=pgsql
- DB_HOST=db.supabase.co
- DB_PORT=5432
- DB_DATABASE, DB_USERNAME, DB_PASSWORD

**Supabase** (Auth):
- SUPABASE_URL, SUPABASE_KEY, SUPABASE_JWT_SECRET

**Cache**:
- CACHE_DRIVER=file (dev) or redis (production)

**Session**:
- SESSION_DRIVER=database or redis

**Validation Rules**:
- All sensitive values (passwords, keys) MUST be in .env only (not committed)
- .env.example MUST document all required variables with placeholders
- Comments MUST explain each configuration's purpose

---

## File System Structure

### Directory Hierarchy

```
Root Level:
├── app/                     # Laravel application code (MVC)
├── packages/                # Monorepo packages (new)
│   ├── universo-types-srv/
│   └── universo-utils-srv/
├── resources/               # Frontend source (Vue components, styles)
├── public/                  # Web root with compiled assets
├── database/                # Migrations, seeders, factories
├── tests/                   # PHPUnit tests
├── .github/                 # GitHub configuration
│   └── instructions/        # Process documentation
└── specs/                   # Feature specifications
    └── 001-laravel-platform-setup/
```

**Validation Rules**:
- Build artifacts (vendor/, node_modules/, public/build/) MUST be in .gitignore
- Source code MUST be in version control
- Each directory MUST have clear, documented purpose
- Naming MUST follow Laravel and project conventions

---

## Junction Table Pattern (Reference)

For many-to-many relationships in future features:

```sql
CREATE TABLE entity1_entity2 (
    id UUID PRIMARY KEY,
    entity1_id UUID NOT NULL,
    entity2_id UUID NOT NULL,
    metadata JSONB,
    created_at TIMESTAMP,
    FOREIGN KEY (entity1_id) REFERENCES entity1(id) ON DELETE CASCADE,
    FOREIGN KEY (entity2_id) REFERENCES entity2(id) ON DELETE CASCADE,
    UNIQUE (entity1_id, entity2_id)  -- Prevent duplicates
);
```

**Pattern Rules**:
- Table name: {entity1_plural}_{entity2_plural} (alphabetical order)
- CASCADE delete: Remove relationships when parent deleted
- UNIQUE constraint: Prevent duplicate associations
- Metadata JSONB: Store relationship-specific data
- Timestamps: Track relationship creation

---

## Summary

This data model document establishes:

1. **Package Structure**: Clear organization for monorepo packages with validation rules
2. **Documentation Pattern**: Bilingual documentation with enforced parity
3. **GitHub Configuration**: Label taxonomy for issue management
4. **Three-Tier Pattern**: Reference architecture for future feature entities
5. **Configuration Management**: Environment variable organization
6. **File System**: Directory structure with validation rules

**Next Steps**: Generate API contracts for any setup endpoints, create quickstart guide, and update agent context with these patterns.
