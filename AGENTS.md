# Repository Guidelines

## Project Structure & Module Organization

This is a Laravel application for the Swarna Mandapa villa site and booking flow. PHP code lives in `app/`: controllers in `app/Http/Controllers`, middleware in `app/Http/Middleware`, models in `app/Models`, and mailables in `app/Mail`. Routes are in `routes/web.php`, `routes/api.php`, and `routes/console.php`.

Blade templates are in `resources/views`, including public pages, booking screens, admin pages, components, and emails. Frontend entry points are `resources/css/app.css` and `resources/js/app.js`, compiled by Vite. Public media is under `public/assets` and `public/images`. Database files are in `database/`; tests are in `tests/`.

## Build, Test, and Development Commands

- `composer install` installs PHP dependencies.
- `npm install` installs frontend dependencies.
- `composer run dev` starts the Laravel server, queue listener, log tailing, and Vite together.
- `php artisan serve` runs only the HTTP server.
- `npm run dev` starts only Vite.
- `npm run build` builds production frontend assets.
- `composer test` clears config and runs tests.
- `php artisan migrate --seed` applies migrations and seed data.

Use `composer run setup` for first-time setup when a local `.env` is not yet configured.

## Coding Style & Naming Conventions

Follow `.editorconfig`: UTF-8, LF line endings, spaces, 4-space indentation, final newline, and trimmed trailing whitespace. YAML files use 2 spaces. PHP follows Laravel conventions and PSR-4 namespaces under `App\`. Use singular StudlyCase models such as `Booking`; controllers end in `Controller`; Blade components use kebab-case filenames such as `gold-button.blade.php`.

Laravel Pint is available through Composer. Run `./vendor/bin/pint` before submitting PHP formatting changes.

## Testing Guidelines

Tests use PHPUnit through Laravel's test runner. Put HTTP and workflow tests in `tests/Feature`, isolated class or helper tests in `tests/Unit`, and name files after behavior, for example `BookingFlowTest.php`. Run `composer test` before opening a pull request. Add focused tests for booking, payment, admin authentication, date blocking, and promo logic when those areas change.

## Commit & Pull Request Guidelines

Recent commits use short, imperative, lowercase messages such as `fix entrypoint.sh` and `add docker deployment config`. Keep that style: describe the change, not the process.

Pull requests should include a summary, test commands run, configuration or migration notes, linked issues when applicable, and screenshots for visible Blade or asset changes. Mention `.env`, Docker, payment, or booking behavior changes explicitly.

## Security & Configuration Tips

Do not commit secrets from `.env`, payment credentials, mail credentials, generated PDFs, logs, or local debug artifacts. Keep deploy-specific changes in `Dockerfile`, `docker/`, and documented environment variables.
