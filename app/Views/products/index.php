<div class="page-wrapper">
    <header class="page-header">
        <div class="page-header-group">
            <h1 class="page-title">Products</h1>
            <p class="text-secondary">Manage your product catalog and inventory items.</p>
        </div>
        <div class="header-actions">
            <a href="/products<?= ($showInactive ?? false) ? '' : '?show_inactive=1' ?>" class="btn-ghost">
                <?php if ($showInactive ?? false): ?>
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                    Hide Inactive
                <?php else: ?>
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Show Inactive
                <?php endif; ?>
            </a>
            <a href="/products/create" class="btn btn-primary">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Add Product
            </a>
        </div>
    </header>

    <div class="card">
        <div class="card-header">
            <div class="filters-group">
                <div class="search-wrapper">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" id="searchInput" class="form-input" placeholder="Search…" style="width: 220px;">
                </div>
                <select id="categoryFilter" class="filter-dropdown" style="width: 160px;">
                    <option value="">All Categories</option>
                    <?php foreach ($categories ?? [] as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <span class="badge-info" id="productCount"><?= count($products ?? []) ?> items</span>
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
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><span class="sku-code"><?= htmlspecialchars($product['sku']) ?></span></td>
                        <td style="font-weight: 600;"><?= htmlspecialchars($product['name'] ?? '') ?></td>
                        <td style="color: var(--text-secondary);"><?= htmlspecialchars($product['category_name'] ?? '—') ?></td>
                        <td style="font-weight: 600;">₱<?= number_format($product['unit_cost'], 2) ?></td>
                        <td style="color: var(--text-secondary);"><?= htmlspecialchars($product['unit_of_measure'] ?? '') ?></td>
                        <td>
                            <?php if ($product['is_active']): ?>
                                <span class="status-badge badge-active">Active</span>
                            <?php else: ?>
                                <span class="status-badge badge-inactive">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <a href="/products/update?id=<?= htmlspecialchars($product['id'] ?? '') ?>" class="btn-icon btn-edit" title="Edit">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                                </div>
                                <h3>No Products Found</h3>
                                <p class="text-secondary">Your catalog is empty. Add your first product to get started.</p>
                                <a href="/products/create" class="btn btn-primary">Add Product</a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>const showInactive = <?= ($showInactive ?? false) ? 'true' : 'false' ?>;</script>
