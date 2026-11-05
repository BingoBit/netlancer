<?php

class Order
{
    private const SELECT = "SELECT o.*, c.name AS category_name, c.icon AS category_icon,
               cu.name AS customer_name, ex.name AS executor_name,
               (SELECT COUNT(*) FROM responses WHERE order_id = o.id) AS responses_count
        FROM orders o
        JOIN categories c ON c.id = o.category_id
        JOIN users cu ON cu.id = o.customer_id
        LEFT JOIN users ex ON ex.id = o.executor_id";

    public static function find(int $id): ?array
    {
        return db_one(self::SELECT . ' WHERE o.id = ?', [$id]);
    }

    public static function search(array $filters, int $limit, int $offset): array
    {
        [$where, $params] = self::filtersWhere($filters);
        $order = $filters['sort'] === 'budget' ? 'o.budget DESC' : 'o.created_at DESC';
        return db_all(
            self::SELECT . $where . ' ORDER BY ' . $order . ' LIMIT ' . $limit . ' OFFSET ' . $offset,
            $params
        );
    }

    public static function countSearch(array $filters): int
    {
        [$where, $params] = self::filtersWhere($filters);
        return (int)db_value('SELECT COUNT(*) FROM orders o JOIN users cu ON cu.id = o.customer_id' . $where, $params);
    }

    private static function filtersWhere(array $filters): array
    {
        $where = ["o.status = 'open'", 'o.is_hidden = 0', 'cu.is_blocked = 0'];
        $params = [];

        if ($filters['q'] !== '') {
            $where[] = '(o.title LIKE ? OR o.description LIKE ?)';
            $params[] = '%' . $filters['q'] . '%';
            $params[] = '%' . $filters['q'] . '%';
        }
        if ($filters['category'] > 0) {
            $ids = Category::withChildrenIds($filters['category']);
            $where[] = 'o.category_id IN (' . implode(',', $ids) . ')';
        }
        if ($filters['budget_from'] > 0) {
            $where[] = 'o.budget >= ?';
            $params[] = $filters['budget_from'];
        }
        if ($filters['budget_to'] > 0) {
            $where[] = 'o.budget <= ?';
            $params[] = $filters['budget_to'];
        }

        return [' WHERE ' . implode(' AND ', $where), $params];
    }

    public static function latest(int $limit): array
    {
        return db_all(self::SELECT . " WHERE o.status = 'open' AND o.is_hidden = 0 AND cu.is_blocked = 0
                                      ORDER BY o.created_at DESC LIMIT " . $limit);
    }

    public static function create(int $customerId, array $data): int
    {
        db_run(
            "INSERT INTO orders (customer_id, category_id, title, description, budget, deadline, status)
             VALUES (?, ?, ?, ?, ?, ?, 'moderation')",
            [$customerId, $data['category_id'], $data['title'], $data['description'], (int)$data['budget'], $data['deadline']]
        );
        return (int)db()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        db_run(
            "UPDATE orders SET category_id = ?, title = ?, description = ?, budget = ?, deadline = ?,
                    status = 'moderation', reject_reason = NULL
             WHERE id = ? AND status IN ('moderation', 'open')",
            [$data['category_id'], $data['title'], $data['description'], (int)$data['budget'], $data['deadline'], $id]
        );
    }

    public static function cancel(int $id): bool
    {
        db()->beginTransaction();
        $changed = db_run("UPDATE orders SET status = 'cancelled' WHERE id = ? AND status IN ('moderation', 'open')", [$id]);
        if ($changed > 0) {
            db_run("UPDATE responses SET status = 'rejected' WHERE order_id = ? AND status = 'sent'", [$id]);
        }
        db()->commit();
        return $changed > 0;
    }

    public static function approve(int $id): void
    {
        db_run("UPDATE orders SET status = 'open', reject_reason = NULL WHERE id = ? AND status = 'moderation'", [$id]);
    }

    public static function reject(int $id, string $reason): void
    {
        db_run("UPDATE orders SET status = 'rejected', reject_reason = ? WHERE id = ? AND status = 'moderation'", [$reason, $id]);
    }

    public static function setHidden(int $id, bool $hidden): void
    {
        db_run('UPDATE orders SET is_hidden = ? WHERE id = ?', [$hidden ? 1 : 0, $id]);
    }

    // Исполнитель отмечает, что работа сдана
    public static function markDelivered(int $id): void
    {
        db_run("UPDATE orders SET delivered_at = NOW() WHERE id = ? AND status = 'in_progress'", [$id]);
    }

    // Заказчик принимает работу
    public static function complete(int $id): void
    {
        db_run("UPDATE orders SET status = 'completed' WHERE id = ? AND status = 'in_progress' AND delivered_at IS NOT NULL", [$id]);
    }

    public static function byCustomer(int $customerId): array
    {
        return db_all(self::SELECT . ' WHERE o.customer_id = ? ORDER BY o.created_at DESC', [$customerId]);
    }

    public static function byExecutor(int $executorId): array
    {
        return db_all(self::SELECT . ' WHERE o.executor_id = ? ORDER BY o.status = \'completed\', o.created_at DESC', [$executorId]);
    }

    public static function adminList(string $status, int $limit, int $offset): array
    {
        return db_all(
            self::SELECT . ' WHERE o.status = ? ORDER BY o.created_at DESC LIMIT ' . $limit . ' OFFSET ' . $offset,
            [$status]
        );
    }

    public static function countByStatus(): array
    {
        $result = array_fill_keys(array_keys(ORDER_STATUSES), 0);
        foreach (db_all('SELECT status, COUNT(*) AS cnt FROM orders GROUP BY status') as $row) {
            $result[$row['status']] = (int)$row['cnt'];
        }
        return $result;
    }
}
