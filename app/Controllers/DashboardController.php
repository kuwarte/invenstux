<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/../Services/DashboardService.php';
require_once __DIR__ . '/../Services/SalesService.php';

class DashboardController extends Controller
{
    private DashboardService $dashboardService;
    private SalesService $salesService;

    public function __construct(PDO $db)
    {
        parent::__construct($db);

        $this->authService = new AuthService($db);
        $this->dashboardService = new DashboardService($db);
        $this->salesService = new SalesService($db);
    }

    public function index(): void
    {
        $this->authService->requireAuth();

        $userRole = Session::get('role_name');

        switch ($userRole) {
            case 'cashier':
                $this->cashierDashboard();
                break;
            case 'staff':
                $this->staffDashboard();
                break;
            default:
                $this->adminDashboard();
                break;
        }
    }

    public function filter(): void
    {
        $this->authService->requireAuth();

        header('Content-Type: application/json');

        $range = $_GET['range'] ?? 'today';

        $metrics = $this->dashboardService->getDashboardMetrics($range);

        echo json_encode($metrics);
        exit;
    }

    public function indexTopRevenue(): void
    {
        $this->authService->requireAuth();
        $range = $_GET['range'] ?? '30days';

        $topProducts = $this->dashboardService->getTopProductsByRange($range);

        $this->view('dashboard/top-revenue', compact('topProducts', 'range'));
    }

    public function cashierDashboard(): void
    {
        $salesStats = $this->salesService->getTodayStats();
        $recentSales = $this->salesService->getAllSales(10, 0);

        $this->view(
            'dashboard/cashier',
            compact('salesStats', 'recentSales')
        );
    }

    public function staffDashboard(): void
    {
        $stats = $this->dashboardService->getStats();
        $lowStockItems = $this->dashboardService->getLowStockItems();

        $this->view(
            'dashboard/staff',
            compact('stats', 'lowStockItems')
        );
    }

    private function adminDashboard(): void
    {
        $range = $_GET['range'] ?? 'today';

        $metrics = $this->dashboardService->getDashboardMetrics($range);

        $stats = [
            'total_products'   => $metrics['globalCounters']['total_products'] ?? 0,
            'total_warehouses' => $metrics['globalCounters']['total_warehouses'] ?? 0,
            'total_categories' => $metrics['globalCounters']['total_categories'] ?? 0,
        ];

        $salesStats = $metrics['salesStats'];
        $topProducts = $metrics['topProducts'];

        $lowStockItems = $this->dashboardService->getLowStockItems();

        $this->view(
            'dashboard/index',
            compact(
                'stats',
                'lowStockItems',
                'salesStats',
                'topProducts',
                'range'
            )
        );
    }
}
