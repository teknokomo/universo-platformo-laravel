# Universo Platformo Laravel

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com/)
[![Vue.js](https://img.shields.io/badge/Vue-3.x-brightgreen.svg)](https://vuejs.org/)

**Implementation of Universo Platformo / Universo MMOOMM / Universo Kiberplano built on Laravel and related PHP technologies.**

> **Note:** This is a Laravel/PHP implementation of Universo Platformo. For the React/TypeScript version, see [universo-platformo-react](https://github.com/teknokomo/universo-platformo-react).

Universo Platformo Laravel is a comprehensive platform for creating multi-user 3D/AR/VR experiences, metaverses, and interactive digital worlds. This implementation leverages Laravel's robust ecosystem and PHP's mature tooling to provide a scalable, maintainable backend with a Vue.js-powered single-page application frontend.

**Universo Platformo serves as the foundation for implementing Universo Kiberplano** — a global planning and implementation system that unifies plans, tasks, and resources while controlling robots. This system aims to create a comprehensive framework for worldwide coordination of efforts, optimizing resource allocation, and enabling efficient automation through robotic systems, all within a unified digital environment.

## Vision and Purpose

Our wonderful project, which will help create a global teknokomization and save humanity from final enslavement and total destruction, is currently in early development stage. We are implementing a Laravel-based version of Universo Platformo that will serve as a foundation for creating interactive 3D/AR/VR experiences with enterprise-grade reliability and scalability.

More details about all this are written in "The Book of The Future" and various other materials of ours, most of which are still poorly structured and not in English, but right now work is underway to create new detailed documentation, which will be presented in many languages.

## Contact Information

For questions or collaboration, please contact:

- VK: [https://vk.com/vladimirlevadnij](https://vk.com/vladimirlevadnij)
- Telegram: [https://t.me/Vladimir_Levadnij](https://t.me/Vladimir_Levadnij)
- Email: [universo.pro@yandex.com](mailto:universo.pro@yandex.com)

Our website: [https://universo.pro](https://universo.pro)

## Overview

Universo Platformo Laravel provides:

- **Monorepo Architecture**: Organized package structure with `-frt` (frontend) and `-srv` (backend) packages
- **Vue.js SPA Frontend**: Modern single-page application built with Vue 3 and Vite
- **Secure Supabase Auth Proxy**: Supabase authentication handled server-side — the browser never communicates with Supabase directly
- **Session-Based Auth**: Laravel server-side sessions store auth tokens securely using httpOnly cookies
- **Start Pages**: Guest landing page and authenticated onboarding wizard
- **Bilingual Documentation**: Complete English and Russian documentation
- **Modular Structure**: Packages organized by functionality with clear separation of concerns

## Current Status

**Project Phase**: Start Pages and Authentication Implementation

**Implemented Features**:
- Guest start page (landing page for unauthenticated visitors)
- Authentication page (sign-in and sign-up forms)
- Supabase authentication proxy via Laravel backend
- Authenticated start page with multi-step onboarding wizard
- Server-side session management; raw tokens never reach the browser

## Technology Stack

### Backend
- PHP 8.2+
- Laravel 11.x
- Guzzle HTTP Client (Supabase Auth API proxy)
- Laravel Session (server-side auth token storage via httpOnly cookies)
- Composer for dependency management

### Frontend
- Vue.js 3.x (Composition API, `<script setup>`)
- Vite (asset bundling with hot reload via laravel-vite-plugin)
- Axios (HTTP client, automatically sends CSRF token)
- Custom CSS (scoped component styles, no external UI framework)

### Authentication
- Supabase Auth REST API (proxied exclusively via Laravel backend)
- Laravel session driver (httpOnly cookie, server-side token storage)
- CSRF protection on all state-changing requests

### Database
- Supabase (PostgreSQL, primary database)
- Abstracted for future multi-database support

### Development Tools
- Composer for PHP packages
- Node.js / NPM for frontend assets
- PHPUnit for backend testing
- Laravel Sail for Docker development (optional)

## Project Structure

```
universo-platformo-laravel/
├── packages/                          # All feature packages
│   ├── start-frt/                     # Start pages frontend package
│   │   ├── base/resources/js/
│   │   │   ├── App.vue                # Root component; auth provider
│   │   │   ├── Pages/                 # GuestStartPage, AuthPage, etc.
│   │   │   ├── Components/            # AppAppBar, Hero, Testimonials, etc.
│   │   │   ├── Composables/           # useAuth.js, useI18n.js
│   │   │   └── api/                   # onboarding.js API client
│   │   ├── README.md
│   │   └── README-RU.md
│   └── start-srv/                     # Start pages backend package
│       ├── base/src/
│       │   ├── Controllers/           # AuthController
│       │   ├── Services/              # SupabaseAuthService
│       │   └── Providers/             # StartServiceProvider
│       ├── README.md
│       └── README-RU.md
├── resources/
│   ├── css/app.css                    # Global styles
│   ├── js/app.js                      # Vue application entry point
│   ├── lang/en/landing.php            # Translation strings (i18n)
│   └── views/welcome.blade.php        # SPA HTML shell with CSRF + i18n
├── routes/
│   ├── web.php                        # Catch-all SPA route
│   └── api.php                        # Root API routes file
├── config/services.php                # Supabase credentials config
├── tests/                             # Backend test suites
├── .env.example                       # Environment configuration template
├── composer.json                      # PHP dependencies
├── package.json                       # Node.js dependencies
├── vite.config.js                     # Vite + Vue plugin configuration
└── README.md                          # This file
```

### Package Naming Convention

Packages follow a consistent naming pattern:

- **Frontend packages**: `{feature-name}-frt` (e.g., `start-frt`, `clusters-frt`)
- **Backend packages**: `{feature-name}-srv` (e.g., `start-srv`, `clusters-srv`)

Each package contains a `base/` subdirectory for its core implementation, allowing for future alternative implementations if needed.

## Authentication Architecture

All Supabase communication happens exclusively through the Laravel backend. The browser never holds Supabase credentials or raw auth tokens.

```
Browser (Vue SPA)        Laravel Backend           Supabase
      │                        │                      │
      │  POST /api/v1/auth/login│                      │
      │ ──────────────────────► │                      │
      │                        │  POST /auth/v1/token  │
      │                        │ ────────────────────► │
      │                        │ ◄──────────────────── │
      │                        │   tokens + user data  │
      │                        │                      │
      │                        │  Store in session     │
      │                        │  (httpOnly cookie)    │
      │                        │                      │
      │  {user, authenticated} │                      │
      │ ◄────────────────────── │                      │
```

Raw Supabase tokens are stored in the Laravel session and never sent to the browser. The frontend only receives sanitized user data and an `authenticated` boolean flag.

## API Endpoints

All auth routes are prefixed with `/api/v1/auth`.

| Method | Path                    | Description                         |
|--------|-------------------------|-------------------------------------|
| `POST` | `/api/v1/auth/login`    | Sign in with email and password     |
| `POST` | `/api/v1/auth/register` | Register a new account              |
| `POST` | `/api/v1/auth/logout`   | Sign out and clear session          |
| `GET`  | `/api/v1/auth/user`     | Get current authenticated user     |
| `POST` | `/api/v1/auth/refresh`  | Refresh session (used internally)   |

## Getting Started

### Prerequisites

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18+ and NPM
- A Supabase project (for authentication features)

### Installation

1. **Clone the repository**

```bash
git clone https://github.com/teknokomo/universo-platformo-laravel.git
cd universo-platformo-laravel
```

2. **Install PHP dependencies**

```bash
composer install
```

3. **Install Node.js dependencies**

```bash
npm install
```

4. **Set up environment variables**

```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure Supabase credentials**

Edit `.env` with your Supabase project credentials:

```env
SUPABASE_URL=https://your-project.supabase.co
SUPABASE_KEY=your-supabase-anon-key
SUPABASE_SERVICE_KEY=your-supabase-service-key
```

6. **Build frontend assets**

```bash
npm run build
```

7. **Start the development server**

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

### Development Workflow

1. **Create a new package**
   - Create directory in `packages/` following naming convention
   - Add `base/` subdirectory for implementation
   - Create package `composer.json` if needed
   - Register package in root `composer.json`

2. **Add bilingual documentation**
   - Create `README.md` in English
   - Create `README-RU.md` in Russian (identical structure)
   - Keep line counts and sections synchronized

3. **Follow Laravel best practices**
   - Use proper MVC structure with a service layer
   - Implement business logic in dedicated service classes
   - Write PHPUnit tests for new features
   - Follow PSR-12 coding standards

## Cross-Platform Implementations

Universo Platformo is being developed on multiple technology stacks:

- **Laravel**: This repository implements Universo Platformo on Laravel and PHP (current)
- **React**: [Universo Platformo React](https://github.com/teknokomo/universo-platformo-react) — React and TypeScript implementation
- **Godot**: [Universo Platformo Godot](https://github.com/teknokomo/universo-platformo-godot) — Godot engine implementation
- **PlayCanvas**: [Universo Platformo Nebulo](https://github.com/teknokomo/universo-platformo-nebulo) — PlayCanvas engine implementation

Each implementation shares the same core concepts and goals while leveraging the strengths of its respective technology stack.

## Contributing

We welcome contributions! Please follow these guidelines:

1. **Create Issues**: Use GitHub Issues with bilingual content (English with Russian in spoiler tags)
2. **Follow Guidelines**: See `.github/instructions/` for detailed guidelines:
   - `github-issues.md` — Issue creation guidelines
   - `github-pr.md` — Pull request guidelines
   - `github-labels.md` — Label usage guidelines
   - `i18n-docs.md` — Bilingual documentation guidelines

3. **Code Standards**: Follow Laravel conventions and PSR-12 coding standards
4. **Testing**: Write tests for new features
5. **Documentation**: Update documentation for any changes

## License

This project is licensed under the MIT License — see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- Built with [Laravel](https://laravel.com/) and [Vue.js](https://vuejs.org/)
- Authentication via [Supabase](https://supabase.com/) (proxied server-side)
- Inspired by [Universo Platformo React](https://github.com/teknokomo/universo-platformo-react)
- Assets bundled with [Vite](https://vitejs.dev/)

## Roadmap

- [x] Repository structure and foundation
- [x] Start pages: guest landing page, auth page, authenticated onboarding
- [x] Supabase authentication proxy via Laravel backend
- [x] Vue.js SPA with server-side session auth (tokens never reach the browser)
- [ ] Clusters functionality (three-tier entity system)
- [ ] Metaverses functionality
- [ ] Uniks (Units) functionality
- [ ] Spaces and Canvases with node system
- [ ] Publication system
- [ ] Multi-language support expansion
- [ ] API documentation
- [ ] Comprehensive test coverage

---

**Note**: This project is in active development. The structure and features are being continuously refined based on the reference implementation at [universo-platformo-react](https://github.com/teknokomo/universo-platformo-react) while following Laravel best practices.
