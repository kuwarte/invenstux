<div class="page-wrapper">
    <header class="page-header">
        <div class="page-header-group">
            <h1 class="page-title">Storage Facilities</h1>
            <p class="text-secondary">Manage your physical inventory locations.</p>
        </div>
        <div class="header-actions">
            <a href="/warehouses<?= ($showInactive ?? false) ? '' : '?show_inactive=1' ?>" class="btn btn-white">
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
            <a href="/warehouses/create" class="btn btn-primary">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Add Warehouse
            </a>
        </div>
    </header>

    <div class="card">
        <div class="card-header">
            <div class="search-wrapper">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" id="searchInput" class="form-input" placeholder="Search…" style="width: 260px;">
            </div>
            <span class="badge-info" id="warehouseCount"><?= count($warehouses ?? []) ?> locations</span>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Warehouse Name</th>
                        <th>Location</th>
                        <th>Manager</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="warehouseTableBody">
                    <?php if (!empty($warehouses)): ?>
                    <?php foreach ($warehouses as $warehouse): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($warehouse['name']) ?></td>
                        <td style="color: var(--text-secondary);"><?= htmlspecialchars($warehouse['location'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($warehouse['manager_name'] ?? 'Unassigned') ?></td>
                        <td>
                            <?php if ($warehouse['is_active']): ?>
                                <span class="status-badge badge-active">Active</span>
                            <?php else: ?>
                                <span class="status-badge badge-inactive">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <a href="/warehouses/update?id=<?= $warehouse['id'] ?>" class="btn-icon btn-edit" title="Edit">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                                </div>
                                <h3>No Warehouses Found</h3>
                                <p class="text-secondary">Add your first warehouse location to get started.</p>
                                <a href="/warehouses/create" class="btn btn-primary">Add Warehouse</a>
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
