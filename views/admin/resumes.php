<div class="container admin-layout">
    <?php require __DIR__ . '/_menu.php'; ?>

    <div>
        <h1>Резюме исполнителей</h1>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr><th>Исполнитель</th><th>Специализация</th><th>Категория</th><th>Обновлено</th><th></th></tr>
                </thead>
                <tbody>
                <?php foreach ($resumes as $resume): ?>
                    <tr>
                        <td>
                            <?= e($resume['user_name']) ?>
                            <?php if ($resume['is_blocked']): ?><div><span class="badge badge-rejected">Пользователь заблокирован</span></div><?php endif; ?>
                        </td>
                        <td><a href="<?= url('resume', ['id' => $resume['id']]) ?>"><?= e($resume['title']) ?></a></td>
                        <td><?= e($resume['category_name']) ?></td>
                        <td><?= date_ru($resume['updated_at']) ?></td>
                        <td>
                            <form method="post" action="<?= url('admin_resume_hide', ['p' => current_page()]) ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $resume['id'] ?>">
                                <input type="hidden" name="hide" value="<?= $resume['is_hidden'] ? '0' : '1' ?>">
                                <button class="btn btn-small <?= $resume['is_hidden'] ? '' : 'btn-danger' ?>" type="submit">
                                    <?= $resume['is_hidden'] ? 'Показать' : 'Скрыть' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php require __DIR__ . '/../partials/pagination.php'; ?>
    </div>
</div>
