<div class="container admin-layout">
    <?php require __DIR__ . '/_menu.php'; ?>

    <div>
        <h1>Заказы</h1>
        <nav class="tabs">
            <?php foreach (ORDER_STATUSES as $key => $label): ?>
                <a class="<?= $key === $status ? 'active' : '' ?>" href="<?= url('admin_orders', ['status' => $key]) ?>"><?= $label ?> (<?= $counts[$key] ?>)</a>
            <?php endforeach; ?>
        </nav>

        <?php if (!$orders): ?>
            <div class="empty">Заказов в этом состоянии нет.</div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr><th>Заказ</th><th>Заказчик</th><th>Бюджет</th><th>Создан</th><th>Действия</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>
                                <a href="<?= url('order', ['id' => $order['id']]) ?>"><?= e($order['title']) ?></a>
                                <div class="small muted"><?= e($order['category_name']) ?></div>
                                <?php if ($order['is_hidden']): ?><span class="badge badge-rejected">Скрыт</span><?php endif; ?>
                                <?php if ($order['reject_reason']): ?><div class="small muted">Причина: <?= e($order['reject_reason']) ?></div><?php endif; ?>
                            </td>
                            <td><?= e($order['customer_name']) ?></td>
                            <td><?= money($order['budget']) ?></td>
                            <td><?= date_ru($order['created_at'], true) ?></td>
                            <td>
                                <?php if ($order['status'] === 'moderation'): ?>
                                    <form method="post" action="<?= url('admin_order_action') ?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= $order['id'] ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <button class="btn btn-small" type="submit">Одобрить</button>
                                    </form>
                                    <form class="reject-form" method="post" action="<?= url('admin_order_action') ?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= $order['id'] ?>">
                                        <input type="hidden" name="action" value="reject">
                                        <input class="input" name="reason" placeholder="Причина отклонения" required minlength="5">
                                        <button class="btn btn-danger btn-small" type="submit">Отклонить</button>
                                    </form>
                                <?php else: ?>
                                    <div class="btn-row">
                                        <form class="inline-form" method="post" action="<?= url('admin_order_action') ?>">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= $order['id'] ?>">
                                            <input type="hidden" name="action" value="<?= $order['is_hidden'] ? 'show' : 'hide' ?>">
                                            <button class="btn btn-small <?= $order['is_hidden'] ? '' : 'btn-danger' ?>" type="submit"><?= $order['is_hidden'] ? 'Показать' : 'Скрыть' ?></button>
                                        </form>
                                        <?php if ($order['executor_id']): ?>
                                            <a class="btn btn-outline btn-small" href="<?= url('admin_dialog', ['order' => $order['id']]) ?>">Переписка</a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php require __DIR__ . '/../partials/pagination.php'; ?>
        <?php endif; ?>
    </div>
</div>
