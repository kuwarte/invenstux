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
        --success: #10b981;
        --radius-sm: 8px;
        --radius-md: 10px;
        --radius-lg: 14px;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --font-sans: 'Inter', system-ui, -apple-system, sans-serif;
        --font-heading: 'Plus Jakarta Sans', sans-serif;
    }

    /* Page Header */
    .page-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        animation: fadeIn 0.4s ease-out;
    }
    
    .page-title {
        font-family: var(--font-heading);
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-main);
        margin: 0;
        letter-spacing: -0.02em;
    }
    
    .page-subtitle {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin: 4px 0 0 0;
        font-weight: 400;
    }

    /* Cards */
    .card {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        animation: fadeIn 0.5s ease-out;
    }
    
    .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--surface);
    }
    
    .card-title-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-title { 
        font-family: var(--font-heading);
        font-size: 1.1rem; 
        font-weight: 700; 
        color: var(--text-main); 
        margin: 0;
    }

    .badge-count {
        background: var(--primary-soft);
        color: var(--primary-dark);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: -0.01em;
    }

    /* Buttons */
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

    /* Tables */
    .table-container { 
        overflow-x: auto; 
    }
    
    .table { 
        width: 100%; 
        border-collapse: collapse; 
    }
    
    .table th {
        background: var(--border-light); 
        padding: 0.875rem 1.5rem;
        font-size: 0.75rem; 
        font-weight: 700; 
        text-transform: uppercase;
        letter-spacing: 0.05em; 
        color: var(--text-muted); 
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }
    
    .table td { 
        padding: 1rem 1.5rem; 
        border-bottom: 1px solid var(--border-color); 
        font-size: 0.875rem; 
        vertical-align: middle; 
        color: var(--text-main);
    }
    
    .table tr:last-child td { border-bottom: none; }
    .table tr { transition: background 0.15s ease; }
    .table tr:hover td { background: var(--bg-main); }

    /* Badges & Specific formatting */
    .sku-code {
        font-family: 'JetBrains Mono', 'Courier New', monospace;
        background: var(--bg-main);
        color: var(--text-muted);
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
        border: 1px solid var(--border-color);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-active {
        background: var(--primary-soft);
        color: var(--primary-dark);
    }

    .status-active::before {
        content: '';
        display: block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--success);
    }

    .status-inactive {
        background: var(--border-light);
        color: var(--text-muted);
        border: 1px solid var(--border-color);
    }

    .status-inactive::before {
        content: '';
        display: block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--text-muted);
    }

    .action-link {
        color: var(--text-muted);
        padding: 6px;
        border-radius: var(--radius-sm);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-link:hover {
        background: var(--primary-soft);
        color: var(--primary);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .empty-icon {
        background: var(--primary-soft);
        color: var(--primary);
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    /* Utilities */
    .text-muted { color: var(--text-muted); font-size: 0.875rem; }
    .font-semibold { font-weight: 600; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="page-header">
    <div>
        <h1 style="font-size:1.75rem; font-weight:600; color:var(--text-main); margin:0 0 0.25rem 0;">Products</h1>
        <p class="text-muted" style="margin:0;">Manage your product catalog and inventory items.</p>
    </div>
    <div class="page-actions" style="display: flex; gap: 0.75rem; align-items: center;">
        <a href="/products<?= ($showInactive ?? false) ? '' : '?show_inactive=1' ?>" class="btn" style="<?= ($showInactive ?? false) ? 'background: var(--surface); border: 1px solid var(--border-color); color: var(--text-main);' : 'background: var(--primary); color: white;' ?>">
            <?= ($showInactive ?? false) ? 'Hide Inactive' : 'Show Inactive' ?>
        </a>
        <a href="/products/create" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Add New Product
        </a>
    </div>
</div>

<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <h2 class="card-title">Search & Filter</h2>
    </div>
    <div style="padding: 1.5rem;">
        <div style="display: grid; grid-template-columns: 2fr 1fr auto; gap: 1rem; align-items: end;">
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 8px;">Search</label>
                <input type="text" id="searchInput" placeholder="Search by name or SKU..." class="form-input" style="width: 100%; padding: 0.65rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); font-size: 0.9rem;">
            </div>
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 8px;">Category</label>
                <select id="categoryFilter" class="form-input" style="width: 100%; padding: 0.65rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); font-size: 0.9rem;">
                    <option value="">All Categories</option>
                    <?php if (isset($categories) && is_array($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div>
                <button onclick="clearFilters()" class="btn" style="background: var(--surface); border: 1px solid var(--border-color); color: var(--text-main);">Clear</button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title-group">
            <h2 class="card-title">All Products</h2>
            <span class="badge-count" id="productCount">
                <?= count($products ?? []) ?> items
            </span>
        </div>
    </div>
    
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Unit Cost</th>
                    <th>UOM</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><span class="sku-code"><?= htmlspecialchars($product['sku']) ?></span></td>
                    <td class="font-semibold"><?= htmlspecialchars($product['name'] ?? '') ?></td>
                    <td class="text-muted"><?= htmlspecialchars($product['category_name'] ?? 'General') ?></td>
                    <td class="font-semibold">$<?= number_format($product['unit_cost'], 2) ?></td>
                    <td class="text-muted"><?= htmlspecialchars($product['unit_of_measure'] ?? '') ?></td>
                    <td>
                        <?php if ($product['is_active']): ?>
                            <span style="color: var(--primary)">Active</span>
                        <?php else: ?>
                            <span style="color: var(--danger)">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: right;">
                        <a href="/products/update?id=<?= htmlspecialchars($product['id'] ?? '') ?>" class="action-link" title="Edit Product">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                            </div>
                            <h3 style="font-family: var(--font-heading); font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem; font-size: 1.25rem;">No Products Found</h3>
                            <p class="text-muted" style="margin-bottom: 1.5rem; max-width: 300px; line-height: 1.5;">Your catalog is currently empty. Start by adding your first product to manage your inventory.</p>
                            <a href="/products/create" class="btn btn-primary">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                Add Product
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
let searchTimeout;
const showInactive = <?= ($showInactive ?? false) ? 'true' : 'false' ?>;

document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 500);
});

document.getElementById('categoryFilter').addEventListener('change', applyFilters);

function applyFilters() {
    const search = document.getElementById('searchInput').value;
    const categoryId = document.getElementById('categoryFilter').value;
    
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (categoryId) params.append('category_id', categoryId);
    if (showInactive) params.append('show_inactive', '1');
    
    fetch('/products/filter?' + params.toString())
        .then(response => response.json())
        .then(products => renderProducts(products))
        .catch(error => console.error('Error:', error));
}

function renderProducts(products) {
    const tbody = document.getElementById('productTableBody');
    document.getElementById('productCount').textContent = products.length + ' items';
    
    if (products.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                        </div>
                        <h3 style="font-family: var(--font-heading); font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem; font-size: 1.25rem;">No Products Found</h3>
                        <p class="text-muted" style="margin-bottom: 1.5rem; max-width: 300px; line-height: 1.5;">No products match your search criteria.</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = products.map(product => `
        <tr>
            <td><span class="sku-code">${escapeHtml(product.sku)}</span></td>
            <td class="font-semibold">${escapeHtml(product.name || '')}</td>
            <td class="text-muted">${escapeHtml(product.category_name || 'General')}</td>
            <td class="font-semibold">$${parseFloat(product.unit_cost).toFixed(2)}</td>
            <td class="text-muted">${escapeHtml(product.unit_of_measure || '')}</td>
            <td>
                ${product.is_active ? '<span style="color: var(--primary)">Active</span>' : '<span style="color: var(--danger)">Inactive</span>'}
            </td>
            <td style="text-align: right;">
                <a href="/products/update?id=${product.id}" class="action-link" title="Edit Product">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </a>
            </td>
        </tr>
    `).join('');
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('categoryFilter').value = '';
    applyFilters();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
