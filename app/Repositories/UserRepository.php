<?php

class UserRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        return $this->db->query("
            SELECT u.*, r.name AS role_name
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
        ")->fetchAll();
    }

    public function getManagers(): array
    {
        return $this->db->query("
            SELECT u.id, u.full_name, u.email, r.name AS role_name
            FROM users u
            INNER JOIN roles r ON u.role_id = r.id
            WHERE r.name = 'manager'
              AND u.is_active = 1
            ORDER BY u.full_name
        ")->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function usernameExists(string $username): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        return $stmt->fetch() !== false;
    }
    
    public function create(array $data): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, full_name, password_hash, role_id, is_active)
            VALUES (?, ?, ?, ?, ?, 1)
        ");

        $stmt->execute([
            $data['username'],
            $data['email'],
            $data['full_name'],
            $data['password_hash'],
            $data['role_id']
        ]);
    }

    public function update(int $id, array $data): void
    {
        if (isset($data['password_hash'])) {
            $stmt = $this->db->prepare("
                UPDATE users
                SET email = ?, full_name = ?, password_hash = ?, role_id = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $data['email'],
                $data['full_name'],
                $data['password_hash'],
                $data['role_id'],
                $id
            ]);
        } else {
            $stmt = $this->db->prepare("
                UPDATE users
                SET email = ?, full_name = ?, role_id = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $data['email'],
                $data['full_name'],
                $data['role_id'],
                $id
            ]);
        }
    }

    public function toggleStatus(int $id): void
    {
        $this->db->prepare("
            UPDATE users
            SET is_active = NOT is_active
            WHERE id = ?
        ")->execute([$id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function updatePassword(int $id, string $passwordHash): void
    {
        $stmt = $this->db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmt->execute([$passwordHash, $id]);
    }

    public function updateLastLogin(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE users SET last_login_at = NOW() WHERE id = ?");
        $stmt->execute([$id]);
    }
}
