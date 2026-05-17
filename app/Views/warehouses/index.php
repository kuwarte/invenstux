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

<div class="fade-in">
    <header class="page-header">
        <div>
            <h1 style="font-size:1.75rem; font-weight:600; color:var(--text-main); margin:0 0 0.25rem 0;">Storage Facilities</h1>
            <p class="text-muted" style="margin:0;">Manage your physical inventory locations.</p>
        </div>
        <div style="display: flex; gap: 0.75rem; align-items: center;">
            <a href="/warehouses<?= ($showInactive ?? false) ? '' : '?show_inactive=1' ?>" class="btn" style="<?= ($showInactive ?? false) ? 'background: var(--surface); border: 1px solid var(--border-color); color: var(--text-main);' : 'background: var(--primary); color: white;' ?>">
                <?= ($showInactive ?? false) ? 'Hide Inactive' : 'Show Inactive' ?>
            </a>
            <a href="/warehouses/create" class="btn btn-primary">+ Add Warehouse</a>
        </div>
    </header>

    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h2 class="card-title">Search</h2>
        </div>
        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: 1fr auto; gap: 1rem; align-items: end;">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 8px;">Search</label>
                    <input type="text" id="searchInput" placeholder="Search by name or location..." class="form-input" style="width: 100%; padding: 0.65rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); font-size: 0.9rem;">
                </div>
                <div>
                    <button onclick="clearFilters()" class="btn" style="background: var(--surface); border: 1px solid var(--border-color); color: var(--text-main);">Clear</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">All Warehouses</span>
            <span class="badge badge-info" id="warehouseCount"><?= count($warehouses ?? []) ?> Locations</span>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Warehouse Name</th>
                        <th>Location</th>
                        <th>Manager</th>
                        <th>Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="warehouseTableBody">
                    <?php if (isset($warehouses) && !empty($warehouses)): ?>
                    <?php foreach ($warehouses as $warehouse): ?>
                    <tr>
                        <td><strong style="color: var(--text-main);"><?= htmlspecialchars($warehouse['name']) ?></strong></td>
                        <td class="text-muted"><?= htmlspecialchars($warehouse['location'] ?? 'Not specified') ?></td>
                        <td style="font-weight: 500;"><?= htmlspecialchars($warehouse['manager_name'] ?? 'Unassigned') ?></td>
                        <td>
                            <span style=" <?= $warehouse['is_active'] ? 'color: var(--primary);' : 'color: var(--danger);' ?>">
                                <?= $warehouse['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <a href="/warehouses/update?id=<?= $warehouse['id'] ?>" class="action-link" title="Edit Warehouse">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                                </div>
                                <h3 style="font-family: var(--font-heading); font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem; font-size: 1.25rem;">No Warehouses Found</h3>
                                <p class="text-muted" style="margin-bottom: 1.5rem; max-width: 300px; line-height: 1.5;">Start by adding your first warehouse location.</p>
                                <a href="/warehouses/create" class="btn btn-primary">+ Add Warehouse</a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
let searchTimeout;
const showInactive = <?= ($showInactive ?? false) ? 'true' : 'false' ?>;

document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 500);
});

function applyFilters() {
    const search = document.getElementById('searchInput').value;
    
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (showInactive) params.append('show_inactive', '1');
    
    fetch('/warehouses/filter?' + params.toString())
        .then(response => response.json())
        .then(warehouses => renderWarehouses(warehouses))
        .catch(error => console.error('Error:', error));
}

function renderWarehouses(warehouses) {
    const tbody = document.getElementById('warehouseTableBody');
    document.getElementById('warehouseCount').textContent = warehouses.length + ' Locations';
    
    if (warehouses.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                        </div>
                        <h3 style="font-family: var(--font-heading); font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem; font-size: 1.25rem;">No Warehouses Found</h3>
                        <p class="text-muted" style="margin-bottom: 1.5rem; max-width: 300px; line-height: 1.5;">No warehouses match your search criteria.</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = warehouses.map(warehouse => `
        <tr>
            <td><strong style="color: var(--text-main);">${escapeHtml(warehouse.name)}</strong></td>
            <td class="text-muted">${escapeHtml(warehouse.location || 'Not specified')}</td>
            <td style="font-weight: 500;">${escapeHtml(warehouse.manager_name || 'Unassigned')}</td>
            <td>
                <span style="${warehouse.is_active ? 'color: var(--primary);' : 'color: var(--danger);'}">
                    ${warehouse.is_active ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td style="text-align: right;">
                <a href="/warehouses/update?id=${warehouse.id}" class="action-link" title="Edit Warehouse">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </a>
            </td>
        </tr>
    `).join('');
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    applyFilters();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
