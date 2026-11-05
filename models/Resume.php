<?php

class Resume
{
    private const SELECT = "SELECT r.*, u.name AS user_name, u.avatar, u.language, u.is_blocked, c.name AS category_name,
               (SELECT ROUND(AVG(rating), 1) FROM reviews WHERE executor_id = r.user_id) AS rating,
               (SELECT COUNT(*) FROM reviews WHERE executor_id = r.user_id) AS reviews_count
        FROM resumes r
        JOIN users u ON u.id = r.user_id
        JOIN categories c ON c.id = r.category_id";

    public static function find(int $id): ?array
    {
        return db_one(self::SELECT . ' WHERE r.id = ?', [$id]);
    }

    public static function findByUser(int $userId): ?array
    {
        return db_one(self::SELECT . ' WHERE r.user_id = ?', [$userId]);
    }

    public static function search(array $filters, int $limit, int $offset): array
    {
        [$where, $params] = self::filtersWhere($filters);
        return db_all(
            self::SELECT . $where . ' ORDER BY rating IS NULL, rating DESC, r.updated_at DESC'
            . ' LIMIT ' . $limit . ' OFFSET ' . $offset,
            $params
        );
    }

    public static function countSearch(array $filters): int
    {
        [$where, $params] = self::filtersWhere($filters);
        return (int)db_value(
            'SELECT COUNT(*) FROM resumes r JOIN users u ON u.id = r.user_id' . $where,
            $params
        );
    }

    private static function filtersWhere(array $filters): array
    {
        $where = ['r.is_hidden = 0', 'u.is_blocked = 0'];
        $params = [];

        if ($filters['q'] !== '') {
            $where[] = '(r.title LIKE ? OR r.skills LIKE ?)';
            $params[] = '%' . $filters['q'] . '%';
            $params[] = '%' . $filters['q'] . '%';
        }
        if ($filters['category'] > 0) {
            // id берутся из справочника категорий, это целые числа – их можно вставить в запрос
            $ids = Category::withChildrenIds($filters['category']);
            $where[] = 'r.category_id IN (' . implode(',', $ids) . ')';
        }

        return [' WHERE ' . implode(' AND ', $where), $params];
    }

    public static function best(int $limit): array
    {
        return db_all(
            self::SELECT . ' WHERE r.is_hidden = 0 AND u.is_blocked = 0
            ORDER BY rating IS NULL, rating DESC, reviews_count DESC, r.updated_at DESC
            LIMIT ' . $limit
        );
    }

    public static function save(int $userId, array $data): void
    {
        $params = [
            $data['category_id'], $data['title'], $data['about'], $data['skills'],
            $data['rate'] === '' ? null : (int)$data['rate'], $data['portfolio'],
            $data['photo'] ?? null, $data['gallery'] ?? null,
        ];

        if (self::findByUser($userId)) {
            $params[] = $userId;
            db_run('UPDATE resumes SET category_id = ?, title = ?, about = ?, skills = ?, rate = ?, portfolio = ?,
                    photo = ?, gallery = ? WHERE user_id = ?', $params);
        } else {
            array_unshift($params, $userId);
            db_run('INSERT INTO resumes (user_id, category_id, title, about, skills, rate, portfolio, photo, gallery)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', $params);
        }
    }

    public static function setHidden(int $id, bool $hidden): void
    {
        db_run('UPDATE resumes SET is_hidden = ? WHERE id = ?', [$hidden ? 1 : 0, $id]);
    }

    public static function adminList(int $limit, int $offset): array
    {
        return db_all(self::SELECT . ' ORDER BY r.is_hidden DESC, r.id DESC LIMIT ' . $limit . ' OFFSET ' . $offset);
    }

    public static function countAll(): int
    {
        return (int)db_value('SELECT COUNT(*) FROM resumes');
    }
}
