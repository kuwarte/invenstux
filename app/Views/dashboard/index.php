<?php
$range = $range ?? "";
?>

<main class="dashboard-wrapper">
    <!-- Header Section -->
    <header class="dash-header">
        <div class="dash-header-left">
            <div class="dash-greeting">Good day, <?= htmlspecialchars(Session::get('user_name') ?? 'there') ?> 👋</div>
            <h1>Executive Summary</h1>
        </div>
        <div class="dash-header-actions">
            <select class="filter-dropdown" id="dateFilter" aria-label="Filter by date range">
                <option value="today" <?= ($range === 'today') ? 'selected' : '' ?>>Today</option>
                <option value="7days" <?= ($range === '7days') ? 'selected' : '' ?>>Last 7 Days</option>
                <option value="30days" <?= ($range === '30days') ? 'selected' : '' ?>>Last 30 Days</option>
            </select>
            
            <button class="btn btn-primary" id="exportBtn" aria-label="Export data" onclick="openExportModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>Export</span>
            </button>
        </div>
    </header>

    <section class="stats-grid">
        <?php
        $rangeLabel = 'Today';
        $pillLabel = 'Today';
        $targetAmountText = 'Target: ₱20,000.00';
        $phpTargetVal = 20000.00;

        if ($range === '7days') {
            $rangeLabel = 'Last 7 Days';
            $pillLabel = '7 Days';
            $targetAmountText = 'Target: ₱140,000.00';
            $phpTargetVal = 140000.00;
        } elseif ($range === '30days') {
            $rangeLabel = 'Last 30 Days';
            $pillLabel = '30 Days';
            $targetAmountText = 'Target: ₱600,000.00';
            $phpTargetVal = 600000.00;
        }

        $cards = [
            ['Total Inventory',  $stats['total_products'] ?? 0, 'Total SKUs tracked', '↑', false, '<path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>'],
            ['Warehouses',       $stats['total_warehouses'] ?? 0, 'Active locations', '↑', false, '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
            ["Orders ({$rangeLabel})", $salesStats['total_sales'] ?? 0, 'Transactions processed', $pillLabel, false, '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>'],
            ["Revenue ({$rangeLabel})", '₱' . number_format($salesStats['total_revenue'] ?? 0, 2), $targetAmountText, '↑', true, '<rect x="2" y="5" width="20" height="14" rx="2"></rect><circle cx="12" cy="12" r="2.5"></circle><path d="M6 9h.01M18 9h.01M6 15h.01M18 15h.01"></path>'],
        ];

        foreach ($cards as $index => $c): ?>
        <div class="stat-card" data-card-index="<?= $index ?>">
            <div class="stat-card-row">
                <div class="stat-text">
                    <h3 class="stat-label card-label"><?= $c[0] ?></h3>
                    <div class="stat-value"><?= $c[1] ?></div>
                </div>
                <div class="stat-icon-wrap">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $c[5] ?></svg>
                </div>
            </div>
            <div class="stat-footer">
                <span class="stat-pill footer-pill <?= $c[4] ? 'positive' : '' ?>"><?= $c[3] ?></span>
                <span class="footer-text"><?= $c[2] ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </section>

    <section class="charts-grid">
        <div class="content-card">
            <div class="card-head">
                <h2>Revenue vs Target Overview</h2>
            </div>
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="content-card">
            <div class="card-head">
                <h2>Top Product Distribution</h2>
            </div>
            <div class="chart-container">
                <canvas id="productsPieChart"></canvas>
            </div>
        </div>
    </section>

    <section class="data-grid">
        <div class="content-card">
            <div class="card-head">
                <h2>Top Revenue Generators</h2>
                <a href="/dashboard/top-revenue?range=<?= urlencode($range ?? 'today') ?>" class="card-link">
                    View all
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4l4 4-4 4"/></svg>
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table-custom" id="topRevenueTable" style="display: <?= !empty($topProducts) ? 'table' : 'none' ?>;">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Units Sold</th>
                            <th class="th-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($topProducts) && is_array($topProducts)): ?>
                        <?php foreach ($topProducts as $i => $product): ?>
                        <tr>
                            <td>
                                <div class="product-cell">
                                    <span class="rank-num rank-<?= $i + 1 ?>"><?= $i + 1 ?></span>
                                    <div class="product-info">
                                        <div class="product-name" title="<?= htmlspecialchars($product['name'] ?? '') ?>"><?= htmlspecialchars($product['name'] ?? '') ?></div>
                                        <div class="product-sku"><?= htmlspecialchars($product['sku'] ?? '') ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="td-units"><?= number_format($product['total_sold'] ?? 0) ?></td>
                            <td class="td-revenue">₱<?= number_format($product['total_revenue'] ?? 0, 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="empty-state" id="topRevenueEmpty" style="display: <?= empty($topProducts) ? 'flex' : 'none' ?>;">
                <div class="empty-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                </div>
                <p>No revenue data available for this period.</p>
            </div>
        </div>

        <div class="content-card">
            <div class="card-head">
                <h2>Inventory Risk Assessment</h2>
            </div>

            <?php if (!empty($lowStockItems)): ?>
            <div class="table-responsive">
                <table class="table-custom" id="inventoryRiskTable">
                    <thead>
                        <tr>
                            <th>Stock Item</th>
                            <th>Available</th>
                            <th class="th-right">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lowStockItems as $item): ?>
                        <tr class="low-stock">
                            <td>
                                <div class="stock-cell">
                                    <div class="stock-info">
                                        <div class="stock-name" title="<?= htmlspecialchars($item['product_name'] ?? '') ?>"><?= htmlspecialchars($item['product_name'] ?? '') ?></div>
                                        <div class="stock-loc"><?= htmlspecialchars($item['warehouse_name'] ?? '') ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="qty-cell"><?= number_format($item['quantity'] ?? 0) ?></td>
                            <td class="th-right"><span class="badge-critical">CRITICAL</span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon positive-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <p>All stock levels are healthy — no risks detected.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<div id="exportModal" class="pdf-export-modal" aria-hidden="true" role="dialog">
    <div class="pdf-modal-content">
        <header class="pdf-modal-header">
            <h3>Export Dashboard Data</h3>
            <p>Choose format and what data to export</p>
        </header>
        <div class="pdf-options">
            <label class="pdf-option">
                <input type="radio" name="exportFormat" value="csv" checked>
                <div class="pdf-option-label">
                    <span class="pdf-option-title">CSV</span>
                    <span class="pdf-option-desc">Spreadsheet-compatible, opens in Excel</span>
                </div>
            </label>
            <label class="pdf-option">
                <input type="radio" name="exportFormat" value="json">
                <div class="pdf-option-label">
                    <span class="pdf-option-title">JSON</span>
                    <span class="pdf-option-desc">Structured data for developers or APIs</span>
                </div>
            </label>
        </div>
        <div class="pdf-options" style="margin-top:.75rem; border-top:1px solid var(--border-light); padding-top:.75rem;">
            <label class="pdf-option">
                <input type="radio" name="exportDataset" value="top_products" checked>
                <div class="pdf-option-label">
                    <span class="pdf-option-title">Top Revenue Products</span>
                    <span class="pdf-option-desc">Product name, SKU, units sold, revenue</span>
                </div>
            </label>
            <label class="pdf-option">
                <input type="radio" name="exportDataset" value="low_stock">
                <div class="pdf-option-label">
                    <span class="pdf-option-title">Inventory Risk</span>
                    <span class="pdf-option-desc">Low-stock items with warehouse and quantity</span>
                </div>
            </label>
            <label class="pdf-option">
                <input type="radio" name="exportDataset" value="summary">
                <div class="pdf-option-label">
                    <span class="pdf-option-title">Dashboard Summary</span>
                    <span class="pdf-option-desc">Key KPI metrics for the selected period</span>
                </div>
            </label>
        </div>
        <footer class="pdf-modal-actions">
            <button class="btn-cancel" onclick="closeExportModal()">Cancel</button>
            <button class="btn-export" onclick="runExport()">Download</button>
        </footer>
    </div>
</div>

<script>
    window.dashboardData = {
        totalRevenue: <?= json_encode((float)($salesStats['total_revenue'] ?? 0)) ?>,
        targetRevenue: <?= json_encode((float)$phpTargetVal) ?>,
        topProductsLabels: <?= json_encode(array_column($topProducts ?? [], 'name')) ?>,
        topProductsSold: <?= json_encode(array_map('intval', array_column($topProducts ?? [], 'total_sold'))) ?>,
        topProducts: <?= json_encode($topProducts ?? []) ?>,
        lowStockItems: <?= json_encode($lowStockItems ?? []) ?>,
        totalProducts: <?= json_encode((int)($stats['total_products'] ?? 0)) ?>,
        totalWarehouses: <?= json_encode((int)($stats['total_warehouses'] ?? 0)) ?>,
        totalSales: <?= json_encode((int)($salesStats['total_sales'] ?? 0)) ?>,
        range: <?= json_encode($range ?? 'today') ?>
    };
</script>