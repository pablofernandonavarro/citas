# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

Laravel 12 application for managing medical appointments ("citas" means appointments in Spanish). Features include appointment scheduling, doctor schedule management, WhatsApp notifications via Meta Business API, and automated reminders. Uses Jetstream with Livewire stack, Laravel Sanctum for API authentication, WireUI and Flowbite for UI components.

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

### WhatsApp
```powershell
# Enviar recordatorios 24 horas antes manualmente
php artisan appointments:send-day-before-reminders

# Enviar recordatorios 2 horas antes manualmente
php artisan appointments:send-two-hours-before-reminders

# Diagnosticar configuración de WhatsApp
php artisan whatsapp:diagnose

# Probar envío de WhatsApp
php artisan whatsapp:test
```

### Queue Worker
```powershell
# Iniciar queue worker (necesario para WhatsApp)
php artisan queue:work

# Con reintentos
php artisan queue:listen --tries=1

# Ver trabajos fallidos
php artisan queue:failed
```

### Scheduler (Tareas Programadas)
```powershell
# Para desarrollo - ejecutar scheduler manualmente
php artisan schedule:work

# Ver tareas programadas
php artisan schedule:list
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
- **Authorization**: Spatie Laravel Permission (roles y permisos)
- **Build Tool**: Vite 7
- **Database**: SQLite (default), configurable via .env
- **Queue**: Database driver
- **External APIs**: WhatsApp Business API (Meta Graph API v21.0)

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

### WhatsApp Integration
**Service**: `app/Services/WhatsAppService.php` maneja toda la interacción con la API de WhatsApp Business.

**Configuración requerida** (`.env`):
- `WHATSAPP_API_URL`: URL de la API de Meta (https://graph.facebook.com/v21.0)
- `WHATSAPP_ACCESS_TOKEN`: Token de acceso de Meta for Developers
- `WHATSAPP_PHONE_NUMBER_ID`: ID del número de teléfono de WhatsApp Business

**Funcionalidad**:
- Envío automático de confirmación al crear turnos (template: `confirmacion_de_turno`)
- Recordatorios automáticos 24 horas antes (diario a las 9:00 AM, timezone Argentina)
- Recordatorios automáticos 2 horas antes (cada 15 minutos)
- Formateo automático de números argentinos (+54)
- Los mensajes se envían mediante queue jobs

**Comandos programados** (definidos en `bootstrap/app.php`):
- `appointments:send-day-before-reminders`: Ejecuta diariamente a las 9:00 AM
- `appointments:send-two-hours-before-reminders`: Ejecuta cada 15 minutos

**Template de WhatsApp**: Ver `WHATSAPP_TEMPLATE.md` para configuración en Meta Business Manager.

### Schedule Configuration
La configuración de horarios de citas se maneja en `config/schedule.php`:
- `days`: Array de días habilitados (1=lunes, 5=viernes, etc.)
- `appointments_duration`: Duración de cada intervalo en minutos (ej: 60)
- `start_time`: Hora de inicio del horario laboral (ej: '08:00:00')
- `end_time`: Hora de fin del horario laboral (ej: '18:00:00')

Después de cambiar esta configuración, ejecutar `php artisan config:clear`.

### Key Services
- `app/Services/WhatsAppService.php`: Integración con WhatsApp Business API
- `app/Services/AppointmentService.php`: Lógica de negocio de citas

### Livewire Components (Admin)
- `AppointmentManager`: Gestión de citas médicas
- `ScheduleManager`: Gestión de horarios de doctores
- `ConsultationManager`: Gestión de consultas

### Helper Global
El archivo `app/Helpers/helpers.php` está cargado automáticamente (ver composer.json).
