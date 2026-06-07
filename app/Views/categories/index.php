<?php
$categories = $categories ?? [];

$totalCategories = count($categories);

$totalProducts = 0;
foreach ($categories as $cat) {
    $totalProducts += (int)($cat['product_count'] ?? 0);
}

$lastAdded = '—';
if (!empty($categories)) {
    $latest   = max(array_map(fn($c) => strtotime($c['created_at'] ?? '1970-01-01'), $categories));
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
/* ── Categories index – compact design ── */
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
.empty-tbl { text-align:center; padding:4rem 2rem; color:var(--text-secondary); font-size:.88rem; }
.view-btn { display:inline-flex; align-items:center; gap:4px; padding:.25rem .65rem; font-size:.72rem; font-weight:600; border-radius:var(--radius-sm); border:1px solid var(--border-light); background:var(--surface); color:var(--text-primary); text-decoration:none; transition:var(--transition-base); cursor:pointer; }
.view-btn:hover { background:var(--brand-accent); color:#fff; border-color:var(--brand-accent); }

/* ── Compact stat cards ── */
.cat-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1.1rem;
}
@media (max-width: 768px) { .cat-stats-grid { grid-template-columns: 1fr; } }

.cat-stat-card {
    background: var(--surface);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-lg);
    padding: .85rem 1.1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: var(--shadow-sm);
    transition: var(--transition-base);
}
.cat-stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }

.cat-stat-icon-wrap {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    background: var(--brand-accent-light);
    color: var(--brand-accent-dark);
}
.cat-stat-card-content { display: flex; flex-direction: column; }
.cat-stat-info-value { font-size: 1.25rem; font-weight: 800; color: var(--text-primary); line-height: 1.2; }
.cat-stat-info-label { font-size: .7rem; color: var(--text-secondary); font-weight: 700; text-transform: uppercase; letter-spacing: .05em; margin-top: 2px; }

/* ── Category tree ── */
.col-structural { width: 55%; }
.col-date       { width: 25%; }
.col-actions    { text-align: right; width: 120px; }
.date-cell      { color: var(--text-secondary); font-weight: 500; }

.cat-cell {
    display: flex; align-items: center; gap: 12px;
    position: relative;
    padding-left: calc(var(--level, 0) * 32px);
}
.cat-cell[data-level="0"] { --level: 0; }
.cat-cell[data-level="1"] { --level: 1; }
.cat-cell[data-level="2"] { --level: 2; }
.cat-cell[data-level="3"] { --level: 3; }
.cat-cell[data-level="4"] { --level: 4; }
.cat-cell[data-level="5"] { --level: 5; }

.indent-connector {
    position: absolute;
    left: calc((var(--level, 1) - 1) * 32px + 14px);
    top: -1rem; width: 18px; height: calc(1rem + 20px);
    border-left: 2px solid var(--border-light);
    border-bottom: 2px solid var(--border-light);
    border-bottom-left-radius: 8px;
    z-index: 1;
}

.cat-mono {
    width: 34px; height: 34px; border-radius: 7px;
    background: var(--brand-accent-light); color: var(--brand-accent-dark);
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; font-weight: 700; letter-spacing: .05em;
    flex-shrink: 0; z-index: 2;
    border: 1px solid rgba(16, 185, 129, .15);
}
.cat-cell[data-level="0"] .cat-mono { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }

.cat-name-container { display: flex; align-items: center; gap: 7px; }
.cat-name  { font-size: .82rem; font-weight: 600; color: var(--text-primary); }

.product-badge {
    font-size: .7rem; font-weight: 600;
    background: #f3f4f6; color: var(--text-secondary);
    padding: 1px 7px; border-radius: 99px;
    border: 1px solid var(--border-light);
}

.cat-desc {
    font-size: .76rem; color: var(--text-secondary); margin-top: 1px;
    max-width: 400px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

.empty-state-container {
    text-align: center; padding: 4rem 2rem; background: #fafafa;
    border-radius: var(--radius-lg); margin: 1.25rem;
    border: 2px dashed var(--border-light);
}
.empty-state-icon {
    color: var(--text-secondary); margin-bottom: 1rem;
    background: var(--surface); width: 56px; height: 56px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin-left: auto; margin-right: auto; box-shadow: var(--shadow-sm);
}
.empty-state-title { font-size: 1rem; font-weight: 700; color: var(--text-primary); margin-bottom: .2rem; }
.empty-state-desc  { color: var(--text-secondary); font-size: .825rem; max-width: 340px; margin: 0 auto; line-height: 1.5; }

#noResultsRow { display: none; }

.action-buttons { display: flex; gap: .4rem; justify-content: flex-end; align-items: center; }
.btn-icon {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px; border-radius: var(--radius-sm);
    cursor: pointer; transition: all var(--transition-base);
    background: var(--surface); box-sizing: border-box;
}
.btn-edit   { border: 1px solid var(--border-light); color: var(--text-primary); }
.btn-edit:hover { background: var(--input-bg); border-color: var(--text-secondary); }
.btn-delete { border: 1px solid var(--error-border,#fca5a5); color: var(--error-text,#b91c1c); }
.btn-delete:hover { background: var(--error-bg,#fee2e2); }

/* Toast */
.toast-container { position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; }
.toast { min-width: 260px; padding: .85rem 1rem; border-radius: var(--radius-md); background: var(--surface); border: 1px solid var(--border-light); box-shadow: var(--shadow-md); display: flex; align-items: center; gap: 10px; font-size: .82rem; font-weight: 600; color: var(--text-primary); transform: translateY(8px); opacity: 0; transition: all .25s ease; }
.toast.show { transform: translateY(0); opacity: 1; }
.toast-success { border-left: 4px solid var(--brand-accent); }
.toast-error   { border-left: 4px solid var(--error-text,#b91c1c); }
.toast-icon { width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
.toast-success .toast-icon { background: var(--brand-accent-light); color: var(--brand-accent-dark); }
.toast-error   .toast-icon { background: var(--error-bg,#fee2e2); color: var(--error-text,#b91c1c); }
</style>

<div id="toastContainer" class="toast-container"></div>

<div class="page-wrapper">

    <!-- Header -->
    <header class="page-header" style="margin-bottom:1rem;">
        <div class="page-header-group">
            <h1 class="page-title">Categories</h1>
            <p class="text-secondary">Organize your products into nested hierarchy tiers.</p>
        </div>
        <div>
            <button class="btn btn-primary" type="button" onclick="openModal('addModal')">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Add Category
            </button>
        </div>
    </header>

    <!-- Compact stat cards -->
    <div class="cat-stats-grid">
        <div class="cat-stat-card">
            <div class="cat-stat-icon-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
            </div>
            <div class="cat-stat-card-content">
                <div class="cat-stat-info-value"><?= number_format($totalCategories) ?></div>
                <div class="cat-stat-info-label">Total Tiers</div>
            </div>
        </div>

        <div class="cat-stat-card">
            <div class="cat-stat-icon-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
            </div>
            <div class="cat-stat-card-content">
                <?php $rootCount = count(array_filter($categories, fn($c) => empty($c['parent_id']))); ?>
                <div class="cat-stat-info-value"><?= number_format($rootCount) ?></div>
                <div class="cat-stat-info-label">Root Categories</div>
            </div>
        </div>

        <div class="cat-stat-card">
            <div class="cat-stat-icon-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="cat-stat-card-content">
                <div class="cat-stat-info-value"><?= $lastAdded ?></div>
                <div class="cat-stat-info-label">Last Modified</div>
            </div>
        </div>
    </div>

    <!-- Main card -->
    <div class="main-card">

        <!-- Toolbar -->
        <div class="page-toolbar">
            <div class="toolbar-left">
                <div class="record-pill"><span><?= $totalCategories ?></span> categories</div>

                <!-- Filter button -->
                <div class="filter-btn-wrap" id="catFilterWrap">
                    <button class="filter-btn" id="catFilterToggle" type="button" onclick="catTogglePanel()">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M7 8h10M11 12h2M9 16h6"/>
                        </svg>
                        Filters
                        <div class="filter-badge-dot"></div>
                    </button>
                    <div class="fp-panel" id="catFilterPanel">
                        <div class="fp-grid">
                            <div class="fp-full">
                                <label class="fp-label">Search</label>
                                <!-- id="categorySearch" kept so categories.index.js still works -->
                                <input class="fp-input" type="text" id="categorySearch"
                                       placeholder="Category name or description…"
                                       oninput="handleSearchInput()">
                            </div>
                        </div>
                        <div class="fp-actions">
                            <button class="view-btn" type="button" onclick="catClearFilters()">Clear</button>
                            <button class="view-btn" type="button"
                                    style="background:var(--brand-accent);color:#fff;border-color:var(--brand-accent);"
                                    onclick="catTogglePanel()">Done</button>
                        </div>
                    </div>
                </div>

                <!-- Active chip -->
                <div id="catChipArea" style="display:flex;gap:.35rem;flex-wrap:wrap;"></div>
            </div>
            <div class="toolbar-right"></div>
        </div>

        <!-- Table -->
        <div class="tbl-wrap">
            <table class="dense-table">
                <thead>
                    <tr>
                        <th class="col-structural">Category Structural Group</th>
                        <th class="col-date">Created Date</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody id="categoryTableBody">
                <?php
                if (!function_exists('renderRows')) {
                    function renderRows(array $categories, int $level = 0) {
                        foreach ($categories as $cat): ?>
                            <tr class="category-data-row"
                                data-name="<?= htmlspecialchars(strtolower($cat['name'] ?? '')) ?>"
                                data-desc="<?= htmlspecialchars(strtolower($cat['description'] ?? '')) ?>">
                                <td>
                                    <div class="cat-cell" data-level="<?= (int)$level ?>">
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
                                <td class="date-cell">
                                    <?= !empty($cat['created_at']) ? date('M d, Y', strtotime($cat['created_at'])) : '—' ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-icon btn-edit" type="button"
                                                onclick="editCategory(<?= htmlspecialchars(json_encode($cat)) ?>)"
                                                title="Edit Category">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button class="btn-icon btn-delete" type="button"
                                                onclick="confirmDelete(<?= (int)$cat['id'] ?>, '<?= htmlspecialchars(addslashes($cat['name'])) ?>')"
                                                title="Delete Category">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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
                                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                    </svg>
                                </div>
                                <h3 class="empty-state-title">No categories tracked yet</h3>
                                <p class="empty-state-desc">Create groupings to structurally sort inventory items efficiently.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>

                <tr id="noResultsRow">
                    <td colspan="3">
                        <div class="empty-state-container">
                            <div class="empty-state-icon">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
    </div><!-- /.main-card -->
</div><!-- /.page-wrapper -->

<!-- ══════════ MODALS (unchanged) ══════════ -->

<!-- Add Category Modal -->
<div class="modal-backdrop" id="addModal">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title">Create New Category</span>
            <button class="modal-close" type="button" onclick="closeModal('addModal')">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="/categories/create">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Category Title <span class="req">*</span></label>
                    <input class="form-input" name="name" style="width:100%;" placeholder="Cold Beverages, Electronics…" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Structural Level Placement</label>
                    <select class="filter-dropdown" name="parent_id">
                        <option value="">Top-Level Grouping (Root)</option>
                        <?php renderCategoryOptions($categoryOptions ?? []); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Contextual Details / Description</label>
                    <textarea class="form-input" name="description" style="width:100%;" placeholder="Provide context about what types of items fit this sector…"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-ghost" onclick="closeModal('addModal')">Discard</button>
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="/categories/update">
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Category Title <span class="req">*</span></label>
                    <input class="form-input" name="name" style="width:100%;" id="edit_name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Structural Level Placement</label>
                    <select class="filter-dropdown" name="parent_id" id="edit_parent">
                        <option value="">Top-Level Grouping (Root)</option>
                        <?php renderCategoryOptions($categoryOptions ?? []); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Contextual Details / Description</label>
                    <textarea class="form-input" name="description" style="width:100%;" id="edit_desc"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-ghost" onclick="closeModal('editModal')">Discard Changes</button>
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="/categories/delete">
            <input type="hidden" name="id" id="delete_id">
            <div class="modal-body">
                <div class="delete-warning">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <p class="warning-title">Are you sure?</p>
                        <p>This action cannot be undone. This will permanently delete the category <strong id="delete_cat_name"></strong>.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    // Close panel when clicking outside
    document.addEventListener('click', function (e) {
        const wrap = document.getElementById('catFilterWrap');
        if (wrap && !wrap.contains(e.target)) {
            document.getElementById('catFilterPanel').classList.remove('open');
        }
    });

    window.catTogglePanel = function () {
        document.getElementById('catFilterPanel').classList.toggle('open');
    };

    // Update button active state + chip whenever the search value changes.
    // handleSearchInput() is defined in categories.index.js and debounces applyFilter().
    // We hook into the oninput event on #categorySearch above.
    // After each search we also update the chip and button state.
    var origHandleSearchInput = window.handleSearchInput;
    window.handleSearchInput = function () {
        if (typeof origHandleSearchInput === 'function') origHandleSearchInput();
        updateCatFilterState();
    };

    function updateCatFilterState() {
        var val = (document.getElementById('categorySearch') || {}).value || '';
        var btn = document.getElementById('catFilterToggle');
        var chipArea = document.getElementById('catChipArea');
        var hasVal = val.trim().length > 0;

        if (btn) btn.classList.toggle('has-active', hasVal);

        if (chipArea) {
            chipArea.innerHTML = hasVal
                ? '<span class="chip">"' + escHtml(val.trim()) +
                  '<span class="chip-remove" onclick="catClearFilters()">✕</span></span>'
                : '';
        }
    }

    window.catClearFilters = function () {
        var inp = document.getElementById('categorySearch');
        if (inp) { inp.value = ''; inp.dispatchEvent(new Event('input')); }
        updateCatFilterState();
    };

    function escHtml(s) {
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }
}());
</script>
