<article class="order-card">
    <div>
        <h3><a href="<?= url('order', ['id' => $order['id']]) ?>"><?= e($order['title']) ?></a></h3>
        <p class="order-desc clamp-2"><?= e($order['description']) ?></p>
        <div class="order-meta">
            <span><?= e($order['category_name']) ?></span>
            <span>Срок: до <?= date_ru($order['deadline']) ?></span>
            <span><?= plural((int)$order['responses_count'], 'отклик', 'отклика', 'откликов') ?></span>
            <span>Опубликован <?= date_ru($order['created_at']) ?></span>
        </div>
    </div>
    <div class="order-side">
        <div class="order-budget"><?= money($order['budget']) ?></div>
        <?php if (!empty($showStatus)): ?>
            <?= status_badge($order['status']) ?>
        <?php endif; ?>
    </div>
</article>
