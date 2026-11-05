<?php

class Dialog
{
    private const SELECT = 'SELECT d.*, cu.name AS customer_name, cu.avatar AS customer_avatar,
               ex.name AS executor_name, ex.avatar AS executor_avatar, o.title AS order_title
        FROM dialogs d
        JOIN users cu ON cu.id = d.customer_id
        JOIN users ex ON ex.id = d.executor_id
        LEFT JOIN orders o ON o.id = d.order_id';

    public static function find(int $id): ?array
    {
        return db_one(self::SELECT . ' WHERE d.id = ?', [$id]);
    }

    public static function findByPair(int $customerId, int $executorId): ?array
    {
        return db_one(self::SELECT . ' WHERE d.customer_id = ? AND d.executor_id = ?', [$customerId, $executorId]);
    }

    // Находит диалог пары или создаёт новый; запоминает заказ, по которому идёт обсуждение
    public static function open(int $customerId, int $executorId, ?int $orderId): int
    {
        $dialog = self::findByPair($customerId, $executorId);
        if ($dialog) {
            if ($orderId) {
                db_run('UPDATE dialogs SET order_id = ? WHERE id = ?', [$orderId, $dialog['id']]);
            }
            return (int)$dialog['id'];
        }

        db_run(
            'INSERT INTO dialogs (customer_id, executor_id, order_id) VALUES (?, ?, ?)',
            [$customerId, $executorId, $orderId]
        );
        return (int)db()->lastInsertId();
    }

    public static function isMember(array $dialog, int $userId): bool
    {
        return $dialog['customer_id'] === $userId || $dialog['executor_id'] === $userId;
    }

    public static function forUser(int $userId): array
    {
        return db_all(
            'SELECT d.*, cu.name AS customer_name, cu.avatar AS customer_avatar,
                ex.name AS executor_name, ex.avatar AS executor_avatar, o.title AS order_title,
                (SELECT body FROM messages WHERE dialog_id = d.id ORDER BY id DESC LIMIT 1) AS last_text,
                (SELECT COUNT(*) FROM messages WHERE dialog_id = d.id AND sender_id <> ? AND is_read = 0) AS unread
             FROM dialogs d
             JOIN users cu ON cu.id = d.customer_id
             JOIN users ex ON ex.id = d.executor_id
             LEFT JOIN orders o ON o.id = d.order_id
             WHERE d.customer_id = ? OR d.executor_id = ?
             ORDER BY COALESCE(d.last_message_at, d.created_at) DESC',
            [$userId, $userId, $userId]
        );
    }

    public static function unreadTotal(int $userId): int
    {
        return (int)db_value(
            'SELECT COUNT(*) FROM messages m
             JOIN dialogs d ON d.id = m.dialog_id
             WHERE (d.customer_id = ? OR d.executor_id = ?) AND m.sender_id <> ? AND m.is_read = 0',
            [$userId, $userId, $userId]
        );
    }

    // Удаляет диалог целиком; сообщения удаляются каскадно в базе
    public static function deleteForMember(int $id, int $userId): bool
    {
        return db_run(
            'DELETE FROM dialogs WHERE id = ? AND (customer_id = ? OR executor_id = ?)',
            [$id, $userId, $userId]
        ) > 0;
    }
}
