<div class="container admin-layout">
    <?php require __DIR__ . '/_menu.php'; ?>

    <div>
        <h1>Пользователи</h1>
        <form class="btn-row" method="get">
            <input type="hidden" name="page" value="admin_users">
            <input class="input input-narrow" name="q" value="<?= e($q) ?>" placeholder="Имя или почта">
            <button class="btn" type="submit">Найти</button>
            <?php if ($q !== ''): ?><a href="<?= url('admin_users') ?>">Сбросить</a><?php endif; ?>
        </form>
        <p class="form-note">Найдено: <?= $total ?></p>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr><th>Имя</th><th>Почта</th><th>Роль</th><th>Регистрация</th><th>Состояние</th><th></th></tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= e($u['name']) ?></td>
                        <td><?= e($u['email']) ?></td>
                        <td><?= e(ROLES[$u['role']]) ?></td>
                        <td><?= date_ru($u['created_at']) ?></td>
                        <td><?= $u['is_blocked'] ? '<span class="badge badge-rejected">Заблокирован</span>' : '<span class="badge badge-open">Активен</span>' ?></td>
                        <td>
                            <?php if ($u['role'] !== 'admin'): ?>
                                <form method="post" action="<?= url('admin_user_block') ?>"
                                      data-confirm="<?= $u['is_blocked'] ? 'Разблокировать' : 'Заблокировать' ?> пользователя <?= e($u['name']) ?>?">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                    <input type="hidden" name="q" value="<?= e($q) ?>">
                                    <input type="hidden" name="block" value="<?= $u['is_blocked'] ? '0' : '1' ?>">
                                    <button class="btn btn-small <?= $u['is_blocked'] ? '' : 'btn-danger' ?>" type="submit">
                                        <?= $u['is_blocked'] ? 'Разблокировать' : 'Заблокировать' ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php require __DIR__ . '/../partials/pagination.php'; ?>
    </div>
</div>
