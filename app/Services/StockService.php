<?php

require_once __DIR__ . '/../Repositories/ProductRepository.php';
require_once __DIR__ . '/../Repositories/WarehouseRepository.php';
require_once __DIR__ . '/../Repositories/ProductWarehouseRepository.php';
require_once __DIR__ . '/../Repositories/StockRepository.php';

class StockService
{
    private ProductRepository $productRepo;
    private WarehouseRepository $warehouseRepo;
    private ProductWarehouseRepository $stockRepo;
    private StockRepository $stockProcRepo;

    public function __construct(PDO $db)
    {
        $this->productRepo   = new ProductRepository($db);
        $this->warehouseRepo = new WarehouseRepository($db);
        $this->stockRepo     = new ProductWarehouseRepository($db);
        $this->stockProcRepo = new StockRepository($db);
    }

    public function getStockDashboardData(array $filters = []): array
    {
        return [
            'stockItems' => $this->stockRepo->getAllStock($filters),
            'products'   => $this->productRepo->getAll(),
            'warehouses' => $this->warehouseRepo->getAll()
        ];
    }

    public function getFilteredStock(array $filters = []): array
    {
        return $this->stockRepo->getAllStock($filters);
    }

    public function getThresholdData(): array
    {
        return [
            'products'   => $this->productRepo->getAll(),
            'warehouses' => $this->warehouseRepo->getAll(),
            'thresholds' => $this->stockRepo->getAll()
        ];
    }

    /**
     * Add stock to a warehouse via sp_adjust_stock (IN).
     * SQL is the brain — the procedure validates, updates, and logs atomically.
     */
    public function stockIn(
        int $productId,
        int $warehouseId,
        int $quantity,
        int $userId,
        ?string $notes = null
    ): void {
        $result = $this->stockProcRepo->adjustStock(
            $productId,
            $warehouseId,
            'IN',
            $quantity,
            $userId,
            $notes ?? 'Stock in operation'
        );

        if ($result['status'] !== 'SUCCESS') {
            throw new Exception($result['message']);
        }
    }

    /**
     * Remove stock from a warehouse via sp_adjust_stock (OUT).
     * SQL is the brain — the procedure validates, updates, and logs atomically.
     */
    public function stockOut(
        int $productId,
        int $warehouseId,
        int $quantity,
        int $userId,
        ?string $notes = null
    ): void {
        $result = $this->stockProcRepo->adjustStock(
            $productId,
            $warehouseId,
            'OUT',
            $quantity,
            $userId,
            $notes ?? 'Stock out operation'
        );

        if ($result['status'] !== 'SUCCESS') {
            throw new Exception($result['message']);
        }
    }

    /**
     * Transfer stock between two warehouses via sp_transfer_stock.
     * SQL is the brain — the procedure validates both sides and logs TRANSFER_OUT/IN atomically.
     */
    public function transferStock(
        int $productId,
        int $fromWarehouseId,
        int $toWarehouseId,
        int $quantity,
        int $userId,
        ?string $notes = null
    ): void {
        $result = $this->stockProcRepo->transferStock(
            $productId,
            $fromWarehouseId,
            $toWarehouseId,
            $quantity,
            $userId,
            $notes ?? ''
        );

        if ($result['status'] !== 'SUCCESS') {
            throw new Exception($result['message']);
        }
    }

    public function updateThresholds(array $data): void
    {
        $minStocks = $data['min_stock'] ?? [];
        $maxStocks = $data['max_stock'] ?? [];

        foreach ($minStocks as $productId => $warehouses) {
            foreach ($warehouses as $warehouseId => $minStock) {
                $maxStock = $maxStocks[$productId][$warehouseId] ?? 100;
                $this->stockRepo->updateThresholds($productId, $warehouseId, $minStock, $maxStock);
            }
        }
    }

    public function getLowStockAlerts(): array
    {
        return $this->stockProcRepo->getLowStock();
    }

    public function getProductMovements(int $productId, int $limit = 100): array
    {
        return $this->stockProcRepo->getMovementsByProduct($productId, $limit);
    }

    public function getWarehouseMovements(int $warehouseId, int $limit = 100): array
    {
        return $this->stockProcRepo->getMovementsByWarehouse($warehouseId, $limit);
    }
}
