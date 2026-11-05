<div class="container category-layout">
    <aside class="category-side">
        <h2><?= e($section['name']) ?></h2>
        <?php foreach ($groups as $group): ?>
            <div class="side-group">
                <h3><a href="<?= url('orders', ['category' => $group['id']]) ?>"><?= e($group['name']) ?></a></h3>
                <?php foreach (Category::children($group['id']) as $item): ?>
                    <a href="<?= url('orders', ['category' => $item['id']]) ?>">
                        <?= e($item['name']) ?><?php if (!empty($counts[$item['id']])): ?> <span>(<?= $counts[$item['id']] ?>)</span><?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <div class="side-group">
            <h3>Другие разделы</h3>
            <?php foreach (Category::sections() as $other): ?>
                <?php if ($other['id'] !== $section['id']): ?>
                    <a href="<?= url('category', ['id' => $other['id']]) ?>"><?= e($other['name']) ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </aside>

    <div class="category-main">
        <h1><?= e($section['name']) ?></h1>
        <p class="subtitle"><?= e($section['description']) ?></p>

        <div class="tiles">
            <?php foreach ($groups as $group): ?>
                <?php foreach (Category::children($group['id']) as $item): ?>
                    <a class="tile" href="<?= url('orders', ['category' => $item['id']]) ?>">
                        <?php if (!empty($counts[$item['id']])): ?>
                            <span class="count" title="Открытых заказов"><?= $counts[$item['id']] ?></span>
                        <?php endif; ?>
                        <img src="<?= e(category_icon($item['icon'])) ?>" width="76" height="76" alt="" loading="lazy">
                        <span><?= e($item['name']) ?></span>
                    </a>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>

        <div class="btn-row center">
            <a class="btn" href="<?= url('orders', ['category' => $section['id']]) ?>">Все заказы раздела</a>
            <a class="btn btn-outline" href="<?= url('resumes', ['category' => $section['id']]) ?>">Исполнители раздела</a>
        </div>
    </div>
</div>
