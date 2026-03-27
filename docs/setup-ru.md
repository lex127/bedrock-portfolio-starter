# Инструкция по запуску (для своих)

Этот проект — WordPress-сайт-портфолио на базе Bedrock. Запускается локально через Docker, все тексты меняются через панель администратора — код трогать не нужно.

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

## Шаг 4 — Залить демо-контент

```bash
make db-import FILE=demo/demo.sql
```

После этого скажи WordPress'у, что сайт теперь на `localhost:8880`:

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
| Имя, email, соцсети, локация | Appearance → Customize → Personal Info |
| Заголовок, подзаголовок, статистика | Appearance → Customize → Hero Section |
| Услуги (Services) | Appearance → Customize → Services |
| Процесс работы | Appearance → Customize → Process |
| Тексты на всех языках | Appearance → Customize → выбери язык вверху |
| Фото профиля | Медиатека → загрузи фото → Customize → Personal Info → Profile Image |
| PDF резюме | Медиатека → загрузи PDF → Customize → Personal Info → CV PDF |

### Портфолио

Новые кейсы добавляются через **Portfolio → Add New**. Для каждого проекта:
- Заголовок и описание — в редакторе
- Превью — через "Featured Image"
- Технологии — через таксономию "Portfolio Skills"
- Ссылка на живой сайт — через поле `portfolio_link` (в блоке "Custom Fields")

### Языки

Сайт по умолчанию работает на 4 языках: EN, RU, UK, ES. Если нужно меньше — отключи лишние в **Languages → Languages** (там же в админке), тема адаптируется автоматически.

---

## Полезные команды

```bash
make up          # Запустить контейнеры (если остановил)
make down        # Остановить контейнеры
make logs        # Посмотреть логи
make shell       # Зайти в контейнер (для отладки)
make db-export   # Экспортировать базу в backups/
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

---

Вопросы — пиши автору: [alexsinyaev.com](https://alexsinyaev.com) / [github.com/lex127](https://github.com/lex127)
