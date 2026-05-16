<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/Session.php';
require_once __DIR__ . '/../Services/AuthService.php';

class AuthController extends Controller
{
    public function __construct(PDO $db)
    {
        parent::__construct($db);

        $this->authService = new AuthService($db);
    }

    public function indexLogin(): void
    {
        if ($this->authService->isAuthenticated()) {
            header('Location: /dashboard');
            exit;
        }

        $systemStats = $this->authService->getPublicSystemStats();
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($this->authService->login($email, $password)) {
            header('Location: /dashboard');
            exit;
        }

        Session::set('error', 'Invalid credentials');
        $this->redirect('/login');
    }

    public function logout(): void
    {
        $this->authService->logout();
        $this->redirect('/login');
    }
}
