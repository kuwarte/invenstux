<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Services/StockService.php';

class StockController extends Controller
{
    private StockService $stockService;

    public function __construct(PDO $db)
    {
        parent::__construct($db);

        $this->authorize('manage_stock');

        $this->stockService = new StockService($db);
    }

    public function index(): void
    {
        $filters = [
            'warehouse_id' => $_GET['warehouse_id'] ?? null,
            'status' => $_GET['status'] ?? null,
            'search' => $_GET['search'] ?? null
        ];
        
        $data = $this->stockService->getStockDashboardData($filters);

        $this->view('stocks/index', $data);
    }

    public function filter(): void
    {
        header('Content-Type: application/json');
        
        $filters = [
            'warehouse_id' => $_GET['warehouse_id'] ?? null,
            'status' => $_GET['status'] ?? null,
            'search' => $_GET['search'] ?? null
        ];
        
        try {
            $stockItems = $this->stockService->getFilteredStock($filters);
            echo json_encode(['success' => true, 'stockItems' => $stockItems]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function stockIn(): void
    {
        try {
            $this->stockService->stockIn(
                (int) $_POST['product_id'],
                (int) $_POST['warehouse_id'],
                (int) $_POST['quantity'],
                (int) Session::get('user_id')
            );

            Session::set('success', 'Stock added successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }

        $this->redirect('/stocks');
    }

    public function stockOut(): void
    {
        try {
            $this->stockService->stockOut(
                (int) $_POST['product_id'],
                (int) $_POST['warehouse_id'],
                (int) $_POST['quantity'],
                (int) Session::get('user_id')
            );

            Session::set('success', 'Stock removed successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }

        $this->redirect('/stocks');
    }

    public function transfer(): void
    {
        try {
            $quantity = (int) $_POST['quantity'];
            if ($quantity <= 0) {
                throw new Exception('Quantity must be greater than zero');
            }

            $this->stockService->transferStock(
                (int) $_POST['product_id'],
                (int) $_POST['from_warehouse_id'],
                (int) $_POST['to_warehouse_id'],
                $quantity,
                (int) Session::get('user_id'),
                trim($_POST['notes'] ?? '')
            );

            Session::set('success', 'Stock transferred successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }

        $this->redirect('/stocks');
    }

    public function indexThresholds(): void
    {
        $data = $this->stockService->getThresholdData();

        $this->view('stocks/thresholds', $data);
    }

    public function updateThresholds(): void
    {
        $warehouseId = (int)($_POST['warehouse_id'] ?? 0);

        try {
            $this->stockService->updateThresholds($_POST);
            Session::set('success', 'Stock thresholds updated successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }

        $redirect = '/stocks/thresholds';
        if ($warehouseId) {
            $redirect .= '?warehouse_id=' . $warehouseId;
        }
        $this->redirect($redirect);
    }
}
