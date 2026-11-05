<?php

class Message
{
    public static function after(int $dialogId, int $afterId): array
    {
        return db_all(
            'SELECT m.id, m.sender_id, m.body, m.created_at, u.name AS sender_name
             FROM messages m
             JOIN users u ON u.id = m.sender_id
             WHERE m.dialog_id = ? AND m.id > ?
             ORDER BY m.id
             LIMIT 200',
            [$dialogId, $afterId]
        );
    }

    public static function create(int $dialogId, int $senderId, string $body): void
    {
        db_run('INSERT INTO messages (dialog_id, sender_id, body) VALUES (?, ?, ?)', [$dialogId, $senderId, $body]);
        db_run('UPDATE dialogs SET last_message_at = NOW() WHERE id = ?', [$dialogId]);
    }

    public static function markRead(int $dialogId, int $readerId): void
    {
        db_run(
            'UPDATE messages SET is_read = 1 WHERE dialog_id = ? AND sender_id <> ? AND is_read = 0',
            [$dialogId, $readerId]
        );
    }

    public static function countAll(): int
    {
        return (int)db_value('SELECT COUNT(*) FROM messages');
    }
}
