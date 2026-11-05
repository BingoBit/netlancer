<?php
$tabNames = [
    'open'        => 'Открытые',
    'moderation'  => 'На модерации',
    'in_progress' => 'В работе',
    'completed'   => 'Завершённые',
    'closed'      => 'Отменённые',
];
$first = 'open';
foreach ($tabNames as $key => $name) {
    if ($tabs[$key]) {
        $first = $key;
        break;
    }
}
$totalOrders = array_sum(array_map('count', $tabs));
$year = date('Y', strtotime($user['created_at']));
?>
<div class="container cabinet">
    <div class="breadcrumbs">
        <a href="<?= url('home') ?>">Главная</a> / Личный кабинет
    </div>

    <div class="lk-layout">
        <div class="lk-main">
            <section class="lk-card">
                <div class="lk-card-main">
                    <?= user_avatar($user, 'avatar-light lk-avatar') ?>
                    <div class="lk-card-info">
                        <div class="lk-card-title">
                            <h1><?= e($user['name']) ?></h1>
                            <span class="badge badge-open"><?= e(ROLES[$user['role']]) ?></span>
                        </div>
                        <p class="muted"><?= e($user['email']) ?></p>
                        <?php $langRaw = $user['language'] ?? 'ru'; require __DIR__ . '/../partials/lang_chips.php'; ?>
                        <div class="lk-stats">
                            <div>
                                <span class="lk-stat-label">Заказы</span>
                                <b><?= $totalOrders ?></b>
                            </div>
                            <div>
                                <span class="lk-stat-label">В работе</span>
                                <b><?= count($tabs['in_progress']) ?></b>
                            </div>
                            <div>
                                <span class="lk-stat-label">Завершено</span>
                                <b><?= count($tabs['completed']) ?></b>
                            </div>
                            <div>
                                <span class="lk-stat-label">Отзывы</span>
                                <b><?= (int)$reviews ?></b>
                            </div>
                        </div>
                        <p class="small muted">На сайте с <?= e($year) ?> года</p>
                    </div>
                </div>
            </section>

            <section class="lk-section">
                <div class="lk-section-head">
                    <h2>Обо мне</h2>
                    <a class="lk-edit" href="<?= url('profile') ?>" title="Редактировать">Изменить</a>
                </div>
                <div class="panel lk-about">
                    <?php if (trim((string)$user['about']) !== ''): ?>
                        <p class="pre"><?= e($user['about']) ?></p>
                    <?php else: ?>
                        <p class="muted">Расскажите о себе — это поможет исполнителям лучше понимать, с кем они работают.
                            <a href="<?= url('profile') ?>">Заполнить профиль</a></p>
                    <?php endif; ?>
                </div>
            </section>

            <section class="lk-section" id="orders">
                <div class="lk-section-head">
                    <h2>Мои заказы</h2>
                </div>

                <nav class="tabs" data-tabs>
                    <?php foreach ($tabNames as $key => $name): ?>
                        <a href="#tab-<?= $key ?>" class="<?= $key === $first ? 'active' : '' ?>"><?= $name ?> (<?= count($tabs[$key]) ?>)</a>
                    <?php endforeach; ?>
                </nav>

                <?php foreach ($tabNames as $key => $name): ?>
                    <div class="tab-panel <?= $key === $first ? 'active' : '' ?>" id="tab-<?= $key ?>">
                        <?php if (!$tabs[$key]): ?>
                            <div class="empty">
                                Здесь пока пусто.
                                <?php if ($key === 'open'): ?>
                                    <div class="btn-row center lk-empty-action">
                                        <a class="btn" href="<?= url('order_create') ?>">Разместить первый заказ</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="orders-list">
                                <?php $showStatus = true; ?>
                                <?php foreach ($tabs[$key] as $order): ?>
                                    <?php require __DIR__ . '/../partials/order_card.php'; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </section>
        </div>

        <?php require __DIR__ . '/../partials/lk_nav.php'; ?>
    </div>
</div>
