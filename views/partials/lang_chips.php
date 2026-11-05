<?php
$langCodes = language_codes($langRaw ?? null) ?: ['ru'];
$langClass = trim('lang-chips ' . ($langClass ?? ''));
?>
<div class="<?= e($langClass) ?>">
    <span class="lang-chips-label">Языки общения</span>
    <ul class="lang-chip-list" aria-label="Языки общения">
        <?php foreach ($langCodes as $code): ?>
            <li><?= e(language_label($code)) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
