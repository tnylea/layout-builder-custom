# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a layout builder application built with Laravel 12, Livewire Volt, Alpine.js, and TailwindCSS v4. The app allows users to create and manage page layouts with configurable rows and slots.

## Development Commands

```bash
# Start all development services concurrently (server, queue, logs, vite)
composer run dev

# Or start services individually
php artisan serve      # Laravel dev server
npm run dev           # Vite dev server with hot reload

# Build for production
npm run build

# Run tests
./vendor/bin/pest
./vendor/bin/pest tests/Feature    # Feature tests only
./vendor/bin/pest tests/Unit       # Unit tests only
./vendor/bin/pest --filter=TestName  # Single test

# Code formatting
./vendor/bin/pint
```

## Architecture

### Tech Stack
- **Laravel 12** with PHP 8.2+
- **Livewire Volt** for reactive components
- **Alpine.js** for client-side interactivity
- **TailwindCSS v4** with Vite plugin (no config file needed)

### Key Files

- `resources/views/home.blade.php` - Main layout builder interface with Alpine.js state management
- `resources/views/components/layouts/app.blade.php` - Base HTML layout component
- `resources/views/partials/head.blade.php` - Document head with Vite assets
- `routes/web.php` - Simple route serving the home view

### Layout Builder Structure

The layout builder uses an Alpine.js data structure with:
- **Rows**: Container elements with width modes (`full`, `boxed`, `fixed`)
- **Slots**: Child elements within rows with names, components, and width settings
- **History**: Undo/redo support with 50-entry limit

### Testing

Uses Pest PHP with RefreshDatabase trait automatically applied to Feature tests. Tests use SQLite in-memory database.
