.PHONY: init up down restart build logs shell db-shell db-export db-import composer wp install

# First-time setup: build, install deps, install WordPress
init: build install
	@echo ""
	@echo "Waiting for database to be ready..."
	@sleep 5
	docker compose exec app wp core install \
		--url=http://localhost:8880 \
		--title="My Portfolio" \
		--admin_user=admin \
		--admin_password=admin \
		--admin_email=admin@example.com \
		--allow-root
	@echo ""
	@echo "========================================="
	@echo "  Site:  http://localhost:8880"
	@echo "  Admin: http://localhost:8880/wp/wp-admin"
	@echo "  Login: admin / admin"
	@echo "========================================="

# Start containers
up:
	docker compose up -d

# Stop containers
down:
	docker compose down

# Restart containers
restart:
	docker compose restart

# Rebuild and start
build:
	docker compose up -d --build

# View logs
logs:
	docker compose logs -f

# Shell into the app container
shell:
	docker compose exec app bash

# MySQL shell
db-shell:
	docker compose exec db mysql -u wordpress -pwordpress wordpress

# Export database to backups/
db-export:
	@mkdir -p backups
	docker compose exec db mysqldump --no-tablespaces -u wordpress -pwordpress wordpress > backups/db-$$(date +%Y%m%d-%H%M%S).sql
	@echo "Database exported to backups/"
	@ls -la backups/*.sql | tail -1

# Import database from SQL file (usage: make db-import FILE=backups/dump.sql)
db-import:
	@test -n "$(FILE)" || (echo "Usage: make db-import FILE=path/to/file.sql" && exit 1)
	docker compose exec -T db mysql -u wordpress -pwordpress wordpress < $(FILE)
	@echo "Database imported from $(FILE)"

# Run composer inside container
composer:
	docker compose exec app composer $(CMD) -d /var/www/html

# Run WP-CLI inside container
wp:
	docker compose exec app wp $(CMD) --allow-root

# Install dependencies
install:
	docker compose exec app composer install -d /var/www/html
