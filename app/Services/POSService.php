<?php

require_once __DIR__ . '/../Repositories/ProductRepository.php';
require_once __DIR__ . '/../Repositories/WarehouseRepository.php';
require_once __DIR__ . '/../Repositories/SalesRepository.php';

class POSService
{
    private ProductRepository $productRepo;
    private WarehouseRepository $warehouseRepo;
    private SalesRepository $salesRepo;

    public function __construct(PDO $db)
    {
        $this->productRepo = new ProductRepository($db);
        $this->warehouseRepo = new WarehouseRepository($db);
        $this->salesRepo = new SalesRepository($db);
    }

    public function getAllWarehouses(): array
    {
        return $this->warehouseRepo->getAll(false);
    }

    public function getProductsByWarehouse(int $warehouseId): array
    {
        return $this->productRepo->getProductsWithWarehouseStock($warehouseId);
    }

    public function processCheckout(int $userId, array $cartItems, float $paymentAmount): int
    {
        if (empty($cartItems)) {
            throw new Exception('Cart is empty');
        }

        if ($paymentAmount <= 0) {
            throw new Exception('Invalid payment amount');
        }

        $total = 0;
        foreach ($cartItems as $item) {
            if (!isset($item['product_id'], $item['quantity'], $item['price'])) {
                throw new Exception('Invalid cart item data');
            }
            $total += $item['price'] * $item['quantity'];
        }

        if ($paymentAmount < $total) {
            throw new Exception('Insufficient payment amount');
        }

        $cartJson = json_encode($cartItems);
        error_log('Cart JSON: ' . $cartJson);
        error_log('User ID: ' . $userId . ', Payment: ' . $paymentAmount);

        try {
            $result = $this->salesRepo->processSale(
                $userId,
                $cartJson,
                $paymentAmount
            );
            error_log('Result: ' . json_encode($result));
        } catch (Exception $e) {
            error_log('POS Error: ' . $e->getMessage());
            throw new Exception('Transaction failed: ' . $e->getMessage());
        }

        if ($result['status'] !== 'SUCCESS') {
            error_log('POS Failed: ' . $result['message']);
            throw new Exception($result['message']);
        }

        return (int) $result['sale_id'];
    }

    public function searchProducts(string $query, ?int $warehouseId = null): array
    {
        if (empty($query)) {
            return [];
        }

        if ($warehouseId) {
            // For warehouse-specific search, get products with stock info
            return $this->productRepo->searchWithWarehouseStock($query, $warehouseId);
        }

        return $this->productRepo->search($query);
    }

    public function checkStock(int $productId, int $warehouseId): array
    {
        $product = $this->productRepo->findById($productId);
        
        if (!$product) {
            throw new Exception('Product not found');
        }

        $warehouse = $this->warehouseRepo->findById($warehouseId);
        
        if (!$warehouse) {
            throw new Exception('Warehouse not found');
        }

        $stockInfo = $this->productRepo->getProductsWithWarehouseStock($warehouseId);
        
        $productStock = array_filter($stockInfo, function($item) use ($productId) {
            return $item['id'] === $productId;
        });

        return [
            'product_id' => $productId,
            'warehouse_id' => $warehouseId,
            'stock' => !empty($productStock) ? reset($productStock)['stock'] : 0,
            'available' => !empty($productStock) && reset($productStock)['stock'] > 0
        ];
    }
}
