<div class="container">
    <div class="breadcrumbs"><a href="<?= url('home') ?>">Главная</a> / Исполнители</div>

    <div class="catalog">
        <form class="filters" method="get">
            <input type="hidden" name="page" value="resumes">
            <h3>Поиск исполнителя</h3>
            <div class="field">
                <label for="f-q">Специализация или навык</label>
                <input class="input" id="f-q" name="q" value="<?= e($filters['q']) ?>" placeholder="Например, Figma">
            </div>
            <div class="field">
                <label for="f-category">Раздел</label>
                <select id="f-category" name="category">
                    <option value="">Все разделы</option>
                    <?php foreach (Category::sections() as $section): ?>
                        <option value="<?= $section['id'] ?>" <?= $filters['category'] === $section['id'] ? 'selected' : '' ?>><?= e($section['name']) ?></option>
                        <?php foreach (Category::children($section['id']) as $group): ?>
                            <option value="<?= $group['id'] ?>" <?= $filters['category'] === $group['id'] ? 'selected' : '' ?>>&nbsp;&nbsp;– <?= e($group['name']) ?></option>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="btn-row">
                <button class="btn" type="submit">Найти</button>
                <a class="btn btn-outline" href="<?= url('resumes') ?>">Сбросить</a>
            </div>
        </form>

        <div>
            <div class="catalog-head">
                <h1>Исполнители</h1>
                <span class="muted">Найдено: <?= $total ?></span>
            </div>
            <?php if (!$resumes): ?>
                <div class="empty">Исполнители по вашему запросу не найдены.</div>
            <?php else: ?>
                <div class="people people-list">
                    <?php foreach ($resumes as $resume): ?>
                        <?php require __DIR__ . '/../partials/resume_card.php'; ?>
                    <?php endforeach; ?>
                </div>
                <?php require __DIR__ . '/../partials/pagination.php'; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
