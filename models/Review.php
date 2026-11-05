<?php

class Review
{
    private const SELECT = 'SELECT rv.*, cu.name AS customer_name, ex.name AS executor_name,
               o.title AS order_title, o.budget, rs.id AS resume_id
        FROM reviews rv
        JOIN users cu ON cu.id = rv.customer_id
        JOIN users ex ON ex.id = rv.executor_id
        JOIN orders o ON o.id = rv.order_id
        LEFT JOIN resumes rs ON rs.user_id = rv.executor_id';

    public static function create(int $orderId, int $customerId, int $executorId, int $rating, string $text): void
    {
        db_run(
            'INSERT INTO reviews (order_id, customer_id, executor_id, rating, text) VALUES (?, ?, ?, ?, ?)',
            [$orderId, $customerId, $executorId, $rating, $text]
        );
    }

    public static function findByOrder(int $orderId): ?array
    {
        return db_one(self::SELECT . ' WHERE rv.order_id = ?', [$orderId]);
    }

    public static function latest(int $limit): array
    {
        return db_all(self::SELECT . ' ORDER BY rv.created_at DESC LIMIT ' . $limit);
    }

    public static function forExecutor(int $executorId): array
    {
        return db_all(self::SELECT . ' WHERE rv.executor_id = ? ORDER BY rv.created_at DESC', [$executorId]);
    }

    public static function countAll(): int
    {
        return (int)db_value('SELECT COUNT(*) FROM reviews');
    }

    public static function countByCustomer(int $customerId): int
    {
        return (int)db_value('SELECT COUNT(*) FROM reviews WHERE customer_id = ?', [$customerId]);
    }
}
