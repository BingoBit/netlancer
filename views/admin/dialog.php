<div class="container admin-layout">
    <?php require __DIR__ . '/_menu.php'; ?>

    <div>
        <h1>Переписка по заказу</h1>
        <p>Заказ: <a href="<?= url('order', ['id' => $order['id']]) ?>"><?= e($order['title']) ?></a> · <?= status_badge($order['status']) ?></p>
        <p class="muted">Заказчик: <?= e($order['customer_name']) ?> · Исполнитель: <?= e($order['executor_name']) ?></p>

        <?php if (!$messages): ?>
            <div class="empty">Участники заказа ещё не переписывались.</div>
        <?php else: ?>
            <div class="chat-messages">
                <?php foreach ($messages as $message): ?>
                    <div class="msg <?= $message['sender_id'] === $order['executor_id'] ? 'mine' : '' ?>">
                        <p><b><?= e($message['sender_name']) ?>:</b> <?= e($message['body']) ?></p>
                        <time><?= date_ru($message['created_at'], true) ?></time>
                    </div>
                <?php endforeach; ?>
            </div>
            <p class="form-note">Справа – сообщения исполнителя, слева – заказчика. Просмотр только для чтения.</p>
        <?php endif; ?>
    </div>
</div>
