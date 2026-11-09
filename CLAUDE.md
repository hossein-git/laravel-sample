# sample-modular

Modular Laravel project. Each module lives under `Modules/` at the repo root (e.g. `Modules/User`), registered via PSR-4 in `composer.json` and booted by its own `*ServiceProvider` wired up in `app/Providers/AppServiceProvider.php`.

## Existing modules

- `Modules/Base` — shared base classes, traits, helpers.
- `Modules/Employee` — employees and roles/permissions.
- `Modules/Trade` — strategies and mql5 entries.
- `Modules/User` — users, brokers, accounts, billing, notification configs.

## Admin & user panel

Both the admin panel and the user panel are built with **Filament**.

In the **user panel**, never expose database IDs in URLs or UI — use slugs instead (route-model binding by slug, resource keys by slug, etc.).

## Local environment

This project runs on **Laravel Sail** (Docker). Use `./vendor/bin/sail` for artisan, composer, and tests.

## Conventions

Always generate models (and any other scaffolded files) with the standard Laravel `artisan make:*` commands, then move the result into the right module under `Modules/` and fix its namespace. Do not hand-write files that `artisan` can scaffold.

## Git

Always create a new branch before starting work, and always branch off `main` (never off whatever branch happens to be checked out). If the current branch is not `main`, stop and ask before switching.
