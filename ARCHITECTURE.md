# Universo Platformo Laravel - Architecture

This document describes the architectural decisions, patterns, and structure of Universo Platformo Laravel.

## Table of Contents

- [Overview](#overview)
- [Monorepo Structure](#monorepo-structure)
- [Package Architecture](#package-architecture)
- [Three-Tier Entity Pattern](#three-tier-entity-pattern)
- [Database Architecture](#database-architecture)
- [Authentication and Authorization](#authentication-and-authorization)
- [API Design](#api-design)
- [Frontend Architecture](#frontend-architecture)
- [Testing Strategy](#testing-strategy)
- [Deployment](#deployment)

## Overview

Universo Platformo Laravel is built as a modular monorepo application using Laravel 11.x and PHP 8.2+. The architecture emphasizes:

- **Modularity**: Features are isolated in packages - **MANDATORY REQUIREMENT**
- **Scalability**: Packages can be extracted into separate repositories
- **Maintainability**: Clear separation of concerns
- **Testability**: Comprehensive test coverage
- **Flexibility**: Abstracted database layer for multi-provider support

### Critical Architectural Principle: Package-Based Modular Implementation

**ALL functionality** (except common startup files, root configuration, and build scripts) **MUST** be implemented within packages in the `packages/` directory. This is a **non-negotiable requirement**:

- ✅ **DO**: Create all features as packages in `packages/{feature}-frt/` and `packages/{feature}-srv/`
- ✅ **DO**: Separate frontend and backend into distinct packages
- ✅ **DO**: Include a `base/` directory in each package for the core implementation
- ❌ **DON'T**: Implement feature functionality directly in `app/`, `resources/`, or other root directories
- ❌ **DON'T**: Combine frontend and backend in a single package
- ❌ **DON'T**: Create features without the package structure

This modular structure is designed to support the long-term goal of extracting individual packages into separate repositories as the platform grows. Non-modular implementations violate the project constitution and will be rejected.

## Monorepo Structure

```
universo-platformo-laravel/
├── app/                          # Core Laravel application (MINIMAL - only base classes)
│   ├── Console/                  # Base console commands
│   ├── Exceptions/               # Base exception handlers
│   ├── Http/                     # HTTP layer (MINIMAL)
│   │   ├── Controllers/          # Base controllers (abstract classes only)
│   │   └── Middleware/           # Core HTTP middleware
│   ├── Models/                   # Base models (abstract classes only)
│   └── Providers/                # Core service providers
├── packages/                     # ⭐ ALL FEATURE PACKAGES GO HERE ⭐
│   ├── {feature}-frt/            # Frontend package
│   │   └── base/                 # Required base/ directory
│   ├── {feature}-srv/            # Backend package
│   │   └── base/                 # Required base/ directory
│   ├── universo-types-srv/       # Shared types (no -frt/-srv suffix)
│   └── universo-utils-srv/       # Shared utilities (no -frt/-srv suffix)
├── bootstrap/                    # Application bootstrap (Laravel core)
├── config/                       # Configuration files (Laravel core)
├── database/                     # Database files (MINIMAL - core migrations only)
│   ├── factories/                # Core model factories (package factories in packages/)
│   ├── migrations/               # Core migrations (package migrations in packages/)
│   └── seeders/                  # Core seeders (package seeders in packages/)
├── public/                       # Public assets (compiled output only)
├── resources/                    # Views and raw assets (MINIMAL - root app only)
│   ├── css/                      # Core stylesheets (package styles in packages/)
│   ├── js/                       # Core JavaScript (package JS in packages/)
│   └── views/                    # Base Blade templates (package views in packages/)
├── routes/                       # Route definitions (MINIMAL - core routes only)
│   ├── api.php                   # API routes (package routes in packages/)
│   ├── web.php                   # Web routes (package routes in packages/)
│   └── console.php               # Console routes
├── storage/                      # Storage files (Laravel core)
│   ├── app/                      # Application files
│   ├── framework/                # Framework files
│   └── logs/                     # Log files
└── tests/                        # Test files (core integration tests only)
    ├── Feature/                  # Core feature tests (package tests in packages/)
    └── Unit/                     # Core unit tests (package tests in packages/)
```

### Root Directory Usage Rules

**What MUST be in root directories** (MINIMAL usage only):
- `artisan` - Laravel CLI script
- `composer.json` - Root package manager with workspace configuration
- `package.json` - Frontend build configuration
- `vite.config.js` - Asset bundler configuration
- `.env` and `.env.example` - Environment configuration
- `app/` - ONLY abstract base classes, core middleware, and framework integration
- `bootstrap/` - Laravel bootstrap files (framework requirement)
- `config/` - Laravel configuration files
- `public/` - Compiled assets and entry point (index.php)
- `storage/` - Laravel runtime storage

**What MUST be in packages/** (ALL feature functionality):
- Controllers (feature-specific)
- Models (domain entities)
- Services (business logic)
- API Resources (response transformers)
- Form Requests (validation)
- Policies (authorization)
- Database migrations (feature-specific)
- Database factories and seeders (feature-specific)
- Routes (feature-specific)
- Views and components (feature-specific)
- Frontend JavaScript/Vue components (feature-specific)
- Tests (feature-specific)

**Key Principle**: If it relates to a specific feature or domain, it MUST be in a package. The root directories should contain ONLY what is absolutely necessary for framework bootstrapping and application-wide configuration.

### Why Monorepo?

- **Atomic changes**: Changes across packages can be committed together
- **Simplified dependency management**: All packages share the same dependencies
- **Easier refactoring**: Cross-package refactoring is straightforward
- **Unified tooling**: One set of tools for all packages
- **Future extraction**: Packages can still be extracted when needed

## Package Architecture

Each package follows a consistent structure that allows it to be self-contained yet integrated with the main application.

### Package Structure

**MANDATORY**: Every package MUST have a `base/` directory as the root of its implementation. This design accommodates future multiple implementations of the same conceptual package.

```
packages/feature-name-srv/
├── base/                         # ⭐ REQUIRED base implementation directory
│   ├── src/                      # PHP source code
│   │   ├── Controllers/          # HTTP controllers
│   │   ├── Models/               # Eloquent models
│   │   ├── Services/             # Business logic
│   │   ├── Requests/             # Form requests
│   │   ├── Resources/            # API resources
│   │   ├── Policies/             # Authorization policies
│   │   └── Providers/            # Service providers
│   ├── routes/                   # Package routes
│   │   └── api.php               # API routes
│   ├── database/                 # Database files
│   │   ├── migrations/           # Migrations
│   │   ├── factories/            # Factories
│   │   └── seeders/              # Seeders
│   ├── tests/                    # Package tests
│   │   ├── Feature/              # Feature tests
│   │   └── Unit/                 # Unit tests
│   ├── config/                   # Package configuration
│   └── composer.json             # Package dependencies
├── README.md                     # English documentation (REQUIRED)
└── README-RU.md                  # Russian documentation (REQUIRED)
```

**Frontend Package Structure** (`-frt`):
```
packages/feature-name-frt/
├── base/                         # ⭐ REQUIRED base implementation directory
│   ├── resources/                # Frontend resources
│   │   ├── js/                   # Vue.js components
│   │   │   ├── Components/       # Reusable components
│   │   │   ├── Pages/            # Page components (Inertia)
│   │   │   └── Composables/      # Vue composables
│   │   └── css/                  # Component styles
│   ├── routes/                   # Frontend routes (if applicable)
│   └── tests/                    # Frontend tests
│       └── Unit/                 # Component tests
├── README.md                     # English documentation (REQUIRED)
└── README-RU.md                  # Russian documentation (REQUIRED)
```

### Package Types

#### Backend Packages (`-srv`)

Backend packages handle:
- API endpoints
- Business logic
- Data persistence
- Background jobs
- Event handling

Example: `clusters-srv`, `auth-srv`, `metaverses-srv`

#### Frontend Packages (`-frt`)

Frontend packages handle:
- UI components
- Client-side routing
- State management
- User interactions

Example: `clusters-frt`, `auth-frt`, `metaverses-frt`

### Package Registration

Packages are registered in the root `composer.json`:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "packages/feature-srv"
    }
  ],
  "require": {
    "universo/feature-srv": "@dev"
  }
}
```

### Package Service Providers

Each package has a service provider that registers:
- Routes
- Views
- Migrations
- Commands
- Policies

## Reference Implementation Pattern

This Laravel implementation follows the architectural patterns established in [universo-platformo-react](https://github.com/teknokomo/universo-platformo-react). The React repository serves as the **conceptual reference** for:

- Package organization and naming conventions
- Modular monorepo structure with workspace packages
- Feature boundaries and domain separation
- Three-tier entity patterns (see below)
- Separation of frontend (-frt) and backend (-srv) packages
- Base directory structure for future implementations

**IMPORTANT**: 
- ✅ **DO**: Extract conceptual patterns and feature designs from the React version
- ✅ **DO**: Adapt patterns to Laravel/PHP best practices
- ✅ **DO**: Monitor the React repository for new features and implement equivalents
- ❌ **DON'T**: Replicate legacy code or incomplete implementations (particularly Flowise components)
- ❌ **DON'T**: Copy poor implementation details - improve them in Laravel version

The goal is to maintain feature parity and consistent architecture across both implementations while each uses best practices for its respective technology stack.

## Three-Tier Entity Pattern

Many features follow a three-tier hierarchical pattern:

```
Level 1 (Top)
└── Level 2 (Middle)
    └── Level 3 (Bottom)
```

### Examples

**Clusters**:
```
Cluster (Organization level)
└── Domain (Category level)
    └── Resource (Item level)
```

**Metaverses**:
```
Metaverse (World level)
└── Section (Area level)
    └── Entity (Object level)
```

### Database Schema for Three-Tier Pattern

```sql
-- Level 1: Parent entity
CREATE TABLE clusters (
    id BIGINT PRIMARY KEY,
    user_id BIGINT REFERENCES users(id),
    name VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Level 2: Middle entity
CREATE TABLE domains (
    id BIGINT PRIMARY KEY,
    cluster_id BIGINT REFERENCES clusters(id),
    name VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Level 3: Child entity
CREATE TABLE resources (
    id BIGINT PRIMARY KEY,
    domain_id BIGINT REFERENCES domains(id),
    name VARCHAR(255),
    type VARCHAR(50),
    data JSONB,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Model Relationships

```php
// Level 1 Model
class Cluster extends Model
{
    public function domains()
    {
        return $this->hasMany(Domain::class);
    }
    
    public function resources()
    {
        return $this->hasManyThrough(Resource::class, Domain::class);
    }
}

// Level 2 Model
class Domain extends Model
{
    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }
    
    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}

// Level 3 Model
class Resource extends Model
{
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
    
    public function cluster()
    {
        return $this->hasOneThrough(Cluster::class, Domain::class);
    }
}
```

## Database Architecture

### Primary Database: Supabase

Supabase (PostgreSQL) is the primary database provider. Configuration is in `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=your-project.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-password
```

### Database Abstraction

The application uses Laravel's database abstraction layer to remain database-agnostic:

- Use Eloquent ORM for queries
- Avoid raw SQL when possible
- Use database-agnostic features
- Test with multiple database providers

### Migration Strategy

Migrations are organized by package:

```
database/migrations/           # Core migrations
packages/clusters-srv/database/migrations/  # Cluster migrations
packages/auth-srv/database/migrations/      # Auth migrations
```

## Authentication and Authorization

### Laravel Passport

OAuth2 authentication is provided by Laravel Passport:

```php
// Install Passport
php artisan passport:install

// Configure in AuthServiceProvider
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
    }
}
```

### Supabase Integration

Supabase authentication is integrated with Laravel:

```php
// config/supabase.php
return [
    'url' => env('SUPABASE_URL'),
    'key' => env('SUPABASE_KEY'),
    'service_key' => env('SUPABASE_SERVICE_KEY'),
];
```

### Authorization

Policies define authorization logic:

```php
class ClusterPolicy
{
    public function view(User $user, Cluster $cluster): bool
    {
        return $user->id === $cluster->user_id;
    }
    
    public function update(User $user, Cluster $cluster): bool
    {
        return $user->id === $cluster->user_id;
    }
}
```

## API Design

### RESTful API Structure

```
GET    /api/v1/clusters              # List clusters
POST   /api/v1/clusters              # Create cluster
GET    /api/v1/clusters/{id}         # Get cluster
PUT    /api/v1/clusters/{id}         # Update cluster
DELETE /api/v1/clusters/{id}         # Delete cluster

GET    /api/v1/clusters/{id}/domains # List domains
POST   /api/v1/clusters/{id}/domains # Create domain
```

### API Resources

Laravel Resources transform models for API responses:

```php
class ClusterResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'domains_count' => $this->domains()->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
```

### API Versioning

APIs are versioned in the URL:

```
/api/v1/*  # Version 1
/api/v2/*  # Version 2 (future)
```

## Frontend Architecture

### Inertia.js with Vue.js 3 Integration

The frontend uses **Inertia.js** to bridge Laravel backend with Vue.js 3 frontend, creating a modern single-page application experience without building a separate API.

#### Architecture Overview

```
Laravel Backend                    Inertia.js                    Vue.js 3 Frontend
┌──────────────┐                  ┌─────────┐                 ┌─────────────┐
│  Controller  │  ───render()───> │ Inertia │  ────props───>  │  Vue Page   │
│              │                   │         │                 │  Component  │
│   Routes     │  <──Navigate───  │ Router  │  <──<Link>───   │             │
└──────────────┘                  └─────────┘                 └─────────────┘
```

**Key Benefits**:
- Server-side routing (no client-side router needed)
- Automatic code splitting per page
- Prefetching for instant navigation
- Shared data via props (no REST API needed for pages)
- Form handling with automatic error binding

#### Page Resolution from Packages

For modular architecture with packages, customize Inertia's page resolver:

```javascript
// resources/js/app.js
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({ components, directives })

createInertiaApp({
  resolve: (name) => {
    // Support both root pages and package pages
    const pages = import.meta.glob([
      './Pages/**/*.vue',
      '../../packages/*/base/resources/js/Pages/**/*.vue',
    ], { eager: true })
    
    return pages[`./Pages/${name}.vue`] 
      || pages[`../../packages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(vuetify)
      .mount(el)
  },
})
```

#### Data Flow Pattern

**Backend Controller** → **Inertia Render** → **Vue Component**

```php
// Backend: packages/clusters-srv/base/src/Controllers/ClusterController.php
namespace Universo\Clusters\Controllers;

use Inertia\Inertia;
use Universo\Clusters\Resources\ClusterResource;

class ClusterController extends Controller
{
    public function index()
    {
        return Inertia::render('Clusters/Index', [
            'clusters' => ClusterResource::collection(
                auth()->user()->clusters()
                    ->with('domains')
                    ->paginate(20)
            ),
            'filters' => request()->only(['search', 'sort']),
        ]);
    }
    
    public function show(Cluster $cluster)
    {
        $this->authorize('view', $cluster);
        
        return Inertia::render('Clusters/Show', [
            'cluster' => new ClusterResource($cluster->load('domains.resources')),
        ]);
    }
}
```

```vue
<!-- Frontend: packages/clusters-frt/base/resources/js/Pages/Index.vue -->
<template>
  <div>
    <h4 class="text-h4 mb-4">Clusters</h4>
    
    <v-text-field 
      label="Search" 
      v-model="searchQuery"
      @input="handleSearch"
    />
    
    <v-card v-for="cluster in clusters.data" :key="cluster.id" class="mb-4">
      <v-card-title>{{ cluster.name }}</v-card-title>
      <v-card-text>{{ cluster.description }}</v-card-text>
      <v-card-actions>
        <Link :href="`/clusters/${cluster.id}`">
          <v-btn color="primary">View Details</v-btn>
        </Link>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  clusters: Object,
  filters: Object
})

const searchQuery = ref(props.filters?.search || '')

const handleSearch = () => {
  router.get('/clusters', { search: searchQuery.value }, { 
    preserveState: true,
    replace: true 
  })
}
</script>
```

#### Form Handling with Inertia.js

Inertia.js provides a `useForm` composable for handling forms with automatic error binding:

```vue
<!-- Frontend: packages/clusters-frt/base/resources/js/Pages/Create.vue -->
<template>
  <form @submit.prevent="submit">
    <v-alert v-if="form.errors.general" type="error" class="mb-4">
      {{ form.errors.general }}
    </v-alert>
    
    <v-text-field
      label="Cluster Name"
      v-model="form.name"
      :error="!!form.errors.name"
      :error-messages="form.errors.name"
    />
    
    <v-textarea
      label="Description"
      v-model="form.description"
      :error="!!form.errors.description"
      :error-messages="form.errors.description"
      rows="4"
    />
    
    <v-btn 
      type="submit" 
      color="primary"
      :loading="form.processing"
    >
      Create Cluster
    </v-btn>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  description: '',
})

const submit = () => {
  form.post('/clusters')
}
</script>
```

**Backend Validation** returns automatically bound to form:

```php
class StoreClusterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ];
    }
}

// Controller
public function store(StoreClusterRequest $request)
{
    $cluster = auth()->user()->clusters()->create($request->validated());
    
    return redirect()->route('clusters.show', $cluster)
        ->with('success', 'Cluster created successfully');
}
```

### Vuetify 3 Integration

Vuetify 3 provides Vue.js components following Material Design 3 principles:

#### Vuetify Setup

```javascript
// resources/js/app.js
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import '@mdi/font/css/materialdesignicons.css'

const vuetify = createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: 'light',
    themes: {
      light: {
        colors: {
          primary: '#1976d2',
          secondary: '#dc004e',
        },
      },
    },
  },
})

createInertiaApp({
  // ... resolve config
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(vuetify)
      .mount(el)
  },
})
```

#### Vuetify Component Usage

**Layout Component with Vuetify**:

```vue
<!-- packages/universo-components-frt/base/resources/js/Layouts/AppLayout.vue -->
<template>
  <v-app>
    <v-app-bar color="primary">
      <v-toolbar-title>{{ title }}</v-toolbar-title>
    </v-app-bar>
    
    <v-navigation-drawer permanent>
      <v-list>
        <v-list-item :href="'/clusters'" title="Clusters">
          <template v-slot:prepend>
            <v-icon>mdi-folder-multiple</v-icon>
          </template>
        </v-list-item>
        <v-list-item :href="'/metaverses'" title="Metaverses">
          <template v-slot:prepend>
            <v-icon>mdi-earth</v-icon>
          </template>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    
    <v-main>
      <v-container>
        <slot />
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    default: 'Universo Platformo'
  }
})
</script>
```

#### Best Practices

- **Use Vuetify components consistently** across all frontend packages
- **Configure theme centrally** in shared package or root app
- **Leverage Vuetify's responsive utilities** for mobile-friendly UI
- **Use Material Design Icons** via `@mdi/font`
- **Follow Material Design 3 guidelines** for spacing, colors, typography
- **Integrate Vuetify with Inertia's Link component** for navigation

## Testing Strategy

### Test Types

1. **Unit Tests**: Test individual classes and methods
2. **Feature Tests**: Test HTTP endpoints and workflows
3. **Integration Tests**: Test package integration
4. **End-to-End Tests**: Test complete user flows

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

### Test Structure

```php
class ClusterControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_create_cluster(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->postJson('/api/v1/clusters', [
            'name' => 'Test Cluster',
            'description' => 'Test Description',
        ]);
        
        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'name']]);
            
        $this->assertDatabaseHas('clusters', [
            'name' => 'Test Cluster',
            'user_id' => $user->id,
        ]);
    }
}
```

## Deployment

### Environment Configuration

Different environments use different `.env` files:

- `.env` - Local development
- `.env.testing` - Test environment
- `.env.production` - Production environment

### Deployment Checklist

1. Update dependencies: `composer install --no-dev --optimize-autoloader`
2. Build assets: `npm run build`
3. Run migrations: `php artisan migrate --force`
4. Clear caches: `php artisan optimize`
5. Configure web server (Nginx/Apache)
6. Set up SSL certificates
7. Configure queue workers
8. Set up scheduled tasks

### Docker Support

Laravel Sail provides Docker support for development:

```bash
./vendor/bin/sail up
```

---

<details>
<summary>In Russian</summary>

# Universo Platformo Laravel - Архитектура

Этот документ описывает архитектурные решения, паттерны и структуру Universo Platformo Laravel.

## Содержание

- [Обзор](#обзор)
- [Структура монорепозитория](#структура-монорепозитория)
- [Архитектура пакетов](#архитектура-пакетов)
- [Паттерн трёхуровневых сущностей](#паттерн-трёхуровневых-сущностей)
- [Архитектура базы данных](#архитектура-базы-данных)
- [Аутентификация и авторизация](#аутентификация-и-авторизация)
- [Дизайн API](#дизайн-api)
- [Архитектура фронтенда](#архитектура-фронтенда)
- [Стратегия тестирования](#стратегия-тестирования)
- [Развёртывание](#развёртывание)

## Обзор

Universo Platformo Laravel построен как модульное монорепозиторное приложение с использованием Laravel 11.x и PHP 8.2+. Архитектура подчёркивает:

- **Модульность**: Функции изолированы в пакетах
- **Масштабируемость**: Пакеты могут быть извлечены в отдельные репозитории
- **Поддерживаемость**: Чёткое разделение ответственности
- **Тестируемость**: Комплексное покрытие тестами
- **Гибкость**: Абстрагированный слой базы данных для поддержки нескольких провайдеров

## Структура монорепозитория

```
universo-platformo-laravel/
├── app/                          # Основное приложение Laravel
│   ├── Console/                  # Консольные команды
│   ├── Exceptions/               # Обработчики исключений
│   ├── Http/                     # HTTP слой
│   │   ├── Controllers/          # Базовые контроллеры
│   │   └── Middleware/           # HTTP middleware
│   ├── Models/                   # Основные модели
│   └── Providers/                # Сервис-провайдеры
├── packages/                     # Пакеты функциональности
│   ├── {feature}-frt/            # Пакет фронтенда
│   └── {feature}-srv/            # Пакет бэкенда
├── bootstrap/                    # Загрузка приложения
├── config/                       # Файлы конфигурации
├── database/                     # Файлы базы данных
│   ├── factories/                # Фабрики моделей
│   ├── migrations/               # Миграции базы данных
│   └── seeders/                  # Наполнители базы данных
├── public/                       # Публичные ресурсы
├── resources/                    # Представления и исходные ресурсы
│   ├── css/                      # Таблицы стилей
│   ├── js/                       # JavaScript
│   └── views/                    # Шаблоны Blade
├── routes/                       # Определения маршрутов
│   ├── api.php                   # API маршруты
│   ├── web.php                   # Web маршруты
│   └── console.php               # Консольные маршруты
├── storage/                      # Файлы хранилища
│   ├── app/                      # Файлы приложения
│   ├── framework/                # Файлы фреймворка
│   └── logs/                     # Файлы логов
└── tests/                        # Тестовые файлы
    ├── Feature/                  # Feature тесты
    └── Unit/                     # Unit тесты
```

### Почему монорепозиторий?

- **Атомарные изменения**: Изменения в пакетах могут быть закоммичены вместе
- **Упрощённое управление зависимостями**: Все пакеты используют одни и те же зависимости
- **Более простой рефакторинг**: Рефакторинг между пакетами выполняется легко
- **Единый инструментарий**: Один набор инструментов для всех пакетов
- **Будущее извлечение**: Пакеты всё ещё могут быть извлечены при необходимости

## Архитектура пакетов

Каждый пакет следует последовательной структуре, которая позволяет ему быть автономным, но интегрированным с основным приложением.

### Структура пакета

```
packages/feature-name-srv/
├── base/                         # Базовая реализация
│   ├── src/                      # Исходный код PHP
│   │   ├── Controllers/          # HTTP контроллеры
│   │   ├── Models/               # Eloquent модели
│   │   ├── Services/             # Бизнес-логика
│   │   ├── Requests/             # Запросы форм
│   │   ├── Resources/            # API ресурсы
│   │   ├── Policies/             # Политики авторизации
│   │   └── Providers/            # Сервис-провайдеры
│   ├── routes/                   # Маршруты пакета
│   │   └── api.php               # API маршруты
│   ├── database/                 # Файлы базы данных
│   │   ├── migrations/           # Миграции
│   │   ├── factories/            # Фабрики
│   │   └── seeders/              # Наполнители
│   ├── tests/                    # Тесты пакета
│   │   ├── Feature/              # Feature тесты
│   │   └── Unit/                 # Unit тесты
│   ├── config/                   # Конфигурация пакета
│   └── composer.json             # Зависимости пакета
├── README.md                     # Документация на английском
└── README.ru.md                  # Документация на русском
```

### Типы пакетов

#### Пакеты бэкенда (`-srv`)

Пакеты бэкенда обрабатывают:
- API endpoints
- Бизнес-логику
- Сохранение данных
- Фоновые задачи
- Обработку событий

Пример: `clusters-srv`, `auth-srv`, `metaverses-srv`

#### Пакеты фронтенда (`-frt`)

Пакеты фронтенда обрабатывают:
- UI компоненты
- Маршрутизацию на стороне клиента
- Управление состоянием
- Взаимодействия пользователей

Пример: `clusters-frt`, `auth-frt`, `metaverses-frt`

### Регистрация пакетов

Пакеты регистрируются в корневом `composer.json`:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "packages/feature-srv"
    }
  ],
  "require": {
    "universo/feature-srv": "@dev"
  }
}
```

### Сервис-провайдеры пакетов

Каждый пакет имеет сервис-провайдер, который регистрирует:
- Маршруты
- Представления
- Миграции
- Команды
- Политики

## Паттерн трёхуровневых сущностей

Многие функции следуют трёхуровневому иерархическому паттерну:

```
Уровень 1 (Верхний)
└── Уровень 2 (Средний)
    └── Уровень 3 (Нижний)
```

### Примеры

**Кластеры**:
```
Кластер (Уровень организации)
└── Домен (Уровень категории)
    └── Ресурс (Уровень элемента)
```

**Метавселенные**:
```
Метавселенная (Уровень мира)
└── Секция (Уровень области)
    └── Сущность (Уровень объекта)
```

### Схема базы данных для трёхуровневого паттерна

```sql
-- Уровень 1: Родительская сущность
CREATE TABLE clusters (
    id BIGINT PRIMARY KEY,
    user_id BIGINT REFERENCES users(id),
    name VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Уровень 2: Средняя сущность
CREATE TABLE domains (
    id BIGINT PRIMARY KEY,
    cluster_id BIGINT REFERENCES clusters(id),
    name VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Уровень 3: Дочерняя сущность
CREATE TABLE resources (
    id BIGINT PRIMARY KEY,
    domain_id BIGINT REFERENCES domains(id),
    name VARCHAR(255),
    type VARCHAR(50),
    data JSONB,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Отношения моделей

```php
// Модель уровня 1
class Cluster extends Model
{
    public function domains()
    {
        return $this->hasMany(Domain::class);
    }
    
    public function resources()
    {
        return $this->hasManyThrough(Resource::class, Domain::class);
    }
}

// Модель уровня 2
class Domain extends Model
{
    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }
    
    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}

// Модель уровня 3
class Resource extends Model
{
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
    
    public function cluster()
    {
        return $this->hasOneThrough(Cluster::class, Domain::class);
    }
}
```

## Архитектура базы данных

### Основная база данных: Supabase

Supabase (PostgreSQL) является основным провайдером базы данных. Конфигурация в `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=your-project.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-password
```

### Абстракция базы данных

Приложение использует слой абстракции базы данных Laravel для независимости от базы данных:

- Используйте Eloquent ORM для запросов
- Избегайте сырого SQL, когда возможно
- Используйте независимые от базы данных функции
- Тестируйте с несколькими провайдерами баз данных

### Стратегия миграций

Миграции организованы по пакетам:

```
database/migrations/           # Основные миграции
packages/clusters-srv/database/migrations/  # Миграции кластеров
packages/auth-srv/database/migrations/      # Миграции аутентификации
```

## Аутентификация и авторизация

### Laravel Passport

OAuth2 аутентификация обеспечивается Laravel Passport:

```php
// Установка Passport
php artisan passport:install

// Конфигурация в AuthServiceProvider
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
    }
}
```

### Интеграция с Supabase

Аутентификация Supabase интегрирована с Laravel:

```php
// config/supabase.php
return [
    'url' => env('SUPABASE_URL'),
    'key' => env('SUPABASE_KEY'),
    'service_key' => env('SUPABASE_SERVICE_KEY'),
];
```

### Авторизация

Политики определяют логику авторизации:

```php
class ClusterPolicy
{
    public function view(User $user, Cluster $cluster): bool
    {
        return $user->id === $cluster->user_id;
    }
    
    public function update(User $user, Cluster $cluster): bool
    {
        return $user->id === $cluster->user_id;
    }
}
```

## Дизайн API

### Структура RESTful API

```
GET    /api/v1/clusters              # Список кластеров
POST   /api/v1/clusters              # Создать кластер
GET    /api/v1/clusters/{id}         # Получить кластер
PUT    /api/v1/clusters/{id}         # Обновить кластер
DELETE /api/v1/clusters/{id}         # Удалить кластер

GET    /api/v1/clusters/{id}/domains # Список доменов
POST   /api/v1/clusters/{id}/domains # Создать домен
```

### API ресурсы

Laravel Resources преобразуют модели для API ответов:

```php
class ClusterResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'domains_count' => $this->domains()->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
```

### Версионирование API

API версионируются в URL:

```
/api/v1/*  # Версия 1
/api/v2/*  # Версия 2 (будущее)
```

## Архитектура фронтенда

### Интеграция Inertia.js с Vue.js 3

Фронтенд использует **Inertia.js** для связи бэкенда Laravel с фронтендом Vue.js 3, создавая современное одностраничное приложение без построения отдельного API.

#### Обзор архитектуры

```
Бэкенд Laravel                    Inertia.js                    Фронтенд Vue.js 3
┌──────────────┐                  ┌─────────┐                 ┌─────────────┐
│ Контроллер   │  ───render()───> │ Inertia │  ────props───>  │   Vue.js    │
│              │                   │         │                 │  Страница   │
│  Маршруты    │  <──Navigate───  │ Router  │  <──<Link>───   │             │
└──────────────┘                  └─────────┘                 └─────────────┘
```

**Ключевые преимущества**:
- Серверная маршрутизация (не нужен клиентский роутер)
- Автоматическое разделение кода по страницам
- Предварительная загрузка для мгновенной навигации
- Общие данные через props (не нужен REST API для страниц)
- Обработка форм с автоматической привязкой ошибок

#### Разрешение страниц из пакетов

Для модульной архитектуры с пакетами настройте резолвер страниц Inertia:

```javascript
// resources/js/app.js
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({ components, directives })

createInertiaApp({
  resolve: (name) => {
    // Поддержка как корневых страниц, так и страниц пакетов
    const pages = import.meta.glob([
      './Pages/**/*.vue',
      '../../packages/*/base/resources/js/Pages/**/*.vue',
    ], { eager: true })
    
    return pages[`./Pages/${name}.vue`] 
      || pages[`../../packages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(vuetify)
      .mount(el)
  },
})
```

#### Паттерн потока данных

**Контроллер бэкенда** → **Рендер Inertia** → **Компонент Vue**

```php
// Бэкенд: packages/clusters-srv/base/src/Controllers/ClusterController.php
namespace Universo\Clusters\Controllers;

use Inertia\Inertia;
use Universo\Clusters\Resources\ClusterResource;

class ClusterController extends Controller
{
    public function index()
    {
        return Inertia::render('Clusters/Index', [
            'clusters' => ClusterResource::collection(
                auth()->user()->clusters()
                    ->with('domains')
                    ->paginate(20)
            ),
            'filters' => request()->only(['search', 'sort']),
        ]);
    }
    
    public function show(Cluster $cluster)
    {
        $this->authorize('view', $cluster);
        
        return Inertia::render('Clusters/Show', [
            'cluster' => new ClusterResource($cluster->load('domains.resources')),
        ]);
    }
}
```

```vue
<!-- Фронтенд: packages/clusters-frt/base/resources/js/Pages/Index.vue -->
<template>
  <div>
    <h4 class="text-h4 mb-4">Кластеры</h4>
    
    <v-text-field 
      label="Поиск" 
      v-model="searchQuery"
      @input="handleSearch"
    />
    
    <v-card v-for="cluster in clusters.data" :key="cluster.id" class="mb-4">
      <v-card-title>{{ cluster.name }}</v-card-title>
      <v-card-text>{{ cluster.description }}</v-card-text>
      <v-card-actions>
        <Link :href="`/clusters/${cluster.id}`">
          <v-btn color="primary">Подробнее</v-btn>
        </Link>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  clusters: Object,
  filters: Object
})

const searchQuery = ref(props.filters?.search || '')

const handleSearch = () => {
  router.get('/clusters', { search: searchQuery.value }, { 
    preserveState: true,
    replace: true 
  })
}
</script>
```

#### Обработка форм с Inertia.js

Inertia.js предоставляет composable `useForm` для обработки форм с автоматической привязкой ошибок:

```vue
<!-- Фронтенд: packages/clusters-frt/base/resources/js/Pages/Create.vue -->
<template>
  <form @submit.prevent="submit">
    <v-alert v-if="form.errors.general" type="error" class="mb-4">
      {{ form.errors.general }}
    </v-alert>
    
    <v-text-field
      label="Название кластера"
      v-model="form.name"
      :error="!!form.errors.name"
      :error-messages="form.errors.name"
    />
    
    <v-textarea
      label="Описание"
      v-model="form.description"
      :error="!!form.errors.description"
      :error-messages="form.errors.description"
      rows="4"
    />
    
    <v-btn 
      type="submit" 
      color="primary"
      :loading="form.processing"
    >
      Создать кластер
    </v-btn>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  description: '',
})

const submit = () => {
  form.post('/clusters')
}
</script>
```

**Валидация бэкенда** автоматически привязывается к форме:

```php
class StoreClusterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ];
    }
}

// Контроллер
public function store(StoreClusterRequest $request)
{
    $cluster = auth()->user()->clusters()->create($request->validated());
    
    return redirect()->route('clusters.show', $cluster)
        ->with('success', 'Кластер успешно создан');
}
```

### Интеграция Vuetify 3

Vuetify 3 предоставляет компоненты Vue.js, следующие принципам Material Design 3:

#### Настройка Vuetify

```javascript
// resources/js/app.js
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import '@mdi/font/css/materialdesignicons.css'

const vuetify = createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: 'light',
    themes: {
      light: {
        colors: {
          primary: '#1976d2',
          secondary: '#dc004e',
        },
      },
    },
  },
})

createInertiaApp({
  // ... resolve настройки
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(vuetify)
      .mount(el)
  },
})
```

#### Использование компонентов Vuetify

**Компонент макета с Vuetify**:

```vue
<!-- packages/universo-components-frt/base/resources/js/Layouts/AppLayout.vue -->
<template>
  <v-app>
    <v-app-bar color="primary">
      <v-toolbar-title>{{ title }}</v-toolbar-title>
    </v-app-bar>
    
    <v-navigation-drawer permanent>
      <v-list>
        <v-list-item :href="'/clusters'" title="Кластеры">
          <template v-slot:prepend>
            <v-icon>mdi-folder-multiple</v-icon>
          </template>
        </v-list-item>
        <v-list-item :href="'/metaverses'" title="Метавселенные">
          <template v-slot:prepend>
            <v-icon>mdi-earth</v-icon>
          </template>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    
    <v-main>
      <v-container>
        <slot />
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    default: 'Universo Platformo'
  }
})
</script>
```

#### Лучшие практики

- **Используйте компоненты Vuetify последовательно** во всех пакетах фронтенда
- **Настройте тему централизованно** в общем пакете или корневом приложении
- **Используйте адаптивные утилиты Vuetify** для мобильного UI
- **Используйте иконки Material Design** через `@mdi/font`
- **Следуйте руководствам Material Design 3** для отступов, цветов, типографики
- **Интегрируйте Vuetify с компонентом Link Inertia** для навигации

## Стратегия тестирования

### Типы тестов

1. **Unit тесты**: Тестирование отдельных классов и методов
2. **Feature тесты**: Тестирование HTTP endpoints и рабочих процессов
3. **Интеграционные тесты**: Тестирование интеграции пакетов
4. **End-to-End тесты**: Тестирование полных пользовательских потоков

### Запуск тестов

```bash
# Запустить все тесты
php artisan test

# Запустить конкретный набор тестов
php artisan test --testsuite=Feature

# Запустить с покрытием
php artisan test --coverage
```

### Структура тестов

```php
class ClusterControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_create_cluster(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->postJson('/api/v1/clusters', [
            'name' => 'Test Cluster',
            'description' => 'Test Description',
        ]);
        
        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'name']]);
            
        $this->assertDatabaseHas('clusters', [
            'name' => 'Test Cluster',
            'user_id' => $user->id,
        ]);
    }
}
```

## Развёртывание

### Конфигурация окружения

Различные окружения используют разные файлы `.env`:

- `.env` - Локальная разработка
- `.env.testing` - Тестовое окружение
- `.env.production` - Производственное окружение

### Чек-лист развёртывания

1. Обновление зависимостей: `composer install --no-dev --optimize-autoloader`
2. Сборка ресурсов: `npm run build`
3. Запуск миграций: `php artisan migrate --force`
4. Очистка кэшей: `php artisan optimize`
5. Настройка веб-сервера (Nginx/Apache)
6. Настройка SSL сертификатов
7. Настройка обработчиков очередей
8. Настройка запланированных задач

### Поддержка Docker

Laravel Sail обеспечивает поддержку Docker для разработки:

```bash
./vendor/bin/sail up
```
</details>
