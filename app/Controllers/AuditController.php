<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Services/StockService.php';

class AuditController extends Controller
{
    private StockService $stockService;

    public function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->authorize('view_reports');
        $this->stockService = new StockService($db);
    }

    public function index(): void
    {
        $data = $this->stockService->getAuditData();
        $this->view('stocks/audit', $data);
    }
}
