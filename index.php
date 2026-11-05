<?php

if (!extension_loaded('pdo_mysql') || !extension_loaded('mbstring')) {
    exit('Для работы сайта нужны расширения PHP pdo_mysql и mbstring (в PHP из OSPanel они уже включены).');
}

session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');

require __DIR__ . '/config.php';
require __DIR__ . '/core/db.php';
require __DIR__ . '/core/helpers.php';
require __DIR__ . '/core/auth.php';

foreach (['User', 'Category', 'Resume', 'Order', 'Response', 'Dialog', 'Message', 'Review'] as $model) {
    require __DIR__ . '/models/' . $model . '.php';
}
foreach (['Home', 'Auth', 'Order', 'Response', 'Resume', 'Cabinet', 'Chat', 'Admin'] as $controller) {
    require __DIR__ . '/controllers/' . $controller . 'Controller.php';
}

$routes = [
    'home'     => ['HomeController', 'index'],
    'category' => ['HomeController', 'category'],
    'info'     => ['HomeController', 'info'],

    'register' => ['AuthController', 'register'],
    'login'    => ['AuthController', 'login'],
    'logout'   => ['AuthController', 'logout'],

    'orders'         => ['OrderController', 'index'],
    'order'          => ['OrderController', 'show'],
    'order_create'   => ['OrderController', 'create'],
    'order_edit'     => ['OrderController', 'edit'],
    'order_cancel'   => ['OrderController', 'cancel'],
    'order_deliver'  => ['OrderController', 'deliver'],
    'order_complete' => ['OrderController', 'complete'],
    'order_review'   => ['OrderController', 'review'],

    'response_create'   => ['ResponseController', 'create'],
    'response_withdraw' => ['ResponseController', 'withdraw'],
    'response_accept'   => ['ResponseController', 'accept'],

    'resumes'     => ['ResumeController', 'index'],
    'resume'      => ['ResumeController', 'show'],
    'resume_edit' => ['ResumeController', 'edit'],

    'cabinet' => ['CabinetController', 'index'],
    'profile' => ['CabinetController', 'profile'],

    'chats'       => ['ChatController', 'index'],
    'chat'        => ['ChatController', 'show'],
    'chat_start'  => ['ChatController', 'start'],
    'chat_poll'   => ['ChatController', 'poll'],
    'chat_send'   => ['ChatController', 'send'],
    'chat_delete' => ['ChatController', 'delete'],

    'admin'              => ['AdminController', 'index'],
    'admin_orders'       => ['AdminController', 'orders'],
    'admin_order_action' => ['AdminController', 'orderAction'],
    'admin_users'        => ['AdminController', 'users'],
    'admin_user_block'   => ['AdminController', 'blockUser'],
    'admin_resumes'      => ['AdminController', 'resumes'],
    'admin_resume_hide'  => ['AdminController', 'hideResume'],
    'admin_categories'   => ['AdminController', 'categories'],
    'admin_dialog'       => ['AdminController', 'dialog'],
];

$page = query('page') ?: 'home';
if (!isset($routes[$page])) {
    not_found();
}
call_user_func($routes[$page]);
