<?php

class AuthController
{
    public static function register(): void
    {
        if (current_user()) {
            redirect(url('cabinet'));
        }

        $form = ['name' => '', 'email' => '', 'role' => 'customer'];
        $errors = [];

        if (is_post()) {
            check_csrf();
            $form = [
                'name'  => input('name'),
                'email' => mb_strtolower(input('email')),
                'role'  => input('role'),
            ];
            $password = input('password');

            if (text_length($form['name']) < 2 || text_length($form['name']) > 100) {
                $errors['name'] = 'Укажите имя длиной от 2 до 100 символов.';
            }
            if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL) || text_length($form['email']) > 150) {
                $errors['email'] = 'Укажите корректный адрес электронной почты.';
            } elseif (User::findByEmail($form['email'])) {
                $errors['email'] = 'Пользователь с такой почтой уже зарегистрирован.';
            }
            if (text_length($password) < 8) {
                $errors['password'] = 'Пароль должен содержать не менее 8 символов.';
            } elseif ($password !== input('password2')) {
                $errors['password2'] = 'Пароли не совпадают.';
            }
            if (!in_array($form['role'], ['customer', 'executor'], true)) {
                $errors['role'] = 'Выберите роль.';
            }
            if (empty($_POST['agree'])) {
                $errors['agree'] = 'Без согласия на обработку персональных данных регистрация невозможна.';
            }

            if (!$errors) {
                $id = User::create($form['name'], $form['email'], $password, $form['role']);
                login_user(User::find($id));
                if ($form['role'] === 'executor') {
                    flash('success', 'Регистрация завершена. Заполните резюме, чтобы откликаться на заказы.');
                    redirect(url('resume_edit'));
                }
                flash('success', 'Регистрация завершена. Теперь вы можете разместить первый заказ.');
                redirect(url('cabinet'));
            }
        }

        render('auth/register', ['title' => 'Регистрация', 'form' => $form, 'errors' => $errors]);
    }

    public static function login(): void
    {
        if (current_user()) {
            redirect(url('cabinet'));
        }

        $email = '';
        $error = '';

        if (is_post()) {
            check_csrf();
            $email = mb_strtolower(input('email'));
            $user = User::findByEmail($email);

            if (!$user || !password_verify(input('password'), $user['password_hash'])) {
                $error = 'Неверный адрес почты или пароль.';
            } elseif ($user['is_blocked']) {
                $error = 'Учётная запись заблокирована администратором.';
            } else {
                login_user($user);
                flash('success', 'Здравствуйте, ' . $user['name'] . '!');
                redirect($user['role'] === 'admin' ? url('admin') : url('cabinet'));
            }
        }

        render('auth/login', ['title' => 'Вход', 'email' => $email, 'error' => $error]);
    }

    public static function logout(): void
    {
        logout_user();
        flash('success', 'Вы вышли из учётной записи.');
        redirect(url('home'));
    }
}
