<?php

require_once __DIR__ . '/../Repositories/ProductRepository.php';
require_once __DIR__ . '/../Repositories/WarehouseRepository.php';
require_once __DIR__ . '/../Repositories/ProductWarehouseRepository.php';
require_once __DIR__ . '/../Repositories/StockMovementRepository.php';

class StockService
{
    private ProductRepository $productRepo;
    private WarehouseRepository $warehouseRepo;
    private ProductWarehouseRepository $stockRepo;
    private StockMovementRepository $movementRepo;

    public function __construct(PDO $db)
    {
        $this->productRepo = new ProductRepository($db);
        $this->warehouseRepo = new WarehouseRepository($db);
        $this->stockRepo = new ProductWarehouseRepository($db);
        $this->movementRepo = new StockMovementRepository($db);
    }

    public function getStockDashboardData(array $filters = []): array
    {
        return [
            'stockItems' => $this->stockRepo->getAllStock($filters),
            'products' => $this->productRepo->getAll(),
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
            'products' => $this->productRepo->getAll(),
            'warehouses' => $this->warehouseRepo->getAll(),
            'thresholds' => $this->stockRepo->getAll()
        ];
    }

    public function stockIn(
        int $productId,
        int $warehouseId,
        int $quantity,
        int $userId,
        ?string $notes = null
    ): void {
        $this->stockRepo->updateQuantity(
            $productId,
            $warehouseId,
            $quantity
        );

        $this->movementRepo->logMovement(
            $productId,
            $warehouseId,
            'IN',
            $quantity,
            $userId,
            null,
            $notes ?? 'Stock in operation'
        );
    }

    public function stockOut(
        int $productId,
        int $warehouseId,
        int $quantity,
        int $userId,
        ?string $notes = null
    ): void {
        $this->stockRepo->updateQuantity(
            $productId,
            $warehouseId,
            -$quantity
        );

        $this->movementRepo->logMovement(
            $productId,
            $warehouseId,
            'OUT',
            -$quantity,
            $userId,
            null,
            $notes ?? 'Stock out operation'
        );
    }

    public function updateThresholds(array $data): void
    {
        $minStocks = $data['min_stock'] ?? [];
        $maxStocks = $data['max_stock'] ?? [];

        foreach ($minStocks as $productId => $warehouses) {
            foreach ($warehouses as $warehouseId => $minStock) {

                $maxStock = $maxStocks[$productId][$warehouseId] ?? 100;

                $this->stockRepo->updateThresholds(
                    $productId,
                    $warehouseId,
                    $minStock,
                    $maxStock
                );
            }
        }
    }

    public function getLowStockAlerts(): array
    {
        return $this->stockRepo->getLowStock();
    }

    public function getProductMovements(
        int $productId,
        int $limit = 100
    ): array {
        return $this->movementRepo->getMovementsByProduct(
            $productId,
            $limit
        );
    }

    public function getWarehouseMovements(
        int $warehouseId,
        int $limit = 100
    ): array {
        return $this->movementRepo->getMovementsByWarehouse(
            $warehouseId,
            $limit
        );
    }
}
