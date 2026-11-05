<?php
// Скопируйте этот файл как config.php и подставьте данные своего MySQL/MariaDB.

define('DB_HOST', 'localhost');   // на хостинге обычно localhost
define('DB_PORT', 3306);
define('DB_NAME', 'netlancer');   // имя базы на хостинге
define('DB_USER', 'root');        // пользователь БД
define('DB_PASS', '');            // пароль БД

define('SITE_NAME', 'NetLancer');
define('PER_PAGE', 10);
define('CHAT_POLL_SECONDS', 3);

date_default_timezone_set('Europe/Moscow');
