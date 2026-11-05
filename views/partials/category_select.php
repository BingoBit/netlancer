<select name="category_id" id="category_id" required>
    <option value="">– выберите категорию –</option>
    <?php foreach (Category::selectGroups() as $groupName => $items): ?>
        <optgroup label="<?= e($groupName) ?>">
            <?php foreach ($items as $item): ?>
                <option value="<?= $item['id'] ?>" <?= $item['id'] === (int)$selected ? 'selected' : '' ?>><?= e($item['name']) ?></option>
            <?php endforeach; ?>
        </optgroup>
    <?php endforeach; ?>
</select>
