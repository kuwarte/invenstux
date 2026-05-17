<?php
$categories = $categories ?? [];

$totalCategories = count($categories);

$totalProducts = 0;
foreach ($categories as $cat) {
    $totalProducts += (int)($cat['product_count'] ?? 0);
}

$lastAdded = '—';
if (!empty($categories)) {
    $latest = max(array_map(fn($c) => strtotime($c['created_at'] ?? '1970-01-01'), $categories));
    $lastAdded = date('M j, Y', $latest);
}

if (!function_exists('renderCategoryOptions')) {
    function renderCategoryOptions(array $categories, mixed $excludeId = null)
    {
        foreach ($categories as $cat) {
            if ($excludeId && $cat['id'] == $excludeId) continue;
            echo '<option value="' . (int)$cat['id'] . '">'
                . htmlspecialchars($cat['name']) .
                '</option>';
        }
    }
}
?>

<style>
    /* Premium Dashboard Component Enhancements */
    @keyframes dashIn {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .pos-wrapper {
        width: 100%;
        max-width: 1440px;
        margin: 0 auto;
        padding: 24px;
        animation: dashIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .header-title-area h1 {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.03em;
        margin: 0;
    }

    .header-title-area p {
        margin: 0.25rem 0 0 0;
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    /* Renamed Stats Grid Components */
    .cat-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .cat-stats-grid { grid-template-columns: 1fr; }
        .page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .page-header .btn { width: 100%; }
    }

    .cat-stat-card {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        box-shadow: var(--shadow-sm);
        transition: var(--transition-base);
    }

    .cat-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .cat-stat-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: var(--brand-accent-light);
        color: var(--brand-accent-dark);
    }

    .cat-stat-card-content { display: flex; flex-direction: column; }
    .cat-stat-info-value { font-size: 1.5rem; font-weight: 800; color: var(--text-primary); line-height: 1.2; }
    .cat-stat-info-label { font-size: 0.75rem; color: var(--text-secondary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 2px; }

    /* Master Table Container Card */
    .main-card {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .table-control-bar {
        padding: 1rem 1.5rem;
        background: #fbfbfc;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .search-wrapper { position: relative; width: 100%; max-width: 360px; }
    .search-wrapper svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); pointer-events: none; }
    .search-wrapper .form-input { padding-left: 2.5rem; height: 40px; }

    /* Interactive Elements */
    .form-input {
        padding: 0.6rem 1rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-light);
        font-family: inherit;
        font-size: 0.9rem;
        color: var(--text-primary);
        background: var(--input-bg);
        transition: var(--transition-base);
        outline: none;
        width: 100%;
        box-sizing: border-box;
    }

    .form-input:focus {
        border-color: var(--brand-accent);
        background: var(--surface);
        box-shadow: 0 0 0 3px var(--brand-accent-light);
    }

    select.form-input { appearance: none; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1em; padding-right: 2.5rem; }

    /* ADAPTED BUTTONS REFERENCE SYSTEM */
    .btn {
        background-color: var(--brand-accent);
        color: #ffffff;
        border-radius: var(--radius-md);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0.6rem 1.1rem;
        font-family: "Plus Jakarta Sans", sans-serif;
        font-size: 0.875rem;
        font-weight: 700;
        border: none;
        text-decoration: none;
        box-sizing: border-box;
        transition: all var(--transition-base);
    }

    .btn-primary {
        background: var(--brand-accent);
        color: white;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
    }

    .btn-primary:hover {
        background: var(--brand-accent-hover);
        box-shadow: var(--shadow-glow);
    }

    .btn-primary:active {
        transform: scale(0.98);
    }

    .btn-white {
        background: var(--surface);
        border: 1px solid var(--border-light);
        color: var(--text-primary);
        box-shadow: var(--shadow-sm);
    }
    
    .btn-white:hover {
        background: var(--input-bg);
        border-color: var(--text-secondary);
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
        align-items: center;
    }

    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all var(--transition-base);
        background: var(--surface);
        box-sizing: border-box;
    }

    .btn-edit {
        border: 1px solid var(--border-light);
        color: var(--text-primary);
    }
    
    .btn-edit:hover {
        background: var(--bg-color);
        border-color: var(--text-secondary);
    }

    .btn-delete {
        border: 1px solid var(--error-border);
        color: var(--error-text);
    }
    
    .btn-delete:hover {
        background: var(--error-bg);
    }

    /* Modernized Table Design */
    .table-container { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; text-align: left; }
    
    .table th {
        padding: 0.85rem 1.5rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-secondary);
        background: #f8fafc;
        border-bottom: 1px solid var(--border-light);
    }

    .table td { padding: 1rem 1.5rem; border-bottom: 1px solid var(--border-light); font-size: 0.9rem; vertical-align: middle; }
    .table tr:last-child td { border-bottom: none; }
    .table tr:hover td { background: rgba(249, 250, 251, 0.7); }

    /* Perfectly Aligned Tree Hierarchy Rows */
    .cat-cell {
        display: flex;
        align-items: center;
        gap: 14px;
        position: relative;
        padding-left: calc(var(--level, 0) * 36px);
    }

    .indent-connector {
        position: absolute;
        left: calc((var(--level, 1) - 1) * 36px + 16px);
        top: -1rem;
        width: 20px;
        height: calc(1rem + 22px);
        border-left: 2px solid var(--border-light);
        border-bottom: 2px solid var(--border-light);
        border-bottom-left-radius: 8px;
        z-index: 1;
    }

    .cat-mono {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: var(--brand-accent-light);
        color: var(--brand-accent-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        flex-shrink: 0;
        z-index: 2;
        border: 1px solid rgba(16, 185, 129, 0.15);
    }

    /* Target alternative styling context for children */
    tr[style*="--level: 0"] .cat-mono { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }

    .cat-name-container { display: flex; align-items: center; gap: 8px; }
    .cat-name { font-size: 0.95rem; font-weight: 600; color: var(--text-primary); }
    
    /* Product Pill Badge */
    .product-badge {
        font-size: 0.75rem;
        font-weight: 600;
        background: #f3f4f6;
        color: var(--text-secondary);
        padding: 2px 8px;
        border-radius: 99px;
        border: 1px solid var(--border-light);
    }

    .cat-desc {
        font-size: 0.825rem;
        color: var(--text-secondary);
        margin-top: 2px;
        max-width: 420px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Empty States */
    .empty-state-container { text-align: center; padding: 5rem 2rem; background: #fafafa; border-radius: var(--radius-lg); margin: 1.5rem; border: 2px dashed var(--border-light); }
    .empty-state-icon { color: var(--text-muted); margin-bottom: 1.25rem; background: var(--surface); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-left: auto; margin-right: auto; box-shadow: var(--shadow-sm); }
    .empty-state-title { font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.25rem; }
    .empty-state-desc { color: var(--text-secondary); font-size: 0.875rem; max-width: 360px; margin: 0 auto; line-height: 1.5; }

    /* Modals Layout Blueprint */
    .modal-backdrop { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); z-index: 1000; display: none; align-items: center; justify-content: center; padding: 1.5rem; backdrop-filter: blur(4px); }
    .modal-backdrop.show { display: flex; }
    .modal { background: var(--surface); border-radius: var(--radius-lg); width: 100%; max-width: 480px; box-shadow: var(--shadow-md); border: 1px solid var(--border-light); overflow: hidden; animation: dashIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) both; }
    .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-light); background: #f8fafc; }
    .modal-title { font-size: 1.1rem; font-weight: 700; color: var(--text-primary); }
    .modal-close { width: 32px; height: 32px; background: var(--surface); border: 1px solid var(--border-light); border-radius: var(--radius-md); cursor: pointer; color: var(--text-secondary); display: flex; align-items: center; justify-content: center; transition: var(--transition-base); }
    .modal-close:hover { background: var(--input-bg); color: var(--text-primary); }
    .modal-body { padding: 1.5rem; }
    .modal-footer { padding: 1rem 1.5rem; border-top: 1px solid var(--border-light); background: #f8fafc; display: flex; justify-content: flex-end; gap: 0.75rem; }
    .form-group { margin-bottom: 1.25rem; }
    .form-group:last-child { margin-bottom: 0; }
    .form-label { display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-secondary); margin-bottom: 0.5rem; }
    .form-label .req { color: var(--error-text); margin-left: 2px; }
    textarea.form-input { resize: vertical; min-height: 90px; }

    .delete-warning { display: flex; gap: 16px; align-items: flex-start; padding: 1.25rem; background: var(--error-bg); border: 1px solid var(--error-border); border-radius: var(--radius-md); }
    .delete-warning svg { color: var(--error-text); flex-shrink: 0; background: #fee2e2; padding: 6px; border-radius: 50%; width: 36px; height: 36px; box-sizing: border-box; }
    .delete-warning p { font-size: 0.9rem; color: #991b1b; line-height: 1.5; margin: 0; }

    /* Notification System Toasts */
    .toast-container { position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; }
    .toast { min-width: 280px; padding: 1rem; border-radius: var(--radius-md); background: var(--surface); border: 1px solid var(--border-light); box-shadow: var(--shadow-md); display: flex; align-items: center; gap: 12px; font-size: 0.85rem; font-weight: 600; color: var(--text-primary); transform: translateY(8px); opacity: 0; transition: all 0.25s ease; }
    .toast.show { transform: translateY(0); opacity: 1; }
    .toast-success { border-left: 4px solid var(--brand-accent); }
    .toast-error { border-left: 4px solid var(--error-text); }
    .toast-icon { width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .toast-success .toast-icon { background: var(--brand-accent-light); color: var(--brand-accent-dark); }
    .toast-error .toast-icon { background: var(--error-bg); color: var(--error-text); }
</style>

<div id="toastContainer" class="toast-container"></div>

<div class="pos-wrapper">
    <!-- Header Block -->
    <header class="page-header">
        <div class="header-title-area">
            <h1>Categories</h1>
            <p>Organize your products into nested hierarchy tiers smoothly.</p>
        </div>
        <div>
            <button class="btn btn-primary" type="button" onclick="openModal('addModal')">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                Add Category
            </button>
        </div>
    </header>

    <!-- Stats Grid -->
    <div class="cat-stats-grid">
        <div class="cat-stat-card">
            <div class="cat-stat-icon-wrap">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
            </div>
            <div class="cat-stat-card-content">
                <div class="cat-stat-info-value"><?= number_format($totalCategories) ?></div>
                <div class="cat-stat-info-label">Total Tiers</div>
            </div>
        </div>

        <div class="cat-stat-card">
            <div class="cat-stat-icon-wrap">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div class="cat-stat-card-content">
                <div class="cat-stat-info-value"><?= number_format($totalProducts) ?></div>
                <div class="cat-stat-info-label">Tracked Items</div>
            </div>
        </div>

        <div class="cat-stat-card">
            <div class="cat-stat-icon-wrap">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="cat-stat-card-content">
                <div class="cat-stat-info-value"><?= $lastAdded ?></div>
                <div class="cat-stat-info-label">Last Modified</div>
            </div>
        </div>
    </div>

    <!-- Master Card Wrapper Container -->
    <div class="main-card">
        <div class="table-control-bar">
            <div class="search-wrapper">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" id="categorySearch" class="form-input" placeholder="Search categories by phrase..." oninput="filterTable()">
            </div>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 55%;">Category Structural Group</th>
                        <th style="width: 25%;">Created Date</th>
                        <th style="text-align: right; width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="categoryTableBody">
                <?php
                if (!function_exists('renderRows')) {
                    function renderRows(array $categories, int $level = 0) {
                        foreach ($categories as $cat): ?>
                            <tr class="category-data-row" data-name="<?= htmlspecialchars(strtolower($cat['name'] ?? '')) ?>" data-desc="<?= htmlspecialchars(strtolower($cat['description'] ?? '')) ?>">
                                <td>
                                    <div class="cat-cell" style="--level: <?= (int)$level ?>;">
                                        <?php if ($level > 0): ?>
                                            <div class="indent-connector"></div>
                                        <?php endif; ?>
                                        
                                        <div class="cat-mono">
                                            <?= strtoupper(substr(htmlspecialchars($cat['name'] ?? 'C'), 0, 2)) ?>
                                        </div>
                                        <div>
                                            <div class="cat-name-container">
                                                <span class="cat-name"><?= htmlspecialchars($cat['name']) ?></span>
                                                <?php if ((int)($cat['product_count'] ?? 0) > 0): ?>
    <span class="product-badge"><?= (int)$cat['product_count'] ?> items</span>
<?php endif; ?>
                                            </div>
                                            <?php if (!empty($cat['description'])): ?>
                                                <div class="cat-desc" title="<?= htmlspecialchars($cat['description']) ?>">
                                                    <?= htmlspecialchars($cat['description']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td style="color: var(--text-secondary); font-weight: 500;">
                                    <?= !empty($cat['created_at']) ? date('M d, Y', strtotime($cat['created_at'])) : '—' ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-icon btn-edit" type="button" onclick="editCategory(<?= htmlspecialchars(json_encode($cat)) ?>)" title="Edit Category">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button class="btn-icon btn-delete" type="button" onclick="confirmDelete(<?= (int)$cat['id'] ?>, '<?= htmlspecialchars(addslashes($cat['name'])) ?>')" title="Delete Category">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php if (!empty($cat['children'])): ?>
                                <?php renderRows($cat['children'], $level + 1); ?>
                            <?php endif; ?>
                        <?php endforeach;
                    }
                }
                ?>

                <?php if (!empty($categories)): ?>
                    <?php renderRows($categories); ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">
                            <div class="empty-state-container">
                                <div class="empty-state-icon">
                                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                </div>
                                <h3 class="empty-state-title">No categories tracked yet</h3>
                                <p class="empty-state-desc">Create groupings to structurally sort inventory items efficiently.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>

                <tr id="noResultsRow" style="display:none;">
                    <td colspan="3">
                        <div class="empty-state-container">
                            <div class="empty-state-icon">
                                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="empty-state-title">No matches discovered</h3>
                            <p class="empty-state-desc">Adjust phrase query parameters and try again.</p>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal-backdrop" id="addModal">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title">Create New Category</span>
            <button class="modal-close" type="button" onclick="closeModal('addModal')">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form method="POST" action="/categories/create">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Category Title <span class="req">*</span></label>
                    <input class="form-input" name="name" placeholder="e.g., Cold Beverages, Electronics" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Structural Level Placement</label>
                    <select class="form-input" name="parent_id">
                        <option value="">Top-Level Grouping (Root)</option>
                        <?php renderCategoryOptions($categories); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Contextual Details / Description</label>
                    <textarea class="form-input" name="description" placeholder="Provide context about what types of items fit this sector..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" onclick="closeModal('addModal')">Discard</button>
                <button type="submit" class="btn btn-primary">Save Category</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal-backdrop" id="editModal">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title">Modify Category Record</span>
            <button class="modal-close" type="button" onclick="closeModal('editModal')">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form method="POST" action="/categories/update">
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Category Title <span class="req">*</span></label>
                    <input class="form-input" name="name" id="edit_name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Structural Level Placement</label>
                    <select class="form-input" name="parent_id" id="edit_parent">
                        <option value="">Top-Level Grouping (Root)</option>
                        <?php renderCategoryOptions($categories); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Contextual Details / Description</label>
                    <textarea class="form-input" name="description" id="edit_desc"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" onclick="closeModal('editModal')">Discard Changes</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Category Warning Modal -->
<div class="modal-backdrop" id="deleteModal">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title">Delete Category</span>
            <button class="modal-close" type="button" onclick="closeModal('deleteModal')">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form method="POST" action="/categories/delete">
            <input type="hidden" name="id" id="delete_id">
            <div class="modal-body">
                <div class="delete-warning">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <p style="font-weight: 700; margin-bottom: 2px;">Are you sure?</p>
                        <p>This action cannot be undone. This will permanently delete the category <strong id="delete_cat_name"></strong>.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" onclick="closeModal('deleteModal')">Cancel</button>
                <button type="submit" class="btn btn-primary btn-delete" style="box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2); color: var(--surface); background: var(--error-text); border: none;">Delete</button>
            </div>
        </form>
    </div>
</div>