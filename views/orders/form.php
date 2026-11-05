<div class="container page-title">
    <h1><?= e($title) ?></h1>
    <p class="muted">После сохранения заказ проверит модератор, затем он появится в каталоге.</p>

    <form class="form" method="post" action="<?= $order ? url('order_edit', ['id' => $order['id']]) : url('order_create') ?>">
        <?= csrf_field() ?>

        <div class="field <?= isset($errors['title']) ? 'has-error' : '' ?>">
            <label for="title">Название заказа</label>
            <input class="input" id="title" name="title" value="<?= e($form['title']) ?>" maxlength="150" required
                   placeholder="Например: Логотип для кофейни">
            <?php if (isset($errors['title'])): ?><div class="error"><?= e($errors['title']) ?></div><?php endif; ?>
        </div>

        <div class="field <?= isset($errors['category_id']) ? 'has-error' : '' ?>">
            <label for="category_id">Категория</label>
            <?php $selected = $form['category_id']; require __DIR__ . '/../partials/category_select.php'; ?>
            <?php if (isset($errors['category_id'])): ?><div class="error"><?= e($errors['category_id']) ?></div><?php endif; ?>
        </div>

        <div class="field <?= isset($errors['description']) ? 'has-error' : '' ?>">
            <label for="description">Описание задачи</label>
            <textarea class="input" id="description" name="description" rows="8" maxlength="5000" required
                      placeholder="Что нужно сделать, какой результат ожидаете, есть ли примеры"><?= e($form['description']) ?></textarea>
            <?php if (isset($errors['description'])): ?><div class="error"><?= e($errors['description']) ?></div><?php endif; ?>
        </div>

        <div class="field-row">
            <div class="field <?= isset($errors['budget']) ? 'has-error' : '' ?>">
                <label for="budget">Бюджет, ₽</label>
                <input class="input" type="number" id="budget" name="budget" min="500" step="100" value="<?= e($form['budget']) ?>" required>
                <?php if (isset($errors['budget'])): ?><div class="error"><?= e($errors['budget']) ?></div><?php endif; ?>
            </div>
            <div class="field <?= isset($errors['deadline']) ? 'has-error' : '' ?>">
                <label for="deadline">Выполнить до</label>
                <input class="input" type="date" id="deadline" name="deadline" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" value="<?= e($form['deadline']) ?>" required>
                <?php if (isset($errors['deadline'])): ?><div class="error"><?= e($errors['deadline']) ?></div><?php endif; ?>
            </div>
        </div>

        <div class="btn-row">
            <button class="btn" type="submit"><?= $order ? 'Сохранить и отправить на модерацию' : 'Опубликовать заказ' ?></button>
            <a class="btn btn-outline" href="<?= $order ? url('order', ['id' => $order['id']]) : url('cabinet') ?>">Отмена</a>
        </div>
    </form>
</div>
