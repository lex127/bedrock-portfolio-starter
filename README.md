# Bedrock Portfolio Starter

A WordPress portfolio/resume site built on [Roots Bedrock](https://roots.io/bedrock/). Multilingual (EN/RU/UK/ES via Polylang), Docker-ready, fully customizable through WP Admin — no code changes required to make it your own.

## Stack

- WordPress 6.9 + [Bedrock](https://roots.io/bedrock/) (Composer-managed)
- PHP 8.4 / Apache / MySQL 8.0 (Docker)
- Polylang (multilingual), ACF, Yoast SEO, Contact Form 7
- Custom `developer-theme` — no frameworks, no jQuery

## Quick Start

### 1. Clone and configure

```bash
git clone https://github.com/your-username/bedrock-portfolio-starter.git
cd bedrock-portfolio-starter
cp .env.example .env
```

Edit `.env` — the defaults work for local Docker as-is. Generate unique salts at https://roots.io/salts.html and paste them in.

### 2. Start Docker and install

```bash
make init
```

This builds the container, runs `composer install`, and installs WordPress. Takes ~2–3 minutes on first run.

### 3. Import demo content

```bash
make db-import FILE=demo/demo.sql
```

After import, run a URL search-replace so all links point to your local site:

```bash
make wp CMD="search-replace 'https://demo.example.com' 'http://localhost:8880' --all-tables"
```

### 4. Open in browser

- **Site:** http://localhost:8880
- **Admin:** http://localhost:8880/wp/wp-admin
- **Login:** `admin` / `admin`

### 5. Customize

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

## Customizer: all text lives in one place

The theme uses a single PHP config (`web/app/themes/developer-theme/inc/customizer-config.php`) as the source of truth for all default text. If you want to change the defaults permanently (e.g. rename labels, rewrite sections), edit that file — each key is clearly named and grouped by language.

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

The demo content ships with 4 languages: English, Russian, Ukrainian, Spanish. You can add or remove languages in **WP Admin → Languages** (Polylang). To use fewer languages, simply deactivate the ones you don't need — the theme degrades gracefully.

## Deployment

The project is designed for deployment to shared hosting (e.g. Hostinger) or any PHP/MySQL server:

1. Push code to your server via `git pull`
2. Run `composer install --no-dev` on the server
3. Configure a production `.env` with real credentials and `WP_ENV=production`
4. Import your DB, run WP-CLI search-replace for the production URL

## Credits

Built on [Roots Bedrock](https://roots.io/bedrock/) — MIT License.
