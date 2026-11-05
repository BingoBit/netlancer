<div class="container">
    <div class="auth-box">
        <h1>Вход</h1>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>
        <form class="form" method="post" action="<?= url('login') ?>">
            <?= csrf_field() ?>
            <div class="field">
                <label for="email">Электронная почта</label>
                <input class="input" type="email" id="email" name="email" value="<?= e($email) ?>" required autocomplete="email" autofocus>
            </div>
            <div class="field">
                <label for="password">Пароль</label>
                <input class="input" type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button class="btn" type="submit">Войти</button>
            <p class="form-note">Нет учётной записи? <a href="<?= url('register') ?>">Зарегистрируйтесь</a></p>
        </form>
    </div>
</div>
