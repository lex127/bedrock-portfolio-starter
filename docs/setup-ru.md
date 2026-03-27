# Инструкция по запуску

Этот проект — WordPress-сайт-портфолио на базе Bedrock. Его можно клонировать, поднять локально через Docker, импортировать демо-базу из репозитория и получить сайт в готовом стартовом состоянии.

## Что нужно установить заранее

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) — запустить и оставить работать в фоне
- [Git](https://git-scm.com/downloads)
- Терминал (на Mac — встроенный Terminal или iTerm, на Windows — Git Bash)

## Шаг 1 — Клонировать проект

```bash
git clone https://github.com/lex127/bedrock-portfolio-starter.git
cd bedrock-portfolio-starter
```

## Шаг 2 — Создать файл настроек

```bash
cp .env.example .env
```

Открой `.env` в любом редакторе. Для локального запуска ничего менять не нужно — дефолтные значения уже правильные. Но желательно заменить строки с `generateme` на уникальные ключи — сгенерируй их на https://roots.io/salts.html и вставь.

## Шаг 3 — Запустить

```bash
make init
```

Эта команда:
- Соберёт Docker-контейнер (первый раз ~3–5 минут, скачает образы)
- Установит все PHP-зависимости через Composer
- Установит WordPress

Когда увидишь в терминале строки вроде:
```
=========================================
  Site:  http://localhost:8880
  Admin: http://localhost:8880/wp/wp-admin
  Login: admin / admin
=========================================
```

— сервер готов.

### Запуск на Windows без `make`

Если работаешь из PowerShell или CMD и не хочешь ставить `make`, используй прямые команды Docker:

```powershell
docker compose up -d --build
docker compose exec app composer install -d /var/www/html
docker compose exec app wp core install --url=http://localhost:8880 --title="My Portfolio" --admin_user=admin --admin_password=admin --admin_email=admin@example.com --allow-root
docker compose exec -T db mysql -u wordpress -pwordpress wordpress < demo\demo.sql
docker compose exec app wp search-replace "https://demo.example.com" "http://localhost:8880" --all-tables --allow-root
```

После этого сайт будет доступен по `http://localhost:8880` с тем же демо-контентом, что и у всех остальных.

## Шаг 4 — Импортировать демо-базу

```bash
make db-import FILE=demo/demo.sql
```

`demo/demo.sql` — это коммитимая демо-база, которая хранится в Git и должна давать одинаковый стартовый результат у любого, кто клонирует репозиторий.

После импорта скажи WordPress'у, что сайт теперь на `localhost:8880`:

```bash
make wp CMD="search-replace 'https://demo.example.com' 'http://localhost:8880' --all-tables"
```

## Шаг 5 — Открыть в браузере

- **Сайт:** http://localhost:8880
- **Админка:** http://localhost:8880/wp/wp-admin
- **Логин:** `admin`
- **Пароль:** `admin`

---

## Как заменить всё на свои данные

Заходи в **Внешний вид → Настроить** (Appearance → Customize) — там всё.

| Что поменять | Где в админке |
|---|---|
| Имя, email, соцсети, локация, URL резюме | Appearance → Customize → Shared Settings |
| Заголовок, подзаголовок, статистика | Appearance → Customize → English / Русский / Українська → Front Page |
| Услуги (Services) | Appearance → Customize → English / Русский / Українська → Front Page |
| Процесс работы | Appearance → Customize → English / Русский / Українська → Front Page |
| Тексты на всех языках | Appearance → Customize → нужная языковая панель |
| Фото профиля | Медиатека → загрузи фото → Appearance → Customize → Shared Settings |
| Кнопка `Download CV` | Загрузи PDF в Медиатеку, скопируй URL файла и вставь в Appearance → Customize → Shared Settings → CV PDF URL |

### Портфолио

Новые кейсы добавляются через **Portfolio → Add New**. Для каждого проекта:
- Заголовок и описание — в редакторе
- Превью — через "Featured Image"
- Технологии — через таксономию "Portfolio Skills"
- Ссылка на живой сайт — через поле `portfolio_link` (в блоке "Custom Fields")

### Языки

Сайт по умолчанию работает на 3 языках: EN, RU и UK. Если нужен один язык или другая комбинация — настрой это в **Languages → Languages**.

---

## Полезные команды

```bash
make up          # Запустить контейнеры (если остановил)
make down        # Остановить контейнеры
make logs        # Посмотреть логи
make shell       # Зайти в контейнер (для отладки)
make db-export   # Экспортировать базу в backups/
make db-import FILE=demo/demo.sql   # Импортировать tracked демо-базу из репозитория
```

### Windows-команды без `make`

```powershell
docker compose up -d
docker compose down
docker compose logs -f
docker compose exec app bash
docker compose exec -T db mysql -u wordpress -pwordpress wordpress < demo\demo.sql
docker compose exec db mysqldump -u wordpress -pwordpress wordpress > backups\dump.sql
docker compose exec app wp plugin list --allow-root
docker compose exec app composer install -d /var/www/html
```

### Где хранится база

- `demo/demo.sql` — основная демо-база, которая хранится в Git.
- `backups/` — локальные экспорты во время разработки. Эти `.sql` не коммитятся.

Если ты обновил демо-контент и хочешь перезаписать коммитимую базу:

```bash
make db-export
cp backups/db-YYYYMMDD-HHMMSS.sql demo/demo.sql
./demo/scrub-db.sh demo/demo.sql > /tmp/demo-scrubbed.sql && mv /tmp/demo-scrubbed.sql demo/demo.sql
```

## Остановить и запустить снова

```bash
make down   # остановить
make up     # запустить снова (быстро, контейнер уже собран)
```

Данные базы сохраняются в Docker volume — при `make down` ничего не теряется.

## Если что-то пошло не так

**Порт занят** (`port 8880 already in use`) — в `docker-compose.yml` поменяй `8880:80` на другой порт, например `8881:80`.

**`make: command not found`** на Windows — используй Git Bash или установи `make` через chocolatey: `choco install make`.

**База не импортируется** — убедись что контейнеры запущены (`make up`) перед `make db-import`.
