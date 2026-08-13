# PHP Blog

Блог на **PHP 8.5 + PHP-FPM + Nginx + MySQL 8.4 + Smarty**.
Фреймворки не используются.

## Требования

- Docker
- Docker Compose

PHP, Composer и MySQL локально устанавливать не требуется.

## Запуск

```bash
docker compose up -d --build
docker compose exec php composer install
docker compose exec php php bin/seed.php
```

Открыть:

**http://localhost:8080**

## База данных

SQL-схема:

```text
docker/mysql/init/schema-1.sql
```

Создаются таблицы:

- `categories` — категории;
- `articles` — статьи;
- `article_categories` — связь статей с категориями.

MySQL внутри Docker:

```text
Host: db
Port: 3306
Database: blog
User: blog
Password: blog_password
```

С локальной машины MySQL доступен на `localhost:3307`.

## Seeder

Тестовые категории и статьи добавляются командой:

```bash
docker compose exec php php bin/seed.php
```

## Структура

```text
bin/                    CLI-команды / Seeder
docker/
  mysql/init/           SQL-схема
  nginx/                конфигурация Nginx
  php/                  Dockerfile и PHP config
public/                 Front Controller
src/
  Controller/            контроллеры
  Database/              подключение к БД
  Repository/            работа с БД
  Seeder/                заполнение данных
templates/              Smarty-шаблоны
var/cache/              кэш Smarty
```

## Страницы

```text
/                              Главная
/category/{slug}               Категория
/article/{slug}                Статья
```

На главной странице выводятся категории со статьями и по 3 последних статьи каждой категории.

На странице категории доступны сортировка по дате/просмотрам и пагинация.

На странице статьи отображается вся информация, увеличивается счётчик просмотров и выводятся 3 похожие статьи.

## Docker

Архитектура:

```text
Browser → Nginx → PHP-FPM → MySQL
```

Контейнеры:

```text
blog_nginx
blog_php
blog_db
```

Проверить состояние:

```bash
docker compose ps
```

Логи:

```bash
docker compose logs
```

Остановить:

```bash
docker compose down
```