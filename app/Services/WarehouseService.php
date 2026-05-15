<?php

class WarehouseService
{
    private WarehouseRepository $warehouseRepo;

    public function __construct(PDO $db)
    {
        require_once __DIR__ . '/../Repositories/WarehouseRepository.php';
        $this->warehouseRepo = new WarehouseRepository($db);
    }

    public function getAllWarehouses(bool $showInactive = false): array
    {
        return $this->warehouseRepo->getAll($showInactive);
    }

    public function getFilteredWarehouses(bool $showInactive, string $search): array
    {
        return $this->warehouseRepo->getFiltered($showInactive, $search);
    }

    public function getWarehouseById(int $id): ?array
    {
        return $this->warehouseRepo->findById($id);
    }

    public function createWarehouse(array $data): int
    {
        $this->validateWarehouseData($data);
        
        return $this->warehouseRepo->create($data);
    }

    public function updateWarehouse(int $id, array $data): void
    {
        $warehouse = $this->warehouseRepo->findById($id);
        
        if (!$warehouse) {
            throw new Exception('Warehouse not found');
        }
        
        $this->validateWarehouseData($data);
        
        $this->warehouseRepo->update($id, $data);
    }

    public function deleteWarehouse(int $id): void
    {
        $warehouse = $this->warehouseRepo->findById($id);
        
        if (!$warehouse) {
            throw new Exception('Warehouse not found');
        }
        
        $this->warehouseRepo->delete($id);
    }

    private function validateWarehouseData(array $data): void
    {
        if (empty($data['name'])) {
            throw new Exception('Warehouse name is required');
        }
        
        if (empty($data['location'])) {
            throw new Exception('Warehouse location is required');
        }
    }
}
