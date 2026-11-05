<?php

class Response
{
    public static function forOrder(int $orderId): array
    {
        return db_all(
            "SELECT r.*, u.name AS executor_name, u.avatar, rs.id AS resume_id, rs.title AS resume_title,
                    (SELECT ROUND(AVG(rating), 1) FROM reviews WHERE executor_id = r.executor_id) AS rating
             FROM responses r
             JOIN users u ON u.id = r.executor_id
             LEFT JOIN resumes rs ON rs.user_id = r.executor_id
             WHERE r.order_id = ?
             ORDER BY r.status = 'accepted' DESC, r.created_at",
            [$orderId]
        );
    }

    public static function find(int $id): ?array
    {
        return db_one('SELECT * FROM responses WHERE id = ?', [$id]);
    }

    public static function findMine(int $orderId, int $executorId): ?array
    {
        return db_one('SELECT * FROM responses WHERE order_id = ? AND executor_id = ?', [$orderId, $executorId]);
    }

    public static function create(int $orderId, int $executorId, string $message, int $price, int $days): void
    {
        db_run(
            'INSERT INTO responses (order_id, executor_id, message, price, days) VALUES (?, ?, ?, ?, ?)',
            [$orderId, $executorId, $message, $price, $days]
        );
    }

    public static function withdraw(int $id, int $executorId): void
    {
        db_run("DELETE FROM responses WHERE id = ? AND executor_id = ? AND status = 'sent'", [$id, $executorId]);
    }

    public static function accept(array $response): bool
    {
        $pdo = db();
        $pdo->beginTransaction();
        try {
            $changed = db_run(
                "UPDATE orders SET executor_id = ?, status = 'in_progress' WHERE id = ? AND status = 'open'",
                [$response['executor_id'], $response['order_id']]
            );
            if ($changed === 0) {
                $pdo->rollBack();  // заказ уже не открыт
                return false;
            }
            db_run("UPDATE responses SET status = 'accepted' WHERE id = ?", [$response['id']]);
            db_run(
                "UPDATE responses SET status = 'rejected' WHERE order_id = ? AND id <> ?",
                [$response['order_id'], $response['id']]
            );
            $pdo->commit();
            return true;
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function byExecutor(int $executorId): array
    {
        return db_all(
            'SELECT r.*, o.title AS order_title, o.status AS order_status, o.budget
             FROM responses r
             JOIN orders o ON o.id = r.order_id
             WHERE r.executor_id = ?
             ORDER BY r.created_at DESC',
            [$executorId]
        );
    }

    public static function countAll(): int
    {
        return (int)db_value('SELECT COUNT(*) FROM responses');
    }
}
