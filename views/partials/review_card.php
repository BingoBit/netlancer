<article class="review-card">
    <div class="review-head">
        <span class="avatar"><?= e(initials($review['customer_name'])) ?></span>
        <span class="review-name"><?= e($review['customer_name']) ?></span>
        <span class="review-role">Заказчик</span>
    </div>
    <div class="review-rating">
        <?= stars($review['rating']) ?>
        <span><?= date_ru($review['created_at']) ?></span>
    </div>
    <div class="review-order"><?= e($review['order_title']) ?> – <?= money($review['budget']) ?></div>
    <p class="review-text"><?= e($review['text']) ?></p>
    <div class="review-foot">
        Исполнитель –
        <?php if ($review['resume_id']): ?>
            <a href="<?= url('resume', ['id' => $review['resume_id']]) ?>"><?= e($review['executor_name']) ?></a>
        <?php else: ?>
            <?= e($review['executor_name']) ?>
        <?php endif; ?>
    </div>
</article>
