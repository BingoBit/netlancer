<div class="container">
    <div class="auth-box">
        <h1>Регистрация</h1>
        <form class="form" method="post" action="<?= url('register') ?>" novalidate>
            <?= csrf_field() ?>

            <div class="field <?= isset($errors['role']) ? 'has-error' : '' ?>">
                <label>Я хочу</label>
                <div class="roles">
                    <label class="role-option">
                        <input type="radio" name="role" value="customer" <?= $form['role'] === 'customer' ? 'checked' : '' ?>>
                        <b>Разместить заказ</b>
                        <span class="small muted">Роль «Заказчик»</span>
                    </label>
                    <label class="role-option">
                        <input type="radio" name="role" value="executor" <?= $form['role'] === 'executor' ? 'checked' : '' ?>>
                        <b>Выполнять заказы</b>
                        <span class="small muted">Роль «Исполнитель»</span>
                    </label>
                </div>
                <?php if (isset($errors['role'])): ?><div class="error"><?= e($errors['role']) ?></div><?php endif; ?>
            </div>

            <div class="field <?= isset($errors['name']) ? 'has-error' : '' ?>">
                <label for="name">Имя и фамилия</label>
                <input class="input" id="name" name="name" value="<?= e($form['name']) ?>" maxlength="100" required>
                <?php if (isset($errors['name'])): ?><div class="error"><?= e($errors['name']) ?></div><?php endif; ?>
            </div>

            <div class="field <?= isset($errors['email']) ? 'has-error' : '' ?>">
                <label for="email">Электронная почта</label>
                <input class="input" type="email" id="email" name="email" value="<?= e($form['email']) ?>" maxlength="150" required autocomplete="email">
                <?php if (isset($errors['email'])): ?><div class="error"><?= e($errors['email']) ?></div><?php endif; ?>
            </div>

            <div class="field-row">
                <div class="field <?= isset($errors['password']) ? 'has-error' : '' ?>">
                    <label for="password">Пароль</label>
                    <input class="input" type="password" id="password" name="password" minlength="8" required autocomplete="new-password">
                    <?php if (isset($errors['password'])): ?><div class="error"><?= e($errors['password']) ?></div><?php endif; ?>
                </div>
                <div class="field <?= isset($errors['password2']) ? 'has-error' : '' ?>">
                    <label for="password2">Повторите пароль</label>
                    <input class="input" type="password" id="password2" name="password2" minlength="8" required autocomplete="new-password">
                    <?php if (isset($errors['password2'])): ?><div class="error"><?= e($errors['password2']) ?></div><?php endif; ?>
                </div>
            </div>

            <div class="field <?= isset($errors['agree']) ? 'has-error' : '' ?>">
                <label class="checkbox">
                    <input type="checkbox" name="agree" value="1" <?= !empty($_POST['agree']) ? 'checked' : '' ?>>
                    <span>Даю согласие на обработку персональных данных в соответствии с
                        <a href="<?= url('info', ['slug' => 'privacy']) ?>" target="_blank">политикой</a>
                        и принимаю <a href="<?= url('info', ['slug' => 'rules']) ?>" target="_blank">правила площадки</a></span>
                </label>
                <?php if (isset($errors['agree'])): ?><div class="error"><?= e($errors['agree']) ?></div><?php endif; ?>
            </div>

            <button class="btn" type="submit">Зарегистрироваться</button>
            <p class="form-note">Уже есть учётная запись? <a href="<?= url('login') ?>">Войдите</a></p>
        </form>
    </div>
</div>
