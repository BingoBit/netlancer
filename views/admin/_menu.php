<?php
$adminPages = [
    'admin'            => 'Сводка',
    'admin_orders'     => 'Заказы',
    'admin_users'      => 'Пользователи',
    'admin_resumes'    => 'Резюме',
    'admin_categories' => 'Категории',
];
$active = query('page');
if ($active === 'admin_dialog') {
    $active = 'admin_orders';
}
?>
<nav class="admin-menu" aria-label="Разделы административной панели">
    <?php foreach ($adminPages as $pageName => $label): ?>
        <a class="<?= $active === $pageName ? 'active' : '' ?>" href="<?= url($pageName) ?>"><?= $label ?></a>
    <?php endforeach; ?>
</nav>
