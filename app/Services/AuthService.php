<?php

require_once __DIR__ . '/../../core/Session.php';
require_once __DIR__ . '/../Repositories/RoleRepository.php';
require_once __DIR__ . '/../Repositories/SalesRepository.php';
require_once __DIR__ . '/../Repositories/WarehouseRepository.php';

class AuthService
{
    private PDO $db;
    private UserRepository $userRepo;
    private RoleRepository $roleRepo;
    private SalesRepository $salesRepo;
    private WarehouseRepository $warehouseRepo;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        require_once __DIR__ . '/../Repositories/UserRepository.php';
        $this->userRepo = new UserRepository($db);
        $this->roleRepo = new RoleRepository($db);
        $this->salesRepo = new SalesRepository($db);
        $this->warehouseRepo = new WarehouseRepository($db);
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->userRepo->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash']) || !$user['is_active']) {
            return false;
        }

        $role = $this->roleRepo->findById($user['role_id']);

        Session::set('user_id', $user['id']);
        Session::set('user_name', $user['full_name']);
        Session::set('full_name', $user['full_name']);
        Session::set('user_role', $user['role_id']);
        Session::set('role_name', $role['name'] ?? 'user');

        $this->userRepo->updateLastLogin($user['id']);

        return true;
    }

    public function logout(): void
    {
        Session::destroy();
    }

    public function isAuthenticated(): bool
    {
        return Session::get('user_id') !== null;
    }

    public function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            header('Location: /login');
            exit;
        }
    }

    public function getPublicSystemStats(): array
    {
        try {
            $warehouses = $this->warehouseRepo->countActive();
            $transactions = $this->salesRepo->getTotalSalesCount();
        } catch (Exception $e) {
            $warehouses = 0;
            $transactions = 0;
        }

        return [
            'warehouses'   => $warehouses,
            'uptime'       => '99.9%',
            'transactions' => $transactions
        ];
    }
}
