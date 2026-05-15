<?php

require_once __DIR__ . '/../Repositories/SalesRepository.php';

class SalesService
{
    private SalesRepository $salesRepo;

    public function __construct(PDO $db)
    {
        $this->salesRepo = new SalesRepository($db);
    }

    public function getSaleDetails(int $saleId): ?array
    {
        return $this->salesRepo->getSaleById($saleId);
    }

    public function getAllSales(int $limit = 50, int $offset = 0): array
    {
        return $this->salesRepo->getAllSales($limit, $offset);
    }

    public function getFilteredSales(string $dateFrom, string $dateTo): array
    {
        return $this->salesRepo->getFiltered($dateFrom, $dateTo);
    }

    public function getTodayStats(): array
    {
        return $this->salesRepo->getTodayStats();
    }

    public function getTopSellingProducts(int $limit = 10, int $days = 30): array
    {
        return $this->salesRepo->getTopSellingProducts($limit, $days);
    }

    public function getDailySalesSummary(int $days = 30): array
    {
        return $this->salesRepo->getDailySalesSummary($days);
    }

    public function getSalesByDateRange(string $startDate, string $endDate): array
    {
        return $this->salesRepo->getSalesByDateRange($startDate, $endDate);
    }
}
