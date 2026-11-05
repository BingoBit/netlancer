<?php

class User
{
    public static function find(int $id): ?array
    {
        return db_one('SELECT * FROM users WHERE id = ?', [$id]);
    }

    public static function findByEmail(string $email): ?array
    {
        return db_one('SELECT * FROM users WHERE email = ?', [$email]);
    }

    public static function create(string $name, string $email, string $password, string $role): int
    {
        db_run(
            'INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)',
            [$name, $email, password_hash($password, PASSWORD_DEFAULT), $role]
        );
        return (int)db()->lastInsertId();
    }

    public static function updateProfile(int $id, string $name, string $about, string $language): void
    {
        db_run(
            'UPDATE users SET name = ?, about = ?, language = ? WHERE id = ?',
            [$name, $about, $language, $id]
        );
    }

    public static function updateAvatar(int $id, ?string $avatar): void
    {
        db_run('UPDATE users SET avatar = ? WHERE id = ?', [$avatar, $id]);
    }

    public static function updatePassword(int $id, string $password): void
    {
        db_run('UPDATE users SET password_hash = ? WHERE id = ?', [password_hash($password, PASSWORD_DEFAULT), $id]);
    }

    public static function setBlocked(int $id, bool $blocked): void
    {
        db_run('UPDATE users SET is_blocked = ? WHERE id = ?', [$blocked ? 1 : 0, $id]);
    }

    public static function search(string $q, int $limit, int $offset): array
    {
        [$where, $params] = self::searchWhere($q);
        return db_all(
            'SELECT * FROM users' . $where . ' ORDER BY id DESC LIMIT ' . $limit . ' OFFSET ' . $offset,
            $params
        );
    }

    public static function countSearch(string $q): int
    {
        [$where, $params] = self::searchWhere($q);
        return (int)db_value('SELECT COUNT(*) FROM users' . $where, $params);
    }

    private static function searchWhere(string $q): array
    {
        if ($q === '') {
            return ['', []];
        }
        return [' WHERE name LIKE ? OR email LIKE ?', ['%' . $q . '%', '%' . $q . '%']];
    }

    public static function countByRole(): array
    {
        $result = ['customer' => 0, 'executor' => 0, 'admin' => 0];
        foreach (db_all('SELECT role, COUNT(*) AS cnt FROM users GROUP BY role') as $row) {
            $result[$row['role']] = (int)$row['cnt'];
        }
        return $result;
    }
}
