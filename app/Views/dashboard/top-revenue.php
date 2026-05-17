<?php
$range = $range ?? '30days'; 

$rangeLabel = 'Last 30 Days';
if ($range === 'today') {
    $rangeLabel = 'Today';
} elseif ($range === '7days') {
    $rangeLabel = 'Last 7 Days';
}
?>

<div class="revenue-wrapper">
    <div class="page-header">
        <div>
            <h1>Top Revenue Generators</h1>
            <div style="font-size: 14px; color: var(--text-secondary); margin-top: 6px; font-weight: 500;">
                Filtering by: <strong><?= htmlspecialchars($rangeLabel) ?></strong>
            </div>
        </div>
        <div class="page-header-actions">
            <a href="/dashboard?range=<?= urlencode($range) ?>" class="btn-ghost">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Back to Dashboard
            </a>
        </div>
    </div>

    <?php if (!empty($topProducts)): ?>
        <div class="stats-summary">
            <div class="summary-card">
                <div class="summary-label">Top Products</div>
                <div class="summary-value"><?= count($topProducts) ?></div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Total Units Sold</div>
                <div class="summary-value"><?= number_format(array_sum(array_column($topProducts, 'total_sold'))) ?></div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Total Revenue</div>
                <div class="summary-value">₱<?= number_format(array_sum(array_column($topProducts, 'total_revenue')), 2) ?></div>
            </div>
            <div class="summary-card">
                <div class="summary-label">Average Revenue</div>
                <div class="summary-value">₱<?= number_format(array_sum(array_column($topProducts, 'total_revenue')) / count($topProducts), 2) ?></div>
            </div>
        </div>

        <div class="content-card">
            <div class="card-head">
                <h2>All Revenue Generators (<?= htmlspecialchars($rangeLabel) ?>)</h2>
            </div>
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Product</th>
                        <th>Units Sold</th>
                        <th class="th-right">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topProducts as $i => $product): ?>
                    <tr>
                        <td>
                            <span class="rank-num rank-<?= $i < 3 ? ($i + 1) : 'n' ?>">
                                #<?= $i + 1 ?>
                            </span>
                        </td>
                        <td>
                            <div class="product-cell">
                                <div class="product-info">
                                    <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
                                    <div class="product-sku"><?= htmlspecialchars($product['sku']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="td-units"><?= number_format($product['total_sold']) ?> units</td>
                        <td class="td-revenue">₱<?= number_format($product['total_revenue'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="content-card">
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M2 12h20"/></svg>
                </div>
                <p>No sales data available for this period.</p>
            </div>
        </div>
    <?php endif; ?>
</div>