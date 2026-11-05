<?php $me = current_user(); ?>
<div class="container">
    <div class="breadcrumbs">
        <a href="<?= url('orders') ?>">Заказы</a>
        <?php foreach ($path as $item): ?>
            / <a href="<?= url('orders', ['category' => $item['id']]) ?>"><?= e($item['name']) ?></a>
        <?php endforeach; ?>
    </div>

    <div class="layout-2">
        <div>
            <div class="panel">
                <div class="btn-row">
                    <?= status_badge($order['status']) ?>
                    <?php if ($order['is_hidden']): ?><span class="badge badge-rejected">Скрыт администратором</span><?php endif; ?>
                    <?php if ($order['status'] === 'in_progress' && $order['delivered_at']): ?>
                        <span class="badge badge-completed">Работа сдана <?= date_ru($order['delivered_at']) ?></span>
                    <?php endif; ?>
                </div>
                <h1><?= e($order['title']) ?></h1>
                <p class="pre"><?= e($order['description']) ?></p>

                <?php if ($order['status'] === 'moderation' && $isOwner): ?>
                    <div class="alert">Заказ проверяется модератором и пока не виден исполнителям.</div>
                <?php endif; ?>
                <?php if ($order['status'] === 'rejected' && $order['reject_reason'] && ($isOwner || $isAdmin)): ?>
                    <div class="alert alert-error">Причина отклонения: <?= e($order['reject_reason']) ?></div>
                <?php endif; ?>
            </div>

            <?php if ($isOwner || $isAdmin): ?>
                <div class="panel" id="responses">
                    <h2>Отклики (<?= count($responses) ?>)</h2>
                    <?php if (!$responses): ?>
                        <p class="muted">Откликов пока нет. Исполнители увидят заказ после модерации.</p>
                    <?php endif; ?>
                    <?php foreach ($responses as $response): ?>
                        <div class="response">
                            <div class="response-head">
                                <div class="who">
                                    <?= user_avatar(['name' => $response['executor_name'], 'avatar' => $response['avatar'] ?? null], 'avatar-light') ?>
                                    <div>
                                        <?php if ($response['resume_id']): ?>
                                            <b><a href="<?= url('resume', ['id' => $response['resume_id']]) ?>"><?= e($response['executor_name']) ?></a></b>
                                        <?php else: ?>
                                            <b><?= e($response['executor_name']) ?></b>
                                        <?php endif; ?>
                                        <div class="small muted dark-stars">
                                            <?= e($response['resume_title'] ?? '') ?>
                                            <?php if ($response['rating']): ?> · <?= stars($response['rating']) ?> <?= e($response['rating']) ?><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <b class="order-budget"><?= money($response['price']) ?></b>
                                    <span class="muted">за <?= plural((int)$response['days'], 'день', 'дня', 'дней') ?></span>
                                </div>
                            </div>
                            <p class="pre"><?= e($response['message']) ?></p>
                            <div class="btn-row">
                                <?= response_badge($response['status']) ?>
                                <?php if ($isOwner && $order['status'] === 'open' && $response['status'] === 'sent'): ?>
                                    <form class="inline-form" method="post" action="<?= url('response_accept') ?>"
                                          data-confirm="Выбрать исполнителем: <?= e($response['executor_name']) ?>? Остальные отклики будут отклонены.">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= $response['id'] ?>">
                                        <button class="btn btn-small" type="submit">Выбрать исполнителем</button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($isOwner): ?>
                                    <form class="inline-form" method="post" action="<?= url('chat_start') ?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="user_id" value="<?= $response['executor_id'] ?>">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <button class="btn btn-outline btn-small" type="submit">Написать</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($me && $me['role'] === 'executor' && !$isExecutor): ?>
                <?php if ($myResponse): ?>
                    <div class="panel">
                        <h2>Ваш отклик</h2>
                        <p><b class="order-budget"><?= money($myResponse['price']) ?></b>
                            <span class="muted">за <?= plural((int)$myResponse['days'], 'день', 'дня', 'дней') ?></span></p>
                        <p class="pre"><?= e($myResponse['message']) ?></p>
                        <div class="btn-row">
                            <?= response_badge($myResponse['status']) ?>
                            <?php if ($myResponse['status'] === 'sent'): ?>
                                <form class="inline-form" method="post" action="<?= url('response_withdraw') ?>" data-confirm="Отозвать отклик?">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $myResponse['id'] ?>">
                                    <button class="btn btn-danger btn-small" type="submit">Отозвать отклик</button>
                                </form>
                            <?php endif; ?>
                            <form class="inline-form" method="post" action="<?= url('chat_start') ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="user_id" value="<?= $order['customer_id'] ?>">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <button class="btn btn-outline btn-small" type="submit">Написать заказчику</button>
                            </form>
                        </div>
                    </div>
                <?php elseif ($order['status'] === 'open' && !$hasResume): ?>
                    <div class="panel">
                        <h2>Откликнуться на заказ</h2>
                        <p>Чтобы откликаться на заказы, заполните резюме – заказчик увидит его вместе с откликом.</p>
                        <a class="btn" href="<?= url('resume_edit') ?>">Заполнить резюме</a>
                    </div>
                <?php elseif ($order['status'] === 'open'): ?>
                    <div class="panel" id="respond">
                        <h2>Откликнуться на заказ</h2>
                        <form class="form" method="post" action="<?= url('response_create') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <div class="field">
                                <label for="message">Сопроводительное сообщение</label>
                                <textarea class="input" id="message" name="message" rows="5" maxlength="2000" required
                                          placeholder="Расскажите, как выполните задачу и почему выбрать вас"></textarea>
                            </div>
                            <div class="field-row">
                                <div class="field">
                                    <label for="price">Ваша цена, ₽</label>
                                    <input class="input" type="number" id="price" name="price" min="100" step="100" value="<?= (int)$order['budget'] ?>" required>
                                </div>
                                <div class="field">
                                    <label for="days">Срок, дней</label>
                                    <input class="input" type="number" id="days" name="days" min="1" max="365" value="7" required>
                                </div>
                            </div>
                            <button class="btn" type="submit">Отправить отклик</button>
                        </form>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($review): ?>
                <div class="panel">
                    <h2>Отзыв заказчика</h2>
                    <p class="dark-stars"><?= stars($review['rating']) ?> <span class="muted"><?= date_ru($review['created_at']) ?></span></p>
                    <p class="pre"><?= e($review['text']) ?></p>
                </div>
            <?php elseif ($isOwner && $order['status'] === 'completed'): ?>
                <div class="panel">
                    <h2>Оцените работу исполнителя</h2>
                    <form class="form" method="post" action="<?= url('order_review') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $order['id'] ?>">
                        <div class="field">
                            <label>Оценка</label>
                            <div class="rating-input">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="rate<?= $i ?>" name="rating" value="<?= $i ?>" <?= $i === 5 ? 'checked' : '' ?>>
                                    <label for="rate<?= $i ?>" title="<?= $i ?> из 5">★</label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="field">
                            <label for="text">Отзыв</label>
                            <textarea class="input" id="text" name="text" rows="4" maxlength="1000" required
                                      placeholder="Что понравилось, что можно улучшить"></textarea>
                        </div>
                        <button class="btn" type="submit">Опубликовать отзыв</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <aside>
            <div class="panel">
                <dl class="facts">
                    <div><dt>Бюджет</dt><dd><?= money($order['budget']) ?></dd></div>
                    <div><dt>Срок</dt><dd>до <?= date_ru($order['deadline']) ?></dd></div>
                    <div><dt>Категория</dt><dd><?= e($order['category_name']) ?></dd></div>
                    <div><dt>Заказчик</dt><dd><?= e($order['customer_name']) ?></dd></div>
                    <?php if ($order['executor_name']): ?>
                        <div><dt>Исполнитель</dt><dd><?= e($order['executor_name']) ?></dd></div>
                    <?php endif; ?>
                    <div><dt>Отклики</dt><dd><?= (int)$order['responses_count'] ?></dd></div>
                    <div><dt>Создан</dt><dd><?= date_ru($order['created_at']) ?></dd></div>
                </dl>
            </div>

            <div class="panel">
                <?php if (!$me && $order['status'] === 'open'): ?>
                    <p>Чтобы откликнуться на заказ, войдите как исполнитель.</p>
                    <div class="btn-row">
                        <a class="btn" href="<?= url('login') ?>">Войти</a>
                        <a class="btn btn-outline" href="<?= url('register') ?>">Регистрация</a>
                    </div>

                <?php elseif ($isOwner): ?>
                    <?php if (in_array($order['status'], ['moderation', 'open'], true)): ?>
                        <div class="btn-row">
                            <a class="btn btn-outline" href="<?= url('order_edit', ['id' => $order['id']]) ?>">Редактировать</a>
                            <form class="inline-form" method="post" action="<?= url('order_cancel') ?>" data-confirm="Отменить заказ? Все отклики будут отклонены.">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $order['id'] ?>">
                                <button class="btn btn-danger" type="submit">Отменить заказ</button>
                            </form>
                        </div>
                    <?php elseif ($order['status'] === 'in_progress'): ?>
                        <?php if ($order['delivered_at']): ?>
                            <p>Исполнитель сдал работу. Проверьте результат и подтвердите выполнение.</p>
                            <form method="post" action="<?= url('order_complete') ?>" data-confirm="Подтвердить, что работа выполнена?">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $order['id'] ?>">
                                <button class="btn" type="submit">Подтвердить выполнение</button>
                            </form>
                        <?php else: ?>
                            <p class="muted">Заказ в работе. Когда исполнитель сдаст результат, здесь появится кнопка приёмки.</p>
                        <?php endif; ?>
                        <form method="post" action="<?= url('chat_start') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="user_id" value="<?= $order['executor_id'] ?>">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <button class="btn btn-outline" type="submit">Написать исполнителю</button>
                        </form>
                    <?php else: ?>
                        <p class="muted">Это ваш заказ.</p>
                    <?php endif; ?>

                <?php elseif ($isExecutor): ?>
                    <?php if ($order['status'] === 'in_progress' && !$order['delivered_at']): ?>
                        <p>Вы выбраны исполнителем. Когда работа будет готова, отметьте её сдачу.</p>
                        <form method="post" action="<?= url('order_deliver') ?>" data-confirm="Отметить работу как сданную?">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $order['id'] ?>">
                            <button class="btn" type="submit">Сдать работу</button>
                        </form>
                    <?php elseif ($order['status'] === 'in_progress'): ?>
                        <p class="muted">Работа сдана и ожидает приёмки заказчиком.</p>
                    <?php else: ?>
                        <p class="muted">Вы выполнили этот заказ.</p>
                    <?php endif; ?>
                    <form method="post" action="<?= url('chat_start') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="user_id" value="<?= $order['customer_id'] ?>">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <button class="btn btn-outline" type="submit">Написать заказчику</button>
                    </form>

                <?php elseif ($isAdmin): ?>
                    <div class="btn-row">
                        <a class="btn btn-outline" href="<?= url('admin_orders', ['status' => $order['status']]) ?>">К модерации</a>
                        <?php if ($order['executor_id']): ?>
                            <a class="btn btn-outline" href="<?= url('admin_dialog', ['order' => $order['id']]) ?>">Переписка</a>
                        <?php endif; ?>
                    </div>

                <?php elseif ($me && $me['role'] === 'executor' && $order['status'] === 'open' && !$myResponse && $hasResume): ?>
                    <a class="btn" href="#respond">Откликнуться</a>
                <?php elseif ($myResponse): ?>
                    <p class="muted">Вы откликнулись на этот заказ <?= date_ru($myResponse['created_at']) ?>.</p>
                <?php else: ?>
                    <p class="muted">Заказ размещён на площадке NetLancer.</p>
                <?php endif; ?>
            </div>
        </aside>
    </div>
</div>
