<div class="container cabinet">
    <div class="breadcrumbs">
        <a href="<?= url('home') ?>">Главная</a> /
        <a href="<?= url('cabinet') ?>">Личный кабинет</a> /
        Сообщения
    </div>

    <div class="lk-layout">
        <div class="lk-main">
            <div class="lk-section-head">
                <h1>Сообщения</h1>
            </div>

            <?php if (!$dialogs): ?>
                <div class="empty">
                    Диалогов пока нет. Начать переписку можно со страницы заказа или резюме исполнителя.
                </div>
            <?php else: ?>
                <div class="chat-list">
                    <?php foreach ($dialogs as $dialog): ?>
                        <?php
                        $isCustomerSide = $user['id'] === $dialog['customer_id'];
                        $partner = $isCustomerSide ? $dialog['executor_name'] : $dialog['customer_name'];
                        $partnerAvatar = $isCustomerSide ? ($dialog['executor_avatar'] ?? null) : ($dialog['customer_avatar'] ?? null);
                        ?>
                        <div class="chat-item-row">
                            <a class="chat-item" href="<?= url('chat', ['id' => $dialog['id']]) ?>">
                                <?= user_avatar(['name' => $partner, 'avatar' => $partnerAvatar]) ?>
                                <span>
                                    <b><?= e($partner) ?></b>
                                    <?php if ($dialog['order_title']): ?><span class="small muted"> · <?= e($dialog['order_title']) ?></span><?php endif; ?><br>
                                    <span class="muted clamp-2"><?= e($dialog['last_text'] ?? 'Сообщений пока нет') ?></span>
                                </span>
                                <span>
                                    <?php if ($dialog['unread']): ?><span class="counter"><?= (int)$dialog['unread'] ?></span><?php endif; ?>
                                    <span class="small muted"><?= date_ru($dialog['last_message_at'] ?? $dialog['created_at'], true) ?></span>
                                </span>
                            </a>
                            <form class="chat-item-delete" method="post" action="<?= url('chat_delete') ?>"
                                  data-confirm="Удалить переписку с «<?= e($partner) ?>»? Сообщения исчезнут у обоих участников.">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $dialog['id'] ?>">
                                <button class="btn btn-danger btn-small" type="submit" title="Удалить переписку">Удалить</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php require __DIR__ . '/../partials/lk_nav.php'; ?>
    </div>
</div>
