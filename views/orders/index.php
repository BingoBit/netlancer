<div class="container">
    <div class="breadcrumbs">
        <a href="<?= url('home') ?>">Главная</a> / <a href="<?= url('orders') ?>">Заказы</a>
        <?php if ($category): ?>
            <?php foreach (Category::path($category['id']) as $item): ?>
                / <a href="<?= url('orders', ['category' => $item['id']]) ?>"><?= e($item['name']) ?></a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="catalog">
        <form class="filters" method="get">
            <input type="hidden" name="page" value="orders">
            <h3>Фильтры</h3>
            <div class="field">
                <label for="f-q">Поиск</label>
                <input class="input" id="f-q" name="q" value="<?= e($filters['q']) ?>" placeholder="Например, логотип">
            </div>
            <div class="field">
                <label for="f-category">Категория</label>
                <select id="f-category" name="category">
                    <option value="">Все категории</option>
                    <?php if ($category && Category::isLeaf($category['id'])): ?>
                        <option value="<?= $category['id'] ?>" selected><?= e($category['name']) ?></option>
                    <?php endif; ?>
                    <?php foreach (Category::sections() as $section): ?>
                        <option value="<?= $section['id'] ?>" <?= $filters['category'] === $section['id'] ? 'selected' : '' ?>><?= e($section['name']) ?></option>
                        <?php foreach (Category::children($section['id']) as $group): ?>
                            <option value="<?= $group['id'] ?>" <?= $filters['category'] === $group['id'] ? 'selected' : '' ?>>&nbsp;&nbsp;– <?= e($group['name']) ?></option>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label>Бюджет, ₽</label>
                <div class="field-row">
                    <input class="input" type="number" name="budget_from" min="0" step="500" placeholder="от" value="<?= $filters['budget_from'] ?: '' ?>" aria-label="Бюджет от">
                    <input class="input" type="number" name="budget_to" min="0" step="500" placeholder="до" value="<?= $filters['budget_to'] ?: '' ?>" aria-label="Бюджет до">
                </div>
            </div>
            <div class="field">
                <label for="f-sort">Сортировка</label>
                <select id="f-sort" name="sort">
                    <option value="new">Сначала новые</option>
                    <option value="budget" <?= $filters['sort'] === 'budget' ? 'selected' : '' ?>>Сначала дорогие</option>
                </select>
            </div>
            <div class="btn-row">
                <button class="btn" type="submit">Показать</button>
                <a class="btn btn-outline" href="<?= url('orders') ?>">Сбросить</a>
            </div>
        </form>

        <div>
            <div class="catalog-head">
                <h1><?= $category ? e($category['name']) : 'Заказы' ?></h1>
                <span class="muted">Найдено: <?= plural($total, 'заказ', 'заказа', 'заказов') ?></span>
            </div>

            <?php if (!$orders): ?>
                <div class="empty">По вашему запросу заказов нет. Попробуйте изменить фильтры.</div>
            <?php else: ?>
                <div class="orders-list">
                    <?php foreach ($orders as $order): ?>
                        <?php require __DIR__ . '/../partials/order_card.php'; ?>
                    <?php endforeach; ?>
                </div>
                <?php require __DIR__ . '/../partials/pagination.php'; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
