<div class="container admin-layout">
    <?php require __DIR__ . '/_menu.php'; ?>

    <div>
        <h1>Справочник категорий</h1>
        <p class="muted">Категорию можно удалить, только если в ней нет подкатегорий, заказов и резюме.
            В скобках – число открытых заказов.</p>

        <form class="panel btn-row" method="post" action="<?= url('admin_categories') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="add">
            <select class="input input-narrow" name="parent_id" aria-label="Куда добавить">
                <option value="">Новый раздел (верхний уровень)</option>
                <?php foreach (Category::sections() as $section): ?>
                    <option value="<?= $section['id'] ?>"><?= e($section['name']) ?></option>
                    <?php foreach (Category::children($section['id']) as $group): ?>
                        <option value="<?= $group['id'] ?>">&nbsp;&nbsp;– <?= e($group['name']) ?></option>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </select>
            <input class="input input-narrow" name="name" placeholder="Название категории" required maxlength="100">
            <button class="btn" type="submit">Добавить</button>
        </form>

        <ul class="tree panel">
            <?php foreach (Category::sections() as $section): ?>
                <li>
                    <?php $item = $section; require __DIR__ . '/_category_row.php'; ?>
                    <ul>
                        <?php foreach (Category::children($section['id']) as $group): ?>
                            <li>
                                <?php $item = $group; require __DIR__ . '/_category_row.php'; ?>
                                <ul>
                                    <?php foreach (Category::children($group['id']) as $leaf): ?>
                                        <li><?php $item = $leaf; require __DIR__ . '/_category_row.php'; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
