# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

Laravel 12 application for managing appointments ("citas" means appointments in Spanish). Uses Jetstream with Livewire stack, Laravel Sanctum for API authentication, WireUI and Flowbite for UI components.

## Development Commands

### Setup
```powershell
# Initial setup (runs composer install, npm install, generates app key, runs migrations, builds assets)
composer setup
```

### Development Server
```powershell
# Start dev environment (runs artisan serve, queue listener, and vite dev server concurrently)
composer dev

# Alternatively, run services individually:
php artisan serve                    # Start Laravel server (http://localhost:8000)
php artisan queue:listen --tries=1   # Start queue worker
npm run dev                          # Start Vite dev server for hot module reloading
```

### Testing
```powershell
# Run all tests
composer test

# Run PHPUnit directly
php artisan test

# Run specific test file
php artisan test tests/Feature/ExampleTest.php

# Run specific test method
php artisan test --filter test_method_name
```

### Code Quality
```powershell
# Format code with Laravel Pint
vendor/bin/pint

# Format specific files/directories
vendor/bin/pint app/Models
```

### Building Assets
```powershell
npm run build   # Production build
npm run dev     # Development build with HMR
```

### Database
```powershell
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migrations (drop all tables and re-run)
php artisan migrate:fresh

# Run seeders
php artisan db:seed
```

### Artisan Commands
```powershell
# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Generate application key
php artisan key:generate

# Create storage symlink
php artisan storage:link
```

## Architecture

### Stack
- **Framework**: Laravel 12 (PHP 8.2+)
- **Frontend**: Livewire 3 + Blade templates
- **UI Libraries**: Jetstream (Livewire stack), WireUI, Flowbite, Tailwind CSS 3
- **Authentication**: Laravel Fortify + Sanctum
- **Build Tool**: Vite 7
- **Database**: SQLite (default), configurable via .env

### Route Organization
Routes are organized into separate files with middleware applied at bootstrap level:

- **routes/web.php**: Public and authenticated web routes
- **routes/admin.php**: Admin routes (auto-prefixed with `/admin`, requires `web` + `auth` middleware)
- **routes/api.php**: API routes with Sanctum authentication
- **routes/console.php**: Artisan console commands

Admin routes are registered in `bootstrap/app.php` with middleware applied automatically.

### Authentication & Authorization
- **Jetstream**: Provides authentication scaffolding with Livewire components
- **Fortify**: Handles authentication backend (login, registration, password reset, 2FA)
- **Sanctum**: API token authentication for API routes
- **Features enabled**: Profile photos, account deletion, 2FA
- **Features disabled**: Email verification, API tokens, teams

### Frontend Architecture
**View Layouts**:
- `layouts/app.blade.php`: Main application layout (authenticated users)
- `layouts/admin.blade.php`: Admin panel layout with sidebar and breadcrumbs
- `layouts/guest.blade.php`: Guest/authentication pages layout

**View Components** (app/View/Components):
- `AppLayout.php`: Main app layout component
- `AdminLayout.php`: Admin layout component  
- `GuestLayout.php`: Guest layout component

**UI Components**:
- Jetstream components in `resources/views/components/`
- WireUI components (installed via Composer)
- Flowbite components (installed via npm)
- Custom Blade components follow Laravel conventions

**Asset Pipeline**:
- Vite builds `resources/css/app.css` and `resources/js/app.js`
- Tailwind configured with Flowbite and WireUI presets
- Livewire styles/scripts injected via directives

### Directory Structure Notes
- **app/Actions**: Jetstream actions (user management, password reset)
- **app/Models**: Eloquent models
- **app/Providers**: Service providers (AppServiceProvider, FortifyServiceProvider, JetstreamServiceProvider)
- **database/migrations**: Database schema migrations
- **database/factories**: Model factories for testing
- **database/seeders**: Database seeders
- **resources/views/admin**: Admin panel views
- **resources/views/api**: API token management views (if enabled)
- **resources/views/auth**: Authentication views
- **resources/views/profile**: User profile views

### Configuration
- SQLite database by default (DB_CONNECTION=sqlite in .env.example)
- Queue connection: database
- Cache store: database
- Session driver: database
- Mail mailer: log (for development)

### Testing
- PHPUnit configured in phpunit.xml
- Test suites: Unit (`tests/Unit`), Feature (`tests/Feature`)
- Uses in-memory SQLite for testing
- Run via `composer test` or `php artisan test`
