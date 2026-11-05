<?php

class CabinetController
{
    public static function index(): void
    {
        $user = require_login();

        if ($user['role'] === 'admin') {
            redirect(url('admin'));
        }

        if ($user['role'] === 'customer') {
            $tabs = ['open' => [], 'moderation' => [], 'in_progress' => [], 'completed' => [], 'closed' => []];
            foreach (Order::byCustomer($user['id']) as $order) {
                $tab = in_array($order['status'], ['cancelled', 'rejected'], true) ? 'closed' : $order['status'];
                $tabs[$tab][] = $order;
            }
            render('cabinet/customer', [
                'title'   => 'Личный кабинет',
                'user'    => $user,
                'tabs'    => $tabs,
                'reviews' => Review::countByCustomer($user['id']),
            ]);
            return;
        }

        $resume = Resume::findByUser($user['id']);
        render('cabinet/executor', [
            'title'     => 'Личный кабинет',
            'user'      => $user,
            'resume'    => $resume,
            'responses' => Response::byExecutor($user['id']),
            'orders'    => Order::byExecutor($user['id']),
            'reviews'   => Review::forExecutor($user['id']),
        ]);
    }

    public static function profile(): void
    {
        $user = require_login();
        $errors = [];

        if (is_post()) {
            check_csrf();

            if (input('action') === 'password') {
                if (!password_verify(input('current'), $user['password_hash'])) {
                    $errors['current'] = 'Текущий пароль указан неверно.';
                } elseif (text_length(input('new')) < 8) {
                    $errors['new'] = 'Новый пароль – не менее 8 символов.';
                } elseif (input('new') !== input('new2')) {
                    $errors['new2'] = 'Пароли не совпадают.';
                } else {
                    User::updatePassword($user['id'], input('new'));
                    flash('success', 'Пароль изменён.');
                    redirect(url('profile'));
                }
            } else {
                $name = input('name');
                $about = input('about');
                $selected = $_POST['languages'] ?? [];
                if (!is_array($selected)) {
                    $selected = [];
                }
                $languages = [];
                foreach ($selected as $code) {
                    $code = (string)$code;
                    if (isset(LANGUAGES[$code]) && !in_array($code, $languages, true)) {
                        $languages[] = $code;
                    }
                }
                $language = implode(',', $languages);
                $avatar = $user['avatar'] ?? null;

                if (text_length($name) < 2 || text_length($name) > 100) {
                    $errors['name'] = 'Имя – от 2 до 100 символов.';
                } elseif (text_length($about) > 2000) {
                    $errors['about'] = 'Рассказ о себе – не длиннее 2000 символов.';
                } elseif (!$languages) {
                    $errors['language'] = 'Выберите хотя бы один язык общения.';
                }

                if (!empty($_POST['remove_avatar']) && $avatar) {
                    delete_upload($avatar);
                    $avatar = null;
                }

                if (!empty($_FILES['avatar']['name'])) {
                    $saved = save_image_upload($_FILES['avatar'], 'avatar_u' . $user['id']);
                    if (!$saved['ok']) {
                        $errors['avatar'] = $saved['error'];
                    } elseif ($saved['file']) {
                        if ($avatar) {
                            delete_upload($avatar);
                        }
                        $avatar = $saved['file'];
                    }
                }

                if (!$errors) {
                    User::updateProfile($user['id'], $name, $about, $language);
                    User::updateAvatar($user['id'], $avatar);
                    flash('success', 'Профиль сохранён.');
                    redirect(url('profile'));
                }

                $user['name'] = $name;
                $user['about'] = $about;
                $user['language'] = $language;
                $user['avatar'] = $avatar;
            }
        }

        render('cabinet/profile', ['title' => 'Профиль', 'user' => $user, 'errors' => $errors]);
    }
}
