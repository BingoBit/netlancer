<div class="container cabinet">
    <div class="breadcrumbs">
        <a href="<?= url('home') ?>">Главная</a> /
        <a href="<?= url('cabinet') ?>">Личный кабинет</a> /
        Моё резюме
    </div>

    <div class="lk-layout">
        <div class="lk-main">
            <div class="lk-section-head">
                <h1><?= $isNew ? 'Заполните резюме' : 'Моё резюме' ?></h1>
            </div>
            <p class="muted">Резюме видят заказчики в каталоге исполнителей и рядом с вашими откликами. Аватар меняется в личных данных, здесь можно добавить примеры работ.</p>

            <form class="form panel resume-form lk-form" method="post" action="<?= url('resume_edit') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="field">
                    <label>Аватар</label>
                    <div class="photo-edit">
                        <?= user_avatar($user, 'avatar-light photo-preview') ?>
                        <div class="hint">Чтобы сменить фото профиля, откройте
                            <a href="<?= url('profile') ?>">личные данные</a>.</div>
                    </div>
                </div>

                <div class="field <?= isset($errors['title']) ? 'has-error' : '' ?>">
                    <label for="title">Специализация</label>
                    <input class="input" id="title" name="title" value="<?= e($form['title']) ?>" maxlength="150" required
                           placeholder="Например: Дизайнер логотипов и фирменного стиля">
                    <?php if (isset($errors['title'])): ?><div class="error"><?= e($errors['title']) ?></div><?php endif; ?>
                </div>

                <div class="field <?= isset($errors['category_id']) ? 'has-error' : '' ?>">
                    <label for="category_id">Основная категория</label>
                    <?php $selected = $form['category_id']; require __DIR__ . '/../partials/category_select.php'; ?>
                    <?php if (isset($errors['category_id'])): ?><div class="error"><?= e($errors['category_id']) ?></div><?php endif; ?>
                </div>

                <div class="field <?= isset($errors['about']) ? 'has-error' : '' ?>">
                    <label for="about">Опыт и услуги</label>
                    <textarea class="input" id="about" name="about" rows="7" maxlength="5000" required
                              placeholder="Сколько лет работаете, какие задачи решаете, как строите работу с заказчиком"><?= e($form['about']) ?></textarea>
                    <?php if (isset($errors['about'])): ?><div class="error"><?= e($errors['about']) ?></div><?php endif; ?>
                </div>

                <div class="field <?= isset($errors['skills']) ? 'has-error' : '' ?>">
                    <label for="skills">Навыки</label>
                    <input class="input" id="skills" name="skills" value="<?= e($form['skills']) ?>" maxlength="255" required
                           placeholder="Через запятую: Figma, Photoshop, брендинг">
                    <?php if (isset($errors['skills'])): ?><div class="error"><?= e($errors['skills']) ?></div><?php endif; ?>
                </div>

                <div class="field <?= isset($errors['rate']) ? 'has-error' : '' ?>">
                    <label for="rate">Желаемая ставка, ₽ в час</label>
                    <input class="input" type="number" id="rate" name="rate" min="0" step="50" value="<?= e($form['rate']) ?>">
                    <div class="hint">Можно оставить пустым – тогда в резюме будет «по договорённости».</div>
                    <?php if (isset($errors['rate'])): ?><div class="error"><?= e($errors['rate']) ?></div><?php endif; ?>
                </div>

                <div class="field <?= isset($errors['gallery']) ? 'has-error' : '' ?>">
                    <label for="gallery">Фотографии работ</label>
                    <?php if (!empty($gallery)): ?>
                        <div class="gallery-edit">
                            <?php foreach ($gallery as $file): ?>
                                <label class="gallery-edit-item">
                                    <img src="<?= e(upload_url($file)) ?>" alt="Работа из портфолио">
                                    <span><input type="checkbox" name="remove_gallery[]" value="<?= e($file) ?>"> Удалить</span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <input class="input" type="file" id="gallery" name="gallery[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple>
                    <div class="hint">До <?= UPLOAD_GALLERY_MAX ?> фотографий, каждая до 2 МБ. Можно выбрать несколько файлов сразу.</div>
                    <?php if (isset($errors['gallery'])): ?><div class="error"><?= e($errors['gallery']) ?></div><?php endif; ?>
                </div>

                <div class="field <?= isset($errors['portfolio']) ? 'has-error' : '' ?>">
                    <label for="portfolio">Ссылки на портфолио</label>
                    <textarea class="input" id="portfolio" name="portfolio" rows="3"
                              placeholder="https://..., по одной ссылке в строке"><?= e($form['portfolio']) ?></textarea>
                    <?php if (isset($errors['portfolio'])): ?><div class="error"><?= e($errors['portfolio']) ?></div><?php endif; ?>
                </div>

                <div class="btn-row">
                    <button class="btn" type="submit">Сохранить резюме</button>
                    <a class="btn btn-outline" href="<?= url('cabinet') ?>">В личный кабинет</a>
                </div>
            </form>
        </div>

        <?php require __DIR__ . '/../partials/lk_nav.php'; ?>
    </div>
</div>
