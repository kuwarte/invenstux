<style>
    .threshold-cell {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .threshold-input-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .threshold-label {
        font-size: 0.68rem;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-align: center;
    }

    .threshold-input {
        padding: 0.5rem 0.4rem;
        border: 1.5px solid var(--border-light);
        border-radius: var(--radius-sm);
        font-size: 0.85rem;
        text-align: center;
        background: var(--input-bg);
        color: var(--text-primary);
        font-family: monospace;
        font-weight: 600;
        transition: all var(--transition-base);
        width: 100%;
        box-sizing: border-box;
    }

    .threshold-input:focus {
        outline: none;
        border-color: var(--brand-accent);
        background: var(--surface);
        box-shadow: 0 0 0 3px var(--brand-accent-light);
    }

    .threshold-input:hover { border-color: var(--brand-accent); }

    .sku-chip {
        font-family: 'JetBrains Mono', 'SF Mono', Consolas, monospace;
        background: var(--input-bg);
        color: var(--text-secondary);
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.78rem;
        border: 1px solid var(--border-light);
        white-space: nowrap;
    }

    .form-actions {
        display: flex;
        gap: 0.75rem;
        padding: 1.25rem 1.5rem;
        border-top: 1px solid var(--border-light);
        background: var(--input-bg);
    }

    .card-header-row {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .card-header-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .warehouse-selector {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .warehouse-selector label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-secondary);
        white-space: nowrap;
    }

    .empty-row td {
        text-align: center;
        padding: 3rem 1.5rem;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .no-warehouse-selected {
        padding: 4rem 2rem;
        text-align: center;
        color: var(--text-secondary);
    }

    .no-warehouse-selected svg {
        color: var(--brand-accent-light);
        margin-bottom: 1rem;
    }

    .no-warehouse-selected p {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--text-secondary);
        margin: 0;
    }
</style>

<?php
// Pre-index thresholds by product_id for the selected warehouse
$selectedWarehouseId = (int)($_GET['warehouse_id'] ?? 0);
$thresholdMap = [];
foreach ($thresholds ?? [] as $t) {
    if ($t['warehouse_id'] == $selectedWarehouseId) {
        $thresholdMap[$t['product_id']] = $t;
    }
}
$selectedWarehouse = null;
foreach ($warehouses ?? [] as $w) {
    if ($w['id'] == $selectedWarehouseId) {
        $selectedWarehouse = $w;
        break;
    }
}
?>

<div class="page-wrapper">
    <header class="page-header">
        <div class="page-header-group">
            <h1 class="page-title">Stock Thresholds</h1>
            <p class="text-secondary">Set minimum and maximum stock levels per product for a selected warehouse.</p>
        </div>
        <div>
            <a href="/stocks" class="btn-ghost">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Back to Stocks
            </a>
        </div>
    </header>

    <div class="main-card">
        <div class="card-header-row">
            <span class="card-header-title">
                <?= $selectedWarehouse ? htmlspecialchars($selectedWarehouse['name']) . ' — Thresholds' : 'Select a Warehouse' ?>
            </span>
            <div class="warehouse-selector">
                <label for="warehousePicker">Warehouse:</label>
                <select id="warehousePicker" class="filter-dropdown" style="width: 220px;"
                        onchange="window.location.href='/stocks/thresholds?warehouse_id=' + this.value">
                    <option value="">— Choose warehouse —</option>
                    <?php foreach ($warehouses ?? [] as $w): ?>
                        <option value="<?= $w['id'] ?>" <?= $w['id'] == $selectedWarehouseId ? 'selected' : '' ?>>
                            <?= htmlspecialchars($w['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <?php if (!$selectedWarehouseId): ?>
            <div class="no-warehouse-selected">
                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"/>
                </svg>
                <p>Select a warehouse above to configure its stock thresholds.</p>
            </div>
        <?php elseif (empty($products)): ?>
            <div class="no-warehouse-selected">
                <p>No products found. Add products first before configuring thresholds.</p>
            </div>
        <?php else: ?>
            <form method="POST" action="/stocks/thresholds/update">
                <input type="hidden" name="warehouse_id" value="<?= $selectedWarehouseId ?>">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="min-width: 200px;">Product</th>
                                <th style="min-width: 110px;">SKU</th>
                                <th style="text-align: center; min-width: 180px;">Min / Max Thresholds</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                            <?php $t = $thresholdMap[$product['id']] ?? ['min_stock' => 0, 'max_stock' => 0]; ?>
                            <tr>
                                <td style="font-weight: 600; color: var(--text-primary);">
                                    <?= htmlspecialchars($product['name']) ?>
                                </td>
                                <td>
                                    <span class="sku-chip"><?= htmlspecialchars($product['sku']) ?></span>
                                </td>
                                <td style="text-align: center;">
                                    <div class="threshold-cell" style="max-width: 180px; margin: 0 auto;">
                                        <div class="threshold-input-group">
                                            <input
                                                type="number"
                                                name="min_stock[<?= $product['id'] ?>][<?= $selectedWarehouseId ?>]"
                                                class="threshold-input"
                                                value="<?= (int)$t['min_stock'] ?>"
                                                min="0"
                                                title="Minimum stock"
                                            >
                                            <span class="threshold-label">Min</span>
                                        </div>
                                        <div class="threshold-input-group">
                                            <input
                                                type="number"
                                                name="max_stock[<?= $product['id'] ?>][<?= $selectedWarehouseId ?>]"
                                                class="threshold-input"
                                                value="<?= (int)$t['max_stock'] ?>"
                                                min="0"
                                                title="Maximum stock"
                                            >
                                            <span class="threshold-label">Max</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Thresholds
                    </button>
                    <a href="/stocks" class="btn-ghost">Cancel</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
