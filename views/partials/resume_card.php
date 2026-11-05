<a class="person-card" href="<?= url('resume', ['id' => $resume['id']]) ?>">
    <div class="person-head">
        <?= resume_avatar($resume) ?>
        <span class="person-name"><?= e($resume['user_name']) ?></span>
        <span class="person-title"><?= e($resume['title']) ?></span>
    </div>
    <div class="person-body">
        <p class="clamp-3"><?= e($resume['about']) ?></p>
        <div class="person-meta">
            <?php if ($resume['rating']): ?>
                <span><?= stars($resume['rating']) ?> <?= e($resume['rating']) ?></span>
            <?php else: ?>
                <span>Пока без отзывов</span>
            <?php endif; ?>
            <?php if ($resume['rate']): ?>
                <b><?= money($resume['rate']) ?>/час</b>
            <?php endif; ?>
        </div>
    </div>
</a>
