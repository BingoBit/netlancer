<?php
$lkUser = $user ?? current_user();
$lkPage = query('page') ?: 'cabinet';
$isExecutor = $lkUser && $lkUser['role'] === 'executor';
$isCustomer = $lkUser && $lkUser['role'] === 'customer';
?>
<aside class="lk-aside">
    <nav class="lk-side-nav" aria-label="Разделы кабинета">
        <a class="<?= $lkPage === 'profile' ? 'active' : '' ?>" href="<?= url('profile') ?>">Личные данные</a>
        <?php if ($isExecutor): ?>
            <a class="<?= $lkPage === 'resume_edit' ? 'active' : '' ?>" href="<?= url('resume_edit') ?>">Моё резюме</a>
        <?php endif; ?>
        <a class="<?= $lkPage === 'cabinet' ? 'active' : '' ?>" href="<?= url('cabinet') ?>">Мои заказы</a>
        <a class="<?= in_array($lkPage, ['chats', 'chat'], true) ? 'active' : '' ?>" href="<?= url('chats') ?>">Сообщения</a>
        <?php if ($isCustomer): ?>
            <a class="btn" href="<?= url('order_create') ?>">+ Разместить заказ</a>
        <?php elseif ($isExecutor): ?>
            <a class="btn" href="<?= url('orders') ?>">Найти заказ</a>
        <?php endif; ?>
    </nav>
</aside>
