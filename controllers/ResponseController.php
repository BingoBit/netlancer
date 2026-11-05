<?php

class ResponseController
{
    public static function create(): void
    {
        $user = require_role('executor');
        check_csrf();
        $order = Order::find((int)input('order_id'));
        if (!$order || $order['status'] !== 'open' || $order['is_hidden']) {
            flash('error', 'Заказ уже не принимает отклики.');
            redirect(url('orders'));
        }
        $back = url('order', ['id' => $order['id']]);

        if (!Resume::findByUser($user['id'])) {
            flash('error', 'Чтобы откликаться на заказы, сначала заполните резюме.');
            redirect(url('resume_edit'));
        }
        if (Response::findMine($order['id'], $user['id'])) {
            flash('error', 'Вы уже откликнулись на этот заказ.');
            redirect($back);
        }

        $message = input('message');
        $price = input('price');
        $days = input('days');

        $errors = [];
        if (text_length($message) < 10 || text_length($message) > 2000) {
            $errors[] = 'Сопроводительное сообщение – от 10 до 2000 символов.';
        }
        if (!ctype_digit($price) || (int)$price < 100 || (int)$price > 10000000) {
            $errors[] = 'Цена – целое число от 100 рублей.';
        }
        if (!ctype_digit($days) || (int)$days < 1 || (int)$days > 365) {
            $errors[] = 'Срок – от 1 до 365 дней.';
        }

        if ($errors) {
            flash('error', implode(' ', $errors));
        } else {
            Response::create($order['id'], $user['id'], $message, (int)$price, (int)$days);
            flash('success', 'Отклик отправлен. Заказчик увидит его на странице заказа.');
        }
        redirect($back);
    }

    public static function withdraw(): void
    {
        $user = require_role('executor');
        check_csrf();
        $response = Response::find((int)input('id'));
        if (!$response || $response['executor_id'] !== $user['id']) {
            forbidden();
        }

        Response::withdraw($response['id'], $user['id']);
        flash('success', 'Отклик отозван.');
        redirect(url('order', ['id' => $response['order_id']]));
    }

    public static function accept(): void
    {
        $user = require_role('customer');
        check_csrf();
        $response = Response::find((int)input('id'));
        $order = $response ? Order::find($response['order_id']) : null;
        if (!$order || $order['customer_id'] !== $user['id']) {
            forbidden();
        }

        if ($response['status'] === 'sent' && Response::accept($response)) {
            flash('success', 'Исполнитель выбран, заказ переведён в работу. Остальные отклики отклонены.');
        } else {
            flash('error', 'Исполнитель по этому заказу уже выбран.');
        }
        redirect(url('order', ['id' => $order['id']]));
    }
}
