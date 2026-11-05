<?php

class Category
{
    // Категорий немного, поэтому они читаются одним запросом и хранятся до конца запроса
    public static function all(): array
    {
        static $list = null;

        if ($list === null) {
            $list = [];
            foreach (db_all('SELECT * FROM categories ORDER BY sort_order, name') as $row) {
                $list[$row['id']] = $row;
            }
        }

        return $list;
    }

    public static function find(int $id): ?array
    {
        return self::all()[$id] ?? null;
    }

    public static function children(?int $parentId): array
    {
        $result = [];
        foreach (self::all() as $category) {
            if ($category['parent_id'] === $parentId) {
                $result[] = $category;
            }
        }
        return $result;
    }

    public static function sections(): array
    {
        return self::children(null);
    }

    public static function isLeaf(int $id): bool
    {
        return self::find($id) !== null && self::children($id) === [];
    }

    public static function withChildrenIds(int $id): array
    {
        $ids = [$id];
        foreach (self::children($id) as $child) {
            $ids[] = $child['id'];
            foreach (self::children($child['id']) as $sub) {
                $ids[] = $sub['id'];
            }
        }
        return $ids;
    }

    public static function path(int $id): array
    {
        $path = [];
        $category = self::find($id);
        while ($category) {
            array_unshift($path, $category);
            $category = $category['parent_id'] ? self::find($category['parent_id']) : null;
        }
        return $path;
    }

    public static function selectGroups(): array
    {
        $groups = [];
        foreach (self::sections() as $section) {
            foreach (self::children($section['id']) as $group) {
                $items = self::children($group['id']);
                if ($items) {
                    $groups[$section['name'] . ' / ' . $group['name']] = $items;
                }
            }
        }
        return $groups;
    }

    // Сколько открытых заказов в каждой категории: [id категории => количество]
    public static function openOrderCounts(): array
    {
        $counts = [];
        $rows = db_all("SELECT category_id, COUNT(*) AS cnt FROM orders
                        WHERE status = 'open' AND is_hidden = 0 GROUP BY category_id");
        foreach ($rows as $row) {
            $counts[$row['category_id']] = (int)$row['cnt'];
        }
        return $counts;
    }

    public static function create(?int $parentId, string $name, ?string $icon): void
    {
        db_run(
            'INSERT INTO categories (parent_id, name, icon, sort_order) VALUES (?, ?, ?, 100)',
            [$parentId, $name, $icon]
        );
    }

    public static function rename(int $id, string $name): void
    {
        db_run('UPDATE categories SET name = ? WHERE id = ?', [$name, $id]);
    }

    public static function usage(int $id): int
    {
        return (int)db_value('SELECT COUNT(*) FROM orders WHERE category_id = ?', [$id])
            + (int)db_value('SELECT COUNT(*) FROM resumes WHERE category_id = ?', [$id]);
    }

    public static function delete(int $id): void
    {
        db_run('DELETE FROM categories WHERE id = ?', [$id]);
    }
}
