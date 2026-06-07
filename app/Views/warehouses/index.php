<style>
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
</style>

<div class="page-wrapper">

    <!-- Page Header -->
    <header class="page-header">
        <div class="page-header-group">
            <h1 class="page-title">Storage Facilities</h1>
            <p class="text-secondary">Manage your physical inventory locations.</p>
        </div>
        <div class="header-actions">
            <a href="/warehouses<?= ($showInactive ?? false) ? '' : '?show_inactive=1' ?>" class="btn-ghost">
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

    <!-- Main Card -->
    <div class="main-card">

        <!-- Toolbar -->
        <div class="page-toolbar">
            <div class="toolbar-left">

                <!-- Filter Button + Panel -->
                <div class="filter-btn-wrap" id="whFilterWrap">
                    <button type="button" class="filter-btn" id="whFilterBtn" onclick="whFilters.togglePanel()">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M7 8h10M10 12h4"/>
                        </svg>
                        Filters
                        <span class="filter-badge-dot"></span>
                    </button>

                    <!-- Filter Panel -->
                    <div class="fp-panel" id="whFilterPanel">
                        <div class="fp-grid">
                            <!-- Search -->
                            <div class="fp-full">
                                <label class="fp-label" for="wh-fp-search">Search</label>
                                <input type="text" id="wh-fp-search" class="fp-input" placeholder="Name or location…">
                            </div>
                            <!-- Status -->
                            <div class="fp-full">
                                <label class="fp-label" for="wh-fp-status">Status</label>
                                <select id="wh-fp-status" class="fp-input">
                                    <option value="">All</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="fp-actions">
                            <button type="button" class="btn-ghost" style="font-size:.78rem;padding:.3rem .7rem;" onclick="whFilters.clearAll()">Clear</button>
                            <button type="button" class="btn btn-primary" style="font-size:.78rem;padding:.3rem .8rem;" onclick="whFilters.applyAndClose()">Apply</button>
                        </div>
                    </div>
                </div>

                <!-- Active filter chips -->
                <div id="whChips"></div>

            </div>
            <div class="toolbar-right">
                <div class="record-pill" id="whCount">
                    <span><?= count($warehouses ?? []) ?></span> locations
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="tbl-wrap">
            <table class="dense-table" id="whTable">
                <thead>
                    <tr>
                        <th>Warehouse Name</th>
                        <th>Location</th>
                        <th>Manager</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($warehouses)): ?>
                        <?php foreach ($warehouses as $warehouse): ?>
                        <tr
                            data-search="<?= htmlspecialchars(strtolower(($warehouse['name'] ?? '') . '|' . ($warehouse['location'] ?? ''))) ?>"
                            data-status="<?= $warehouse['is_active'] ? '1' : '0' ?>"
                        >
                            <td style="font-weight:600;"><?= htmlspecialchars($warehouse['name']) ?></td>
                            <td style="color:var(--text-secondary);"><?= htmlspecialchars($warehouse['location'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($warehouse['manager_name'] ?? 'Unassigned') ?></td>
                            <td>
                                <?php if ($warehouse['is_active']): ?>
                                    <span style="display:inline-flex;align-items:center;gap:.3rem;padding:.18rem .55rem;border-radius:20px;font-size:.7rem;font-weight:700;background:rgba(16,185,129,.1);color:#059669;">Active</span>
                                <?php else: ?>
                                    <span style="display:inline-flex;align-items:center;gap:.3rem;padding:.18rem .55rem;border-radius:20px;font-size:.7rem;font-weight:700;background:rgba(107,114,128,.1);color:#6b7280;">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:right;">
                                <a href="/warehouses/update?id=<?= $warehouse['id'] ?>" class="btn-icon btn-edit" title="Edit">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="wh-empty-initial">
                            <td colspan="5" class="empty-tbl">No warehouses found. <a href="/warehouses/create" class="view-btn" style="margin-left:.5rem;">Add Warehouse</a></td>
                        </tr>
                    <?php endif; ?>
                    <!-- Empty state shown by JS when filters yield no results -->
                    <tr id="wh-empty-filtered" style="display:none;">
                        <td colspan="5" class="empty-tbl">No warehouses match the current filters.</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div><!-- /.main-card -->
</div><!-- /.page-wrapper -->

<script>
(function () {
    'use strict';

    // ── State ──────────────────────────────────────────────────────────────
    const state = { search: '', status: '' };

    // ── DOM refs ───────────────────────────────────────────────────────────
    const btn       = document.getElementById('whFilterBtn');
    const panel     = document.getElementById('whFilterPanel');
    const chipsEl   = document.getElementById('whChips');
    const countEl   = document.getElementById('whCount').querySelector('span');
    const tbody     = document.querySelector('#whTable tbody');
    const emptyFiltered = document.getElementById('wh-empty-filtered');

    const fpSearch = document.getElementById('wh-fp-search');
    const fpStatus = document.getElementById('wh-fp-status');

    // ── Panel toggle ───────────────────────────────────────────────────────
    function togglePanel() {
        panel.classList.toggle('open');
    }

    function closePanel() {
        panel.classList.remove('open');
    }

    document.addEventListener('click', function (e) {
        if (!document.getElementById('whFilterWrap').contains(e.target)) {
            closePanel();
        }
    });

    // ── Filtering ──────────────────────────────────────────────────────────
    function applyFilters() {
        state.search = fpSearch.value.trim().toLowerCase();
        state.status = fpStatus.value;

        const rows = tbody.querySelectorAll('tr[data-search]');
        let visible = 0;

        rows.forEach(function (row) {
            const matchSearch = !state.search || (row.dataset.search || '').includes(state.search);
            const matchStatus = !state.status || row.dataset.status === state.status;

            if (matchSearch && matchStatus) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        emptyFiltered.style.display = (rows.length > 0 && visible === 0) ? '' : 'none';
        countEl.textContent = visible;

        renderChips();
        updateBtnState();
    }

    // ── Chips ──────────────────────────────────────────────────────────────
    function renderChips() {
        chipsEl.innerHTML = '';

        if (state.search) {
            chipsEl.appendChild(makeChip('Search: ' + state.search, 'search'));
        }
        if (state.status !== '') {
            const label = state.status === '1' ? 'Active' : 'Inactive';
            chipsEl.appendChild(makeChip('Status: ' + label, 'status'));
        }
    }

    function makeChip(label, filterId) {
        const chip = document.createElement('span');
        chip.className = 'chip';
        chip.innerHTML = label + ' <span class="chip-remove" onclick="whFilters.clearFilter(\'' + filterId + '\')">&times;</span>';
        return chip;
    }

    function clearFilter(id) {
        if (id === 'search') { state.search = ''; fpSearch.value = ''; }
        if (id === 'status') { state.status = ''; fpStatus.value = ''; }
        applyFilters();
    }

    function clearAll() {
        state.search = ''; state.status = '';
        fpSearch.value = ''; fpStatus.value = '';
        applyFilters();
    }

    function updateBtnState() {
        const hasActive = state.search || state.status !== '';
        btn.classList.toggle('has-active', !!hasActive);
    }

    function applyAndClose() {
        applyFilters();
        closePanel();
    }

    // ── Expose globally ────────────────────────────────────────────────────
    window.whFilters = { togglePanel, applyAndClose, clearFilter, clearAll };

    // ── Init ───────────────────────────────────────────────────────────────
    applyFilters();
}());
</script>
