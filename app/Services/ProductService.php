<?php

class ProductService
{
    private ProductRepository $productRepo;
    private PDO $db;

    public function __construct(PDO $db)
    {
        require_once __DIR__ . '/../Repositories/ProductRepository.php';
        $this->productRepo = new ProductRepository($db);
        $this->db = $db;
    }

    public function getAllProducts(bool $showInactive = false): array
    {
        return $showInactive ? $this->productRepo->getAll() : $this->productRepo->getActive();
    }

    public function getFilteredProducts(bool $showInactive, string $search, string $categoryId): array
    {
        return $this->productRepo->getFiltered($showInactive, $search, $categoryId);
    }

    public function getProductById(int $id): ?array
    {
        return $this->productRepo->findById($id);
    }

    public function getProductsWithWarehouseStock(?int $warehouseId = null): array
    {
        return $this->productRepo->getProductsWithWarehouseStock($warehouseId);
    }

    public function createProduct(array $data): int
    {
        $this->validateProductData($data);
        
        if ($this->productRepo->skuExists($data['sku'])) {
            throw new Exception('SKU already exists');
        }

        return $this->productRepo->create($data);
    }

    public function updateProduct(int $id, array $data): void
    {
        $product = $this->productRepo->findById($id);
        
        if (!$product) {
            throw new Exception('Product not found');
        }
        
        $this->validateProductData($data);
        
        if ($data['sku'] !== $product['sku'] && $this->productRepo->skuExists($data['sku'])) {
            throw new Exception('SKU already exists');
        }

        $this->productRepo->update($id, $data);
    }

    public function deleteProduct(int $id): void
    {
        $product = $this->productRepo->findById($id);
        
        if (!$product) {
            throw new Exception('Product not found');
        }
        
        $this->productRepo->deactivate($id);
    }

    private function validateProductData(array $data): void
    {
        if (empty($data['sku']) || empty($data['name'])) {
            throw new Exception('SKU and name are required');
        }

        if (isset($data['unit_cost']) && $data['unit_cost'] < 0) {
            throw new Exception('Unit cost cannot be negative');
        }
    }
}
