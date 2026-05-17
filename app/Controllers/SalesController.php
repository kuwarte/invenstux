<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Services/SalesService.php';

class SalesController extends Controller
{
    private SalesService $salesService;

    public function __construct(PDO $db)
    {
        parent::__construct($db);

        $this->salesService = new SalesService($db);

        $this->authorize('view_reports');
    }

    public function index(): void
    {
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        
        $sales = $this->salesService->getFilteredSales($dateFrom, $dateTo);

        $this->view('sales/index', compact('sales', 'dateFrom', 'dateTo'));
    }

    public function filter(): void
    {
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        
        $sales = $this->salesService->getFilteredSales($dateFrom, $dateTo);
        
        header('Content-Type: application/json');
        echo json_encode($sales);
    }

    public function indexView(): void
    {
        $saleId = (int) ($_GET['id'] ?? 0);

        $sale = $this->salesService->getSaleDetails($saleId);

        if (!$sale) {
            Session::set('error', 'Sale not found');
            $this->redirect('/sales');
            return;
        }

        $this->view('sales/view', compact('sale'));
    }

    public function indexSalesReceipt(): void
    {
        $saleId = (int) ($_GET['id'] ?? 0);

        $sale = $this->salesService->getSaleDetails($saleId);

        if (!$sale) {
            Session::set('error', 'Sale not found');
            $this->redirect('/sales');
            return;
        }

        $this->view('sales/receipt', compact('sale'));
    }

    public function reports(): void
    {
        $stats = $this->salesService->getTodayStats();

        $dailySales = $this->salesService->getDailySalesSummary();

        $topProducts = $this->salesService->getTopSellingProducts();

        $this->view('sales/reports', compact(
            'stats',
            'dailySales',
            'topProducts'
        ));
    }
}
