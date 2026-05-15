<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Services/POSService.php';

class POSController extends Controller
{
    private POSService $posService;

    public function __construct(PDO $db)
    {
        parent::__construct($db);

        $this->posService = new POSService($db);

        $this->authorize('access_pos');
    }

    public function index(): void
    {
        $warehouses = $this->posService->getAllWarehouses();
        $smartWarehouse = !empty($warehouses) ? $warehouses[0] : null;

        $this->view('sales/pos', compact('warehouses', 'smartWarehouse'));
    }

    public function getProductsByWarehouse(): void
    {
        header('Content-Type: application/json');

        $warehouseId = (int) ($_GET['warehouse_id'] ?? 0);

        if (!$warehouseId) {
            echo json_encode(['success' => false, 'message' => 'Warehouse ID is required']);
            exit;
        }

        try {
            $products = $this->posService->getProductsByWarehouse($warehouseId);
            echo json_encode(['success' => true, 'products' => $products]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function checkout(): void
    {
        header('Content-Type: application/json');

        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        $userId = (int) Session::get('user_id');
        $cart = $data['cart'] ?? [];
        $payment = (float) ($data['payment'] ?? 0);

        try {
            $saleId = $this->posService->processCheckout($userId, $cart, $payment);
            echo json_encode(['success' => true, 'sale_id' => $saleId]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function searchProducts(): void
    {
        header('Content-Type: application/json');

        $query = $_GET['q'] ?? '';
        $warehouseId = isset($_GET['warehouse_id']) ? (int) $_GET['warehouse_id'] : null;

        try {
            $products = $this->posService->searchProducts($query, $warehouseId);
            echo json_encode(['success' => true, 'products' => $products]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function checkStock(): void
    {
        header('Content-Type: application/json');

        $productId = (int) ($_GET['product_id'] ?? 0);
        $warehouseId = (int) ($_GET['warehouse_id'] ?? 0);

        if (!$productId || !$warehouseId) {
            echo json_encode(['success' => false, 'message' => 'Product ID and Warehouse ID are required']);
            exit;
        }

        try {
            $stockInfo = $this->posService->checkStock($productId, $warehouseId);
            echo json_encode(['success' => true, 'stock' => $stockInfo]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}
