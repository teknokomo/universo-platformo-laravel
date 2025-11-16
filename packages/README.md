# Universo Platformo Laravel - Packages

This directory contains all modular packages for Universo Platformo Laravel. Each package follows a consistent structure and naming convention to enable modular development and future extraction into separate repositories.

## Package Naming Convention

Packages are named according to their purpose and layer:

- **Frontend packages**: `{feature-name}-frt` (e.g., `clusters-frt`, `auth-frt`)
- **Backend packages**: `{feature-name}-srv` (e.g., `clusters-srv`, `auth-srv`)

## Package Structure

Each package must contain a `base/` subdirectory for its core implementation:

```
packages/
├── feature-name-frt/
│   ├── base/                     # Base frontend implementation
│   │   ├── resources/            # Views, components, assets
│   │   ├── routes/               # Frontend routes
│   │   └── composer.json         # Package dependencies
│   └── README.md                 # Package documentation
└── feature-name-srv/
    ├── base/                     # Base backend implementation
    │   ├── src/                  # PHP source code
    │   ├── routes/               # API routes
    │   ├── database/             # Migrations, seeds
    │   └── composer.json         # Package dependencies
    └── README.md                 # Package documentation
```

The `base/` subdirectory allows for future alternative implementations while maintaining backward compatibility.

## Package Categories

### Core Packages

**Authentication & Profiles**
- `auth-frt` / `auth-srv` - User authentication and session management
- `profile-frt` / `profile-srv` - User profiles and preferences

### Content Management

**Clusters** (Three-tier: Clusters → Domains → Resources)
- `clusters-frt` / `clusters-srv` - Cluster management functionality
- Organizational structure for resources

**Metaverses** (Three-tier: Metaverses → Sections → Entities)
- `metaverses-frt` / `metaverses-srv` - Virtual world management
- World creation and editing tools

**Uniks** (Extended hierarchy)
- `uniks-frt` / `uniks-srv` - User-created units/characters
- Character progression and inventory

### Advanced Features

**Spaces & Canvases**
- `spaces-frt` / `spaces-srv` - Visual programming spaces
- `space-builder-frt` / `space-builder-srv` - Node-based editor

**Publication**
- `publish-frt` / `publish-srv` - Content publishing system
- Version control and deployment

### Support Packages

**Utilities**
- `universo-types` - Shared type definitions
- `universo-utils` - Common utilities
- `universo-i18n` - Internationalization support
- `universo-api-client` - API client library

## Creating a New Package

1. **Create directory structure**
   ```bash
   mkdir -p packages/myfeature-frt/base
   mkdir -p packages/myfeature-srv/base
   ```

2. **Add package composer.json** (if the package has its own dependencies)
   ```json
   {
     "name": "universo/myfeature-frt",
     "type": "library",
     "require": {
       "php": "^8.2"
     },
     "autoload": {
       "psr-4": {
         "Universo\\MyFeature\\Frontend\\": "base/src/"
       }
     }
   }
   ```

3. **Register in root composer.json**
   ```json
   {
     "repositories": [
       {
         "type": "path",
         "url": "packages/myfeature-frt"
       },
       {
         "type": "path",
         "url": "packages/myfeature-srv"
       }
     ],
     "require": {
       "universo/myfeature-frt": "@dev",
       "universo/myfeature-srv": "@dev"
     }
   }
   ```

4. **Create bilingual documentation**
   - `packages/myfeature-frt/README.md` (English)
   - `packages/myfeature-frt/README.ru.md` (Russian)

## Package Development Guidelines

### Separation of Concerns

- **Frontend packages** (`-frt`): UI components, views, client-side logic, frontend assets
- **Backend packages** (`-srv`): API endpoints, business logic, data models, database migrations

### Dependencies

- Packages should declare their own dependencies in their `composer.json`
- Inter-package dependencies are allowed but should be minimized
- Use dependency injection for loose coupling

### Testing

- Each package should have its own test suite
- Tests located in package's `tests/` directory
- Run all package tests: `php artisan test`

### Documentation

- Every package must have README.md (English) and README.ru.md (Russian)
- Documentation must have identical structure and line count
- Include usage examples and API documentation

## Three-Tier Entity Pattern

Many packages follow a three-tier entity pattern, which provides a consistent hierarchical structure:

**Level 1 → Level 2 → Level 3**

Examples:
- **Clusters**: Clusters → Domains → Resources
- **Metaverses**: Metaverses → Sections → Entities
- **Organizations**: Organizations → Departments → Members

This pattern can be adapted with more or fewer levels depending on specific requirements.

## Package Lifecycle

1. **Development**: Packages are developed in `packages/` directory
2. **Integration**: Registered in root composer.json for monorepo development
3. **Extraction** (Future): Packages can be moved to separate repositories while maintaining compatibility
4. **Versioning**: Once extracted, packages follow semantic versioning

## Migration from React Version

When implementing features from [universo-platformo-react](https://github.com/teknokomo/universo-platformo-react):

1. Analyze the React implementation for core concepts
2. Adapt to Laravel/PHP best practices
3. Maintain the same feature boundaries and package structure
4. Do not replicate poor implementations - improve them
5. Keep documentation in sync between implementations

## Package Status

Current packages in development:

- [ ] `auth-frt` / `auth-srv` - Authentication (planned)
- [ ] `profile-frt` / `profile-srv` - User profiles (planned)
- [ ] `clusters-frt` / `clusters-srv` - Clusters (planned - first implementation)
- [ ] `metaverses-frt` / `metaverses-srv` - Metaverses (planned)
- [ ] `uniks-frt` / `uniks-srv` - Uniks (planned)
- [ ] `spaces-frt` / `spaces-srv` - Spaces (planned)
- [ ] `publish-frt` / `publish-srv` - Publication (planned)

## Resources

- [Laravel Package Development](https://laravel.com/docs/11.x/packages)
- [Composer Path Repositories](https://getcomposer.org/doc/05-repositories.md#path)
- [Universo Platformo React](https://github.com/teknokomo/universo-platformo-react) - Reference implementation

---

For questions about package development, see the main [README.md](../README.md) or contact the development team.
