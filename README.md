# Bedrock Portfolio Starter

A WordPress portfolio/resume site built and used in production by [Oleksii Siniaiev](https://alexsinyaev.com). Multilingual (EN/RU/UK/ES via Polylang), Docker-ready, fully customizable through WP Admin — no code changes required to make it your own.

> **Live example:** [alexsinyaev.com](https://alexsinyaev.com) — this starter powers that site.

## What's inside

- **WordPress 6.9 + [Bedrock](https://roots.io/bedrock/)** — Composer-managed, clean folder structure, environment-based config
- **PHP 8.4 / Apache / MySQL 8.0** — fully Dockerized local dev
- **Custom `developer-theme`** — zero frameworks, no jQuery, hand-written CSS (~60KB), fully responsive
- **Polylang** — multilingual out of the box (EN / RU / UK / ES), gracefully degrades to fewer languages
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

### 3. Import demo content

```bash
make db-import FILE=demo/demo.sql
make wp CMD="search-replace 'https://demo.example.com' 'http://localhost:8880' --all-tables"
```

### 4. Open in browser

| | |
|---|---|
| **Site** | http://localhost:8880 |
| **Admin** | http://localhost:8880/wp/wp-admin |
| **Login** | `admin` / `admin` |

### 5. Make it yours

Everything is editable through **WP Admin** — no code changes needed for content:

| What | Where in Admin |
|------|---------------|
| Your name, email, social links | Appearance → Customize → Personal Info |
| Hero headline, services, process | Appearance → Customize → (each section) |
| All text in all 4 languages | Appearance → Customize → select language |
| Profile photo, CV PDF | Media Library → upload, then Customize → Personal Info |
| Portfolio items | Portfolio → Add New |
| Blog posts | Posts → Add New |
| Contact form | Contact → Contact Forms |

To change default text permanently (instead of via DB), edit `web/app/themes/developer-theme/inc/customizer-config.php` — every key is named and grouped by template and language.

## Commands

```bash
make up          # Start containers
make down        # Stop containers
make logs        # View logs
make shell       # Bash into app container
make db-export   # Export DB to backups/
make db-import FILE=backups/dump.sql  # Import SQL
make wp CMD="plugin list"             # Run WP-CLI
make composer CMD="require pkg/name"  # Run Composer
```

## Languages

Ships with 4 languages configured in Polylang. To use fewer, deactivate unwanted languages in **WP Admin → Languages** — the theme degrades gracefully.

## Deployment

Designed for shared hosting (e.g. Hostinger) or any PHP/MySQL server:

1. `git pull` on the server
2. `composer install --no-dev`
3. Set up production `.env` with real credentials and `WP_ENV=production`
4. Import DB, run WP-CLI search-replace for your production URL

## Author

Built by **Oleksii Siniaiev** — Senior Full-Stack Engineer (WordPress & Laravel).

- Site: [alexsinyaev.com](https://alexsinyaev.com)
- GitHub: [github.com/lex127](https://github.com/lex127)
- LinkedIn: [linkedin.com/in/alexsinyayev](https://www.linkedin.com/in/alexsinyayev/)

## Credits

Built on [Roots Bedrock](https://roots.io/bedrock/) — MIT License.
