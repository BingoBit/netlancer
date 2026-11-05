<div class="container cabinet">
    <div class="breadcrumbs">
        <a href="<?= url('home') ?>">Главная</a> /
        <a href="<?= url('cabinet') ?>">Личный кабинет</a> /
        Личные данные
    </div>

    <div class="lk-layout">
        <div class="lk-main">
            <section class="lk-card lk-card-compact">
                <div class="lk-card-main">
                    <?= user_avatar($user, 'avatar-light lk-avatar') ?>
                    <div class="lk-card-info">
                        <div class="lk-card-title">
                            <h1><?= e($user['name']) ?></h1>
                            <span class="badge badge-open"><?= e(ROLES[$user['role']]) ?></span>
                        </div>
                        <p class="muted"><?= e($user['email']) ?></p>
                        <?php $langRaw = $user['language'] ?? 'ru'; require __DIR__ . '/../partials/lang_chips.php'; ?>
                        <p class="small muted">Дата регистрации: <?= date_ru($user['created_at']) ?></p>
                    </div>
                </div>
            </section>

            <section class="lk-section">
                <div class="lk-section-head">
                    <h2>Личные данные</h2>
                </div>

                <form class="form panel lk-form" method="post" action="<?= url('profile') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="profile">

                    <div class="field <?= isset($errors['avatar']) ? 'has-error' : '' ?>">
                        <label for="avatar">Аватар</label>
                        <div class="photo-edit">
                            <?= user_avatar($user, 'avatar-light photo-preview') ?>
                            <div>
                                <input class="input" type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/webp,image/gif">
                                <div class="hint">JPG, PNG, WEBP или GIF до 2 МБ. Фото видно в кабинете, чате и в резюме.</div>
                                <?php if (!empty($user['avatar'])): ?>
                                    <label class="checkbox"><input type="checkbox" name="remove_avatar" value="1"> Удалить текущий аватар</label>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (isset($errors['avatar'])): ?><div class="error"><?= e($errors['avatar']) ?></div><?php endif; ?>
                    </div>

                    <div class="field-row">
                        <div class="field <?= isset($errors['name']) ? 'has-error' : '' ?>">
                            <label for="name">Имя и фамилия</label>
                            <input class="input" id="name" name="name" value="<?= e($user['name']) ?>" maxlength="100" required>
                            <div class="hint">Так вас видят другие пользователи на площадке.</div>
                            <?php if (isset($errors['name'])): ?><div class="error"><?= e($errors['name']) ?></div><?php endif; ?>
                        </div>
                        <div class="field">
                            <label for="email">Электронная почта</label>
                            <input class="input" id="email" value="<?= e($user['email']) ?>" disabled>
                            <div class="hint">Почта используется для входа и не меняется.</div>
                        </div>
                    </div>

                    <?php
                    $userLanguages = language_codes($user['language'] ?? 'ru');
                    if (!$userLanguages && empty($errors['language'])) {
                        $userLanguages = ['ru'];
                    }
                    ?>
                    <div class="field <?= isset($errors['language']) ? 'has-error' : '' ?>">
                        <label>Языки общения</label>
                        <div class="lang-picker" role="group" aria-label="Языки общения">
                            <?php foreach (LANGUAGES as $code => $label): ?>
                                <label class="lang-option">
                                    <input type="checkbox" name="languages[]" value="<?= e($code) ?>"
                                        <?= in_array($code, $userLanguages, true) ? 'checked' : '' ?>>
                                    <span><?= e($label) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <div class="hint">Можно выбрать несколько языков, на которых вам удобно общаться.</div>
                        <?php if (isset($errors['language'])): ?><div class="error"><?= e($errors['language']) ?></div><?php endif; ?>
                    </div>

                    <div class="field <?= isset($errors['about']) ? 'has-error' : '' ?>">
                        <label for="about">О себе</label>
                        <textarea class="input" id="about" name="about" rows="6" maxlength="2000"
                                  placeholder="Кратко расскажите о себе"><?= e($user['about']) ?></textarea>
                        <div class="hint">Не более 2000 символов.</div>
                        <?php if (isset($errors['about'])): ?><div class="error"><?= e($errors['about']) ?></div><?php endif; ?>
                    </div>

                    <button class="btn" type="submit">Сохранить изменения</button>
                </form>
            </section>

            <section class="lk-section">
                <div class="lk-section-head">
                    <h2>Смена пароля</h2>
                </div>
                <form class="form panel lk-form" method="post" action="<?= url('profile') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="password">
                    <div class="field-row">
                        <div class="field <?= isset($errors['current']) ? 'has-error' : '' ?>">
                            <label for="current">Текущий пароль</label>
                            <input class="input" type="password" id="current" name="current" required autocomplete="current-password">
                            <?php if (isset($errors['current'])): ?><div class="error"><?= e($errors['current']) ?></div><?php endif; ?>
                        </div>
                        <div class="field <?= isset($errors['new']) ? 'has-error' : '' ?>">
                            <label for="new">Новый пароль</label>
                            <input class="input" type="password" id="new" name="new" minlength="8" required autocomplete="new-password">
                            <div class="hint">Не менее 8 символов.</div>
                            <?php if (isset($errors['new'])): ?><div class="error"><?= e($errors['new']) ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="field <?= isset($errors['new2']) ? 'has-error' : '' ?>">
                        <label for="new2">Повторите новый пароль</label>
                        <input class="input input-narrow" type="password" id="new2" name="new2" minlength="8" required autocomplete="new-password">
                        <?php if (isset($errors['new2'])): ?><div class="error"><?= e($errors['new2']) ?></div><?php endif; ?>
                    </div>
                    <button class="btn btn-outline" type="submit">Изменить пароль</button>
                </form>
            </section>
        </div>

        <?php require __DIR__ . '/../partials/lk_nav.php'; ?>
    </div>
</div>
