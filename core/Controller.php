<?php

require_once __DIR__ . '/../app/Services/AuthService.php';
require_once __DIR__ . '/../app/Services/AuthorizationService.php';

class Controller
{
    protected PDO $db;
    protected AuthService $authService;
    protected AuthorizationService $authorizationService;

    public function __construct(PDO $db)
    {
        $this->db = $db;

        $this->authService = new AuthService($db);
        $this->authorizationService = new AuthorizationService($db);
    }

    protected function view(string $path, array $data = []): void
    {
        extract($data);

        require_once __DIR__ . "/../app/Views/layouts/main.php";
    }

    protected function authorize(string $permission): void
    {
        $this->authService->requireAuth();

        if (!$this->authorizationService->can($permission)) {
            Session::set('error', 'You do not have permission to access this resource.');
            $this->redirect('/dashboard');
        }
    }

    protected function redirect(string $path): void
    {
        header("Location: {$path}");
        exit;
    }
}
