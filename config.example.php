<?php
// Скопируйте этот файл как config.php и подставьте данные своего MySQL/MariaDB.
// На хостинге/в Docker можно задать переменные окружения DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS.

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', (int)(getenv('DB_PORT') ?: 3306));
define('DB_NAME', getenv('DB_NAME') ?: 'netlancer');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

define('SITE_NAME', 'NetLancer');
define('PER_PAGE', 10);
define('CHAT_POLL_SECONDS', 3);

date_default_timezone_set('Europe/Moscow');
