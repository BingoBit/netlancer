<?php
// Вход, выход и проверка прав доступа.
// В сессии хранится только id пользователя, остальное читается из базы при каждом запросе,
// поэтому блокировка администратором срабатывает сразу.

function current_user(): ?array
{
    static $user = false;  // false – ещё не загружали

    if ($user === false) {
        $user = null;
        if (!empty($_SESSION['user_id'])) {
            $user = User::find((int)$_SESSION['user_id']);
            if ($user && $user['is_blocked']) {
                logout_user();
                flash('error', 'Ваша учётная запись заблокирована администратором.');
                $user = null;
            }
        }
    }

    return $user;
}

function login_user(array $user): void
{
    session_regenerate_id(true);  // новый id сессии после входа – защита от фиксации сессии
    $_SESSION['user_id'] = $user['id'];
}

function logout_user(): void
{
    unset($_SESSION['user_id']);
    session_regenerate_id(true);
}

function require_login(): array
{
    $user = current_user();
    if (!$user) {
        flash('error', 'Войдите, чтобы продолжить.');
        redirect(url('login'));
    }
    return $user;
}

// Пускает дальше только пользователя с одной из перечисленных ролей
function require_role(string ...$roles): array
{
    $user = require_login();
    if (!in_array($user['role'], $roles, true)) {
        forbidden();
    }
    return $user;
}
