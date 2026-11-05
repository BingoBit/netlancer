# Выкладка NetLancer на хостинг

Сайт нужен с **PHP 8+** и **MySQL/MariaDB**. GitHub хранит только код.

## 1. Хостинг

Подойдёт любой shared-хостинг (Beget, Timeweb, Sprinthost, REG.RU и т.п.) или VPS.

В панели создайте:
- сайт / домен (или поддомен);
- базу MySQL и пользователя с полными правами на эту базу.

## 2. База данных

1. Откройте phpMyAdmin.
2. Выберите созданную базу.
3. Импортируйте файл `database.sql` из репозитория.

Если импорт ругается на `CREATE DATABASE` / `USE netlancer` — в phpMyAdmin уже выберите свою БД и импортируйте дамп, либо удалите из SQL строки `CREATE DATABASE` и `USE`.

## 3. Файлы

Загрузите содержимое репозитория в корень сайта (`public_html` / `www` / `htdocs`):

- через FTP/SFTP, или
- `git clone` на сервере (если хостинг даёт SSH).

На сервере:

```bash
cp config.example.php config.php
```

В `config.php` укажите данные БД из панели хостинга:

```php
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_NAME', 'имя_базы');
define('DB_USER', 'пользователь');
define('DB_PASS', 'пароль');
```

Права на запись:

```bash
chmod -R 775 assets/uploads/resumes
```

## 4. Проверка

Откройте сайт в браузере. Войдите тестовым аккаунтом из `README.md` (например `anna@example.com` / `12345678`).

Если видите ошибку про базу — перепроверьте `config.php` и импорт SQL.
