<?php $maxStatus = max(1, max($statuses)); ?>
<div class="container admin-layout">
    <?php require __DIR__ . '/_menu.php'; ?>

    <div>
        <h1>Сводка</h1>
        <div class="stats">
            <div class="stat"><b><?= $roles['customer'] + $roles['executor'] ?></b>Пользователи (заказчиков <?= $roles['customer'] ?>, исполнителей <?= $roles['executor'] ?>)</div>
            <div class="stat"><b><?= array_sum($statuses) ?></b>Заказы всего</div>
            <div class="stat"><b><?= $responses ?></b>Отклики</div>
            <div class="stat"><b><?= $messages ?></b>Сообщения в чате (отзывов: <?= $reviews ?>)</div>
        </div>

        <div class="panel">
            <h2>Заказы по состояниям</h2>
            <div class="bars">
                <?php foreach (ORDER_STATUSES as $key => $label): ?>
                    <div class="bar">
                        <a href="<?= url('admin_orders', ['status' => $key]) ?>"><?= $label ?></a>
                        <div class="bar-track"><div class="bar-fill" style="width: <?= round($statuses[$key] / $maxStatus * 100) ?>%"></div></div>
                        <b><?= $statuses[$key] ?></b>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="panel">
            <h2>Ждут модерации</h2>
            <?php if (!$moderation): ?>
                <p class="muted">Новых заказов на проверку нет.</p>
            <?php else: ?>
                <div class="orders-list">
                    <?php foreach ($moderation as $order): ?>
                        <?php require __DIR__ . '/../partials/order_card.php'; ?>
                    <?php endforeach; ?>
                </div>
                <p class="form-note"><a href="<?= url('admin_orders', ['status' => 'moderation']) ?>">Перейти к модерации →</a></p>
            <?php endif; ?>
        </div>
    </div>
</div>
