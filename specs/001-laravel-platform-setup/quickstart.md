# Quickstart Guide: Universo Platformo Laravel Setup

**Feature**: 001-laravel-platform-setup  
**Date**: 2025-11-17  
**Audience**: Developers setting up the project for the first time

## Prerequisites

Before starting, ensure you have the following installed:

### Required Software

- **PHP**: Version 8.2 or higher
  - Check: `php --version`
  - Install: [php.net](https://www.php.net/downloads)
  
- **Composer**: Latest stable version
  - Check: `composer --version`
  - Install: [getcomposer.org](https://getcomposer.org/download/)
  
- **Node.js**: Version 18.x or higher (for frontend builds)
  - Check: `node --version`
  - Install: [nodejs.org](https://nodejs.org/)
  
- **NPM**: Version 9.x or higher (comes with Node.js)
  - Check: `npm --version`

### Recommended Software

- **Git**: For version control
- **PostgreSQL Client**: For direct database access (optional)
- **Redis**: For production-like caching (optional for development)
- **GitHub CLI**: For automated label creation (optional)

### PHP Extensions Required

Verify these PHP extensions are installed:
- `pdo_pgsql` (PostgreSQL database)
- `mbstring` (String handling)
- `xml` (XML parsing)
- `curl` (HTTP requests)
- `zip` (Archive handling)
- `gd` or `imagick` (Image processing)

Check installed extensions: `php -m`

---

## Initial Setup

### 1. Clone the Repository

```bash
git clone https://github.com/teknokomo/universo-platformo-laravel.git
cd universo-platformo-laravel
```

### 2. Install PHP Dependencies

```bash
composer install
```

This installs:
- Laravel framework and dependencies
- Laravel Sanctum for authentication
- Supabase PHP client
- Development tools (PHPUnit, Laravel Pint)

### 3. Install JavaScript Dependencies

```bash
npm install
```

This installs:
- Vue.js and Inertia.js
- Vuetify (Material Design components)
- Vite (build tool)
- Development dependencies

### 4. Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 5. Configure Database (Supabase)

Edit `.env` file with your Supabase credentials:

```env
# Database Configuration
DB_CONNECTION=pgsql
DB_HOST=db.YOUR_SUPABASE_PROJECT.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your_supabase_password

# Supabase Configuration
SUPABASE_URL=https://YOUR_SUPABASE_PROJECT.supabase.co
SUPABASE_KEY=your_supabase_anon_key
SUPABASE_JWT_SECRET=your_jwt_secret
```

**Getting Supabase Credentials**:
1. Go to [supabase.com](https://supabase.com)
2. Create or select your project
3. Go to Settings → Database for DB credentials
4. Go to Settings → API for SUPABASE_URL and SUPABASE_KEY

### 6. Run Database Migrations

```bash
php artisan migrate
```

This creates necessary database tables.

### 7. Build Frontend Assets

For development (with hot reload):

```bash
npm run dev
```

For production build:

```bash
npm run build
```

### 8. Start Development Server

In a separate terminal:

```bash
php artisan serve
```

Application will be available at: `http://localhost:8000`

---

## Verification Steps

### Check Application Health

Visit the health check endpoint:

```bash
curl http://localhost:8000/api/health
```

Expected response:
```json
{
  "data": {
    "status": "healthy",
    "version": "1.0.0",
    "timestamp": "2025-11-17T06:30:00Z",
    "services": {
      "database": "connected",
      "cache": "connected"
    }
  }
}
```

### Check Database Connection

```bash
php artisan db:show
```

Should display your database connection details.

### Run Tests

```bash
php artisan test
```

All tests should pass.

### Check Code Style

```bash
./vendor/bin/pint --test
```

Should report no styling issues.

---

## Development Workflow

### Running Development Servers

You'll typically need two terminal windows:

**Terminal 1 - Laravel Server**:
```bash
php artisan serve
```

**Terminal 2 - Frontend Dev Server**:
```bash
npm run dev
```

### Database Operations

**Create migration**:
```bash
php artisan make:migration create_table_name
```

**Run migrations**:
```bash
php artisan migrate
```

**Rollback migrations**:
```bash
php artisan migrate:rollback
```

**Fresh database (WARNING: destroys data)**:
```bash
php artisan migrate:fresh
```

### Code Quality

**Format code with Laravel Pint**:
```bash
./vendor/bin/pint
```

**Run tests**:
```bash
php artisan test
```

**Run specific test**:
```bash
php artisan test --filter=TestName
```

---

## Creating Your First Package

### 1. Create Package Directory

```bash
mkdir -p packages/example-srv/base/src
mkdir -p packages/example-srv/base/tests
```

### 2. Create Package composer.json

`packages/example-srv/composer.json`:

```json
{
    "name": "universo/example-srv",
    "description": "Example backend package",
    "type": "library",
    "require": {
        "php": "^8.2"
    },
    "autoload": {
        "psr-4": {
            "Universo\\Example\\": "base/src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Universo\\Example\\Tests\\": "base/tests/"
        }
    }
}
```

### 3. Register Package in Root composer.json

Add to `repositories` array:

```json
{
    "type": "path",
    "url": "./packages/example-srv"
}
```

Add to `require` section:

```json
"universo/example-srv": "*"
```

### 4. Update Dependencies

```bash
composer update universo/example-srv
```

### 5. Create Bilingual README Files

`packages/example-srv/README.md` (English)
`packages/example-srv/README-RU.md` (Russian - identical structure)

---

## GitHub Configuration

### Creating Required Labels

#### Option 1: Manual Creation

Go to repository Settings → Labels and create:

**Type Labels**:
- `bug` (color: #d73a4a) - Something isn't working
- `feature` (color: #0075ca) - New feature or request
- `enhancement` (color: #a2eeef) - Improvement to existing feature
- `documentation` (color: #0075ca) - Documentation updates

**Area Labels**:
- `frontend` (color: #d4c5f9) - Frontend-related changes
- `backend` (color: #c5def5) - Backend-related changes
- `platformo` (color: #fbca04) - Universo Platformo features
- `mmoomm` (color: #fbca04) - Universo MMOOMM features

**Scope Labels**:
- `i18n` (color: #bfdadc) - Internationalization
- `architecture` (color: #5319e7) - Architectural changes
- `releases` (color: #ff6b6b) - Release management

(See `.github/instructions/github-labels.md` for complete list)

#### Option 2: Automated with GitHub CLI

Install GitHub CLI if not already installed, then run:

```bash
# Login to GitHub
gh auth login

# Create labels from script (to be created)
bash .github/scripts/create-labels.sh
```

### Creating Issues

Follow guidelines in `.github/instructions/github-issues.md`:

1. Use descriptive title in English
2. Main content in English
3. Add Russian translation in spoiler:
   ```html
   <details>
   <summary>In Russian</summary>
   
   [Russian translation here]
   
   </details>
   ```
4. Apply appropriate labels
5. Reference related issues/PRs

---

## Common Tasks

### Update Dependencies

**PHP dependencies**:
```bash
composer update
```

**JavaScript dependencies**:
```bash
npm update
```

### Clear Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Optimize for Production

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

---

## Troubleshooting

### Database Connection Failed

**Problem**: Cannot connect to Supabase database

**Solutions**:
1. Verify credentials in `.env` match Supabase dashboard
2. Check Supabase project is not paused
3. Ensure your IP is whitelisted in Supabase (or disable IP restrictions for development)
4. Test connection: `php artisan db:show`

### Frontend Build Errors

**Problem**: Vite build fails or components not loading

**Solutions**:
1. Delete `node_modules` and reinstall: `rm -rf node_modules && npm install`
2. Clear Vite cache: `rm -rf node_modules/.vite`
3. Check for syntax errors in Vue components
4. Ensure Vite dev server is running: `npm run dev`

### Permission Errors

**Problem**: Cannot write to storage or cache directories

**Solutions**:
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Composer Path Repository Not Found

**Problem**: `composer install` can't find local package

**Solutions**:
1. Verify package path in `repositories` array
2. Ensure package has valid `composer.json`
3. Run `composer dump-autoload`

### Laravel Key Not Set

**Problem**: "No application encryption key has been specified"

**Solution**:
```bash
php artisan key:generate
```

---

## Environment-Specific Configuration

### Development

`.env` settings for development:

```env
APP_ENV=local
APP_DEBUG=true
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Production

`.env` settings for production:

```env
APP_ENV=production
APP_DEBUG=false
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

---

## Next Steps

After completing the initial setup:

1. **Read Architecture Documentation**: Review `ARCHITECTURE.md` for three-tier entity pattern
2. **Review Constitution**: Read `.specify/memory/constitution.md` for project principles
3. **Explore Packages**: Check existing packages in `packages/` directory
4. **Study React Version**: Review [universo-platformo-react](https://github.com/teknokomo/universo-platformo-react) for feature concepts
5. **Create First Issue**: Practice creating bilingual Issue following guidelines
6. **Start Development**: Begin implementing features following documented patterns

---

## Additional Resources

### Documentation
- Laravel: https://laravel.com/docs/11.x
- Inertia.js: https://inertiajs.com/
- Vuetify: https://vuetifyjs.com/
- Supabase: https://supabase.com/docs

### Project Documentation
- `.github/instructions/github-issues.md` - Issue creation guidelines
- `.github/instructions/github-pr.md` - Pull request guidelines
- `.github/instructions/i18n-docs.md` - Internationalization guidelines
- `ARCHITECTURE.md` - System architecture patterns
- `CONTRIBUTING.md` - Contribution guidelines

### Support
- VK: https://vk.com/vladimirlevadnij
- Telegram: https://t.me/Vladimir_Levadnij
- Email: universo.pro@yandex.com
- Website: https://universo.pro

---

## Quick Reference Commands

```bash
# Development
php artisan serve              # Start Laravel server
npm run dev                    # Start Vite dev server with hot reload

# Database
php artisan migrate            # Run migrations
php artisan migrate:fresh      # Fresh database (destroys data)
php artisan db:show            # Show database info

# Testing
php artisan test               # Run all tests
./vendor/bin/pint              # Format code

# Cache
php artisan cache:clear        # Clear application cache
php artisan config:clear       # Clear config cache
php artisan route:clear        # Clear route cache

# Package Management
composer install               # Install PHP dependencies
composer update                # Update PHP dependencies
npm install                    # Install JavaScript dependencies
npm update                     # Update JavaScript dependencies

# Production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
npm run build
```

---

**Last Updated**: 2025-11-17  
**Version**: 1.0.0  
**Feature**: 001-laravel-platform-setup
