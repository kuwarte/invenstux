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

    public function indexTopRevenue(): void
    {
        $this->authService->requireAuth();

        $topProducts = $this->salesService->getTopSellingProducts(100);

        $this->view('dashboard/top-revenue', compact('topProducts'));
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
        $stats = $this->dashboardService->getStats();
        $lowStockItems = $this->dashboardService->getLowStockItems();
        $salesStats = $this->salesService->getTodayStats();
        $topProducts = $this->salesService->getTopSellingProducts(5);

        $this->view(
            'dashboard/index',
            compact(
                'stats',
                'lowStockItems',
                'salesStats',
                'topProducts'
            )
        );
    }
}
