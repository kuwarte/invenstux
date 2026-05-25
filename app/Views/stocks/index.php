<style>
    /* Stock-page-specific styles only — ledger grid, intake/release cards */
    .stock-forms-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    /* Flexbox adjustments to keep all cards matched in height dynamically */
    .stock-forms-grid .main-card {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .stock-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-light);
    }

    .stock-card-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    /* Expand the card body to consume vertical grid space */
    .stock-card-body { 
        padding: 1.5rem; 
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    /* Force the forms to act as full-height flex column systems */
    .bottom-pinned-form {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .form-field { margin-bottom: 1rem; }
    .form-field label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.85rem;
    }

    /* Standard action container: pushes contents to the base floor */
    .form-actions {
        margin-top: auto;
        padding-top: 1rem;
    }

    /* Cleaned up shared button style definition rules */
    .form-actions .btn-primary {
        width: 100%;
        padding: 0.75rem;
        cursor: pointer;
    }

    .btn-outline-danger {
        background: var(--surface);
        border: 1px solid #ef4444;
        color: #ef4444;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0.6rem 1.1rem;
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition-base);
        width: 100%;
    }
    .btn-outline-danger:hover { background: #fee2e2; }

    /* Ledger grid layout */
    .ledger-container { display: flex; flex-direction: column; }

    .ledger-header {
        display: grid;
        grid-template-columns: 2fr 1.2fr 1fr 1.2fr 0.8fr;
        gap: 1.5rem;
        padding: 1rem 1.5rem;
        background: var(--input-bg);
        border-bottom: 1px solid var(--border-light);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-secondary);
    }

    .ledger-item {
        display: grid;
        grid-template-columns: 2fr 1.2fr 1fr 1.2fr 0.8fr;
        gap: 1.5rem;
        align-items: center;
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--border-light);
        transition: var(--transition-base);
    }
    .ledger-item:last-child { border-bottom: none; }
    .ledger-item:hover { background: var(--input-bg); }

    .ledger-product { display: flex; flex-direction: column; gap: 0.35rem; }
    .ledger-product-name { font-weight: 600; color: var(--text-primary); font-size: 0.9rem; }
    .ledger-product-sku { font-size: 0.75rem; color: var(--text-secondary); font-family: monospace; }
    .ledger-warehouse { font-size: 0.9rem; color: var(--text-primary); font-weight: 500; }

    .ledger-quantity { display: flex; flex-direction: column; align-items: center; gap: 0.25rem; }
    .ledger-quantity-value { font-family: monospace; font-weight: 700; font-size: 1.1rem; }
    .ledger-quantity-critical { color: #ef4444; }
    .ledger-quantity-optimal { color: var(--brand-accent); }

    .ledger-thresholds { display: flex; flex-direction: column; align-items: center; gap: 0.25rem; text-align: center; }
    .ledger-threshold-label { font-size: 0.7rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; }
    .ledger-threshold-values { font-size: 0.8rem; color: var(--text-primary); font-family: monospace; font-weight: 500; }
    .ledger-status { display: flex; justify-content: center; }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1;
    }
    .badge::before { content: ''; display: block; width: 6px; height: 6px; border-radius: 50%; }
    .badge-success { background: var(--brand-accent-light); color: var(--brand-accent-dark); }
    .badge-success::before { background: var(--brand-accent); }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-danger::before { background: #ef4444; }
    .badge-warning { background: #fef3c7; color: #78350f; }
    .badge-warning::before { background: #f59e0b; }
    .badge-info::before { display: none; }

    .ledger-filter-bar {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
    }

    @media (max-width: 1024px) {
        .ledger-header, .ledger-item { grid-template-columns: 1.5fr 1fr 0.8fr 1fr 0.7fr; gap: 1rem; }
    }
    @media (max-width: 768px) {
        .ledger-header { display: none; }
        .ledger-item {
            grid-template-columns: 1fr;
            gap: 0.75rem;
            padding: 1rem;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            margin-bottom: 0.75rem;
        }
    }
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
                        <select name="product_id" class="filter-dropdown" style="width: 100%" required>
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
                        <select name="warehouse_id" class="filter-dropdown" style="width: 100%" required>
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
                        <select name="product_id" class="filter-dropdown" style="width: 100%" required>
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
                        <select name="warehouse_id" class="filter-dropdown" style="width: 100%" required>
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
                        <select name="from_warehouse_id" id="transferFromWarehouse" class="filter-dropdown" style="width: 100%" required
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
                        <select name="product_id" id="transferProduct" class="filter-dropdown" style="width: 100%" required disabled>
                            <option value="">Select source warehouse first...</option>
                        </select>
                        <span id="transferProductHint" style="font-size:0.78rem; color:var(--text-secondary); margin-top:4px; display:none;"></span>
                    </div>
                    <div class="form-field">
                        <label>To Warehouse</label>
                        <select name="to_warehouse_id" class="filter-dropdown" style="width: 100%" required>
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

    <!-- INVENTORY LEDGER TABLE -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">Inventory Ledger</span>
            <div class="ledger-filter-bar">
                <select id="warehouseFilter" class="filter-dropdown" onchange="applyFilters()">
                    <option value="">All Warehouses</option>
                    <?php if (isset($warehouses) && !empty($warehouses)): ?>
                    <?php foreach ($warehouses as $warehouse): ?>
                        <option value="<?= $warehouse['id'] ?>" <?= (isset($_GET['warehouse_id']) && $_GET['warehouse_id'] == $warehouse['id']) ? 'selected' : '' ?>><?= htmlspecialchars($warehouse['name']) ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <select id="statusFilter" class="filter-dropdown" onchange="applyFilters()">
                    <option value="">All Status</option>
                    <option value="critical" <?= (isset($_GET['status']) && $_GET['status'] === 'critical') ? 'selected' : '' ?>>Critical</option>
                    <option value="optimal" <?= (isset($_GET['status']) && $_GET['status'] === 'optimal') ? 'selected' : '' ?>>Optimal</option>
                    <option value="full" <?= (isset($_GET['status']) && $_GET['status'] === 'full') ? 'selected' : '' ?>>Full</option>
                </select>
                <div class="search-wrapper">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" id="searchFilter" class="form-input" placeholder="Search…" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" style="width: 200px;" oninput="handleSearchInput()">
                </div>
                <span class="badge-info" id="recordCount"><?= count($stockItems ?? []) ?> records</span>
            </div>
        </div>

        <div class="ledger-container">
            <div class="ledger-header">
                <div>Product</div>
                <div>Warehouse</div>
                <div style="text-align: center;">Available</div>
                <div style="text-align: center;">Thresholds</div>
                <div style="text-align: center;">Status</div>
            </div>
            <div id="ledgerItems">
                <?php if (!empty($stockItems)): ?>
                    <?php foreach ($stockItems as $item): ?>
                    <div class="ledger-item">
                        <div class="ledger-product">
                            <div class="ledger-product-name"><?= htmlspecialchars($item['product_name']) ?></div>
                            <div class="ledger-product-sku">SKU: <?= htmlspecialchars($item['sku'] ?? 'N/A') ?></div>
                        </div>
                        <div class="ledger-warehouse"><?= htmlspecialchars($item['warehouse_name']) ?></div>
                        <div class="ledger-quantity">
                            <div class="ledger-quantity-value <?= $item['quantity'] <= $item['min_stock'] ? 'ledger-quantity-critical' : 'ledger-quantity-optimal' ?>">
                                <?= number_format($item['quantity']) ?>
                            </div>
                        </div>
                        <div class="ledger-thresholds">
                            <div class="ledger-threshold-label">Min / Max</div>
                            <div class="ledger-threshold-values"><?= $item['min_stock'] ?> / <?= $item['max_stock'] ?></div>
                        </div>
                        <div class="ledger-status">
                            <?php if ($item['quantity'] <= $item['min_stock']): ?>
                                <span class="badge badge-danger">Critical</span>
                            <?php elseif ($item['quantity'] >= $item['max_stock']): ?>
                                <span class="badge badge-warning">Full</span>
                            <?php else: ?>
                                <span class="badge badge-success">Optimal</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="padding: 3rem; text-align: center; color: var(--text-secondary);">
                        <p>No stock items found. Try adjusting your filters.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>