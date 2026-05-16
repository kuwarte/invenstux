
<style>
    :root {
        --brand-accent: #10b981;
        --brand-accent-hover: #059669;
        --brand-accent-light: #d1fae5;
        --brand-accent-dark: #065f46;
        --bg-color: #f3f4f6;
        --surface: #ffffff;
        --text-primary: #111827;
        --text-secondary: #6b7280;
        --border-light: #e5e7eb;
        --error-bg: #fef2f2;
        --error-text: #dc2626;
        --radius-md: 10px;
        --radius-lg: 16px;
        
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    .dashboard-wrapper {
        font-family: 'Inter', 'Plus Jakarta Sans', system-ui, sans-serif;
        max-width: 1440px;
        margin: 0 auto;
        color: var(--text-primary);
        animation: dashIn 0.4s ease-out both;
    }

    @keyframes dashIn {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .dash-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 32px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .dash-greeting {
        font-size: 14px;
        color: var(--text-secondary);
        font-weight: 500;
        margin-bottom: 6px;
    }

    .dash-header-left h1 {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.02em;
        line-height: 1;
        margin: 0;
    }

    .dash-header-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .btn-ghost {
        background: var(--surface);
        border: 1px solid var(--border-light);
        padding: 10px 16px;
        border-radius: var(--radius-md);
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
    }

    .btn-ghost:hover {
        background: var(--bg-color);
        border-color: #d1d5db;
    }

    .btn-ghost.active-filter {
        background: var(--brand-accent-light);
        border-color: var(--brand-accent);
        color: var(--brand-accent-dark);
    }

    .btn-solid {
        background: var(--brand-accent);
        color: #ffffff;
        border: none;
        padding: 10px 16px;
        border-radius: var(--radius-md);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background 0.2s ease;
        box-shadow: var(--shadow-sm);
    }

    .btn-solid:hover {
        background: var(--brand-accent-hover);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-light);
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        box-shadow: var(--shadow-sm);
        transition: box-shadow 0.2s ease, transform 0.2s ease;
        cursor: default;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-card-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .stat-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--brand-accent-light);
        color: var(--brand-accent-dark);
        flex-shrink: 0;
    }

    .stat-text {
        flex: 1;
        min-width: 0;
    }

    .stat-label {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 8px;
        line-height: 1;
        white-space: nowrap;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.03em;
        line-height: 1;
    }

    .stat-footer {
        display: flex;
        align-items: center;
        gap: 8px;
        padding-top: 16px;
        border-top: 1px solid var(--border-light);
        font-size: 13px;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .stat-pill {
        display: inline-flex;
        align-items: center;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        background: var(--bg-color);
        color: var(--text-primary);
        white-space: nowrap;
    }

    .stat-pill.positive {
        background: var(--brand-accent-light);
        color: var(--brand-accent-dark);
    }

    .data-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 24px;
    }

    .content-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .card-head {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--surface);
    }

    .card-head-left {
        display: flex; 
        align-items: center; 
        gap: 12px; 
    }

    .card-head h2 {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .card-link {
        font-size: 13px;
        color: var(--brand-accent);
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: color 0.2s ease;
    }

    .card-link:hover { color: var(--brand-accent-hover); }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }

    .table-custom thead tr {
        background: #fafafa;
    }

    .table-custom th {
        padding: 14px 24px;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-secondary);
        text-align: left;
        border-bottom: 1px solid var(--border-light);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .table-custom td {
        padding: 16px 24px;
        border-bottom: 1px solid var(--border-light);
        font-size: 14px;
        color: var(--text-primary);
        vertical-align: middle;
    }

    .table-custom tr:last-child td { border-bottom: none; }

    .rank-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        background: var(--bg-color);
        color: var(--text-secondary);
        margin-right: 12px;
        flex-shrink: 0;
    }

    .rank-1 { background: var(--brand-accent-dark); color: #fff; }
    .rank-2 { background: var(--brand-accent); color: #fff; }
    .rank-3 { background: var(--brand-accent-light); color: var(--brand-accent-dark); }

    .product-cell  { display: flex; align-items: center; }
    .product-info  { min-width: 0; }
    .product-name  { font-weight: 600; color: var(--text-primary); font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .product-sku   { font-size: 12px; color: var(--text-secondary); margin-top: 4px; }

    .td-units   { color: var(--text-secondary); font-weight: 500; }
    .td-revenue { font-weight: 600; color: var(--text-primary); text-align: right; }
    .th-right   { text-align: right !important; }

    .low-stock {
        background-color: var(--error-bg);
    }
    .low-stock:hover {
        background-color: var(--error-bg);
    }
    .stock-cell { display: flex; align-items: center; gap: 12px;  }

    .stock-name { font-weight: 600; font-size: 14px; color: var(--text-primary); }
    .stock-loc  { font-size: 12px; color: var(--text-secondary); margin-top: 4px; }

    .qty-cell { font-size: 14px; font-weight: 600; color: var(--text-primary); }

    .badge-critical {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--error-bg);
        color: var(--error-text);
        font-size: 12px;
        font-weight: 800;
        border-radius: 20px;
    }

    .empty-state { 
        padding: 64px 24px; 
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex: 1;
    }

    .empty-icon {
        width: 48px; height: 48px;
        background: var(--brand-accent-light);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 16px;
        color: var(--brand-accent-dark);
    }

    .empty-state p { font-size: 14px; color: var(--text-secondary); font-weight: 500; margin: 0; }

    .pdf-export-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .pdf-export-modal.active {
        display: flex;
    }

    .pdf-modal-content {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: 32px;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .pdf-modal-header {
        margin-bottom: 24px;
    }

    .pdf-modal-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 8px 0;
    }

    .pdf-modal-header p {
        font-size: 14px;
        color: var(--text-secondary);
        margin: 0;
    }

    .pdf-options {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 24px;
    }

    .pdf-option {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border: 1.5px solid var(--border-light);
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pdf-option:hover {
        border-color: var(--brand-accent);
        background: var(--brand-accent-light);
    }

    .pdf-option input[type="radio"] {
        cursor: pointer;
        width: 18px;
        height: 18px;
        accent-color: var(--brand-accent);
    }

    .pdf-option-label {
        flex: 1;
        cursor: pointer;
    }

    .pdf-option-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        display: block;
    }

    .pdf-option-desc {
        font-size: 12px;
        color: var(--text-secondary);
        display: block;
        margin-top: 2px;
    }

    .pdf-modal-actions {
        display: flex;
        gap: 12px;
    }

    .pdf-modal-actions button {
        flex: 1;
        padding: 10px 16px;
        border-radius: var(--radius-md);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-cancel {
        background: var(--bg-color);
        color: var(--text-primary);
        border: 1px solid var(--border-light);
    }

    .btn-cancel:hover {
        background: var(--border-light);
    }

    .btn-export {
        background: var(--brand-accent);
        color: #fff;
    }

    .btn-export:hover {
        background: var(--brand-accent-hover);
    }

    .btn-export:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

<div class="dashboard-wrapper">

    <div class="dash-header">
        <div class="dash-header-left">
            <div class="dash-greeting">Good day, <?= htmlspecialchars(Session::get('user_name') ?? 'there') ?> 👋</div>
            <h1>Executive Summary</h1>
        </div>
        <div class="dash-header-actions">
            <button class="btn-solid" id="exportPdfBtn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    function openPdfModal() {
        document.getElementById('pdfExportModal').classList.add('active');
    }

    function closePdfModal() {
        document.getElementById('pdfExportModal').classList.remove('active');
    }

    function generatePdf() {
        const format = document.querySelector('input[name="pdfFormat"]:checked').value;
        const btn = document.querySelector('.btn-export');
        btn.disabled = true;
        btn.textContent = 'Generating...';

        let element;
        let filename = 'Dashboard_Report.pdf';

        if (format === 'summary') {
            element = document.querySelector('.stats-grid');
            filename = 'Dashboard_Summary.pdf';
        } else if (format === 'revenue') {
            const wrapper = document.createElement('div');
            wrapper.innerHTML = `
                <div style="margin-bottom: 20px;">
                    <h2 style="font-size: 20px; font-weight: 700; margin: 0 0 10px 0;">Top Revenue Generators</h2>
                    <p style="color: #6b7280; margin: 0;">Generated on ${new Date().toLocaleDateString()}</p>
                </div>
            `;
            wrapper.appendChild(document.querySelector('.data-grid').children[0].cloneNode(true));
            element = wrapper;
            filename = 'Revenue_Report.pdf';
        } else {
            element = document.querySelector('.dashboard-wrapper');
            filename = 'Executive_Summary_Report.pdf';
        }

        const opt = {
            margin: [15, 15, 15, 15],
            filename: filename,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true, logging: false },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
        };

        html2pdf().set(opt).from(element).save().then(() => {
            closePdfModal();
            btn.disabled = false;
            btn.textContent = 'Generate PDF';
        }).catch(() => {
            btn.disabled = false;
            btn.textContent = 'Generate PDF';
        });
    }

    document.getElementById('exportPdfBtn').addEventListener('click', openPdfModal);

    document.getElementById('pdfExportModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePdfModal();
        }
    });
</script>
