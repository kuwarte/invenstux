<?php

require_once __DIR__ . '/../Repositories/UserRepository.php';
require_once __DIR__ . '/../Repositories/RoleRepository.php';

class UserService
{
    private UserRepository $userRepo;
    private RoleRepository $roleRepo;

    public function __construct(PDO $db)
    {
        $this->userRepo = new UserRepository($db);
        $this->roleRepo = new RoleRepository($db);
    }

    public function getAll(): array
    {
        return $this->userRepo->getAll();
    }

    public function getRoles(): array
    {
        return $this->roleRepo->getAll();
    }

    public function findById(int $id): ?array
    {
        return $this->userRepo->findById($id);
    }

    public function create(array $data): void
    {
        $email = trim($data['email'] ?? '');
        $fullName = trim($data['full_name'] ?? '');
        $password = $data['password'] ?? '';
        $roleId = (int) ($data['role_id'] ?? 1);

        if (!$email || !$fullName || !$password) {
            throw new Exception('All fields are required');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        if ($this->userRepo->findByEmail($email)) {
            throw new Exception('Email already exists');
        }

        $username = $this->generateUsername($email);

        if ($this->userRepo->usernameExists($username)) {
            $username .= rand(1, 99);
        }

        $this->userRepo->create([
            'username' => $username,
            'email' => $email,
            'full_name' => $fullName,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'role_id' => $roleId
        ]);
    }

    public function update(int $id, array $data): void
    {
        $user = $this->userRepo->findById($id);

        if (!$user) {
            throw new Exception('User not found');
        }

        $email = trim($data['email'] ?? '');
        $fullName = trim($data['full_name'] ?? '');
        $roleId = (int) ($data['role_id'] ?? $user['role_id']);

        if (!$email || !$fullName) {
            throw new Exception('Email and full name are required');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        if ($email !== $user['email'] && $this->userRepo->findByEmail($email)) {
            throw new Exception('Email already exists');
        }

        $updateData = [
            'email' => $email,
            'full_name' => $fullName,
            'role_id' => $roleId
        ];

        if (!empty($data['password'])) {
            $updateData['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $this->userRepo->update($id, $updateData);
    }

    public function toggleStatus(int $id, int $currentUserId): void
    {
        $user = $this->userRepo->findById($id);

        if (!$user) {
            throw new Exception('User not found');
        }

        if ($user['id'] == $currentUserId) {
            throw new Exception('You cannot modify your own account');
        }

        $this->userRepo->toggleStatus($id);
    }

    public function delete(int $id, int $currentUserId): void
    {
        $user = $this->userRepo->findById($id);

        if (!$user) {
            throw new Exception('User not found');
        }

        if ($user['id'] == $currentUserId) {
            throw new Exception('You cannot delete yourself');
        }

        $this->userRepo->delete($id);
    }

    public function changePassword(int $userId, string $currentPassword, string $newPassword): void
    {
        $user = $this->userRepo->findById($userId);

        if (!$user) {
            throw new Exception('User not found');
        }

        if (!password_verify($currentPassword, $user['password_hash'])) {
            throw new Exception('Current password is incorrect');
        }

        if (strlen($newPassword) < 6) {
            throw new Exception('New password must be at least 6 characters');
        }

        $this->userRepo->updatePassword($userId, password_hash($newPassword, PASSWORD_BCRYPT));
    }

    private function generateUsername(string $email): string
    {
        return strtolower(explode('@', $email)[0]);
    }
}
