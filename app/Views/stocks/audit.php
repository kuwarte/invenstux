<?php
$movements  = $movements  ?? [];
$warehouses = $warehouses ?? [];
$products   = $products   ?? [];

$typeLabels = [
    'IN'           => ['label' => 'Stock In',     'class' => 'badge-in'],
    'OUT'          => ['label' => 'Stock Out',    'class' => 'badge-out'],
    'SALE'         => ['label' => 'Sale',         'class' => 'badge-sale'],
    'ADJUSTMENT'   => ['label' => 'Adjustment',   'class' => 'badge-adj'],
    'TRANSFER_IN'  => ['label' => 'Transfer In',  'class' => 'badge-tin'],
    'TRANSFER_OUT' => ['label' => 'Transfer Out', 'class' => 'badge-tout'],
];

$counts = array_fill_keys(array_keys($typeLabels), 0);
foreach ($movements as $m) {
    if (isset($counts[$m['movement_type']])) $counts[$m['movement_type']]++;
}

// Build warehouse/product lookup maps for chip labels
$warehouseMap = [];
foreach ($warehouses as $w) $warehouseMap[$w['id']] = $w['name'];
$productMap = [];
foreach ($products as $p) $productMap[$p['id']] = $p['name'];
?>

<style>
/* ── Card ───────────────────────────────────────── */
.audit-card {
    background: var(--surface);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-lg);
    box-shadow: 0 4px 20px -2px rgba(0,0,0,.03);
    overflow: visible;  /* allow dropdown to escape */
}

/* ── Toolbar ────────────────────────────────────── */
.audit-toolbar {
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

/* record count pill */
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

/* Dropdown panel */
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
    width: 340px;
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
    gap: .65rem;
}
.fp-full { grid-column: 1 / -1; }

.fp-label {
    display: block;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: var(--text-secondary);
    margin-bottom: .3rem;
}

.fp-input {
    width: 100%;
    box-sizing: border-box;
    padding: .42rem .65rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-light);
    font-family: inherit;
    font-size: .8rem;
    color: var(--text-primary);
    background: var(--input-bg);
    outline: none;
    transition: var(--transition-base);
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
    margin-top: .85rem;
    padding-top: .75rem;
    border-top: 1px solid var(--border-light);
}

/* ── Active filter chips ────────────────────────── */
.active-chips {
    display: flex;
    flex-wrap: wrap;
    gap: .4rem;
    padding: .55rem 1.25rem;
    border-bottom: 1px solid var(--border-light);
    background: var(--input-bg);
    min-height: 0;
}
.active-chips:empty { display: none; }

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
    cursor: default;
}
.chip-remove {
    cursor: pointer;
    opacity: .6;
    font-size: .85rem;
    line-height: 1;
    padding: 0 .1rem;
}
.chip-remove:hover { opacity: 1; }

/* ── Summary row ────────────────────────────────── */
.audit-summary {
    display: flex;
    gap: .6rem;
    flex-wrap: wrap;
    padding: .6rem 1.25rem;
    border-bottom: 1px solid var(--border-light);
    align-items: center;
}

.sum-pill {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: .18rem .65rem;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 600;
    border: 1px solid var(--border-light);
    background: var(--surface);
    color: var(--text-secondary);
}
.sum-pill strong { color: var(--text-primary); font-size: .72rem; }

/* ── Dense table ────────────────────────────────── */
.audit-table-wrap { overflow-x: auto; }

.audit-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .78rem;
}

.audit-table th {
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

.audit-table td {
    padding: .55rem 1rem;
    border-bottom: 1px solid var(--border-light);
    color: var(--text-primary);
    vertical-align: middle;
}
.audit-table tr:last-child td { border-bottom: none; }
.audit-table tr:hover td { background: rgba(249,250,251,.5); }

/* ── Type badges ────────────────────────────────── */
.mov-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: .67rem;
    font-weight: 700;
    letter-spacing: .02em;
    white-space: nowrap;
}
.mov-badge::before {
    content:''; display:block;
    width:4px; height:4px; border-radius:50%;
}
.badge-in   { background:#d1fae5; color:#065f46; } .badge-in::before   { background:#10b981; }
.badge-out  { background:#fee2e2; color:#991b1b; } .badge-out::before  { background:#ef4444; }
.badge-sale { background:#ede9fe; color:#4c1d95; } .badge-sale::before { background:#7c3aed; }
.badge-adj  { background:#fef3c7; color:#78350f; } .badge-adj::before  { background:#f59e0b; }
.badge-tin  { background:#dbeafe; color:#1e3a8a; } .badge-tin::before  { background:#3b82f6; }
.badge-tout { background:#fce7f3; color:#831843; } .badge-tout::before { background:#ec4899; }

/* ── Misc ───────────────────────────────────────── */
.qty-pos { font-family:monospace; font-weight:700; color:#10b981; font-size:.8rem; }
.qty-neg { font-family:monospace; font-weight:700; color:#ef4444; font-size:.8rem; }
.qty-neu { font-family:monospace; font-weight:700; color:var(--text-secondary); font-size:.8rem; }

.mono-id { font-family:"SF Mono",Consolas,monospace; font-weight:600; color:var(--text-secondary); font-size:.75rem; }

.prod-name { font-weight:600; color:var(--text-primary); line-height:1.2; }
.prod-sku  { font-size:.7rem; color:var(--text-secondary); font-family:monospace; }

.uavatar {
    width:22px; height:22px; border-radius:5px;
    background:var(--brand-accent-light); color:var(--brand-accent);
    display:inline-flex; align-items:center; justify-content:center;
    font-size:9px; font-weight:700; flex-shrink:0;
    border:1px solid rgba(16,185,129,.15);
}

.ref-link { font-family:monospace; font-size:.75rem; color:var(--brand-accent); text-decoration:none; font-weight:600; }
.ref-link:hover { text-decoration:underline; }

.notes-td {
    max-width:160px;
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    color:var(--text-secondary); font-size:.75rem;
}

.empty-audit {
    text-align:center; padding:4rem 2rem;
    color:var(--text-secondary); font-size:.88rem;
}
</style>

<div class="page-wrapper">
    <header class="page-header">
        <div class="page-header-group">
            <h1 class="page-title">Stock Audit Log</h1>
            <p class="text-secondary">Complete movement history — every stock in, out, sale, adjustment, and transfer.</p>
        </div>
        <a href="/stocks" class="btn-ghost">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Back to Stocks
        </a>
    </header>

    <div class="audit-card">

        <!-- ── Toolbar ── -->
        <div class="audit-toolbar">
            <div class="toolbar-left">

                <!-- Filter button + dropdown -->
                <div class="filter-btn-wrap" id="filterWrap">
                    <button class="filter-btn" id="filterToggleBtn" onclick="toggleFilterPanel(event)">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h18M7 12h10M11 19.5h2"/>
                        </svg>
                        Filters
                        <div class="filter-badge-dot"></div>
                        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" id="filterChevron" style="transition:.15s;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div class="filter-dropdown-panel" id="filterPanel">
                        <div class="fp-grid">
                            <div class="fp-full">
                                <label class="fp-label">Movement Type</label>
                                <select id="typeFilter" class="fp-input">
                                    <option value="">All Types</option>
                                    <option value="IN">Stock In</option>
                                    <option value="OUT">Stock Out</option>
                                    <option value="SALE">Sale</option>
                                    <option value="ADJUSTMENT">Adjustment</option>
                                    <option value="TRANSFER_IN">Transfer In</option>
                                    <option value="TRANSFER_OUT">Transfer Out</option>
                                </select>
                            </div>
                            <div>
                                <label class="fp-label">Warehouse</label>
                                <select id="warehouseFilter" class="fp-input">
                                    <option value="">All</option>
                                    <?php foreach ($warehouses as $w): ?>
                                        <option value="<?= $w['id'] ?>"><?= htmlspecialchars($w['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="fp-label">Product</label>
                                <select id="productFilter" class="fp-input">
                                    <option value="">All</option>
                                    <?php foreach ($products as $p): ?>
                                        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="fp-label">Date From</label>
                                <input type="date" id="dateFrom" class="fp-input">
                            </div>
                            <div>
                                <label class="fp-label">Date To</label>
                                <input type="date" id="dateTo" class="fp-input">
                            </div>
                        </div>
                        <div class="fp-actions">
                            <button class="btn-ghost" onclick="clearAuditFilters()">Clear</button>
                            <button class="btn btn-primary" style="padding:.4rem .9rem; font-size:.8rem;" onclick="applyAndClose()">Apply</button>
                        </div>
                    </div>
                </div>

                <!-- Active filter chips (populated by JS) -->
                <div id="activeChips" style="display:flex;gap:.4rem;flex-wrap:wrap;align-items:center;"></div>
            </div>

            <div class="toolbar-right">
                <div class="record-pill">Showing <span id="summaryTotal"><?= count($movements) ?></span> of <?= count($movements) ?> records</div>
            </div>
        </div>

        <!-- ── Type summary strip ── -->
        <div class="audit-summary">
            <div class="sum-pill">All&nbsp;<strong><?= count($movements) ?></strong></div>
            <?php foreach ($typeLabels as $key => $meta): ?>
                <?php if ($counts[$key] > 0): ?>
                <div class="sum-pill" style="cursor:pointer;" onclick="quickFilterType('<?= $key ?>')">
                    <span class="mov-badge <?= $meta['class'] ?>" style="padding:1px 6px;"><?= $meta['label'] ?></span>
                    <strong><?= $counts[$key] ?></strong>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <!-- ── Table ── -->
        <div class="audit-table-wrap">
            <table class="audit-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date & Time</th>
                        <th>Type</th>
                        <th>Product</th>
                        <th>Warehouse</th>
                        <th style="text-align:right;">Qty Δ</th>
                        <th>By</th>
                        <th>Ref</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody id="auditTableBody">
                    <?php if (empty($movements)): ?>
                        <tr><td colspan="9"><div class="empty-audit">No stock movements recorded yet.</div></td></tr>
                    <?php else: ?>
                        <?php foreach ($movements as $m):
                            $type       = $m['movement_type'] ?? 'IN';
                            $badge      = $typeLabels[$type] ?? ['label' => $type, 'class' => 'badge-adj'];
                            $qty        = (int)($m['quantity_changed'] ?? 0);
                            $qtyClass   = $qty > 0 ? 'qty-pos' : ($qty < 0 ? 'qty-neg' : 'qty-neu');
                            $qtyDisplay = ($qty > 0 ? '+' : '') . number_format($qty);
                            $refId      = $m['reference_id'] ?? null;
                            $refHref    = ($type === 'SALE' && $refId) ? '/sales/view?id='.$refId : null;
                        ?>
                        <tr
                            data-type="<?= htmlspecialchars($type) ?>"
                            data-warehouse="<?= htmlspecialchars($m['warehouse_id'] ?? '') ?>"
                            data-product="<?= htmlspecialchars($m['product_id'] ?? '') ?>"
                            data-date="<?= htmlspecialchars(substr($m['movement_date'] ?? '', 0, 10)) ?>"
                        >
                            <td><span class="mono-id"><?= $m['movement_id'] ?></span></td>
                            <td style="white-space:nowrap; color:var(--text-secondary);">
                                <?= date('M d, Y h:i A', strtotime($m['movement_date'] ?? 'now')) ?>
                            </td>
                            <td><span class="mov-badge <?= $badge['class'] ?>"><?= $badge['label'] ?></span></td>
                            <td style="min-width:140px;">
                                <div class="prod-name"><?= htmlspecialchars($m['product_name'] ?? '—') ?></div>
                                <div class="prod-sku"><?= htmlspecialchars($m['product_sku'] ?? '') ?></div>
                            </td>
                            <td style="white-space:nowrap;"><?= htmlspecialchars($m['warehouse_name'] ?? '—') ?></td>
                            <td style="text-align:right;"><span class="<?= $qtyClass ?>"><?= $qtyDisplay ?></span></td>
                            <td>
                                <div style="display:inline-flex;align-items:center;gap:5px;">
                                    <div class="uavatar"><?= strtoupper(substr($m['user_name'] ?? 'S', 0, 1)) ?></div>
                                    <span style="font-weight:600;font-size:.78rem;"><?= htmlspecialchars($m['user_name'] ?? 'System') ?></span>
                                </div>
                            </td>
                            <td>
                                <?php if ($refHref): ?>
                                    <a href="<?= $refHref ?>" class="ref-link">#<?= $refId ?></a>
                                <?php elseif ($refId): ?>
                                    <span class="mono-id">#<?= $refId ?></span>
                                <?php else: ?>
                                    <span style="color:var(--text-secondary);">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="notes-td" title="<?= htmlspecialchars($m['notes'] ?? '') ?>">
                                    <?= htmlspecialchars($m['notes'] ?? '—') ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div><!-- /audit-card -->
</div><!-- /page-wrapper -->

<script>
(function () {
    // ── Data maps passed from PHP ──────────────────────────────
    const warehouseMap = <?= json_encode($warehouseMap) ?>;
    const productMap   = <?= json_encode($productMap) ?>;
    const typeLabels   = {
        IN:'Stock In', OUT:'Stock Out', SALE:'Sale',
        ADJUSTMENT:'Adjustment', TRANSFER_IN:'Transfer In', TRANSFER_OUT:'Transfer Out'
    };

    // ── Panel open/close ──────────────────────────────────────
    let panelOpen = false;

    window.toggleFilterPanel = function (e) {
        e.stopPropagation();
        panelOpen = !panelOpen;
        document.getElementById('filterPanel').classList.toggle('open', panelOpen);
        document.getElementById('filterChevron').style.transform = panelOpen ? 'rotate(180deg)' : '';
    };

    document.addEventListener('click', function (e) {
        if (panelOpen && !document.getElementById('filterWrap').contains(e.target)) {
            panelOpen = false;
            document.getElementById('filterPanel').classList.remove('open');
            document.getElementById('filterChevron').style.transform = '';
        }
    });

    // ── Apply & close button ──────────────────────────────────
    window.applyAndClose = function () {
        applyFilters();
        panelOpen = false;
        document.getElementById('filterPanel').classList.remove('open');
        document.getElementById('filterChevron').style.transform = '';
    };

    // ── Quick filter from summary strip ──────────────────────
    window.quickFilterType = function (type) {
        document.getElementById('typeFilter').value = type;
        applyFilters();
    };

    // ── Core filter logic ─────────────────────────────────────
    function applyFilters() {
        const type      = document.getElementById('typeFilter').value;
        const warehouse = document.getElementById('warehouseFilter').value;
        const product   = document.getElementById('productFilter').value;
        const dateFrom  = document.getElementById('dateFrom').value;
        const dateTo    = document.getElementById('dateTo').value;

        const rows = document.querySelectorAll('#auditTableBody tr[data-type]');
        let visible = 0;

        rows.forEach(row => {
            const ok = (!type      || row.dataset.type      === type)
                    && (!warehouse || row.dataset.warehouse  === warehouse)
                    && (!product   || row.dataset.product    === product)
                    && (!dateFrom  || row.dataset.date >= dateFrom)
                    && (!dateTo    || row.dataset.date <= dateTo);
            row.style.display = ok ? '' : 'none';
            if (ok) visible++;
        });

        document.getElementById('summaryTotal').textContent = visible;

        // Update filter button active state
        const hasActive = type || warehouse || product || dateFrom || dateTo;
        document.getElementById('filterToggleBtn').classList.toggle('has-active', !!hasActive);

        // Render active chips
        renderChips({ type, warehouse, product, dateFrom, dateTo });
    }

    function renderChips(f) {
        const container = document.getElementById('activeChips');
        container.innerHTML = '';

        const add = (label, clearFn) => {
            const chip = document.createElement('div');
            chip.className = 'chip';
            chip.innerHTML = `${label} <span class="chip-remove" onclick="${clearFn}">×</span>`;
            container.appendChild(chip);
        };

        if (f.type)      add(typeLabels[f.type] || f.type,      `clearFilter('typeFilter')`);
        if (f.warehouse) add(warehouseMap[f.warehouse] || f.warehouse, `clearFilter('warehouseFilter')`);
        if (f.product)   add(productMap[f.product]   || f.product,   `clearFilter('productFilter')`);
        if (f.dateFrom)  add('From: ' + f.dateFrom,  `clearFilter('dateFrom')`);
        if (f.dateTo)    add('To: '   + f.dateTo,    `clearFilter('dateTo')`);
    }

    window.clearFilter = function (id) {
        document.getElementById(id).value = '';
        applyFilters();
    };

    window.clearAuditFilters = function () {
        ['typeFilter','warehouseFilter','productFilter','dateFrom','dateTo']
            .forEach(id => document.getElementById(id).value = '');
        applyFilters();
    };

    // Expose for any inline use
    window.applyAuditFilters = applyFilters;
})();
</script>
