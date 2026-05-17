<?php
$sales = $sales ?? [];

$totalRevenue = 0;
$totalTransactions = count($sales);
foreach ($sales as $sale) {
    $totalRevenue += ($sale['total_amount'] ?? 0);
}
$averageTicket = $totalTransactions > 0 ? ($totalRevenue / $totalTransactions) : 0;
?>

<style>
    .sales-wrapper {
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

    .main-card {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03), 0 2px 8px -1px rgba(0, 0, 0, 0.02);
        overflow: hidden;
        transition: var(--transition-base);
    }

    /* Unified Filter and Control Bar */
    .table-control-bar {
        padding: 1.25rem 1.5rem;
        background: rgba(250, 250, 251, 0.4);
        border-bottom: 1px solid var(--border-light);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1.25rem;
    }

    .filters-group {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .filter-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-secondary);
    }

    /* Styled Inputs & Buttons */
    .form-input {
        padding: 0.55rem 0.85rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-light);
        font-family: inherit;
        font-size: 0.85rem;
        color: var(--text-primary);
        background: var(--input-bg);
        transition: var(--transition-base);
        outline: none;
    }

    .form-input:focus {
        border-color: var(--brand-accent);
        background: var(--surface);
        box-shadow: 0 0 0 3px var(--brand-accent-light);
    }


    .btn-white {
        background: var(--surface);
        border: 1px solid var(--border-light);
        color: var(--text-primary);
    }

    .btn-white:hover {
        background: var(--input-bg);
        border-color: var(--text-secondary);
    }


    .btn-action {
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-light);
        background: var(--surface);
        color: var(--text-primary);
        font-weight: 600;
        transition: var(--transition-base);
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }

    .btn-action:hover {
        background: var(--brand-accent);
        color: white;
        border-color: var(--brand-accent);
    }

    /* Table Design */
    .table-container {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .table th {
        padding: 1rem 1.5rem;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-secondary);
        background: rgba(249, 250, 251, 0.6);
        border-bottom: 1px solid var(--border-light);
        white-space: nowrap;
    }

    .table td {
        padding: 1.15rem 1.5rem;
        border-bottom: 1px solid var(--border-light);
        font-size: 0.88rem;
        color: var(--text-primary);
        vertical-align: middle;
        transition: var(--transition-base);
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    .table tr:hover td {
        background: rgba(249, 250, 251, 0.4);
    }

    /* Modern Badge & Micro-elements */
    .cashier-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .avatar-circle {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: var(--brand-accent-light);
        color: var(--brand-accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 700;
        border: 1px solid rgba(16, 185, 129, 0.1);
    }

    .monospace-id {
        font-family: "SF Mono", SFMono-Regular, Consolas, "Liberation Mono", Menlo, monospace;
        font-weight: 600;
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    .amount-primary {
        font-weight: 700;
        color: var(--text-primary);
    }

    .amount-secondary {
        font-weight: 500;
        color: var(--text-secondary);
    }

    .amount-accent {
        font-weight: 600;
        color: var(--brand-accent);
    }

    /* Empty state styling */
    .empty-state-container {
        text-align: center;
        padding: 6rem 2rem;
    }

    .empty-state-icon {
        color: var(--text-secondary);
        margin-bottom: 1.25rem;
        opacity: 0.4;
    }

    .empty-state-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.4rem;
        margin-top: 0;
    }

    .empty-state-desc {
        color: var(--text-secondary);
        font-size: 0.88rem;
        margin-bottom: 1.5rem;
        margin-top: 0;
    }
</style>

<div class="sales-wrapper">
    <!-- Header Block -->
    <header class="page-header">
        <div class="page-header-group">
            <h1 class="page-title">Sales History</h1>
            <p class="text-secondary">View, manage, and audit all past checkout transactions.</p>
        </div>
    
        <div>
            <a href="/pos" class="btn btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                New Sale
            </a>
        </div>
    </header>

    <!-- Unified Master Content Area -->
    <div class="main-card">
        <!-- Controls & Filters Strip -->
        <div class="table-control-bar">
            <div class="filters-group">
                <div class="filter-item">
                    <span class="filter-label">From:</span>
                    <input type="date" id="dateFrom" class="form-input">
                </div>
                <div class="filter-item">
                    <span class="filter-label">To:</span>
                    <input type="date" id="dateTo" class="form-input">
                </div>
            </div>
            <div>
                <button onclick="clearFilters()" class="btn btn-white">
                    Reset Filters
                </button>
            </div>
        </div>

        <!-- Table View -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 140px;">TID</th>
                        <th>Date & Time</th>
                        <th>Cashier</th>
                        <th style="text-align: right;">Total</th>
                        <th style="text-align: right;">Payment</th>
                        <th style="text-align: right;">Change</th>
                        <th style="text-align: center; width: 120px;">Action</th>
                    </tr>
                </thead>
                <tbody id="salesTableBody">
                    <?php if (empty($sales)): ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state-container">
                                    <div class="empty-state-icon">
                                        <svg width="44" height="44" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </div>
                                    <h3 class="empty-state-title">No transactions found</h3>
                                    <p class="empty-state-desc">There are no documented checkout sales matching the active criteria.</p>
                                    <a href="/pos" class="btn btn-white">Launch Terminal</a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                    <?php foreach ($sales as $sale): ?>
                        <tr>
                            <td><span class="monospace-id">#<?= htmlspecialchars($sale['sale_id'] ?? ''); ?></span></td>
                            <td style="font-weight: 500; color: var(--text-secondary);"><?= date('M d, Y • h:i A', strtotime($sale['sale_date'] ?? 'now')); ?></td>
                            <td>
                                <div class="cashier-badge">
                                    <div class="avatar-circle">
                                        <?= strtoupper(substr($sale['cashier_name'] ?? 'S', 0, 1)); ?>
                                    </div>
                                    <span style="font-weight: 600; color: var(--text-primary);"><?= htmlspecialchars($sale['cashier_name'] ?? 'System'); ?></span>
                                </div>
                            </td>
                            <td class="amount-primary" style="text-align: right;">₱<?= number_format($sale['total_amount'] ?? 0, 2); ?></td>
                            <td class="amount-secondary" style="text-align: right;">₱<?= number_format($sale['payment_amount'] ?? 0, 2); ?></td>
                            <td class="amount-accent" style="text-align: right;">₱<?= number_format($sale['change_amount'] ?? 0, 2); ?></td>
                            <td style="text-align: center;">
                                <a href="/sales/view?id=<?= htmlspecialchars($sale['sale_id'] ?? ''); ?>" class="btn-action">
                                    View
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>