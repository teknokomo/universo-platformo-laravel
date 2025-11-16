# Universo Platformo Laravel

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com/)

**Implementation of Universo Platformo / Universo MMOOMM / Universo Kiberplano built on Laravel and related PHP technologies.**

> **Note:** This is a Laravel/PHP implementation of Universo Platformo. For the React/TypeScript version, see [universo-platformo-react](https://github.com/teknokomo/universo-platformo-react).

Universo Platformo Laravel is a comprehensive platform for creating multi-user 3D/AR/VR experiences, metaverses, and interactive digital worlds. This implementation leverages Laravel's robust ecosystem and PHP's mature tooling to provide a scalable, maintainable backend infrastructure with a modern frontend approach.

**Universo Platformo serves as the foundation for implementing Universo Kiberplano** - a global planning and implementation system that unifies plans, tasks, and resources while controlling robots. This system aims to create a comprehensive framework for worldwide coordination of efforts, optimizing resource allocation, and enabling efficient automation through robotic systems, all within a unified digital environment.

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

- **Monorepo Architecture**: Organized package structure for modular development
- **Package Management**: Composer-based dependency management with workspace support
- **Database Integration**: Supabase as primary database with abstraction for future expansion
- **Authentication System**: Laravel Passport for OAuth2 authentication
- **Material Design UI**: Modern, consistent user interface components
- **Bilingual Documentation**: Complete English and Russian documentation
- **Modular Structure**: Packages organized by functionality with clear separation of concerns

## Current Status

**Project Phase**: Initial Setup and Foundation Development

**Primary Focus**:
- Repository structure establishment
- Core package architecture
- Database and authentication configuration
- Documentation framework
- Development workflow setup

## Technology Stack

### Backend
- PHP 8.2+
- Laravel 11.x
- Laravel Passport (OAuth2 Authentication)
- Composer for dependency management

### Database
- Supabase (Primary)
- Abstracted for future multi-database support

### Frontend (Planned)
- Material Design components
- Modern JavaScript framework integration
- Responsive design with mobile-first approach

### Development Tools
- Composer for PHP packages
- PHPUnit for testing
- Laravel Sail for Docker development (optional)

## Project Structure

```
universo-platformo-laravel/
├── packages/                      # All feature packages
│   ├── clusters-frt/              # Clusters frontend package
│   │   └── base/                  # Base implementation
│   ├── clusters-srv/              # Clusters backend package
│   │   └── base/                  # Base implementation
│   ├── metaverses-frt/            # Metaverses frontend package
│   │   └── base/                  # Base implementation
│   ├── metaverses-srv/            # Metaverses backend package
│   │   └── base/                  # Base implementation
│   └── [other packages]/          # Additional feature packages
├── app/                           # Laravel application core
├── config/                        # Configuration files
├── database/                      # Database migrations and seeds
├── routes/                        # Route definitions
├── resources/                     # Views, assets, and localization
├── storage/                       # Application storage
├── tests/                         # Test suites
├── .env.example                   # Environment configuration template
├── composer.json                  # PHP dependencies
├── artisan                        # Laravel CLI
└── README.md                      # This file
```

### Package Naming Convention

Packages follow a consistent naming pattern:

- **Frontend packages**: `{feature-name}-frt` (e.g., `clusters-frt`, `auth-frt`)
- **Backend packages**: `{feature-name}-srv` (e.g., `clusters-srv`, `auth-srv`)

Each package contains a `base/` subdirectory for its core implementation, allowing for future alternative implementations if needed.

## Core Functional Areas

### Clusters Management
A three-tier entity system for organizing resources:
- **Clusters**: Top-level organizational units
- **Domains**: Mid-level groupings within clusters
- **Resources**: Individual items within domains

### Metaverses
Virtual world creation and management:
- **Metaverses**: Virtual world instances
- **Sections**: Areas within metaverses
- **Entities**: Objects and components within sections

### Uniks (Units/Characters)
User-created entities with extended hierarchies:
- Character management
- Inventory systems
- Progression tracking

### Spaces and Canvases
Visual programming and scene creation:
- Node-based graph systems
- LangChain integration
- UPDL (Universal Platform Description Language) nodes
- Scene composition tools

### Authentication and Profiles
User management and identity:
- OAuth2 authentication via Laravel Passport
- User profiles and preferences
- Role-based access control
- Supabase integration

### Publication System
Content deployment and sharing:
- Project publishing workflows
- Version control
- Access management

## Getting Started

### Prerequisites

- PHP 8.2 or higher
- Composer 2.x
- MySQL/PostgreSQL (or Supabase account)
- Node.js and NPM (for frontend assets)

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

3. **Set up environment variables**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database connection**

Edit `.env` file with your Supabase credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=your-project.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-password
```

5. **Run migrations**

```bash
php artisan migrate
```

6. **Install Laravel Passport**

```bash
php artisan passport:install
```

7. **Start development server**

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
   - Create `README.ru.md` in Russian (identical structure)
   - Keep line counts and sections synchronized

3. **Follow Laravel best practices**
   - Use proper MVC structure
   - Implement service layer for business logic
   - Write tests for new features
   - Follow PSR-12 coding standards

## Cross-Platform Implementations

Universo Platformo is being developed on multiple technology stacks:

- **Laravel**: This repository implements Universo Platformo on Laravel and PHP (current)
- **React**: [Universo Platformo React](https://github.com/teknokomo/universo-platformo-react) - React and TypeScript implementation
- **Godot**: [Universo Platformo Godot](https://github.com/teknokomo/universo-platformo-godot) - Godot engine implementation
- **PlayCanvas**: [Universo Platformo Nebulo](https://github.com/teknokomo/universo-platformo-nebulo) - PlayCanvas engine implementation

Each implementation shares the same core concepts and goals while leveraging the strengths of its respective technology stack.

## Contributing

We welcome contributions! Please follow these guidelines:

1. **Create Issues**: Use GitHub Issues with bilingual content (English with Russian in spoiler tags)
2. **Follow Guidelines**: See `.github/instructions/` for detailed guidelines:
   - `github-issues.md` - Issue creation guidelines
   - `github-pr.md` - Pull request guidelines
   - `github-labels.md` - Label usage guidelines
   - `i18n-docs.md` - Bilingual documentation guidelines

3. **Code Standards**: Follow Laravel conventions and PSR-12 coding standards
4. **Testing**: Write tests for new features
5. **Documentation**: Update documentation for any changes

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- Built with [Laravel](https://laravel.com/)
- Database powered by [Supabase](https://supabase.com/)
- Inspired by [Universo Platformo React](https://github.com/teknokomo/universo-platformo-react)
- Material Design components for consistent UI

## Roadmap

- [x] Repository structure and foundation
- [ ] Core package architecture implementation
- [ ] Clusters functionality (first three-tier entity system)
- [ ] Authentication system with Supabase integration
- [ ] Material Design UI components integration
- [ ] Metaverses functionality
- [ ] Uniks (Units) functionality
- [ ] Spaces and Canvases with node system
- [ ] Publication system
- [ ] Multi-language support (i18n)
- [ ] API documentation
- [ ] Comprehensive test coverage

---

**Note**: This project is in active development. The structure and features are being continuously refined based on the reference implementation at [universo-platformo-react](https://github.com/teknokomo/universo-platformo-react) while following Laravel best practices.
