<?php
$sales = $sales ?? [];
$totalRevenue = 0;
foreach ($sales as $s) $totalRevenue += ($s['total_amount'] ?? 0);
$totalTransactions = count($sales);
$avgTicket = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
?>

<style>
/* ── Card ───────────────────────────────────────── */
.sales-card {
    background: var(--surface);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-lg);
    box-shadow: 0 4px 20px -2px rgba(0,0,0,.03);
    overflow: visible;
}

/* ── Toolbar ────────────────────────────────────── */
.sales-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .75rem 1.25rem;
    border-bottom: 1px solid var(--border-light);
    background: rgba(250,250,251,.5);
    gap: .75rem;
    flex-wrap: wrap;
}
.toolbar-left  { display:flex; align-items:center; gap:.6rem; flex-wrap:wrap; }
.toolbar-right { display:flex; align-items:center; gap:.5rem; }

.record-pill {
    font-size: .75rem;
    font-weight: 700;
    color: var(--text-secondary);
    background: var(--input-bg);
    border: 1px solid var(--border-light);
    border-radius: 20px;
    padding: .2rem .7rem;
}
.record-pill span { color: var(--text-primary); }

/* ── Filter button + dropdown ───────────────────── */
.filter-btn-wrap { position: relative; }

.filter-btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .38rem .8rem;
    font-size: .8rem;
    font-weight: 600;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-light);
    background: var(--surface);
    color: var(--text-primary);
    cursor: pointer;
    transition: var(--transition-base);
    white-space: nowrap;
}
.filter-btn:hover { border-color: var(--brand-accent); color: var(--brand-accent); }
.filter-btn.has-active { border-color: var(--brand-accent); color: var(--brand-accent); background: var(--brand-accent-light); }

.filter-badge-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--brand-accent);
    display: none;
}
.filter-btn.has-active .filter-badge-dot { display: block; }

.filter-dropdown-panel {
    display: none;
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    z-index: 200;
    background: var(--surface);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-lg);
    box-shadow: 0 12px 32px -4px rgba(0,0,0,.12), 0 4px 12px -2px rgba(0,0,0,.06);
    padding: 1rem;
    width: 320px;
    box-sizing: border-box;
    animation: panelIn .18s ease both;
}
@keyframes panelIn {
    from { opacity:0; transform:translateY(-6px); }
    to   { opacity:1; transform:translateY(0); }
}
.filter-dropdown-panel.open { display: block; }

.fp-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .6rem;
}
.fp-label {
    display: block;
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: var(--text-secondary);
    margin-bottom: .28rem;
}
.fp-input {
    width: 100%;
    box-sizing: border-box;
    padding: .4rem .6rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-light);
    font-family: inherit;
    font-size: .78rem;
    color: var(--text-primary);
    background: var(--input-bg);
    outline: none;
    transition: var(--transition-base);
    min-width: 0; /* prevent grid blowout */
}
.fp-input:focus {
    border-color: var(--brand-accent);
    box-shadow: 0 0 0 3px var(--brand-accent-light);
    background: var(--surface);
}
.fp-actions {
    display: flex;
    justify-content: flex-end;
    gap: .5rem;
    margin-top: .8rem;
    padding-top: .7rem;
    border-top: 1px solid var(--border-light);
}

/* ── Chips ──────────────────────────────────────── */
.chip {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .22rem .6rem;
    border-radius: 20px;
    font-size: .72rem;
    font-weight: 600;
    background: var(--brand-accent-light);
    color: var(--brand-accent-dark, var(--brand-accent));
    border: 1px solid rgba(16,185,129,.2);
}
.chip-remove { cursor:pointer; opacity:.6; font-size:.85rem; line-height:1; }
.chip-remove:hover { opacity:1; }

/* ── Dense table ────────────────────────────────── */
.sales-table-wrap { overflow-x: auto; }
.sales-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .78rem;
}
.sales-table th {
    padding: .55rem 1rem;
    font-size: .67rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--text-secondary);
    background: rgba(249,250,251,.7);
    border-bottom: 1px solid var(--border-light);
    white-space: nowrap;
}
.sales-table td {
    padding: .55rem 1rem;
    border-bottom: 1px solid var(--border-light);
    color: var(--text-primary);
    vertical-align: middle;
}
.sales-table tr:last-child td { border-bottom: none; }
.sales-table tr:hover td { background: rgba(249,250,251,.5); }

.mono-id { font-family:"SF Mono",Consolas,monospace; font-weight:600; color:var(--text-secondary); font-size:.75rem; }

.uavatar {
    width:22px; height:22px; border-radius:5px;
    background:var(--brand-accent-light); color:var(--brand-accent);
    display:inline-flex; align-items:center; justify-content:center;
    font-size:9px; font-weight:700;
    border:1px solid rgba(16,185,129,.15); flex-shrink:0;
}

.amt-primary   { font-weight:700; }
.amt-secondary { color:var(--text-secondary); }
.amt-accent    { font-weight:600; color:var(--brand-accent); }

.view-btn {
    display:inline-flex; align-items:center; gap:4px;
    padding:.25rem .65rem;
    font-size:.72rem; font-weight:600;
    border-radius:var(--radius-sm);
    border:1px solid var(--border-light);
    background:var(--surface); color:var(--text-primary);
    text-decoration:none; transition:var(--transition-base);
}
.view-btn:hover { background:var(--brand-accent); color:#fff; border-color:var(--brand-accent); }

.empty-sales { text-align:center; padding:4rem 2rem; color:var(--text-secondary); font-size:.88rem; }
</style>

<div class="page-wrapper">
    <header class="page-header">
        <div class="page-header-group">
            <h1 class="page-title">Sales History</h1>
            <p class="text-secondary">View and audit all past checkout transactions.</p>
        </div>
        <a href="/pos" class="btn btn-primary">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            New Sale
        </a>
    </header>

    <div class="sales-card">

        <!-- Toolbar -->
        <div class="sales-toolbar">
            <div class="toolbar-left">
                <div class="filter-btn-wrap" id="salesFilterWrap">
                    <button class="filter-btn" id="salesFilterBtn" onclick="toggleSalesPanel(event)">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h18M7 12h10M11 19.5h2"/>
                        </svg>
                        Filters
                        <div class="filter-badge-dot"></div>
                        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" id="salesChevron" style="transition:.15s;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div class="filter-dropdown-panel" id="salesFilterPanel">
                        <div class="fp-grid">
                            <div>
                                <label class="fp-label">Date From</label>
                                <input type="date" id="salesDateFrom" class="fp-input">
                            </div>
                            <div>
                                <label class="fp-label">Date To</label>
                                <input type="date" id="salesDateTo" class="fp-input">
                            </div>
                            <div>
                                <label class="fp-label">Cashier</label>
                                <input type="text" id="salesCashier" class="fp-input" placeholder="Name…">
                            </div>
                            <div>
                                <label class="fp-label">Min Total (₱)</label>
                                <input type="number" id="salesMinTotal" class="fp-input" placeholder="0.00" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="fp-actions">
                            <button class="btn-ghost" style="font-size:.78rem;padding:.35rem .75rem;" onclick="clearSalesFilters()">Clear</button>
                            <button class="btn btn-primary" style="padding:.35rem .85rem;font-size:.78rem;" onclick="applyAndCloseSales()">Apply</button>
                        </div>
                    </div>
                </div>

                <!-- Active chips -->
                <div id="salesChips" style="display:flex;gap:.4rem;flex-wrap:wrap;align-items:center;"></div>
            </div>

            <div class="toolbar-right">
                <div class="record-pill">Showing <span id="salesTotal"><?= count($sales) ?></span> of <?= count($sales) ?> records</div>
            </div>
        </div>

        <!-- Table -->
        <div class="sales-table-wrap">
            <table class="sales-table">
                <thead>
                    <tr>
                        <th>TID</th>
                        <th>Date & Time</th>
                        <th>Cashier</th>
                        <th style="text-align:right;">Total</th>
                        <th style="text-align:right;">Payment</th>
                        <th style="text-align:right;">Change</th>
                        <th style="text-align:center; width:80px;">Action</th>
                    </tr>
                </thead>
                <tbody id="salesTableBody">
                    <?php if (empty($sales)): ?>
                        <tr><td colspan="7">
                            <div class="empty-sales">
                                No transactions found. <a href="/pos" style="color:var(--brand-accent);font-weight:600;">Launch POS</a>
                            </div>
                        </td></tr>
                    <?php else: ?>
                        <?php foreach ($sales as $sale): ?>
                        <tr data-date="<?= htmlspecialchars(substr($sale['sale_date'] ?? '', 0, 10)) ?>"
                            data-cashier="<?= htmlspecialchars(strtolower($sale['cashier_name'] ?? '')) ?>"
                            data-total="<?= htmlspecialchars($sale['total_amount'] ?? 0) ?>">
                            <td><span class="mono-id">#<?= htmlspecialchars($sale['sale_id'] ?? '') ?></span></td>
                            <td style="white-space:nowrap; color:var(--text-secondary);">
                                <?= date('M d, Y h:i A', strtotime($sale['sale_date'] ?? 'now')) ?>
                            </td>
                            <td>
                                <div style="display:inline-flex;align-items:center;gap:5px;">
                                    <div class="uavatar"><?= strtoupper(substr($sale['cashier_name'] ?? 'S', 0, 1)) ?></div>
                                    <span style="font-weight:600;font-size:.78rem;"><?= htmlspecialchars($sale['cashier_name'] ?? 'System') ?></span>
                                </div>
                            </td>
                            <td class="amt-primary" style="text-align:right;">₱<?= number_format($sale['total_amount'] ?? 0, 2) ?></td>
                            <td class="amt-secondary" style="text-align:right;">₱<?= number_format($sale['payment_amount'] ?? 0, 2) ?></td>
                            <td class="amt-accent" style="text-align:right;">₱<?= number_format($sale['change_amount'] ?? 0, 2) ?></td>
                            <td style="text-align:center;">
                                <a href="/sales/view?id=<?= htmlspecialchars($sale['sale_id'] ?? '') ?>" class="view-btn">
                                    View
                                    <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
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
</div><!-- /.page-wrapper -->

<script>
(function () {
    let panelOpen = false;

    window.toggleSalesPanel = function (e) {
        e.stopPropagation();
        panelOpen = !panelOpen;
        document.getElementById('salesFilterPanel').classList.toggle('open', panelOpen);
        document.getElementById('salesChevron').style.transform = panelOpen ? 'rotate(180deg)' : '';
    };

    document.addEventListener('click', function (e) {
        if (panelOpen && !document.getElementById('salesFilterWrap').contains(e.target)) {
            panelOpen = false;
            document.getElementById('salesFilterPanel').classList.remove('open');
            document.getElementById('salesChevron').style.transform = '';
        }
    });

    window.applyAndCloseSales = function () {
        applyFilters();
        panelOpen = false;
        document.getElementById('salesFilterPanel').classList.remove('open');
        document.getElementById('salesChevron').style.transform = '';
    };

    function applyFilters() {
        const dateFrom  = document.getElementById('salesDateFrom').value;
        const dateTo    = document.getElementById('salesDateTo').value;
        const cashier   = document.getElementById('salesCashier').value.trim().toLowerCase();
        const minTotal  = parseFloat(document.getElementById('salesMinTotal').value) || 0;

        const rows = document.querySelectorAll('#salesTableBody tr[data-date]');
        let visible = 0;

        rows.forEach(row => {
            const ok = (!dateFrom  || row.dataset.date >= dateFrom)
                    && (!dateTo    || row.dataset.date <= dateTo)
                    && (!cashier   || row.dataset.cashier.includes(cashier))
                    && (!minTotal  || parseFloat(row.dataset.total) >= minTotal);
            row.style.display = ok ? '' : 'none';
            if (ok) visible++;
        });

        document.getElementById('salesTotal').textContent = visible;

        const hasActive = dateFrom || dateTo || cashier || minTotal;
        document.getElementById('salesFilterBtn').classList.toggle('has-active', !!hasActive);

        renderChips({ dateFrom, dateTo, cashier, minTotal });
    }

    function renderChips(f) {
        const container = document.getElementById('salesChips');
        container.innerHTML = '';
        const add = (label, fn) => {
            const c = document.createElement('div');
            c.className = 'chip';
            c.innerHTML = `${label} <span class="chip-remove" onclick="${fn}">×</span>`;
            container.appendChild(c);
        };
        if (f.dateFrom) add('From: ' + f.dateFrom, `clearSalesFilter('salesDateFrom')`);
        if (f.dateTo)   add('To: '   + f.dateTo,   `clearSalesFilter('salesDateTo')`);
        if (f.cashier)  add('Cashier: ' + f.cashier, `clearSalesFilter('salesCashier')`);
        if (f.minTotal) add('Min: ₱' + f.minTotal, `clearSalesFilter('salesMinTotal')`);
    }

    window.clearSalesFilter = function (id) {
        document.getElementById(id).value = '';
        applyFilters();
    };

    window.clearSalesFilters = function () {
        ['salesDateFrom','salesDateTo','salesCashier','salesMinTotal']
            .forEach(id => document.getElementById(id).value = '');
        applyFilters();
    };
})();
</script>
