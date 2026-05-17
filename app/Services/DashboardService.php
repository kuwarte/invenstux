<?php

class DashboardService
{
    private DashboardRepository $dashboardRepo;
    private ProductWarehouseRepository $stockRepo;

    public function __construct(PDO $db)
    {
        require_once __DIR__ . '/../Repositories/DashboardRepository.php';
        require_once __DIR__ . '/../Repositories/ProductWarehouseRepository.php';

        $this->dashboardRepo = new DashboardRepository($db);
        $this->stockRepo = new ProductWarehouseRepository($db);
    }

    public function getDashboardMetrics(string $range): array
    {
        return $this->dashboardRepo->getDashboardData($range);
    }

    public function getStats(): array
    {
        return $this->dashboardRepo->getStats();
    }

    public function getLowStockItems(): array
    {
        return $this->stockRepo->getLowStock();
    }
}
