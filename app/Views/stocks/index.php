<style>
/* Compact operation cards */
.stock-forms-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:1.25rem; margin-bottom:1.25rem; }
.stock-forms-grid .main-card { display:flex; flex-direction:column; }
.stock-card-header { padding:.75rem 1.1rem; border-bottom:1px solid var(--border-light); }
.stock-card-title { font-size:.88rem; font-weight:700; color:var(--text-primary); margin:0; }
.stock-card-body { padding:1.1rem; display:flex; flex-direction:column; flex-grow:1; }
.bottom-pinned-form { display:flex; flex-direction:column; flex-grow:1; }
.form-field { margin-bottom:.75rem; }
.form-field label { display:block; margin-bottom:4px; font-weight:600; color:var(--text-primary); font-size:.78rem; }
.form-actions { margin-top:auto; padding-top:.75rem; }
.form-actions .btn-primary { width:100%; padding:.6rem; cursor:pointer; font-size:.82rem; }

/* Ledger toolbar + panel */
.main-card { background:var(--surface); border:1px solid var(--border-light); border-radius:var(--radius-lg); box-shadow:0 4px 20px -2px rgba(0,0,0,.03); overflow:visible; }
.page-toolbar { display:flex; align-items:center; justify-content:space-between; padding:.75rem 1.25rem; border-bottom:1px solid var(--border-light); background:rgba(250,250,251,.5); gap:.75rem; flex-wrap:wrap; }
.toolbar-left { display:flex; align-items:center; gap:.6rem; flex-wrap:wrap; }
.toolbar-right { display:flex; align-items:center; gap:.5rem; }
.record-pill { font-size:.75rem; font-weight:700; color:var(--text-secondary); background:var(--input-bg); border:1px solid var(--border-light); border-radius:20px; padding:.2rem .7rem; }
.record-pill span { color:var(--text-primary); }
.filter-btn-wrap { position:relative; }
.filter-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.38rem .8rem; font-size:.8rem; font-weight:600; border-radius:var(--radius-md); border:1px solid var(--border-light); background:var(--surface); color:var(--text-primary); cursor:pointer; transition:var(--transition-base); white-space:nowrap; }
.filter-btn:hover { border-color:var(--brand-accent); color:var(--brand-accent); }
.filter-btn.has-active { border-color:var(--brand-accent); color:var(--brand-accent); background:var(--brand-accent-light); }
.filter-badge-dot { width:6px; height:6px; border-radius:50%; background:var(--brand-accent); display:none; }
.filter-btn.has-active .filter-badge-dot { display:block; }
.fp-panel { display:none; position:absolute; top:calc(100% + 6px); left:0; z-index:200; background:var(--surface); border:1px solid var(--border-light); border-radius:var(--radius-lg); box-shadow:0 12px 32px -4px rgba(0,0,0,.12); padding:1rem; min-width:300px; animation:panelIn .18s ease both; }
@keyframes panelIn { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
.fp-panel.open { display:block; }
.fp-grid { display:grid; grid-template-columns:1fr 1fr; gap:.65rem; }
.fp-full { grid-column:1/-1; }
.fp-label { display:block; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--text-secondary); margin-bottom:.3rem; }
.fp-input { width:100%; box-sizing:border-box; padding:.42rem .65rem; border-radius:var(--radius-md); border:1px solid var(--border-light); font-family:inherit; font-size:.8rem; color:var(--text-primary); background:var(--input-bg); outline:none; transition:var(--transition-base); }
.fp-input:focus { border-color:var(--brand-accent); box-shadow:0 0 0 3px var(--brand-accent-light); background:var(--surface); }
.fp-actions { display:flex; justify-content:flex-end; gap:.5rem; margin-top:.85rem; padding-top:.75rem; border-top:1px solid var(--border-light); }
.chip { display:inline-flex; align-items:center; gap:.35rem; padding:.22rem .6rem; border-radius:20px; font-size:.72rem; font-weight:600; background:var(--brand-accent-light); color:var(--brand-accent-dark,var(--brand-accent)); border:1px solid rgba(16,185,129,.2); }
.chip-remove { cursor:pointer; opacity:.6; font-size:.85rem; line-height:1; }
.chip-remove:hover { opacity:1; }
.dense-table { width:100%; border-collapse:collapse; font-size:.78rem; }
.dense-table th { padding:.55rem 1rem; font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--text-secondary); background:rgba(249,250,251,.7); border-bottom:1px solid var(--border-light); white-space:nowrap; }
.dense-table td { padding:.55rem 1rem; border-bottom:1px solid var(--border-light); color:var(--text-primary); vertical-align:middle; }
.dense-table tr:last-child td { border-bottom:none; }
.dense-table tr:hover td { background:rgba(249,250,251,.5); }
.tbl-wrap { overflow-x:auto; }
.prod-name { font-weight:600; color:var(--text-primary); }
.prod-sku { font-size:.7rem; color:var(--text-secondary); font-family:monospace; }
.qty-val { font-family:monospace; font-weight:700; font-size:.88rem; }
.qty-critical { color:#ef4444; }
.qty-ok { color:var(--brand-accent); }
.badge { display:inline-flex; align-items:center; gap:5px; padding:3px 9px; border-radius:20px; font-size:.7rem; font-weight:600; }
.badge::before { content:''; display:block; width:5px; height:5px; border-radius:50%; }
.badge-success { background:var(--brand-accent-light); color:var(--brand-accent-dark); } .badge-success::before { background:var(--brand-accent); }
.badge-danger { background:#fee2e2; color:#991b1b; } .badge-danger::before { background:#ef4444; }
.badge-warning { background:#fef3c7; color:#78350f; } .badge-warning::before { background:#f59e0b; }
.empty-ledger { text-align:center; padding:3rem 2rem; color:var(--text-secondary); font-size:.88rem; }
</style>

<div class="page-wrapper">
    <header class="page-header">
        <div class="page-header-group">
            <h1 class="page-title">Stock Management</h1>
            <p class="text-secondary">Inventory control and warehouse distribution</p>
        </div>
        <div>
            <a href="/stocks/thresholds" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                Configure Thresholds
            </a>
        </div>
    </header>

    <div class="stock-forms-grid">
        <!-- 1. INVENTORY INTAKE CARD -->
        <div class="main-card">
            <div class="stock-card-header">
                <span class="stock-card-title">Inventory Intake</span>
            </div>
            <div class="stock-card-body">
                <form method="POST" action="/stocks/in" class="bottom-pinned-form">
                    <div class="form-field">
                        <label>Product Selection</label>
                        <select name="product_id" class="filter-dropdown" style="width:100%" required>
                            <option value="">Choose item...</option>
                            <?php if (isset($products) && !empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?> (<?= htmlspecialchars($product['sku']) ?>)</option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-field">
                        <label>Destination Warehouse</label>
                        <select name="warehouse_id" class="filter-dropdown" style="width:100%" required>
                            <?php if (isset($warehouses) && !empty($warehouses)): ?>
                            <?php foreach ($warehouses as $warehouse): ?>
                                <option value="<?= $warehouse['id'] ?>"><?= htmlspecialchars($warehouse['name']) ?></option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-field">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-input" required min="1" placeholder="0">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Post Stock In</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. INVENTORY RELEASE CARD -->
        <div class="main-card">
            <div class="stock-card-header">
                <span class="stock-card-title">Inventory Release</span>
            </div>
            <div class="stock-card-body">
                <form method="POST" action="/stocks/out" class="bottom-pinned-form">
                    <div class="form-field">
                        <label>Product Selection</label>
                        <select name="product_id" class="filter-dropdown" style="width:100%" required>
                            <option value="">Choose item...</option>
                            <?php if (isset($products) && !empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?> (<?= htmlspecialchars($product['sku']) ?>)</option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-field">
                        <label>Source Warehouse</label>
                        <select name="warehouse_id" class="filter-dropdown" style="width:100%" required>
                            <?php if (isset($warehouses) && !empty($warehouses)): ?>
                            <?php foreach ($warehouses as $warehouse): ?>
                                <option value="<?= $warehouse['id'] ?>"><?= htmlspecialchars($warehouse['name']) ?></option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-field">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-input" required min="1" placeholder="0">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Post Stock Out</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 3. WAREHOUSE TRANSFER CARD -->
        <div class="main-card">
            <div class="stock-card-header">
                <span class="stock-card-title">Warehouse Transfer</span>
            </div>
            <div class="stock-card-body">
                <form method="POST" action="/stocks/transfer" class="bottom-pinned-form" id="transferForm">
                    <div class="form-field">
                        <label>From Warehouse</label>
                        <select name="from_warehouse_id" id="transferFromWarehouse" class="filter-dropdown" style="width:100%" required
                                onchange="loadTransferProducts(this.value)">
                            <option value="">Select source...</option>
                            <?php if (isset($warehouses) && !empty($warehouses)): ?>
                            <?php foreach ($warehouses as $warehouse): ?>
                                <option value="<?= $warehouse['id'] ?>"><?= htmlspecialchars($warehouse['name']) ?></option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-field">
                        <label>Product</label>
                        <select name="product_id" id="transferProduct" class="filter-dropdown" style="width:100%" required disabled>
                            <option value="">Select source warehouse first...</option>
                        </select>
                        <span id="transferProductHint" style="font-size:.78rem; color:var(--text-secondary); margin-top:4px; display:none;"></span>
                    </div>
                    <div class="form-field">
                        <label>To Warehouse</label>
                        <select name="to_warehouse_id" class="filter-dropdown" style="width:100%" required>
                            <option value="">Select destination...</option>
                            <?php if (isset($warehouses) && !empty($warehouses)): ?>
                            <?php foreach ($warehouses as $warehouse): ?>
                                <option value="<?= $warehouse['id'] ?>"><?= htmlspecialchars($warehouse['name']) ?></option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-field">
                        <label>Quantity</label>
                        <input type="number" name="quantity" id="transferQty" class="form-input" required min="1" placeholder="0">
                    </div>
                    <div class="form-field">
                        <label>Notes <span style="font-weight:400; color:var(--text-secondary);">(optional)</span></label>
                        <input type="text" name="notes" class="form-input" placeholder="Reason for transfer...">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Transfer Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function loadTransferProducts(warehouseId) {
        const productSelect = document.getElementById('transferProduct');
        const hint          = document.getElementById('transferProductHint');
        const qtyInput      = document.getElementById('transferQty');

        productSelect.innerHTML = '<option value="">Loading...</option>';
        productSelect.disabled  = true;
        hint.style.display      = 'none';
        qtyInput.max            = '';

        if (!warehouseId) {
            productSelect.innerHTML = '<option value="">Select source warehouse first...</option>';
            return;
        }

        fetch('/stocks/products-in-warehouse?warehouse_id=' + warehouseId)
            .then(r => r.json())
            .then(products => {
                productSelect.innerHTML = '<option value="">Choose item...</option>';

                if (products.length === 0) {
                    productSelect.innerHTML = '<option value="">No stock in this warehouse</option>';
                    hint.textContent   = 'This warehouse has no available stock to transfer.';
                    hint.style.display = 'block';
                    return;
                }

                products.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value            = p.id;
                    opt.dataset.quantity = p.quantity;
                    opt.textContent      = p.name + ' (' + p.sku + ') — ' + p.quantity + ' available';
                    productSelect.appendChild(opt);
                });

                productSelect.disabled = false;

                productSelect.addEventListener('change', function () {
                    const selected = this.options[this.selectedIndex];
                    const qty      = selected.dataset.quantity;
                    if (qty) {
                        qtyInput.max         = qty;
                        hint.textContent     = 'Max transferable: ' + qty + ' units';
                        hint.style.display   = 'block';
                    } else {
                        qtyInput.max         = '';
                        hint.style.display   = 'none';
                    }
                }, { once: false });
            })
            .catch(() => {
                productSelect.innerHTML = '<option value="">Failed to load products</option>';
            });
    }
    </script>

    <!-- INVENTORY LEDGER -->
    <div class="main-card">
        <div class="page-toolbar">
            <div class="toolbar-left">
                <!-- Filters button + dropdown panel -->
                <div class="filter-btn-wrap" id="fpWrap">
                    <button type="button" class="filter-btn" id="fpToggle" aria-expanded="false">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        Filters
                        <span class="filter-badge-dot"></span>
                    </button>
                    <div class="fp-panel" id="fpPanel" role="dialog" aria-label="Filter options">
                        <div class="fp-grid">
                            <div>
                                <label class="fp-label" for="fpWarehouse">Warehouse</label>
                                <select id="fpWarehouse" class="fp-input">
                                    <option value="">All Warehouses</option>
                                    <?php if (isset($warehouses) && !empty($warehouses)): ?>
                                    <?php foreach ($warehouses as $warehouse): ?>
                                        <option value="<?= $warehouse['id'] ?>"><?= htmlspecialchars($warehouse['name']) ?></option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div>
                                <label class="fp-label" for="fpStatus">Status</label>
                                <select id="fpStatus" class="fp-input">
                                    <option value="">All</option>
                                    <option value="critical">Critical</option>
                                    <option value="optimal">Optimal</option>
                                    <option value="full">Full</option>
                                </select>
                            </div>
                            <div class="fp-full">
                                <label class="fp-label" for="fpSearch">Search</label>
                                <input type="text" id="fpSearch" class="fp-input fp-full" placeholder="Product name, SKU, warehouse…">
                            </div>
                        </div>
                        <div class="fp-actions">
                            <button type="button" class="btn btn-secondary" style="font-size:.78rem; padding:.38rem .8rem;" id="fpClear">Clear all</button>
                            <button type="button" class="btn btn-primary"  style="font-size:.78rem; padding:.38rem .8rem;" id="fpApply">Apply</button>
                        </div>
                    </div>
                </div>
                <!-- Active filter chips -->
                <div id="chipArea"></div>
            </div>
            <div class="toolbar-right">
                <div class="record-pill">Showing <span id="recordCount"><?= count($stockItems ?? []) ?></span> records</div>
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="dense-table" id="ledgerTable">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Warehouse</th>
                        <th style="text-align:center;">Available</th>
                        <th style="text-align:center;">Thresholds</th>
                        <th style="text-align:center;">Status</th>
                    </tr>
                </thead>
                <tbody id="ledgerBody">
                    <?php if (!empty($stockItems)): ?>
                        <?php foreach ($stockItems as $item): ?>
                        <?php
                            if ($item['quantity'] <= $item['min_stock'] && $item['min_stock'] > 0) $statusKey = 'critical';
                            elseif ($item['max_stock'] > 0 && $item['quantity'] >= $item['max_stock']) $statusKey = 'full';
                            else $statusKey = 'optimal';
                        ?>
                        <tr data-warehouse="<?= $item['warehouse_id'] ?>"
                            data-status="<?= $statusKey ?>"
                            data-search="<?= strtolower(htmlspecialchars($item['product_name'] . '|' . ($item['sku'] ?? '') . '|' . $item['warehouse_name'])) ?>">
                            <td>
                                <div class="prod-name"><?= htmlspecialchars($item['product_name']) ?></div>
                                <div class="prod-sku">SKU: <?= htmlspecialchars($item['sku'] ?? 'N/A') ?></div>
                            </td>
                            <td><?= htmlspecialchars($item['warehouse_name']) ?></td>
                            <td style="text-align:center;">
                                <span class="qty-val <?= $statusKey === 'critical' ? 'qty-critical' : 'qty-ok' ?>">
                                    <?= number_format($item['quantity']) ?>
                                </span>
                            </td>
                            <td style="text-align:center; font-family:monospace; font-size:.78rem;">
                                <?= $item['min_stock'] ?> / <?= $item['max_stock'] ?>
                            </td>
                            <td style="text-align:center;">
                                <?php if ($statusKey === 'critical'): ?>
                                    <span class="badge badge-danger">Critical</span>
                                <?php elseif ($statusKey === 'full'): ?>
                                    <span class="badge badge-warning">Full</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Optimal</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="emptyRow">
                            <td colspan="5" class="empty-ledger">No stock items found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="empty-ledger" id="noResults" style="display:none;">No records match the current filters.</div>
        </div>
    </div>
</div>

<script>
(function () {
    const warehouseNames = <?= json_encode(array_column($warehouses ?? [], 'name', 'id')) ?>;

    // State
    const state = { warehouse: '', status: '', search: '' };

    // Elements
    const fpWrap    = document.getElementById('fpWrap');
    const fpToggle  = document.getElementById('fpToggle');
    const fpPanel   = document.getElementById('fpPanel');
    const fpWarehouse = document.getElementById('fpWarehouse');
    const fpStatus  = document.getElementById('fpStatus');
    const fpSearch  = document.getElementById('fpSearch');
    const fpApply   = document.getElementById('fpApply');
    const fpClear   = document.getElementById('fpClear');
    const chipArea  = document.getElementById('chipArea');
    const recordCount = document.getElementById('recordCount');
    const ledgerBody  = document.getElementById('ledgerBody');
    const noResults   = document.getElementById('noResults');

    // Panel toggle
    fpToggle.addEventListener('click', function (e) {
        e.stopPropagation();
        const isOpen = fpPanel.classList.toggle('open');
        fpToggle.setAttribute('aria-expanded', isOpen);
    });

    document.addEventListener('click', function (e) {
        if (!fpWrap.contains(e.target)) {
            fpPanel.classList.remove('open');
            fpToggle.setAttribute('aria-expanded', 'false');
        }
    });

    // Apply button
    fpApply.addEventListener('click', function () {
        state.warehouse = fpWarehouse.value;
        state.status    = fpStatus.value;
        state.search    = fpSearch.value.trim().toLowerCase();
        applyFilters();
        fpPanel.classList.remove('open');
        fpToggle.setAttribute('aria-expanded', 'false');
    });

    // Clear all
    fpClear.addEventListener('click', function () {
        clearAllFilters();
    });

    // Also allow Enter in search field to apply
    fpSearch.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') fpApply.click();
    });

    function applyFilters() {
        const rows = ledgerBody.querySelectorAll('tr[data-warehouse]');
        let visible = 0;

        rows.forEach(function (row) {
            const wMatch = !state.warehouse || row.dataset.warehouse === state.warehouse;
            const sMatch = !state.status    || row.dataset.status    === state.status;
            const qMatch = !state.search    || row.dataset.search.indexOf(state.search) !== -1;

            if (wMatch && sMatch && qMatch) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        recordCount.textContent = visible;
        noResults.style.display = (visible === 0 && rows.length > 0) ? 'block' : 'none';

        // Update button active state
        const hasActive = state.warehouse || state.status || state.search;
        fpToggle.classList.toggle('has-active', !!hasActive);

        renderChips();
    }

    function renderChips() {
        chipArea.innerHTML = '';

        if (state.warehouse) {
            chipArea.appendChild(makeChip('warehouse', 'Warehouse: ' + (warehouseNames[state.warehouse] || state.warehouse)));
        }
        if (state.status) {
            const label = state.status.charAt(0).toUpperCase() + state.status.slice(1);
            chipArea.appendChild(makeChip('status', 'Status: ' + label));
        }
        if (state.search) {
            chipArea.appendChild(makeChip('search', 'Search: ' + state.search));
        }
    }

    function makeChip(id, label) {
        const chip = document.createElement('span');
        chip.className = 'chip';
        chip.innerHTML = label + '<span class="chip-remove" aria-label="Remove filter" data-id="' + id + '">&times;</span>';
        chip.querySelector('.chip-remove').addEventListener('click', function () {
            clearFilter(id);
        });
        return chip;
    }

    function clearFilter(id) {
        state[id] = '';
        // Sync panel inputs
        if (id === 'warehouse') { fpWarehouse.value = ''; }
        if (id === 'status')    { fpStatus.value    = ''; }
        if (id === 'search')    { fpSearch.value    = ''; }
        applyFilters();
    }

    function clearAllFilters() {
        state.warehouse = '';
        state.status    = '';
        state.search    = '';
        fpWarehouse.value = '';
        fpStatus.value    = '';
        fpSearch.value    = '';
        applyFilters();
    }
})();
</script>
