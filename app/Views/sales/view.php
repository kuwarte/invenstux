<?php
$sale = $sale ?? [];
$items = $sale['items'] ?? [];
?>

<style>
    .view-wrapper {
               max-width: 1440px;
        margin: 0 auto;
        padding: 24px;
        color: var(--text-primary);
        font-family: var(--font-family);
        animation: dashIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    @keyframes dashIn {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.25rem 0;
        letter-spacing: -0.02em;
    }

    .text-muted {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin: 0;
    }

    .btn-group {
        display: flex;
        gap: 10px;
    }

    .btn {
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        gap: 8px;
        padding: 0.625rem 1.25rem; 
        border-radius: var(--radius-md);
        font-size: 0.875rem; 
        font-weight: 600; 
        cursor: pointer;
        transition: all var(--transition-base, 0.2s ease); 
        border: none; 
        font-family: inherit;
        text-decoration: none;
    }

    .btn-white { 
        background: var(--surface); 
        border: 1px solid var(--border-light); 
        color: var(--text-secondary); 
    }
    
    .btn-white:hover { 
        background: var(--bg-main); 
        border-color: var(--text-muted);
        color: var(--text-primary);
    }

    .btn-primary { 
        background: var(--brand-accent); 
        color: white; 
    }
    
    .btn-primary:hover { 
        background: var(--brand-accent-hover); 
    }

    .sale-layout {
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 2rem;
        align-items: start;
    }

    .card {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .card-header {
        padding: 1.25rem;
        border-bottom: 1px solid var(--border-light);
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        background: var(--surface);
    }

    .summary-content {
        padding: 1.25rem;
    }

    .summary-row {
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .summary-row:last-child {
        margin-bottom: 0;
    }

    .summary-label { 
        font-size: 0.875rem; 
        color: var(--text-secondary); 
        font-weight: 500; 
    }

    .summary-value {
        font-size: 0.875rem;
        color: var(--text-primary);
        font-weight: 600;
    }

    .summary-total {
        font-size: 1.5rem; 
        font-weight: 800;
        color: var(--brand-accent); 
        letter-spacing: -0.02em;
    }

    .divider {
        height: 1px;
        background: var(--border-light);
        margin: 1.25rem 0;
    }

    .change-row {
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        padding: 1rem;
        background: var(--brand-accent-light);
        border-radius: var(--radius-md);
        margin-top: 1.25rem;
        border: 1px solid var(--brand-accent-light);
    }

    .change-label { 
        font-size: 0.875rem; 
        font-weight: 700; 
        color: var(--brand-accent-dark, var(--brand-accent)); 
    }

    .change-amount {
        font-size: 1.25rem; 
        font-weight: 800;
        color: var(--brand-accent-dark, var(--brand-accent));
    }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    th, td {
        padding: 1rem 1.25rem;
        text-align: left;
        border-bottom: 1px solid var(--border-light);
    }

    th {
        background: var(--bg-main);
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    tbody tr {
        transition: background var(--transition-base, 0.15s);
    }

    tbody tr:hover {
        background: var(--bg-main);
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    .item-name {
        font-weight: 600;
        color: var(--text-primary);
    }

    .item-sku {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 4px;
        font-family: monospace;
    }

    .item-price {
        font-weight: 700;
        color: var(--text-primary);
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        color: var(--text-secondary);
    }
    
    .empty-state svg { 
        opacity: 0.3; 
        margin-bottom: 1rem; 
    }
    
    .empty-state p { 
        font-size: 1rem; 
        font-weight: 600; 
        color: var(--text-primary); 
        margin: 0 0 6px; 
    }

    @media (max-width: 900px) {
        .sale-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .btn-group {
            width: 100%;
        }
        .btn {
            flex: 1;
        }
    }
</style>

<div class="view-wrapper">
    <header class="page-header">
        <div class="page-header-group">
            <h1 class="page-title">Sale #<?= htmlspecialchars($sale['sale_id'] ?? 'N/A') ?></h1>
            <p class="text-secondary">Transaction details and purchased items</p>
        </div>

        <div class="btn-group">
            <a href="/sales" class="btn btn-white">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Back
            </a>
            <a href="/sales/receipt?id=<?= urlencode($sale['sale_id'] ?? '') ?>" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                View Receipt
            </a>
        </div>
    </header>

    <div class="sale-layout">
        <div class="card">
            <div class="card-header">Transaction Overview</div>

            <div class="summary-content">
                <div class="summary-row">
                    <span class="summary-label">Date</span>
                    <span class="summary-value"><?= htmlspecialchars($sale['created_at'] ?? '-') ?></span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Cashier</span>
                    <span class="summary-value"><?= htmlspecialchars($sale['cashier_name'] ?? 'System') ?></span>
                </div>

                <div class="divider"></div>

                <div class="summary-row">
                    <span class="summary-label">Total Amount</span>
                    <span class="summary-total">₱<?= number_format($sale['total_amount'] ?? 0, 2) ?></span>
                </div>

                <div class="summary-row" style="margin-top: 12px;">
                    <span class="summary-label">Cash Tendered</span>
                    <span class="summary-value">₱<?= number_format($sale['payment_amount'] ?? 0, 2) ?></span>
                </div>

                <div class="change-row">
                    <span class="change-label">Change</span>
                    <span class="change-amount">₱<?= number_format($sale['change_amount'] ?? 0, 2) ?></span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Purchased Items (<?= count($items) ?>)
            </div>

            <?php if (empty($items)): ?>
                <div class="empty-state">
                    <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    <p>No items found</p>
                    <small class="text-muted">This transaction doesn't contain any products.</small>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: right;">Price</th>
                                <th style="text-align: right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="item-name"><?= htmlspecialchars($item['product_name'] ?? '-') ?></div>
                                        <div class="item-sku">SKU: <?= htmlspecialchars($item['sku'] ?? '-') ?></div>
                                    </td>
                                    <td style="text-align: center; font-weight: 600;">
                                        <?= (int)($item['quantity'] ?? 0) ?>
                                    </td>
                                    <td style="text-align: right; color: var(--text-secondary);">
                                        ₱<?= number_format($item['price'] ?? 0, 2) ?>
                                    </td>
                                    <td style="text-align: right;" class="item-price">
                                        ₱<?= number_format($item['subtotal'] ?? 0, 2) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>