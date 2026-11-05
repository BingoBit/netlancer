<?php

class AdminController
{
    public static function index(): void
    {
        require_role('admin');

        render('admin/index', [
            'title'      => 'Административная панель',
            'roles'      => User::countByRole(),
            'statuses'   => Order::countByStatus(),
            'responses'  => Response::countAll(),
            'messages'   => Message::countAll(),
            'reviews'    => Review::countAll(),
            'moderation' => Order::adminList('moderation', 5, 0),
        ]);
    }

    public static function orders(): void
    {
        require_role('admin');
        $status = query('status');
        if (!isset(ORDER_STATUSES[$status])) {
            $status = 'moderation';
        }
        $counts = Order::countByStatus();

        render('admin/orders', [
            'title'  => 'Заказы',
            'status' => $status,
            'counts' => $counts,
            'orders' => Order::adminList($status, PER_PAGE, page_offset()),
            'pages'  => pages_count($counts[$status]),
        ]);
    }

    // Одобрить, отклонить, скрыть или вернуть заказ
    public static function orderAction(): void
    {
        require_role('admin');
        check_csrf();
        $order = Order::find((int)input('id'));
        if (!$order) {
            not_found();
        }

        switch (input('action')) {
            case 'approve':
                Order::approve($order['id']);
                flash('success', 'Заказ «' . $order['title'] . '» опубликован.');
                break;
            case 'reject':
                $reason = input('reason');
                if (text_length($reason) < 5) {
                    flash('error', 'Укажите причину отклонения – её увидит заказчик.');
                } else {
                    Order::reject($order['id'], mb_substr($reason, 0, 255));
                    flash('success', 'Заказ отклонён.');
                }
                break;
            case 'hide':
                Order::setHidden($order['id'], true);
                flash('success', 'Заказ скрыт из каталога.');
                break;
            case 'show':
                Order::setHidden($order['id'], false);
                flash('success', 'Заказ снова виден в каталоге.');
                break;
        }
        redirect(url('admin_orders', ['status' => $order['status']]));
    }

    public static function users(): void
    {
        require_role('admin');
        $q = query('q');
        $total = User::countSearch($q);

        render('admin/users', [
            'title' => 'Пользователи',
            'q'     => $q,
            'users' => User::search($q, PER_PAGE, page_offset()),
            'total' => $total,
            'pages' => pages_count($total),
        ]);
    }

    public static function blockUser(): void
    {
        require_role('admin');
        check_csrf();
        $target = User::find((int)input('id'));
        if (!$target || $target['role'] === 'admin') {
            flash('error', 'Этого пользователя заблокировать нельзя.');
        } else {
            $block = input('block') === '1';
            User::setBlocked($target['id'], $block);
            flash('success', $target['name'] . ($block ? ' заблокирован(а).' : ' разблокирован(а).'));
        }
        redirect(url('admin_users', ['q' => input('q')]));
    }

    public static function resumes(): void
    {
        require_role('admin');
        $total = Resume::countAll();

        render('admin/resumes', [
            'title'   => 'Резюме',
            'resumes' => Resume::adminList(PER_PAGE, page_offset()),
            'pages'   => pages_count($total),
        ]);
    }

    public static function hideResume(): void
    {
        require_role('admin');
        check_csrf();
        $resume = Resume::find((int)input('id'));
        if ($resume) {
            $hide = input('hide') === '1';
            Resume::setHidden($resume['id'], $hide);
            flash('success', $hide ? 'Резюме скрыто из каталога.' : 'Резюме снова показывается в каталоге.');
        }
        redirect(url('admin_resumes', ['p' => current_page()]));
    }

    public static function categories(): void
    {
        require_role('admin');

        if (is_post()) {
            check_csrf();
            $action = input('action');
            $name = input('name');

            if (($action === 'add' || $action === 'rename') && (text_length($name) < 2 || text_length($name) > 100)) {
                flash('error', 'Название категории – от 2 до 100 символов.');
            } elseif ($action === 'add') {
                $parentId = (int)input('parent_id') ?: null;
                if ($parentId !== null && !Category::find($parentId)) {
                    flash('error', 'Родительская категория не найдена.');
                } else {
                    Category::create($parentId, $name, null);
                    flash('success', 'Категория «' . $name . '» добавлена.');
                }
            } elseif ($action === 'rename') {
                Category::rename((int)input('id'), $name);
                flash('success', 'Категория переименована.');
            } elseif ($action === 'delete') {
                $id = (int)input('id');
                if (Category::children($id) || Category::usage($id) > 0) {
                    flash('error', 'Нельзя удалить категорию, в которой есть подкатегории, заказы или резюме.');
                } else {
                    Category::delete($id);
                    flash('success', 'Категория удалена.');
                }
            }
            redirect(url('admin_categories'));
        }

        render('admin/categories', ['title' => 'Категории', 'counts' => Category::openOrderCounts()]);
    }

    public static function dialog(): void
    {
        require_role('admin');
        $order = Order::find((int)query('order'));
        if (!$order || !$order['executor_id']) {
            not_found();
        }
        $dialog = Dialog::findByPair($order['customer_id'], $order['executor_id']);

        render('admin/dialog', [
            'title'    => 'Переписка по заказу',
            'order'    => $order,
            'dialog'   => $dialog,
            'messages' => $dialog ? Message::after($dialog['id'], 0) : [],
        ]);
    }
}
