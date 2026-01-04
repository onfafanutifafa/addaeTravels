# Copilot / AI assistant instructions for addaeTrips ⚙️

Purpose: give an AI coding agent the precise, repository-specific context and commands to be productive quickly.

## Big picture
- Monolithic Laravel 11 application (PHP ^8.2) following standard MVC + PSR-4 layout. Key directories: `app/`, `routes/`, `resources/views/`, `database/`.
- Authentication is scaffolded with **Laravel Breeze** (see `routes/auth.php` and `app/Http/Controllers/Auth`).
- Frontend built with **Vite + Tailwind** (`package.json`, `vite.config.js`, `resources/js`, `resources/css`).
- Background processing uses Laravel queues (default: `QUEUE_CONNECTION=database`) and developer tooling includes `laravel/pail` for log tailing.

## Setup & common workflows ✅
- Bootstrap development environment (expected):
  - cp `.env.example` `.env`
  - `composer install`
  - `npm install`
  - `php artisan key:generate`
  - `touch database/database.sqlite` (the project uses sqlite by default) and `php artisan migrate --graceful`
- Quick dev runner: `composer dev` — uses `concurrently` to run: `php artisan serve`, `php artisan queue:listen --tries=1`, `php artisan pail --timeout=0`, and `npm run dev`.
- Individual alternatives:
  - app server: `php artisan serve`
  - asset watcher: `npm run dev`
  - queue worker: `php artisan queue:listen --tries=1`
  - tail logs: `php artisan pail`
- Build for production: `npm run build` (runs `vite build`).

## Tests & CI 🧪
- Uses **Pest** + `pest-plugin-laravel`. Run tests with:
  - `./vendor/bin/pest` or `php artisan test`
- Feature tests use `RefreshDatabase` as configured in `tests/Pest.php` (so tests will migrate/reset DB). The default database configuration often relies on sqlite (see `.env.example`).
- Note: the composer `post-create-project-cmd` already attempts to create `database/database.sqlite` and run migrations.

## Project-specific patterns & examples 💡
- Factories & default test creds:
  - `database/factories/UserFactory.php` uses `Hash::make('password')` for the default password — tests and fixtures assume the password `password`.
- Auth is organized into `routes/auth.php` and controllers under `App\Http\Controllers\Auth` (e.g., `RegisteredUserController`, `AuthenticatedSessionController`). Use these files to understand auth flows and endpoints.
- Blade components: look under `app/View/Components` and the `resources/views/components` folder for reusable UI pieces.
- Routes are split (see `routes/*.php`) — check `web.php`, `auth.php`, etc., before adding endpoints.
- Database migrations and seeders live in `database/migrations` and `database/seeders`.

## Tooling & style 🔧
- Formatting/linting: `laravel/pint` is installed — run `./vendor/bin/pint` to format PHP code.
- Use `composer dev` during development to get an integrated environment (server + queue + logs + vite).

## Integration & environment notes ⚠️
- Default `.env.example` sets `DB_CONNECTION=sqlite` and `MAIL_MAILER=log` — these defaults are intentional for local development.
- CI or container-based runs may prefer `DB_CONNECTION=mysql` or an in-memory sqlite; `phpunit.xml` contains commented-out sqlite `:memory:` examples that can be enabled for CI.
- Sail is available as a dev dependency (`laravel/sail`) if a Docker-based local environment is desired (`./vendor/bin/sail up`).

## When you change something
- If you add migrations, include a migration and add an optional seeder; ensure migrations run in CI.
- Update factories/tests when you change model attributes.
- Run `./vendor/bin/pint` and `./vendor/bin/pest` locally before opening PRs.

---
If anything here is unclear or you want more specific examples (API endpoints, typical bugs, or CI config), say which area to expand and I’ll iterate. ✅
