<?php $users = $users ?? []; ?>

<style>
/* ── Users index – compact design ── */
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
.mono-id { font-family:"SF Mono",Consolas,monospace; font-weight:600; color:var(--text-secondary); font-size:.75rem; }
.view-btn { display:inline-flex; align-items:center; gap:4px; padding:.25rem .65rem; font-size:.72rem; font-weight:600; border-radius:var(--radius-sm); border:1px solid var(--border-light); background:var(--surface); color:var(--text-primary); text-decoration:none; transition:var(--transition-base); }
.view-btn:hover { background:var(--brand-accent); color:#fff; border-color:var(--brand-accent); }
.empty-tbl { text-align:center; padding:4rem 2rem; color:var(--text-secondary); font-size:.88rem; }

/* User-specific */
.u-cell { display:flex; align-items:center; gap:10px; }
.u-avatar { width:26px; height:26px; border-radius:50%; background:var(--brand-accent-light); color:var(--brand-accent-dark); display:flex; align-items:center; justify-content:center; font-size:.72rem; font-weight:800; flex-shrink:0; border:1px solid rgba(16,185,129,.15); }
.u-name { font-weight:700; font-size:.8rem; color:var(--text-primary); line-height:1.2; }
.u-handle { font-size:.7rem; color:var(--text-secondary); }
.role-pill { display:inline-flex; align-items:center; gap:.3rem; padding:.18rem .55rem; border-radius:20px; font-size:.7rem; font-weight:700; text-transform:capitalize; }
.role-pill.role-admin    { background:var(--role-admin-bg,#fee2e2);    color:var(--role-admin,#991b1b); }
.role-pill.role-manager  { background:var(--role-manager-bg,#dbeafe);  color:var(--role-manager,#1d4ed8); }
.role-pill.role-cashier  { background:var(--role-cashier-bg,#fef3c7);  color:var(--role-cashier,#92400e); }
.role-pill.role-staff    { background:var(--role-staff-bg,#f3f4f6);    color:var(--role-staff,#374151); }
.status-dot { display:inline-flex; align-items:center; gap:.3rem; font-size:.72rem; font-weight:600; padding:.18rem .55rem; border-radius:20px; }
.status-dot.s-active   { background:var(--brand-accent-light); color:var(--brand-accent-dark); }
.status-dot.s-inactive { background:var(--error-bg,#fee2e2); color:var(--error-text,#b91c1c); }
.status-dot::before { content:''; display:block; width:5px; height:5px; border-radius:50%; background:currentColor; }
.action-buttons { display:flex; gap:.35rem; justify-content:flex-end; align-items:center; }
.inline-form { display:inline; margin:0; }
.btn-icon { display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:var(--radius-sm); cursor:pointer; transition:all var(--transition-base); background:var(--surface); box-sizing:border-box; }
.btn-edit      { border:1px solid var(--border-light); color:var(--text-primary); }
.btn-edit:hover { background:var(--input-bg); border-color:var(--text-secondary); }
.btn-deactivate { border:1px solid var(--border-light); color:var(--text-secondary); }
.btn-deactivate:hover { background:var(--input-bg); }
.btn-activate  { border:1px solid var(--brand-accent); background:var(--brand-accent); color:#fff; }
.btn-activate:hover { background:var(--brand-accent-hover,#059669); }
.btn-delete    { border:1px solid var(--error-border,#fca5a5); color:var(--error-text,#b91c1c); }
.btn-delete:hover { background:var(--error-bg,#fee2e2); }
.you-label { font-size:.7rem; font-style:italic; color:var(--text-disabled,#9ca3af); padding:0 4px; }
</style>

<div class="page-wrapper">
    <!-- Header -->
    <header class="page-header">
        <div class="page-header-group">
            <h1 class="page-title">Team Members</h1>
            <p class="text-secondary">Manage your organization's members and their deployment roles.</p>
        </div>
        <div>
            <a href="/users/create" class="btn btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Add User
            </a>
        </div>
    </header>

    <!-- Main Card -->
    <div class="main-card">

        <!-- Toolbar -->
        <div class="page-toolbar">
            <div class="toolbar-left">
                <div class="record-pill"><span><?= count($users) ?></span> users</div>

                <!-- Filter button -->
                <div class="filter-btn-wrap" id="filterWrap">
                    <button class="filter-btn" id="filterToggle" type="button" onclick="togglePanel()">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M7 8h10M11 12h2M9 16h6"/>
                        </svg>
                        Filters
                        <div class="filter-badge-dot"></div>
                    </button>
                    <div class="fp-panel" id="filterPanel">
                        <div class="fp-grid">
                            <div class="fp-full">
                                <label class="fp-label">Search</label>
                                <input class="fp-input" type="text" id="fp-search" placeholder="Name, username, email…" oninput="applyFilters()">
                            </div>
                            <div>
                                <label class="fp-label">Role</label>
                                <select class="fp-input" id="fp-role" onchange="applyFilters()">
                                    <option value="">All</option>
                                    <option value="admin">Admin</option>
                                    <option value="manager">Manager</option>
                                    <option value="staff">Staff</option>
                                    <option value="cashier">Cashier</option>
                                </select>
                            </div>
                            <div>
                                <label class="fp-label">Status</label>
                                <select class="fp-input" id="fp-status" onchange="applyFilters()">
                                    <option value="">All</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="fp-actions">
                            <button class="view-btn" type="button" onclick="clearFilters()">Clear</button>
                            <button class="view-btn" type="button" style="background:var(--brand-accent);color:#fff;border-color:var(--brand-accent);" onclick="togglePanel()">Done</button>
                        </div>
                    </div>
                </div>

                <!-- Active filter chips -->
                <div id="chipArea" style="display:flex;gap:.35rem;flex-wrap:wrap;"></div>
            </div>
            <div class="toolbar-right">
                <!-- space for future actions -->
            </div>
        </div>

        <!-- Table -->
        <div class="tbl-wrap">
            <table class="dense-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user):
                        $roleClass  = strtolower(htmlspecialchars($user['role_name'] ?? ''));
                        $searchStr  = strtolower(($user['full_name'] ?? '') . '|' . ($user['username'] ?? '') . '|' . ($user['email'] ?? ''));
                        $isActive   = (int)($user['is_active'] ?? 0);
                        $isCurrent  = ($user['id'] == ($_SESSION['user_id'] ?? null));
                    ?>
                    <tr data-search="<?= htmlspecialchars($searchStr) ?>"
                        data-role="<?= $roleClass ?>"
                        data-status="<?= $isActive ?>">

                        <!-- User cell -->
                        <td>
                            <div class="u-cell">
                                <div class="u-avatar"><?= strtoupper(substr($user['full_name'] ?? 'U', 0, 1)) ?></div>
                                <div>
                                    <div class="u-name"><?= htmlspecialchars($user['full_name'] ?? 'N/A') ?></div>
                                    <div class="u-handle">@<?= htmlspecialchars($user['username'] ?? 'user') ?></div>
                                </div>
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="mono-id" style="font-size:.75rem;"><?= htmlspecialchars($user['email'] ?? '') ?></td>

                        <!-- Role -->
                        <td>
                            <span class="role-pill role-<?= $roleClass ?>">
                                <?= htmlspecialchars($user['role_name'] ?? 'N/A') ?>
                            </span>
                        </td>

                        <!-- Status -->
                        <td>
                            <span class="status-dot <?= $isActive ? 's-active' : 's-inactive' ?>">
                                <?= $isActive ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>

                        <!-- Actions -->
                        <td>
                            <div class="action-buttons">
                                <?php if (!$isCurrent): ?>
                                    <a href="/users/update?id=<?= (int)$user['id'] ?>" class="btn-icon btn-edit" title="Edit">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </a>

                                    <form method="POST" action="/users/toggle-status" class="inline-form">
                                        <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
                                        <button type="submit" class="btn-icon <?= $isActive ? 'btn-deactivate' : 'btn-activate' ?>" title="<?= $isActive ? 'Deactivate' : 'Activate' ?>">
                                            <?php if ($isActive): ?>
                                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12"/></svg>
                                            <?php else: ?>
                                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                            <?php endif; ?>
                                        </button>
                                    </form>

                                    <form method="POST" action="/users/delete" class="inline-form"
                                          onsubmit="return confirm('Delete this user? This cannot be undone.');">
                                        <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
                                        <button type="submit" class="btn-icon btn-delete" title="Delete">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path stroke-linecap="round" stroke-linejoin="round" d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="you-label">You</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="empty-tbl">No users found. <a href="/users/create" style="color:var(--brand-accent);">Add one</a>.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- No-results row (hidden by default) -->
        <div id="noResultsMsg" style="display:none;" class="empty-tbl">
            No users match the current filters.
        </div>

    </div><!-- /.main-card -->
</div><!-- /.page-wrapper -->

<script>
(function () {
    // Close panel when clicking outside
    document.addEventListener('click', function (e) {
        const wrap = document.getElementById('filterWrap');
        if (wrap && !wrap.contains(e.target)) {
            document.getElementById('filterPanel').classList.remove('open');
        }
    });

    window.togglePanel = function () {
        document.getElementById('filterPanel').classList.toggle('open');
    };

    window.applyFilters = function () {
        const search = document.getElementById('fp-search').value.trim().toLowerCase();
        const role   = document.getElementById('fp-role').value;
        const status = document.getElementById('fp-status').value;

        const rows   = document.querySelectorAll('#usersTableBody tr[data-search]');
        let visible  = 0;

        rows.forEach(function (row) {
            const matchSearch = !search || row.dataset.search.includes(search);
            const matchRole   = !role   || row.dataset.role === role;
            const matchStatus = !status || row.dataset.status === status;

            const show = matchSearch && matchRole && matchStatus;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        document.getElementById('noResultsMsg').style.display = visible === 0 ? 'block' : 'none';

        // Button active state
        const hasActive = search || role || status;
        document.getElementById('filterToggle').classList.toggle('has-active', !!hasActive);

        // Chips
        const chips = [];
        if (search) chips.push({ key: 'search', label: '"' + search + '"' });
        if (role)   chips.push({ key: 'role',   label: 'Role: ' + role });
        if (status) chips.push({ key: 'status', label: status === '1' ? 'Active' : 'Inactive' });

        const chipArea = document.getElementById('chipArea');
        chipArea.innerHTML = chips.map(function (c) {
            return '<span class="chip">' + c.label +
                   '<span class="chip-remove" onclick="clearChip(\'' + c.key + '\')">✕</span></span>';
        }).join('');
    };

    window.clearChip = function (key) {
        if (key === 'search') document.getElementById('fp-search').value = '';
        if (key === 'role')   document.getElementById('fp-role').value   = '';
        if (key === 'status') document.getElementById('fp-status').value = '';
        applyFilters();
    };

    window.clearFilters = function () {
        document.getElementById('fp-search').value = '';
        document.getElementById('fp-role').value   = '';
        document.getElementById('fp-status').value = '';
        applyFilters();
    };
}());
</script>
