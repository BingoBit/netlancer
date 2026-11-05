<?php if ($pages > 1): ?>
    <nav class="pagination" aria-label="Страницы списка">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <?php if ($i === current_page()): ?>
                <span class="current"><?= $i ?></span>
            <?php else: ?>
                <a href="<?= e(page_url($i)) ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </nav>
<?php endif; ?>
