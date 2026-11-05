<div class="container cabinet">
    <div class="lk-layout">
        <div class="lk-main">
            <div class="chat-head">
                <div>
                    <div class="breadcrumbs"><a href="<?= url('chats') ?>">Сообщения</a> / <?= e($partner) ?></div>
                    <h1><?= e($partner) ?></h1>
                    <?php if ($dialog['order_id']): ?>
                        <p class="muted">Обсуждение заказа:
                            <a href="<?= url('order', ['id' => $dialog['order_id']]) ?>"><?= e($dialog['order_title']) ?></a></p>
                    <?php endif; ?>
                </div>
                <form method="post" action="<?= url('chat_delete') ?>"
                      data-confirm="Удалить переписку с «<?= e($partner) ?>»? Сообщения исчезнут у обоих участников.">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $dialog['id'] ?>">
                    <button class="btn btn-danger btn-small" type="submit">Удалить переписку</button>
                </form>
            </div>

            <div class="chat" id="chat" data-dialog="<?= $dialog['id'] ?>" data-last-id="<?= $lastId ?>" data-interval="<?= CHAT_POLL_SECONDS ?>">
                <div class="chat-messages" id="chat-messages" aria-live="polite">
                    <?php if (!$messages): ?>
                        <div class="empty">Напишите первое сообщение.</div>
                    <?php endif; ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="msg <?= $message['sender_id'] === $user['id'] ? 'mine' : '' ?>" data-id="<?= $message['id'] ?>">
                            <p><?= e($message['body']) ?></p>
                            <time><?= date_ru($message['created_at'], true) ?></time>
                        </div>
                    <?php endforeach; ?>
                </div>

                <form class="chat-form" id="chat-form" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $dialog['id'] ?>">
                    <textarea class="input" name="text" maxlength="2000" placeholder="Сообщение… (Enter – отправить, Shift+Enter – новая строка)" required aria-label="Текст сообщения"></textarea>
                    <button class="btn" type="submit">Отправить</button>
                </form>
            </div>
        </div>

        <?php require __DIR__ . '/../partials/lk_nav.php'; ?>
    </div>
</div>
<script src="assets/js/chat.js" defer></script>
