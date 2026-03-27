# Bedrock Portfolio Starter

A Bedrock-based WordPress portfolio/resume starter with Docker-based local setup, demo content, and multilingual support out of the box. It is designed so someone can clone the repository, import the included database, and get the same local site state immediately.

Created by Oleksii Siniaiev as an AI-assisted custom WordPress starter.

> **На русском:** [docs/setup-ru.md](docs/setup-ru.md)

## What's inside

- **WordPress 6.9 + [Bedrock](https://roots.io/bedrock/)** — Composer-managed, clean folder structure, environment-based config
- **PHP 8.4 / Apache / MySQL 8.0** — fully Dockerized local dev
- **Custom `developer-theme`** — zero frameworks, no jQuery, hand-written CSS (~60KB), fully responsive
- **Polylang** — multilingual out of the box (EN / RU / UK by default)
- **ACF, Yoast SEO, Contact Form 7, WP Mail SMTP** — standard plugin stack, all free, Composer-managed
- **Portfolio CPT** — custom post type with skill/category taxonomies

## Quick Start

### 1. Clone and configure

```bash
git clone https://github.com/lex127/bedrock-portfolio-starter.git
cd bedrock-portfolio-starter
cp .env.example .env
```

The defaults in `.env` work for local Docker as-is. Generate unique salts at https://roots.io/salts.html and paste them in.

### 2. Start Docker and install

```bash
make init
```

Builds the container, runs `composer install`, installs WordPress. ~2–3 minutes on first run.

### 3. Import the tracked demo database

```bash
make db-import FILE=demo/demo.sql
make wp CMD="search-replace 'https://demo.example.com' 'http://localhost:8880' --all-tables"
```

`demo/demo.sql` is the repository-safe demo dump committed to Git. Use it after the first `make init` so your local site matches the starter state.

### 4. Open in browser

| | |
|---|---|
| **Site** | http://localhost:8880 |
| **Admin** | http://localhost:8880/wp/wp-admin |
| **Login** | `admin` / `admin` |

> The site ships with neutral mock content, 3 languages (EN / RU / UK), 3 sample portfolio entries, and 2 sample blog posts. Replace everything via WP Admin — no code changes needed.

### 5. Make it yours

Everything is editable through **WP Admin** — no code changes needed for content:

| What | Where in Admin |
|------|---------------|
| Your name, email, social links | Appearance → Customize → Personal Info |
| Hero headline, services, process | Appearance → Customize → (each section) |
| All text in all 3 default languages | Appearance → Customize → select language |
| Profile photo, CV PDF | Media Library → upload, then Customize → Personal Info |
| Portfolio items | Portfolio → Add New |
| Blog posts | Posts → Add New |
| Contact form | Contact → Contact Forms |

To change default text permanently (instead of via DB), edit `web/app/themes/developer-theme/inc/customizer-config.php` — every key is named and grouped by template and language.

## Commands

**macOS / Linux** — use `make`:

```bash
make up          # Start containers
make down        # Stop containers
make logs        # View logs
make shell       # Bash into app container
make db-export   # Export DB to backups/db-YYYYMMDD-HHMMSS.sql
make db-import FILE=backups/dump.sql  # Import SQL
make wp CMD="plugin list"             # Run WP-CLI
make composer CMD="require pkg/name"  # Run Composer
```

### Demo DB vs local backups

- `demo/demo.sql` is the tracked demo database that should work for anyone cloning the repo.
- `backups/` is for your local exports while developing. Those `.sql` files are ignored by Git.

To refresh the committed demo DB after content changes:

```bash
make db-export
cp backups/db-YYYYMMDD-HHMMSS.sql demo/demo.sql
./demo/scrub-db.sh demo/demo.sql > /tmp/demo-scrubbed.sql && mv /tmp/demo-scrubbed.sql demo/demo.sql
```

**Windows** — use Docker commands directly (PowerShell or CMD):

```powershell
# Start / stop
docker compose up -d --build        # First run (build + start)
docker compose up -d                # Start
docker compose down                 # Stop
docker compose restart              # Restart
docker compose logs -f              # View logs

# Shell into the container
docker compose exec app bash

# Install Composer dependencies
docker compose exec app composer install -d /var/www/html

# Run WP-CLI
docker compose exec app wp <command> --allow-root
# Example: docker compose exec app wp plugin list --allow-root

# Run Composer
docker compose exec app composer <command> -d /var/www/html
# Example: docker compose exec app composer require wpackagist-plugin/foo -d /var/www/html

# Import database
docker compose exec -T db mysql -u wordpress -pwordpress wordpress < demo\demo.sql

# Export database
docker compose exec db mysqldump -u wordpress -pwordpress wordpress > backups\dump.sql
```

> **First-time setup on Windows:**
> ```powershell
> docker compose up -d --build
> # Wait ~10 seconds for the database to initialize, then:
> docker compose exec app composer install -d /var/www/html
> docker compose exec app wp core install --url=http://localhost:8880 --title="My Portfolio" --admin_user=admin --admin_password=admin --admin_email=admin@example.com --allow-root
> ```

## Languages

Ships with 3 languages configured in Polylang: EN, RU, and UK. If you only need one language, remove the others in **Languages → Languages** after import.

## Deployment

Designed for shared hosting (e.g. Hostinger) or any PHP/MySQL server:

1. `git pull` on the server
2. `composer install --no-dev`
3. Set up production `.env` with real credentials and `WP_ENV=production`
4. Import DB, run WP-CLI search-replace for your production URL

## Credits

Built on [Roots Bedrock](https://roots.io/bedrock/) — MIT License.
