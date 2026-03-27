# Repository Guidelines

## Project Structure & Module Organization
This repository is a Bedrock-based WordPress project. Core config lives in `config/` with environment overrides in `config/environments/`. Application code is under `web/app/`: the main custom theme is `web/app/themes/developer-theme`, Composer-managed plugins live in `web/app/plugins`, mu-plugins in `web/app/mu-plugins`, and uploaded media in `web/app/uploads`. Tests live in `tests/`, demo database files in `demo/`, and setup docs in `docs/`.

## Build, Test, and Development Commands
Use Docker and the provided `Makefile` for local work.

- `make init` builds containers, installs Composer dependencies, and runs the initial WordPress install.
- `make up` / `make down` start and stop the local stack.
- `make logs` tails container logs.
- `make shell` opens a shell in the app container.
- `make composer CMD="test"` runs Composer scripts inside the container.
- `make wp CMD="plugin list"` runs WP-CLI against `web/wp`.
- `make db-import FILE=demo/demo.sql` loads demo content; `make db-export` writes a timestamped dump to `backups/`.

## Coding Style & Naming Conventions
PHP code is formatted with Laravel Pint using the `per` preset; run `composer lint` to check and `composer lint:fix` to apply fixes. Follow the existing style: 4-space indentation, PSR-12-compatible PHP, and short, explicit function names. Keep WordPress template filenames descriptive and aligned with template purpose, for example `front-page.php`, `single-portfolio.php`, and helper files under `inc/`.

## Testing Guidelines
Tests use Pest with PHPUnit bootstrap. Place new tests in `tests/` or `tests/Feature/` and use the `*Test.php` suffix. Run the suite with `composer test` or `make composer CMD="test"`. Current coverage is minimal, so add tests for new PHP helpers, config logic, and any behavior that can be validated outside the WordPress UI.

## Commit & Pull Request Guidelines
Recent history uses short imperative subjects such as `Add Russian setup guide in docs/setup-ru.md`. Keep commits focused, use one-line subjects, and mention the touched area when useful. Pull requests should include a concise summary, manual verification steps, linked issues if applicable, and screenshots for theme or admin UI changes.

## Security & Configuration Tips
Do not commit real `.env` values, salts, database dumps with private data, or production credentials. Treat `web/app/uploads/` as generated content unless the change intentionally adds seed/demo assets.
