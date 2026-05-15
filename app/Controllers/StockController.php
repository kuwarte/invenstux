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

        $this->view('stock/index', $data);
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

    public function indexThresholds(): void
    {
        $data = $this->stockService->getThresholdData();

        $this->view('stock/thresholds', $data);
    }

    public function updateThresholds(): void
    {
        try {
            $this->stockService->updateThresholds($_POST);

            Session::set('success', 'Stock thresholds updated successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }

        $this->redirect('/stocks/thresholds');
    }
}
