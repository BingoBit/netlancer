<?php
$me = current_user();
$unread = ($me && $me['role'] !== 'admin') ? Dialog::unreadTotal($me['id']) : 0;
$currentPage = query('page') ?: 'home';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(isset($title) ? $title . ' – ' . SITE_NAME : SITE_NAME) ?></title>
    <meta name="description" content="<?= e($description ?? 'NetLancer – фриланс-биржа: заказы, резюме исполнителей, отклики и внутренний чат.') ?>">
    <link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/main.js" defer></script>
</head>
<body>
<header class="header">
    <div class="container header-top">
        <a class="logo" href="<?= url('home') ?>">NetLancer</a>

        <form class="search" method="get" role="search">
            <input type="hidden" name="page" value="orders">
            <input type="search" name="q" value="<?= e($currentPage === 'orders' ? query('q') : '') ?>"
                   placeholder="Что вас интересует?" aria-label="Поиск заказов">
            <button type="submit" aria-label="Найти">
                <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><circle cx="10" cy="10" r="7"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg>
            </button>
        </form>

        <nav class="user-menu" aria-label="Учётная запись">
            <?php if (!$me): ?>
                <a href="<?= url('login') ?>">Вход</a>
                <a class="btn btn-dark" href="<?= url('register') ?>">Регистрация</a>
            <?php else: ?>
                <?php if ($me['role'] === 'customer'): ?>
                    <a class="btn btn-dark" href="<?= url('order_create') ?>">+ Заказ</a>
                <?php endif; ?>
                <?php if ($me['role'] === 'admin'): ?>
                    <a class="btn btn-dark" href="<?= url('admin') ?>">Админ-панель</a>
                    <a class="user-link" href="<?= url('logout') ?>">Выход</a>
                <?php else: ?>
                    <a class="icon-btn" href="<?= url('chats') ?>" title="Сообщения" aria-label="Сообщения<?= $unread ? ', непрочитанных: ' . $unread : '' ?>">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v7A2.5 2.5 0 0 1 17.5 16H9l-4 3.5V6.5z"/>
                        </svg>
                        <?php if ($unread): ?><span class="counter"><?= $unread ?></span><?php endif; ?>
                    </a>
                    <div class="account-menu" data-account-menu>
                        <button class="account-toggle" type="button" aria-expanded="false" aria-haspopup="true" data-account-toggle>
                            <?= user_avatar($me, 'avatar-header') ?>
                            <span class="account-caret" aria-hidden="true"></span>
                        </button>
                        <div class="account-dropdown" hidden>
                            <div class="account-dropdown-head">
                                <strong><?= e($me['name']) ?></strong>
                                <span><?= e(ROLES[$me['role']] ?? '') ?></span>
                            </div>
                            <a href="<?= url('cabinet') ?>">Личный кабинет</a>
                            <a href="<?= url('profile') ?>">Личные данные</a>
                            <?php if ($me['role'] === 'executor'): ?>
                                <a href="<?= url('resume_edit') ?>">Моё резюме</a>
                            <?php endif; ?>
                            <a href="<?= url('chats') ?>">Сообщения<?php if ($unread): ?> <span class="counter"><?= $unread ?></span><?php endif; ?></a>
                            <a class="account-logout" href="<?= url('logout') ?>">Выход</a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </nav>

        <button class="burger" type="button" aria-label="Открыть меню" aria-controls="main-nav" data-menu-button>
            <span></span><span></span><span></span>
        </button>
    </div>

    <nav class="main-nav" id="main-nav" aria-label="Основное меню">
        <div class="container nav-list">
            <div class="nav-item has-sub">
                <a href="<?= url('orders') ?>">Работа</a>
                <div class="sub">
                    <a href="<?= url('orders') ?>">Все заказы</a>
                    <?php foreach (Category::sections() as $navSection): ?>
                        <a href="<?= url('category', ['id' => $navSection['id']]) ?>"><?= e($navSection['name']) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="nav-item has-sub">
                <a href="<?= url('orders') ?>">Фрилансеру</a>
                <div class="sub">
                    <a href="<?= url('orders') ?>">Найти заказ</a>
                    <?php if (!$me || $me['role'] === 'executor'): ?>
                        <a href="<?= url('resume_edit') ?>">Моё резюме</a>
                        <a href="<?= url('cabinet') ?>">Мои отклики</a>
                        <a href="<?= url('chats') ?>">Сообщения</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="nav-item">
                <a href="<?= url('resumes') ?>">Исполнители</a>
            </div>
            <div class="nav-item has-sub">
                <a href="<?= url('info', ['slug' => 'about']) ?>">Ещё</a>
                <div class="sub">
                    <a href="<?= url('info', ['slug' => 'about']) ?>">О проекте</a>
                    <a href="<?= url('info', ['slug' => 'faq']) ?>">Вопрос – ответ</a>
                    <a href="<?= url('info', ['slug' => 'support']) ?>">Служба поддержки</a>
                    <a href="<?= url('info', ['slug' => 'rules']) ?>">Правила площадки</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<main class="main">
    <?php foreach (take_flashes() as $flash): ?>
        <div class="container">
            <div class="alert alert-<?= e($flash['type']) ?>" role="status"><?= e($flash['text']) ?></div>
        </div>
    <?php endforeach; ?>
