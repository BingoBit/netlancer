<?php $me = current_user(); ?>

<section class="hero">
    <div class="container hero-inner">
        <div>
            <h1>Найдите исполнителя<br><span>для любой задачи</span></h1>
            <p>Публикуйте заказы, сравнивайте отклики специалистов и обсуждайте детали во внутреннем чате.
                Без платы за отклики и без комиссии с исполнителей.</p>
            <div class="btn-row">
                <?php if (!$me || $me['role'] === 'customer'): ?>
                    <a class="btn" href="<?= url($me ? 'order_create' : 'register') ?>">Разместить заказ</a>
                <?php endif; ?>
                <a class="btn btn-outline" href="<?= url('orders') ?>">Найти работу</a>
            </div>
        </div>
        <ul class="hero-stats">
            <li><b><?= $stats['executors'] ?></b>Исполнители на площадке</li>
            <li><b><?= $stats['open'] ?></b>Открытые заказы ждут откликов</li>
            <li><b><?= $stats['completed'] ?></b>Выполненные заказы</li>
        </ul>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 class="section-title">Лучшие исполнители</h2>
        <div class="people">
            <?php foreach ($resumes as $resume): ?>
                <?php require __DIR__ . '/../partials/resume_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="how" id="how">
    <div class="container">
        <h2>Как пользоваться NetLancer?</h2>
        <div class="how-grid">
            <div>
                <div class="how-icon">
                    <svg viewBox="0 0 24 24" width="92" height="92" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                </div>
                <h3>Опубликуйте заказ</h3>
                <p>Опишите задачу, бюджет и срок. После проверки модератором заказ увидят исполнители.</p>
            </div>
            <div>
                <div class="how-icon">
                    <svg viewBox="0 0 24 24" width="92" height="92" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8z"/><line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="13.5" x2="13" y2="13.5"/></svg>
                </div>
                <h3>Выберите исполнителя</h3>
                <p>Сравните отклики и резюме, уточните детали во внутреннем чате и назначьте исполнителя.</p>
            </div>
            <div>
                <div class="how-icon">
                    <svg viewBox="0 0 24 24" width="92" height="92" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.3a2 2 0 0 0 2-1.7l1.4-9a2 2 0 0 0-2-2.3z"/><path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
                </div>
                <h3>Примите результат</h3>
                <p>Исполнитель сдаёт работу, вы подтверждаете выполнение и оставляете отзыв с оценкой.</p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="catalog-head">
            <h2 class="section-title">Свежие заказы</h2>
            <a href="<?= url('orders') ?>">Все заказы →</a>
        </div>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <?php require __DIR__ . '/../partials/order_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if ($reviews): ?>
<section class="section">
    <div class="container">
        <h2 class="section-title">Отзывы пользователей</h2>
        <div class="reviews-wrap">
            <div class="reviews" id="reviews">
                <?php foreach ($reviews as $review): ?>
                    <?php require __DIR__ . '/../partials/review_card.php'; ?>
                <?php endforeach; ?>
            </div>
            <button class="scroll-next" type="button" data-scroll="reviews" aria-label="Следующие отзывы">➤</button>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section">
    <div class="container about-text">
        <h2>NetLancer – это не просто фриланс!</h2>
        <p>Где найти хорошего специалиста для разовой задачи? На NetLancer заказчики публикуют заказы,
            а дизайнеры, программисты, копирайтеры, маркетологи и видеомонтажёры откликаются на них со своей ценой и сроком.
            Перед выбором исполнителя можно изучить его резюме, портфолио и отзывы других заказчиков,
            а все договорённости сохраняются во внутреннем чате.</p>
        <p>Умеете разрабатывать сайты, писать тексты, рисовать или вести соцсети? Заполните резюме,
            откликайтесь на заказы бесплатно и собирайте отзывы, которые помогут получать новые проекты.</p>
    </div>
</section>
