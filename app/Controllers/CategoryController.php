<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/../Services/AuthorizationService.php';
require_once __DIR__ . '/../Services/CategoryService.php';

class CategoryController extends Controller
{
    private CategoryService $categoryService;

    public function __construct(PDO $db)
    {
        parent::__construct($db);

        $this->authService = new AuthService($db);
        $this->authorizationService = new AuthorizationService($db);
        $this->categoryService = new CategoryService($db);
    }

    public function index(): void
    {
        $this->authorize('manage_categories');
        
        $categories = $this->categoryService->getCategoryTree();
        $categoryOptions = $this->categoryService->getAllCategories();

        $this->view('categories/index', compact('categories', 'categoryOptions'));
    }

    public function filter(): void
    {
        $this->authorize('manage_categories');

        $search = $_GET['search'] ?? '';

        $categories = $this->categoryService->getFilteredCategories($search);

        header('Content-Type: application/json');
        echo json_encode($categories);
        exit;
    }

    public function indexCreate(): void
    {
        $this->authorize('manage_categories');
        
        $categories = $this->categoryService->getAllCategories();

        $this->view('categories/create', compact('categories'));
    }

    public function create(): void
    {
        $this->authorize('manage_categories');
        
        $data = [
            'parent_id' => $_POST['parent_id'] ?: null,
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        try {
            $this->categoryService->createCategory($data);
            Session::set('success', 'Category created successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }
        
        $this->redirect('/categories');
    }

    public function update(): void
    {
        $this->authorize('manage_categories');

        $id = (int) ($_POST['id'] ?? 0);

        $data = [
            'parent_id' => $_POST['parent_id'] ?: null,
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        try {
            $this->categoryService->updateCategory($id, $data);
            Session::set('success', 'Category updated successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }

        $this->redirect('/categories');
    }

    public function delete(): void
    {
        $this->authorize('manage_categories');

        $id = (int) ($_POST['id'] ?? 0);

        try {
            $this->categoryService->deleteCategory($id);
            Session::set('success', 'Category deleted successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }

        $this->redirect('/categories');
    }
}
