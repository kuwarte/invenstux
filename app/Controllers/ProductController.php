<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/../Services/ProductService.php';
require_once __DIR__ . '/../Services/CategoryService.php';

class ProductController extends Controller
{
    private ProductService $productService;
    private CategoryService $categoryService;

    public function __construct(PDO $db)
    {
        parent::__construct($db);
        
        $this->authService = new AuthService($db);
        $this->productService = new ProductService($db);
        $this->categoryService = new CategoryService($db);
    }

    public function index(): void
    {
        $this->authorize('manage_products');
        
        $showInactive = isset($_GET['show_inactive']) && $_GET['show_inactive'] == '1';
        $search = $_GET['search'] ?? '';
        $categoryId = $_GET['category_id'] ?? '';

        $products = $this->productService->getFilteredProducts($showInactive, $search, $categoryId);
        $categories = $this->categoryService->getAllCategories();

        $this->view('products/index', compact('products', 'showInactive', 'categories', 'search', 'categoryId'));
    }

    public function filter(): void
    {
        $this->authorize('manage_products');
        
        $showInactive = isset($_GET['show_inactive']) && $_GET['show_inactive'] == '1';
        $search = $_GET['search'] ?? '';
        $categoryId = $_GET['category_id'] ?? '';

        $products = $this->productService->getFilteredProducts($showInactive, $search, $categoryId);
        
        header('Content-Type: application/json');
        echo json_encode($products);
    }

    public function indexCreate(): void
    {
        $this->authorize('manage_products');
        
        $categories = $this->categoryService->getAllCategories();

        $this->view('products/create', compact('categories'));
    }

    public function create(): void
    {
        $this->authorize('manage_products');
        
        $data = [
            'category_id' => $_POST['category_id'] ?: null,
            'sku' => $_POST['sku'] ?? '',
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'unit_of_measure' => $_POST['unit_of_measure'] ?? 'pcs',
            'unit_cost' => $_POST['unit_cost'] ?? 0,
            'min_stock' => $_POST['min_stock'] ?? 10,
            'max_stock' => $_POST['max_stock'] ?? 100,
            'is_active' => $_POST['is_active'] ?? 1
        ];
        
        try {
            $this->productService->createProduct($data);
            Session::set('success', 'Product created successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }
        
        $this->redirect('/products');
    }

    public function indexUpdate(): void
    {
        $this->authorize('manage_products');
        
        $id = (int) ($_GET['id'] ?? 0);

        $product = $this->productService->getProductById($id);
        
        if (!$product) {
            Session::set('error', 'Product not found');
            $this->redirect('/products');
            return;
        }
        
        $categories = $this->categoryService->getAllCategories();

        require_once __DIR__ . '/../Repositories/WarehouseRepository.php';
        $warehouseRepo = new WarehouseRepository($this->db);
        $warehouses = $warehouseRepo->getAll();

        $this->view('products/edit', compact('product', 'categories', 'warehouses'));
    }

    public function update(): void
    {
        $this->authorize('manage_products');
        
        $id = (int) ($_POST['id'] ?? 0);

        $data = [
            'category_id' => $_POST['category_id'] ?: null,
            'sku' => $_POST['sku'] ?? '',
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'unit_of_measure' => $_POST['unit_of_measure'] ?? 'pcs',
            'unit_cost' => $_POST['unit_cost'] ?? 0,
            'min_stock' => $_POST['min_stock'] ?? 10,
            'max_stock' => $_POST['max_stock'] ?? 100,
            'is_active' => $_POST['is_active'] ?? 1
        ];

        try {
            $this->productService->updateProduct($id, $data);
            Session::set('success', 'Product updated successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }
        
        $this->redirect('/products');
    }

    public function delete(): void
    {
        $this->authorize('manage_products');
        
        $id = (int) ($_POST['id'] ?? 0);
        
        try {
            $this->productService->deleteProduct($id);
            Session::set('success', 'Product deleted successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }
        
        $this->redirect('/products');
    }
}
