<?php
$inWork = 0;
$done = 0;
foreach ($orders as $order) {
    if ($order['status'] === 'in_progress') {
        $inWork++;
    } elseif ($order['status'] === 'completed') {
        $done++;
    }
}
$year = date('Y', strtotime($user['created_at']));
$rating = $resume['rating'] ?? null;
$reviewCount = count($reviews);
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
                        <?php if ($resume): ?>
                            <p class="muted"><?= e($resume['title']) ?></p>
                        <?php endif; ?>
                        <?php $langRaw = $user['language'] ?? 'ru'; require __DIR__ . '/../partials/lang_chips.php'; ?>
                        <div class="lk-stats">
                            <div>
                                <span class="lk-stat-label">Отклики</span>
                                <b><?= count($responses) ?></b>
                            </div>
                            <div>
                                <span class="lk-stat-label">В работе</span>
                                <b><?= $inWork ?></b>
                            </div>
                            <div>
                                <span class="lk-stat-label">Завершено</span>
                                <b><?= $done ?></b>
                            </div>
                            <div>
                                <span class="lk-stat-label">Оценка</span>
                                <b><?= $rating ? e($rating) : '–' ?></b>
                            </div>
                        </div>
                        <p class="small muted">На сайте с <?= e($year) ?> года<?php if ($reviewCount): ?> · отзывов: <?= $reviewCount ?><?php endif; ?></p>
                    </div>
                </div>
            </section>

            <?php if (!$resume): ?>
                <div class="alert alert-error">Резюме не заполнено – без него нельзя откликаться на заказы.
                    <a href="<?= url('resume_edit') ?>">Заполнить резюме</a></div>
            <?php endif; ?>

            <section class="lk-section">
                <div class="lk-section-head">
                    <h2>Обо мне</h2>
                    <a class="lk-edit" href="<?= url('profile') ?>">Изменить</a>
                </div>
                <div class="panel lk-about">
                    <?php if (trim((string)$user['about']) !== ''): ?>
                        <p class="pre"><?= e($user['about']) ?></p>
                    <?php elseif ($resume): ?>
                        <p class="pre clamp-3"><?= e($resume['about']) ?></p>
                        <p class="form-note"><a href="<?= url('resume', ['id' => $resume['id']]) ?>">Открыть резюме →</a></p>
                    <?php else: ?>
                        <p class="muted">Расскажите о себе в профиле или заполните резюме — так заказчикам проще вас выбрать.
                            <a href="<?= url('profile') ?>">Заполнить профиль</a></p>
                    <?php endif; ?>
                </div>
            </section>

            <section class="lk-section" id="orders">
                <div class="lk-section-head">
                    <h2>Заказы, где я исполнитель</h2>
                </div>
                <div class="panel">
                    <?php if (!$orders): ?>
                        <p class="muted">Пока нет. Откликайтесь на заказы – заказчик выберет вас на странице заказа.</p>
                        <div class="btn-row lk-empty-action">
                            <a class="btn" href="<?= url('orders') ?>">Найти заказ</a>
                        </div>
                    <?php else: ?>
                        <div class="orders-list">
                            <?php $showStatus = true; ?>
                            <?php foreach ($orders as $order): ?>
                                <?php require __DIR__ . '/../partials/order_card.php'; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <section class="lk-section">
                <div class="lk-section-head">
                    <h2>Мои отклики</h2>
                </div>
                <div class="panel">
                    <?php if (!$responses): ?>
                        <p class="muted">Вы ещё не откликались на заказы.</p>
                    <?php else: ?>
                        <div class="table-wrap">
                            <table class="table">
                                <thead>
                                    <tr><th>Заказ</th><th>Моя цена</th><th>Срок</th><th>Дата</th><th>Состояние</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($responses as $response): ?>
                                        <tr>
                                            <td><a href="<?= url('order', ['id' => $response['order_id']]) ?>"><?= e($response['order_title']) ?></a></td>
                                            <td><?= money($response['price']) ?></td>
                                            <td><?= plural((int)$response['days'], 'день', 'дня', 'дней') ?></td>
                                            <td><?= date_ru($response['created_at']) ?></td>
                                            <td><?= response_badge($response['status']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>

        <?php require __DIR__ . '/../partials/lk_nav.php'; ?>
    </div>
</div>
