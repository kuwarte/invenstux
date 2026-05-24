<?php
$stats = $stats ?? [];
$lowStockItems = $lowStockItems ?? [];
?>

<div class="page-wrapper">

    <div class="page-header">
        <div class="page-header-group">
            <h1 class="page-title">
                Welcome, <?= htmlspecialchars(Session::get('full_name')) ?>
            </h1>
            <p class="text-secondary">
                Staff Dashboard - Inventory Overview
            </p>
        </div>

        <div class="header-actions">
            <a href="/products" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="m7.5 4.27 9 5.15"/>
                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                    <path d="m3.3 7 8.7 5 8.7-5"/>
                    <path d="M12 22V12"/>
                </svg>
                Manage Products
            </a>

            <a href="/stocks" class="btn-ghost">
                Stock Management
            </a>
        </div>
    </div>

    <div style="
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    ">
        <div class="card">
            <div style="padding: 1.5rem;">
                <p class="text-secondary" style="margin-bottom: 0.5rem;">
                    Total Products
                </p>

                <h2 class="page-title" style="font-size: 2rem;">
                    <?= $stats['total_products'] ?? 0 ?>
                </h2>
            </div>
        </div>

        <div class="card">
            <div style="padding: 1.5rem;">
                <p class="text-secondary" style="margin-bottom: 0.5rem;">
                    Low Stock Alerts
                </p>

                <h2 class="page-title"
                    style="font-size: 2rem; color: var(--error-text);">
                    <?= count($lowStockItems) ?>
                </h2>
            </div>
        </div>
    </div>

    <div class="main-card">

        <div class="card-header">
            <h2 class="card-title">Low Stock Alerts</h2>

            <span class="badge-count">
                <?= count($lowStockItems) ?> Items
            </span>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Warehouse</th>
                        <th>Current Stock</th>
                        <th>Min Stock</th>
                        <th class="text-right">Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($lowStockItems)): ?>

                        <?php foreach ($lowStockItems as $item): ?>

                            <tr>
                                <td>
                                    <strong>
                                        <?= htmlspecialchars($item['product_name']) ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['warehouse_name']) ?>
                                </td>

                                <td>
                                    <strong style="color: var(--error-text);">
                                        <?= $item['quantity'] ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= $item['min_stock'] ?>
                                </td>

                                <td class="text-right">

                                    <?php if ($item['quantity'] == 0): ?>

                                        <span class="status-badge status-inactive">
                                            Out of Stock
                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge status-active">
                                            Low Stock
                                        </span>

                                    <?php endif; ?>

                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="5">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <svg width="28" height="28"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2"
                                             stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path d="M20 6 9 17l-5-5"/>
                                        </svg>
                                    </div>

                                    <h3>All stock levels are healthy</h3>

                                    <p class="text-secondary">
                                        No products are currently below the minimum stock threshold.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>

</div>