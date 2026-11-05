<?php $me = current_user(); ?>
<div class="container">
    <div class="breadcrumbs">
        <a href="<?= url('resumes') ?>">Исполнители</a> / <?= e($resume['category_name']) ?>
    </div>

    <div class="profile-head">
        <?= user_avatar(['name' => $resume['user_name'], 'avatar' => $resume['avatar'] ?? $resume['photo'] ?? null], 'avatar-light') ?>
        <div>
            <h1><?= e($resume['user_name']) ?></h1>
            <p class="muted"><?= e($resume['title']) ?></p>
            <?php $langRaw = $resume['language'] ?? 'ru'; require __DIR__ . '/../partials/lang_chips.php'; ?>
        </div>
    </div>

    <?php if ($resume['is_hidden']): ?>
        <div class="alert alert-error">Резюме скрыто администратором и не показывается в каталоге.</div>
    <?php endif; ?>

    <div class="layout-2">
        <div>
            <div class="panel">
                <h2>Об опыте</h2>
                <p class="pre"><?= e($resume['about']) ?></p>
                <h2>Навыки</h2>
                <ul class="tags">
                    <?php foreach (explode(',', $resume['skills']) as $skill): ?>
                        <?php if (trim($skill) !== ''): ?><li><?= e(trim($skill)) ?></li><?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <?php if ($gallery): ?>
                    <h2>Примеры работ</h2>
                    <div class="gallery">
                        <?php foreach ($gallery as $file): ?>
                            <a class="gallery-item" href="<?= e(upload_url($file)) ?>" target="_blank" rel="noopener">
                                <img src="<?= e(upload_url($file)) ?>" alt="Пример работы <?= e($resume['user_name']) ?>" loading="lazy">
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if ($portfolio): ?>
                    <h2>Ссылки</h2>
                    <?php foreach ($portfolio as $link): ?>
                        <p><a href="<?= e($link) ?>" target="_blank" rel="noopener nofollow"><?= e($link) ?></a></p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="panel">
                <h2>Отзывы (<?= count($reviews) ?>)</h2>
                <?php if (!$reviews): ?>
                    <p class="muted">У исполнителя пока нет отзывов.</p>
                <?php endif; ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="response">
                        <div class="response-head">
                            <b><?= e($review['customer_name']) ?></b>
                            <span class="dark-stars"><?= stars($review['rating']) ?></span>
                        </div>
                        <p class="small muted">Заказ «<?= e($review['order_title']) ?>», <?= date_ru($review['created_at']) ?></p>
                        <p class="pre"><?= e($review['text']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <aside>
            <div class="panel">
                <dl class="facts">
                    <div><dt>Рейтинг</dt><dd class="dark-stars"><?= $resume['rating'] ? stars($resume['rating']) . ' ' . e($resume['rating']) : 'нет оценок' ?></dd></div>
                    <div><dt>Отзывов</dt><dd><?= (int)$resume['reviews_count'] ?></dd></div>
                    <div><dt>Ставка</dt><dd><?= $resume['rate'] ? money($resume['rate']) . '/час' : 'по договорённости' ?></dd></div>
                    <div><dt>Категория</dt><dd><?= e($resume['category_name']) ?></dd></div>
                    <div><dt>Обновлено</dt><dd><?= date_ru($resume['updated_at']) ?></dd></div>
                </dl>
            </div>
            <div class="panel">
                <?php if ($isOwner): ?>
                    <a class="btn" href="<?= url('resume_edit') ?>">Редактировать резюме</a>
                <?php elseif ($me && $me['role'] === 'customer'): ?>
                    <p>Обсудите задачу с исполнителем до публикации заказа.</p>
                    <form method="post" action="<?= url('chat_start') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="user_id" value="<?= $resume['user_id'] ?>">
                        <button class="btn" type="submit">Написать исполнителю</button>
                    </form>
                <?php elseif (!$me): ?>
                    <p>Войдите как заказчик, чтобы написать исполнителю.</p>
                    <a class="btn" href="<?= url('login') ?>">Войти</a>
                <?php else: ?>
                    <p class="muted">Писать исполнителям могут заказчики.</p>
                <?php endif; ?>
            </div>
        </aside>
    </div>
</div>
