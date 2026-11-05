<?php

class OrderController
{
    public static function index(): void
    {
        $filters = [
            'q'           => query('q'),
            'category'    => (int)query('category'),
            'budget_from' => (int)query('budget_from'),
            'budget_to'   => (int)query('budget_to'),
            'sort'        => query('sort') === 'budget' ? 'budget' : 'new',
        ];
        $total = Order::countSearch($filters);

        render('orders/index', [
            'title'    => 'Заказы',
            'filters'  => $filters,
            'orders'   => Order::search($filters, PER_PAGE, page_offset()),
            'total'    => $total,
            'pages'    => pages_count($total),
            'category' => $filters['category'] ? Category::find($filters['category']) : null,
        ]);
    }

    public static function show(): void
    {
        $order = self::load((int)query('id'));
        $user = current_user();
        $userId = $user['id'] ?? 0;
        $isOwner = $order['customer_id'] === $userId;
        $isExecutor = $order['executor_id'] === $userId;
        $isAdmin = $user && $user['role'] === 'admin';

        // Заказы на модерации, отменённые, отклонённые и скрытые видят только участники и администратор
        $isPublic = in_array($order['status'], ['open', 'in_progress', 'completed'], true) && !$order['is_hidden'];
        if (!$isPublic && !$isOwner && !$isExecutor && !$isAdmin) {
            not_found();
        }

        $myResponse = null;
        $hasResume = false;
        if ($user && $user['role'] === 'executor') {
            $myResponse = Response::findMine($order['id'], $userId);
            $hasResume = Resume::findByUser($userId) !== null;
        }

        render('orders/show', [
            'title'      => $order['title'],
            'order'      => $order,
            'path'       => Category::path($order['category_id']),
            'responses'  => ($isOwner || $isAdmin) ? Response::forOrder($order['id']) : [],
            'myResponse' => $myResponse,
            'hasResume'  => $hasResume,
            'review'     => Review::findByOrder($order['id']),
            'isOwner'    => $isOwner,
            'isExecutor' => $isExecutor,
            'isAdmin'    => $isAdmin,
        ]);
    }

    public static function create(): void
    {
        $user = require_role('customer');
        $form = ['title' => '', 'description' => '', 'category_id' => (int)query('category'), 'budget' => '', 'deadline' => ''];
        $errors = [];

        if (is_post()) {
            check_csrf();
            $form = self::readForm();
            $errors = self::validate($form);
            if (!$errors) {
                $id = Order::create($user['id'], $form);
                flash('success', 'Заказ отправлен на модерацию. После проверки он появится в каталоге.');
                redirect(url('order', ['id' => $id]));
            }
        }

        render('orders/form', ['title' => 'Новый заказ', 'form' => $form, 'errors' => $errors, 'order' => null]);
    }

    public static function edit(): void
    {
        $user = require_role('customer');
        $order = self::load((int)query('id'));
        if ($order['customer_id'] !== $user['id']) {
            forbidden();
        }
        if (!in_array($order['status'], ['moderation', 'open'], true)) {
            flash('error', 'Заказ можно изменить только до выбора исполнителя.');
            redirect(url('order', ['id' => $order['id']]));
        }

        $form = $order;
        $errors = [];

        if (is_post()) {
            check_csrf();
            $form = self::readForm();
            $errors = self::validate($form);
            if (!$errors) {
                Order::update($order['id'], $form);
                flash('success', 'Изменения сохранены. Заказ снова отправлен на модерацию.');
                redirect(url('order', ['id' => $order['id']]));
            }
        }

        render('orders/form', ['title' => 'Редактирование заказа', 'form' => $form, 'errors' => $errors, 'order' => $order]);
    }

    public static function cancel(): void
    {
        $user = require_role('customer');
        check_csrf();
        $order = self::load((int)input('id'));
        if ($order['customer_id'] !== $user['id']) {
            forbidden();
        }

        if (Order::cancel($order['id'])) {
            flash('success', 'Заказ отменён.');
        } else {
            flash('error', 'Заказ уже в работе, отменить его нельзя.');
        }
        redirect(url('order', ['id' => $order['id']]));
    }

    public static function deliver(): void
    {
        $user = require_role('executor');
        check_csrf();
        $order = self::load((int)input('id'));
        if ($order['executor_id'] !== $user['id']) {
            forbidden();
        }

        if ($order['status'] === 'in_progress' && !$order['delivered_at']) {
            Order::markDelivered($order['id']);
            flash('success', 'Работа отмечена как сданная. Заказчик получит возможность её принять.');
        }
        redirect(url('order', ['id' => $order['id']]));
    }

    public static function complete(): void
    {
        $user = require_role('customer');
        check_csrf();
        $order = self::load((int)input('id'));
        if ($order['customer_id'] !== $user['id']) {
            forbidden();
        }

        if ($order['status'] === 'in_progress' && $order['delivered_at']) {
            Order::complete($order['id']);
            flash('success', 'Заказ завершён. Оставьте, пожалуйста, отзыв об исполнителе.');
        } else {
            flash('error', 'Подтвердить выполнение можно после того, как исполнитель сдаст работу.');
        }
        redirect(url('order', ['id' => $order['id']]));
    }

    public static function review(): void
    {
        $user = require_role('customer');
        check_csrf();
        $order = self::load((int)input('id'));
        if ($order['customer_id'] !== $user['id']) {
            forbidden();
        }

        $rating = (int)input('rating');
        $text = input('text');

        if ($order['status'] !== 'completed' || Review::findByOrder($order['id'])) {
            flash('error', 'Отзыв можно оставить один раз после завершения заказа.');
        } elseif ($rating < 1 || $rating > 5) {
            flash('error', 'Поставьте оценку от 1 до 5.');
        } elseif (text_length($text) < 10 || text_length($text) > 1000) {
            flash('error', 'Текст отзыва – от 10 до 1000 символов.');
        } else {
            Review::create($order['id'], $user['id'], $order['executor_id'], $rating, $text);
            flash('success', 'Спасибо! Отзыв опубликован.');
        }
        redirect(url('order', ['id' => $order['id']]));
    }

    private static function load(int $id): array
    {
        $order = Order::find($id);
        if (!$order) {
            not_found();
        }
        return $order;
    }

    private static function readForm(): array
    {
        return [
            'title'       => input('title'),
            'description' => input('description'),
            'category_id' => (int)input('category_id'),
            'budget'      => input('budget'),
            'deadline'    => input('deadline'),
        ];
    }

    private static function validate(array $form): array
    {
        $errors = [];

        if (text_length($form['title']) < 5 || text_length($form['title']) > 150) {
            $errors['title'] = 'Название – от 5 до 150 символов.';
        }
        if (text_length($form['description']) < 20 || text_length($form['description']) > 5000) {
            $errors['description'] = 'Опишите задачу подробнее: от 20 до 5000 символов.';
        }
        if (!Category::isLeaf($form['category_id'])) {
            $errors['category_id'] = 'Выберите категорию.';
        }
        if (!ctype_digit($form['budget']) || (int)$form['budget'] < 500 || (int)$form['budget'] > 10000000) {
            $errors['budget'] = 'Бюджет – целое число от 500 до 10 000 000 рублей.';
        }
        $date = DateTime::createFromFormat('Y-m-d', $form['deadline']);
        if (!$date || $date->format('Y-m-d') !== $form['deadline'] || $form['deadline'] <= date('Y-m-d')) {
            $errors['deadline'] = 'Укажите срок позже сегодняшнего дня.';
        }

        return $errors;
    }
}
