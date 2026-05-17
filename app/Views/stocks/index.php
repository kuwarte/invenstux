<style>
    :root {
        --primary: #10b981;
        --primary-hover: #059669;
        --primary-soft: #d1fae5;
        --primary-dark: #065f46;
        --bg-main: #f3f4f6;
        --surface: #ffffff;
        --text-main: #111827;
        --text-muted: #6b7280;
        --border-color: #e5e7eb;
        --border-light: #f9fafb;
        --danger: #ef4444;
        --danger-soft: #fee2e2;
        --warning: #f59e0b;
        --warning-soft: #fef3c7;
        --warning-dark: #78350f;
        --success: #10b981;
        --radius-sm: 8px;
        --radius-md: 10px;
        --radius-lg: 14px;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --font-sans: 'Inter', system-ui, -apple-system, sans-serif;
        --font-heading: 'Plus Jakarta Sans', sans-serif;
    }

    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-header h1 {
        font-family: var(--font-heading);
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-main);
        margin: 0 0 0.25rem 0;
        letter-spacing: -0.02em;
    }
    
    .page-header p {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin: 0;
        font-weight: 400;
    }

    .fade-in { 
        animation: fadeIn 0.4s ease-out forwards; 
    }
    
    .card {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    
    .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--surface);
    }

    .card-title { 
        font-family: var(--font-heading);
        font-size: 1.1rem; 
        font-weight: 700; 
        color: var(--text-main); 
        margin: 0;
    }
    
    .card-body {
        padding: 1.5rem;
        flex: 1;
    }

    .form-input {
        width: 100%;
        padding: 0.65rem 1rem;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-color);
        font-family: var(--font-sans);
        font-size: 0.9rem;
        color: var(--text-main);
        background: var(--surface);
        transition: all 0.2s ease;
        box-sizing: border-box;
    }
    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-soft);
    }

    .btn {
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        gap: 8px;
        padding: 0.6rem 1.1rem; 
        border-radius: var(--radius-md);
        font-family: var(--font-sans);
        font-size: 0.875rem; 
        font-weight: 600; 
        cursor: pointer;
        transition: all 0.2s ease; 
        border: none; 
        text-decoration: none;
        box-sizing: border-box;
    }
    
    .btn-primary { 
        background: var(--primary); 
        color: white; 
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
    }
    .btn-primary:hover { 
        background: var(--primary-hover); 
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);
    }

    .btn-outline-danger {
        background: var(--surface);
        border: 1px solid var(--danger);
        color: var(--danger);
    }
    .btn-outline-danger:hover {
        background: var(--danger-soft);
        transform: translateY(-1px);
    }

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
    .badge::before {
        content: '';
        display: block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .badge-success { background: var(--primary-soft); color: var(--primary-dark); }
    .badge-success::before { background: var(--success); }

    .badge-danger { background: var(--danger-soft); color: #991b1b; }
    .badge-danger::before { background: var(--danger); }

    .badge-warning { background: var(--warning-soft); color: var(--warning-dark); }
    .badge-warning::before { background: var(--warning); }

    .badge-info { 
        background: #f0f9ff; 
        color: #0369a1; 
        font-weight: 700;
    }
    .badge-info::before { display: none; }

    /* Ledger Styles */
    .ledger-container {
        display: flex;
        flex-direction: column;
    }

    .ledger-header {
        display: grid;
        grid-template-columns: 2fr 1.2fr 1fr 1.2fr 0.8fr;
        gap: 1.5rem;
        padding: 1rem 1.5rem;
        background: var(--border-light);
        border-bottom: 1px solid var(--border-color);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
    }

    .ledger-item {
        display: grid;
        grid-template-columns: 2fr 1.2fr 1fr 1.2fr 0.8fr;
        gap: 1.5rem;
        align-items: center;
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        transition: all 0.15s ease;
    }

    .ledger-item:last-child {
        border-bottom: none;
    }

    .ledger-item:hover {
        background: var(--bg-main);
    }

    .ledger-product {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .ledger-product-name {
        font-weight: 600;
        color: var(--text-main);
        font-size: 0.9rem;
    }

    .ledger-product-sku {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-family: monospace;
    }

    .ledger-warehouse {
        font-size: 0.9rem;
        color: var(--text-main);
        font-weight: 500;
    }

    .ledger-quantity {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
    }

    .ledger-quantity-value {
        font-family: monospace;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .ledger-quantity-critical {
        color: var(--danger);
    }

    .ledger-quantity-optimal {
        color: var(--primary);
    }

    .ledger-thresholds {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        text-align: center;
    }

    .ledger-threshold-label {
        font-size: 0.7rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .ledger-threshold-values {
        font-size: 0.8rem;
        color: var(--text-main);
        font-family: monospace;
        font-weight: 500;
    }

    .ledger-status {
        display: flex;
        justify-content: center;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 1024px) {
        .ledger-header,
        .ledger-item {
            grid-template-columns: 1.5fr 1fr 0.8fr 1fr 0.7fr;
            gap: 1rem;
        }
    }

    @media (max-width: 768px) {
        .ledger-header {
            display: none;
        }

        .ledger-item {
            grid-template-columns: 1fr;
            gap: 0.75rem;
            padding: 1rem;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            margin-bottom: 0.75rem;
            background: var(--surface);
        }

        .ledger-item:last-child {
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 0;
        }

        .ledger-item::before {
            content: attr(data-label);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
        }
    }
</style>

<div class="fade-in">
    <header class="page-header">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h1>Stock Management</h1>
                <p>Inventory control and warehouse distribution</p>
            </div>
            <a href="/stocks/thresholds" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                Configure Thresholds
            </a>
        </div>
    </header>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Inventory Intake</span>
            </div>
            <div class="card-body">
                <form method="POST" action="/stocks/in">
                    <div style="margin-bottom: 1rem;">
                        <label style="display:block; margin-bottom: 5px; font-weight: 500; color: var(--text-main); font-size: 0.85rem;">Product Selection</label>
                        <select name="product_id" class="form-input" required>
                            <option value="">Choose item...</option>
                            <?php if (isset($products) && !empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?> (<?= htmlspecialchars($product['sku']) ?>)</option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label style="display:block; margin-bottom: 5px; font-weight: 500; color: var(--text-main); font-size: 0.85rem;">Destination Warehouse</label>
                        <select name="warehouse_id" class="form-input" required>
                            <?php if (isset($warehouses) && !empty($warehouses)): ?>
                            <?php foreach ($warehouses as $warehouse): ?>
                                <option value="<?= $warehouse['id'] ?>"><?= htmlspecialchars($warehouse['name']) ?></option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display:block; margin-bottom: 5px; font-weight: 500; color: var(--text-main); font-size: 0.85rem;">Quantity</label>
                        <input type="number" name="quantity" class="form-input" required min="1" placeholder="0">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">Post Stock In</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Inventory Release</span>
            </div>
            <div class="card-body">
                <form method="POST" action="/stocks/out">
                    <div style="margin-bottom: 1rem;">
                        <label style="display:block; margin-bottom: 5px; font-weight: 500; color: var(--text-main); font-size: 0.85rem;">Product Selection</label>
                        <select name="product_id" class="form-input" required>
                            <option value="">Choose item...</option>
                            <?php if (isset($products) && !empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?> (<?= htmlspecialchars($product['sku']) ?>)</option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label style="display:block; margin-bottom: 5px; font-weight: 500; color: var(--text-main); font-size: 0.85rem;">Source Warehouse</label>
                        <select name="warehouse_id" class="form-input" required>
                            <?php if (isset($warehouses) && !empty($warehouses)): ?>
                            <?php foreach ($warehouses as $warehouse): ?>
                                <option value="<?= $warehouse['id'] ?>"><?= htmlspecialchars($warehouse['name']) ?></option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display:block; margin-bottom: 5px; font-weight: 500; color: var(--text-main); font-size: 0.85rem;">Quantity</label>
                        <input type="number" name="quantity" class="form-input" required min="1" placeholder="0">
                    </div>
                    <button type="submit" class="btn btn-outline-danger" style="width: 100%; padding: 0.75rem;">Post Stock Out</button>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Inventory Ledger</span>
            <div style="display: flex; gap: 0.75rem; align-items: center;">
                <select id="warehouseFilter" class="form-input" style="width: 200px; padding: 0.5rem 0.75rem; font-size: 0.85rem;" onchange="applyFilters()">
                    <option value="">All Warehouses</option>
                    <?php if (isset($warehouses) && !empty($warehouses)): ?>
                    <?php foreach ($warehouses as $warehouse): ?>
                        <option value="<?= $warehouse['id'] ?>" <?= (isset($_GET['warehouse_id']) && $_GET['warehouse_id'] == $warehouse['id']) ? 'selected' : '' ?>><?= htmlspecialchars($warehouse['name']) ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <select id="statusFilter" class="form-input" style="width: 150px; padding: 0.5rem 0.75rem; font-size: 0.85rem;" onchange="applyFilters()">
                    <option value="">All Status</option>
                    <option value="critical" <?= (isset($_GET['status']) && $_GET['status'] === 'critical') ? 'selected' : '' ?>>Critical</option>
                    <option value="optimal" <?= (isset($_GET['status']) && $_GET['status'] === 'optimal') ? 'selected' : '' ?>>Optimal</option>
                    <option value="full" <?= (isset($_GET['status']) && $_GET['status'] === 'full') ? 'selected' : '' ?>>Full</option>
                </select>
                <input type="text" id="searchFilter" class="form-input" placeholder="Search name, SKU, or category..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" style="width: 250px; padding: 0.5rem 0.75rem; font-size: 0.85rem;" oninput="handleSearchInput()">
                <button id="clearBtn" onclick="clearFilters()" class="btn" style="padding: 0.5rem 1rem; font-size: 0.85rem; background: var(--surface); border: 1px solid var(--border-color); color: var(--text-main); display: <?= (!empty($_GET['warehouse_id']) || !empty($_GET['status']) || !empty($_GET['search'])) ? 'inline-flex' : 'none' ?>;">
                    Clear
                </button>
                <span class="badge badge-info" id="recordCount"><?= count($stockItems ?? []) ?> Records</span>
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
                <div style="padding: 2rem; text-align: center; color: var(--text-muted);">
                    <p>No stock items found. Try adjusting your filters.</p>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
let searchTimeout;

function handleSearchInput() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 500);
}

async function applyFilters() {
    const warehouseId = document.getElementById('warehouseFilter').value;
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchFilter').value;
    
    const params = new URLSearchParams();
    if (warehouseId) params.append('warehouse_id', warehouseId);
    if (status) params.append('status', status);
    if (search) params.append('search', search);
    
    // Show/hide clear button
    const clearBtn = document.getElementById('clearBtn');
    clearBtn.style.display = (warehouseId || status || search) ? 'inline-flex' : 'none';
    
    try {
        const response = await fetch(`/stocks/filter?${params.toString()}`);
        const data = await response.json();
        
        if (data.success) {
            renderStockItems(data.stockItems);
            document.getElementById('recordCount').textContent = `${data.stockItems.length} Records`;
        }
    } catch (error) {
        console.error('Filter error:', error);
    }
}

function renderStockItems(items) {
    const container = document.getElementById('ledgerItems');
    
    if (items.length === 0) {
        container.innerHTML = `
            <div style="padding: 2rem; text-align: center; color: var(--text-muted);">
                <p>No stock items found. Try adjusting your filters.</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = items.map(item => {
        const isCritical = item.quantity <= item.min_stock;
        const isFull = item.quantity >= item.max_stock;
        const statusBadge = isCritical 
            ? '<span class="badge badge-danger">Critical</span>'
            : isFull 
                ? '<span class="badge badge-warning">Full</span>'
                : '<span class="badge badge-success">Optimal</span>';
        
        return `
            <div class="ledger-item">
                <div class="ledger-product">
                    <div class="ledger-product-name">${escapeHtml(item.product_name)}</div>
                    <div class="ledger-product-sku">SKU: ${escapeHtml(item.sku || 'N/A')}</div>
                </div>
                <div class="ledger-warehouse">${escapeHtml(item.warehouse_name)}</div>
                <div class="ledger-quantity">
                    <div class="ledger-quantity-value ${isCritical ? 'ledger-quantity-critical' : 'ledger-quantity-optimal'}">
                        ${Number(item.quantity).toLocaleString()}
                    </div>
                </div>
                <div class="ledger-thresholds">
                    <div class="ledger-threshold-label">Min / Max</div>
                    <div class="ledger-threshold-values">${item.min_stock} / ${item.max_stock}</div>
                </div>
                <div class="ledger-status">
                    ${statusBadge}
                </div>
            </div>
        `;
    }).join('');
}

function clearFilters() {
    document.getElementById('warehouseFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('searchFilter').value = '';
    applyFilters();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
