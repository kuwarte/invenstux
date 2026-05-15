<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Services/UserService.php';

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(PDO $db)
    {
        parent::__construct($db);

        $this->userService = new UserService($db);
    }

    public function index(): void
    {
        $this->authorize('manage_users');
        $users = $this->userService->getAll();
        $this->view('users/index', compact('users'));
    }

    public function indexCreate(): void
    {
        $this->authorize('manage_users');
        $roles = $this->userService->getRoles();
        $this->view('users/create', compact('roles'));
    }

    public function create(): void
    {
        $this->authorize('manage_users');
        try {
            $this->userService->create($_POST);
            Session::set('success', 'User created successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }
        
        $this->redirect('/users');
    }

    public function store(): void
    {
        $this->authorize('manage_users');
        try {
            $this->userService->create($_POST);
            Session::set('success', 'User created successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }
        
        $this->redirect('/users');
    }

    public function indexUpdate(): void
    {
        $this->authorize('manage_users');
        $id = (int) ($_GET['id'] ?? 0);

        $user = $this->userService->findById($id);

        if (!$user) {
            Session::set('error', 'User not found');
            $this->redirect('/users');
            return;
        }

        $roles = $this->userService->getRoles();

        $this->view('users/edit', compact('user', 'roles'));
    }

    public function update(): void
    {
        $this->authorize('manage_users');
        $id = (int) ($_POST['id'] ?? 0);
        
        try {
            $this->userService->update($id, $_POST);
            Session::set('success', 'User updated successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }
        
        $this->redirect('/users');
    }

    public function toggleStatus(): void
    {
        $this->authorize('manage_users');
        $id = (int) ($_POST['id'] ?? 0);
        $currentUserId = (int) Session::get('user_id');
        
        try {
            $this->userService->toggleStatus($id, $currentUserId);
            Session::set('success', 'User status updated');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }
        
        $this->redirect('/users');
    }

    public function delete(): void
    {
        $this->authorize('manage_users');
        $id = (int) ($_POST['id'] ?? 0);
        $currentUserId = (int) Session::get('user_id');
        
        try {
            $this->userService->delete($id, $currentUserId);
            Session::set('success', 'User deleted successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }
        
        $this->redirect('/users');
    }

    public function indexSettings(): void
    {
        $userId = (int) Session::get('user_id');
        $user = $this->userService->findById($userId);
        
        if (!$user) {
            Session::set('error', 'User not found');
            $this->redirect('/dashboard');
            return;
        }
        
        $this->view('users/settings', compact('user'));
    }

    public function changePassword(): void
    {
        $userId = (int) Session::get('user_id');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        try {
            if ($newPassword !== $confirmPassword) {
                throw new Exception('New passwords do not match');
            }
            
            $this->userService->changePassword($userId, $currentPassword, $newPassword);
            Session::set('success', 'Password changed successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }
        
        $this->redirect('/users/settings');
    }
}
