<?php
$salesStats = $salesStats ?? [];
$recentSales = $recentSales ?? [];
?>

<div class="page-wrapper">

    <div class="page-header">

        <div class="page-header-group">
            <h1 class="page-title">
                Welcome, <?= htmlspecialchars(Session::get('full_name')) ?>
            </h1>

            <p class="text-secondary">
                Cashier Dashboard - Today's Performance
            </p>
        </div>

        <div class="header-actions">

            <a href="/pos" class="btn btn-primary">
                <svg width="18" height="18"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"/>
                    <circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>

                Open POS Terminal
            </a>

            <a href="/sales" class="btn-ghost">
                View Sales History
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
                    Today's Sales
                </p>

                <h2 class="page-title" style="font-size: 2rem;">
                    <?= $salesStats['total_sales'] ?? 0 ?>
                </h2>

            </div>
        </div>

        <div class="card">
            <div style="padding: 1.5rem;">

                <p class="text-secondary" style="margin-bottom: 0.5rem;">
                    Total Revenue
                </p>

                <h2 class="page-title" style="font-size: 2rem;">
                    ₱<?= number_format($salesStats['total_revenue'] ?? 0, 2) ?>
                </h2>

            </div>
        </div>

        <div class="card">
            <div style="padding: 1.5rem;">

                <p class="text-secondary" style="margin-bottom: 0.5rem;">
                    Items Sold
                </p>

                <h2 class="page-title" style="font-size: 2rem;">
                    <?= $salesStats['total_items_sold'] ?? 0 ?>
                </h2>

            </div>
        </div>

    </div>

    <div class="main-card">

        <div class="card-header">

            <h2 class="card-title">
                Recent Transactions
            </h2>

            <span class="badge-count">
                <?= count($recentSales) ?> Transactions
            </span>

        </div>

        <div class="table-container">

            <table class="table">

                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Date & Time</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($recentSales)): ?>

                        <?php foreach ($recentSales as $sale): ?>

                            <tr>

                                <td>
                                    <span class="sku-code">
                                        #<?= htmlspecialchars($sale['sale_id'] ?? '') ?>
                                    </span>
                                </td>

                                <td>
                                    <?= date('M d, Y • h:i A', strtotime($sale['sale_date'] ?? 'now')) ?>
                                </td>

                                <td class="text-right">
                                    <strong>
                                        ₱<?= number_format($sale['total_amount'] ?? 0, 2) ?>
                                    </strong>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="3">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <svg width="28"
                                             height="28"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2"
                                             stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path d="M3 3h18v18H3z"/>
                                            <path d="M9 9h6v6H9z"/>
                                        </svg>
                                    </div>

                                    <h3>No transactions yet today</h3>

                                    <p class="text-secondary">
                                        Sales transactions will appear here once purchases are made.
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