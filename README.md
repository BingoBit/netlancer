# NetLancer

Учебная фриланс-биржа на PHP + MySQL: заказы, резюме, отклики, личный кабинет и чат.

## Требования

- PHP 8.0+ (расширения `pdo_mysql`, `mbstring`, `fileinfo`)
- MySQL 5.7+ / MariaDB 10.4+
- Веб-сервер (Apache / Nginx / встроенный `php -S`)

## Быстрый старт (локально)

1. Скопируйте настройки БД:

```bash
cp config.example.php config.php
```

2. В `config.php` укажите хост, имя базы, пользователя и пароль.

3. Создайте базу и импортируйте данные:

```bash
mysql -u root -p < database.sql
```

В OSPanel можно импортировать `database.sql` через phpMyAdmin.

4. Откройте папку проекта в браузере (DocumentRoot должен указывать на корень проекта, где лежит `index.php`).

Папка `assets/uploads/resumes` должна быть доступна на запись (права `755` или `775`).

## Выкладка на хостинг

1. Создайте MySQL-базу в панели хостинга.
2. Импортируйте `database.sql` (phpMyAdmin → Импорт). Если хостинг уже создал базу с другим именем — в SQL можно оставить только содержимое после `USE ...` или выбрать нужную БД перед импортом.
3. Загрузите файлы проекта в `public_html` (или в подпапку) по FTP/SFTP/Git.
4. На сервере создайте `config.php` из `config.example.php` и подставьте данные БД хостинга (`DB_HOST` чаще всего `localhost`).
5. Проверьте права на `assets/uploads/resumes`.
6. Откройте сайт по адресу хостинга.

`config.php` в git не попадает — на каждом окружении он свой.

## Тестовые учётные записи

| Роль | Почта | Пароль |
|------|-------|--------|
| Администратор | admin@example.com | admin12345 |
| Заказчик | anna@example.com | 12345678 |
| Заказчик | orlov@example.com | 12345678 |
| Исполнитель | maria@example.com | 12345678 |
| Исполнитель | ivan@example.com | 12345678 |
| Заблокированный исполнитель | kirill@example.com | 12345678 |

Пароль у тестовых заказчиков и исполнителей: `12345678`.

## Структура проекта

```
index.php          точка входа и маршрутизатор (?page=...)
config.example.php пример настроек БД (рабочий файл — config.php)
database.sql       схема и демо-данные
core/              БД, авторизация, хелперы
models/            User, Category, Resume, Order, Response, Dialog, Message, Review
controllers/       Home, Auth, Order, Response, Resume, Cabinet, Chat, Admin
views/             шаблоны (layout — шапка и подвал)
assets/css/        стили
assets/js/         main.js, chat.js
assets/img/icons/  иконки категорий
assets/uploads/    загрузки пользователей (в git не хранятся)
fonts/             локальные шрифты Fredoka
```
