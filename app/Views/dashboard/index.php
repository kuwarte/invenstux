<div class="dashboard-wrapper">

    <div class="dash-header">
        <div class="dash-header-left">
            <div class="dash-greeting">Good day, <?= htmlspecialchars(Session::get('user_name') ?? 'there') ?> 👋</div>
            <h1>Executive Summary</h1>
        </div>
        <div class="dash-header-actions">
            <button class="btn-solid" id="exportPdfBtn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export PDF
            </button>
        </div>
    </div>

    <div class="stats-grid">
        <?php
        $cards = [
            ['Total Inventory',  $stats['total_products'] ?? 0,                             'Total SKUs tracked',       '↑',     false, '<path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>'],
            ['Warehouses',       $stats['total_warehouses'] ?? 0,                           'Active locations',         '↑',       false, '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
            ['Orders Today',     $salesStats['total_sales'] ?? 0,                      'Transactions processed',   'Today',      false, '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>'],
            [
                'Revenue Today',
                '₱' . number_format($salesStats['total_revenue'] ?? 0, 2),
                'Target: ₱20,000.00',
                '↑',
                true,
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                    <circle cx="12" cy="12" r="2.5"></circle>
                    <path d="M6 9h.01M18 9h.01M6 15h.01M18 15h.01"></path>
                </svg>'
            ],
        ];
            foreach ($cards as $c): ?>
        <div class="stat-card">
            <div class="stat-card-row">
                <div class="stat-text">
                    <div class="stat-label"><?= $c[0] ?></div>
                    <div class="stat-value"><?= $c[1] ?></div>
                </div>
                <div class="stat-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $c[5] ?></svg>
                </div>
            </div>
            <div class="stat-footer">
                <span class="stat-pill <?= $c[4] ?>"><?= $c[3] ?></span>
                <?= $c[2] ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="data-grid">

        <div class="content-card">
            <div class="card-head">
                <div class="card-head-left">
                    <h2>Top Revenue Generators</h2>
                </div>
                <a href="/dashboard/top-revenue" class="card-link">
                    View all
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4l4 4-4 4"/></svg>
                </a>
            </div>
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Sold</th>
                        <th class="th-right">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($topProducts ?? []) as $i => $product): ?>
                    <tr>
                        <td>
                            <div class="product-cell">
                                <span class="rank-num rank-<?= $i + 1 ?>"><?= $i + 1 ?></span>
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

        <div class="content-card">
            <div class="card-head">
                <div class="card-head-left">
                    <h2>Inventory Risk Assessment</h2>
                </div>
            </div>

            <?php if (!empty($lowStockItems)): ?>
            <table class="table-custom">
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
                                <div>
                                    <div class="stock-name"><?= htmlspecialchars($item['product_name']) ?></div>
                                    <div class="stock-loc"><?= htmlspecialchars($item['warehouse_name']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="qty-cell"><?= $item['quantity'] ?> units</td>
                        <td class="th-right"><span class="badge-critical">CRITICAL</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <p>All stock levels are healthy — no risks detected.</p>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<div id="pdfExportModal" class="pdf-export-modal">
    <div class="pdf-modal-content">
        <div class="pdf-modal-header">
            <h3>Export Dashboard Report</h3>
            <p>Choose what to include in your PDF</p>
        </div>
        <div class="pdf-options">
            <label class="pdf-option">
                <input type="radio" name="pdfFormat" value="full" checked>
                <div class="pdf-option-label">
                    <span class="pdf-option-title">Full Report</span>
                    <span class="pdf-option-desc">All statistics and tables</span>
                </div>
            </label>
            <label class="pdf-option">
                <input type="radio" name="pdfFormat" value="summary">
                <div class="pdf-option-label">
                    <span class="pdf-option-title">Summary Only</span>
                    <span class="pdf-option-desc">Key metrics and statistics</span>
                </div>
            </label>
            <label class="pdf-option">
                <input type="radio" name="pdfFormat" value="revenue">
                <div class="pdf-option-label">
                    <span class="pdf-option-title">Revenue Report</span>
                    <span class="pdf-option-desc">Top revenue generators only</span>
                </div>
            </label>
        </div>
        <div class="pdf-modal-actions">
            <button class="btn-cancel" onclick="closePdfModal()">Cancel</button>
            <button class="btn-export" onclick="generatePdf()">Generate PDF</button>
        </div>
    </div>
</div>

