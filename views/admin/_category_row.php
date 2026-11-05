<div class="tree-row">
    <form class="tree-row" method="post" action="<?= url('admin_categories') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="rename">
        <input type="hidden" name="id" value="<?= $item['id'] ?>">
        <input class="input" name="name" value="<?= e($item['name']) ?>" maxlength="100" aria-label="Название категории">
        <button class="btn btn-outline btn-small" type="submit">Сохранить</button>
    </form>
    <?php if (!empty($counts[$item['id']])): ?><span class="muted">(<?= $counts[$item['id']] ?>)</span><?php endif; ?>
    <form method="post" action="<?= url('admin_categories') ?>" data-confirm="Удалить категорию «<?= e($item['name']) ?>»?">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" value="<?= $item['id'] ?>">
        <button class="btn btn-danger btn-small" type="submit" title="Удалить">✕</button>
    </form>
</div>
