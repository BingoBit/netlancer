<?php

class ChatController
{
    public static function index(): void
    {
        $user = require_role('customer', 'executor');
        render('chat/index', ['title' => 'Сообщения', 'user' => $user, 'dialogs' => Dialog::forUser($user['id'])]);
    }

    public static function show(): void
    {
        $user = require_role('customer', 'executor');
        $dialog = Dialog::find((int)query('id'));
        if (!$dialog || !Dialog::isMember($dialog, $user['id'])) {
            not_found();
        }

        $messages = Message::after($dialog['id'], 0);
        Message::markRead($dialog['id'], $user['id']);
        $lastId = $messages ? end($messages)['id'] : 0;

        render('chat/show', [
            'title'    => 'Диалог',
            'user'     => $user,
            'dialog'   => $dialog,
            'messages' => $messages,
            'lastId'   => $lastId,
            'partner'  => $user['id'] === $dialog['customer_id'] ? $dialog['executor_name'] : $dialog['customer_name'],
        ]);
    }

    public static function start(): void
    {
        $user = require_role('customer', 'executor');
        check_csrf();
        $other = User::find((int)input('user_id'));
        $orderId = (int)input('order_id') ?: null;

        if ($user['role'] === 'customer' && $other && $other['role'] === 'executor') {
            $dialogId = Dialog::open($user['id'], $other['id'], $orderId);
        } elseif ($user['role'] === 'executor' && $other && $other['role'] === 'customer') {
            $dialogId = Dialog::open($other['id'], $user['id'], $orderId);
        } else {
            flash('error', 'Переписка возможна только между заказчиком и исполнителем.');
            redirect(url('chats'));
        }

        redirect(url('chat', ['id' => $dialogId]));
    }

    public static function poll(): void
    {
        $user = current_user();
        $dialog = Dialog::find((int)query('id'));
        if (!$user || !$dialog || !Dialog::isMember($dialog, $user['id'])) {
            render_json(['error' => 'Нет доступа к диалогу'], 403);
        }

        $messages = Message::after($dialog['id'], (int)query('after'));
        if ($messages) {
            Message::markRead($dialog['id'], $user['id']);
        }

        render_json(['messages' => self::format($messages, $user['id'])]);
    }

    public static function send(): void
    {
        $user = current_user();
        $dialog = Dialog::find((int)input('id'));
        if (!$user || !$dialog || !Dialog::isMember($dialog, $user['id'])) {
            render_json(['error' => 'Нет доступа к диалогу'], 403);
        }
        if (!hash_equals(csrf_token(), input('csrf'))) {
            render_json(['error' => 'Страница устарела, обновите её.'], 419);
        }

        $text = input('text');
        if ($text === '' || text_length($text) > 2000) {
            render_json(['error' => 'Сообщение должно содержать от 1 до 2000 символов.'], 422);
        }

        Message::create($dialog['id'], $user['id'], $text);
        render_json(['ok' => true]);
    }

    public static function delete(): void
    {
        $user = require_role('customer', 'executor');
        check_csrf();
        $id = (int)input('id');
        $dialog = Dialog::find($id);
        if (!$dialog || !Dialog::isMember($dialog, $user['id'])) {
            forbidden();
        }

        if (Dialog::deleteForMember($id, $user['id'])) {
            flash('success', 'Переписка удалена.');
        } else {
            flash('error', 'Не удалось удалить переписку.');
        }
        redirect(url('chats'));
    }

    private static function format(array $messages, int $userId): array
    {
        $result = [];
        foreach ($messages as $m) {
            $result[] = [
                'id'   => $m['id'],
                'mine' => $m['sender_id'] === $userId,
                'text' => $m['body'],
                'time' => date_ru($m['created_at'], true),
            ];
        }
        return $result;
    }
}
