<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/../Services/WarehouseService.php';
require_once __DIR__ . '/../Services/UserService.php';

class WarehouseController extends Controller
{
    private WarehouseService $warehouseService;
    private UserService $userService;

    public function __construct(PDO $db)
    {
        parent::__construct($db);
        
        $this->warehouseService = new WarehouseService($db);
        $this->userService = new UserService($db);
    }

    public function index(): void
    {
        $this->authorize('manage_warehouses');
        
        $showInactive = isset($_GET['show_inactive']) && $_GET['show_inactive'] == '1';
        $search = $_GET['search'] ?? '';
        
        $warehouses = $this->warehouseService->getFilteredWarehouses($showInactive, $search);
        
        $this->view('warehouses/index', compact('warehouses', 'showInactive', 'search'));
    }

    public function filter(): void
    {
        $this->authorize('manage_warehouses');
        
        $showInactive = isset($_GET['show_inactive']) && $_GET['show_inactive'] == '1';
        $search = $_GET['search'] ?? '';
        
        $warehouses = $this->warehouseService->getFilteredWarehouses($showInactive, $search);
        
        header('Content-Type: application/json');
        echo json_encode($warehouses);
    }

    public function indexCreate(): void
    {
        $this->authorize('manage_warehouses');
        
        $managers = $this->userService->getManagers();
        
        $this->view('warehouses/create', compact('managers'));
    }

    public function create(): void
    {
        $this->authorize('manage_warehouses');
        
        $data = [
            'manager_id' => $_POST['manager_id'] ?: null,
            'name' => $_POST['name'] ?? '',
            'location' => $_POST['location'] ?? '',
            'is_active' => $_POST['is_active'] ?? 1
        ];
        
        try {
            $this->warehouseService->createWarehouse($data);
            Session::set('success', 'Warehouse created successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }
        
        $this->redirect('/warehouses');
    }

    public function indexUpdate(): void
    {
        $this->authorize('manage_warehouses');
        
        $id = (int) ($_GET['id'] ?? 0);
        $warehouse = $this->warehouseService->getWarehouseById($id);
        
        if (!$warehouse) {
            Session::set('error', 'Warehouse not found');
            $this->redirect('/warehouses');
            return;
        }
        
        $managers = $this->userService->getManagers();
        
        $this->view('warehouses/edit', compact('warehouse', 'managers'));
    }

    public function update(): void
    {
        $this->authorize('manage_warehouses');
        
        $id = (int) ($_POST['id'] ?? 0);
        $data = [
            'manager_id' => $_POST['manager_id'] ?: null,
            'name' => $_POST['name'] ?? '',
            'location' => $_POST['location'] ?? '',
            'is_active' => $_POST['is_active'] ?? 1
        ];
        
        try {
            $this->warehouseService->updateWarehouse($id, $data);
            Session::set('success', 'Warehouse updated successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }
        
        $this->redirect('/warehouses');
    }

    public function delete(): void
    {
        $this->authorize('manage_warehouses');
        
        $id = (int) ($_POST['id'] ?? 0);
        
        try {
            $this->warehouseService->deleteWarehouse($id);
            Session::set('success', 'Warehouse deleted successfully');
        } catch (Exception $e) {
            Session::set('error', $e->getMessage());
        }
        
        $this->redirect('/warehouses');
    }
}
