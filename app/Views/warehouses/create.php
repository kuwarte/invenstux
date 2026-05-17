<style>
    :root {
        --bg-main: #f8fafc;
        --surface: #ffffff;
        --text-main: #0f172a;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --border-light: #f1f5f9;
        --primary: #10b981; 
        --primary-hover: #059669;
        --primary-soft: #ecfdf5;
        --danger: #ef4444;
        --success: #10b981;
        --radius-sm: 6px;
        --radius-md: 8px;
        --radius-lg: 12px;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-toast: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
        --font-sans: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* General Layout & Animations */
    .fade-in { animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .page-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-color);
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
    }
    .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-title { font-size: 1.05rem; font-weight: 600; color: var(--text-main); }
    .card-body { padding: 1.5rem; }

    /* Buttons */
    .btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 0.5rem 1rem; border-radius: var(--radius-sm);
        font-size: 0.875rem; font-weight: 500; cursor: pointer;
        transition: all 0.2s ease; border: none; font-family: inherit;
    }
    .btn-white { background: var(--surface); border: 1px solid var(--border-color); color: var(--text-main); }
    .btn-white:hover { background: var(--bg-main); border-color: #cbd5e1; }
    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: var(--primary-hover); }
    .btn-primary:disabled { background: #94a3b8; cursor: not-allowed; }
    
    /* Inputs */
    .form-input {
        width: 100%; padding: 0.65rem 1rem;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-color);
        font-family: inherit; font-size: 0.9rem;
        color: var(--text-main); background: var(--surface);
        transition: all 0.2s; box-sizing: border-box;
    }
    .form-input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-soft); }

    /* Tables */
    .table-container { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; }
    .table th {
        background: var(--bg-main); padding: 0.875rem 1.5rem;
        font-size: 0.75rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.05em; color: var(--text-muted); text-align: left;
        border-bottom: 1px solid var(--border-color);
    }
    .table td { padding: 1rem 1.5rem; border-bottom: 1px solid var(--border-light); font-size: 0.875rem; vertical-align: middle; }
    .table tr:last-child td { border-bottom: none; }
    .table tr:hover td { background: var(--bg-main); }

    /* Toast Notifications - Minimal Design */
    .toast-container {
        position: fixed; bottom: 24px; right: 24px; z-index: 9999;
        display: flex; flex-direction: column; gap: 12px; pointer-events: none;
    }
    .toast {
        min-width: 300px; padding: 16px; border-radius: var(--radius-md);
        background: var(--surface); border: 1px solid var(--border-color);
        box-shadow: var(--shadow-toast);
        display: flex; align-items: flex-start; gap: 12px;
        font-size: 0.9rem; font-weight: 500; color: var(--text-main);
        animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .toast-success { border-left: 4px solid var(--success); }
    .toast-error { border-left: 4px solid var(--danger); }
    .toast-icon { flex-shrink: 0; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; margin-top: 2px;}
    .toast-success .toast-icon { background: var(--success); }
    .toast-error .toast-icon { background: var(--danger); }

    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes fadeOut { to { opacity: 0; transform: translateY(10px); } }

    /* Utilities */
    .text-muted { color: var(--text-muted); font-size: 0.85rem; }
    .text-primary { color: var(--primary); }
    .flex-between { display: flex; justify-content: space-between; align-items: center; }

    /* POS Specific Responsive Grid */
    .pos-grid { display: grid; grid-template-columns: 1fr; gap: 1.5rem; align-items: start; }
    @media (min-width: 1024px) {
        .pos-grid { grid-template-columns: 1fr 380px; }
    }

    /* POS Internal Styles */
    .search-wrapper { position: relative; margin-bottom: 1rem; }
    .search-wrapper svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
    .search-wrapper input { padding-left: 2.5rem; }
    
    .product-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 1rem; border-bottom: 1px solid var(--border-light);
        transition: background 0.2s; cursor: pointer;
    }
    .product-item:hover { background: var(--bg-main); }
    .product-item:last-child { border-bottom: none; }

    .qty-btn {
        width: 28px; height: 28px; border: 1px solid var(--border-color); background: var(--surface);
        border-radius: var(--radius-sm); cursor: pointer; font-weight: bold; color: var(--text-main);
        display: flex; align-items: center; justify-content: center; transition: 0.2s;
    }
    .qty-btn:hover { background: var(--bg-main); }
    
    .btn-remove-item {
        color: var(--text-muted); background: transparent; border: none; font-size: 1.2rem;
        cursor: pointer; padding: 4px; border-radius: 4px;
    }
    .btn-remove-item:hover { color: var(--danger); background: #fef2f2; }

    .input-error { border-color: var(--danger) !important; background-color: #fffafb !important; }
    .error-msg { color: var(--danger); font-size: 0.75rem; margin-top: 6px; display: none; font-weight: 500; }
</style>
<link rel="stylesheet" href="/assets/css/warehouses.css">

<div class="fade-in" style="max-width: 600px; margin: 0 auto;">
    <header class="page-header">
        <h1 style="font-size: 1.5rem; font-weight: 600;">Create Warehouse</h1>
    </header>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="/warehouses/store">
                <div style="margin-bottom: 1.25rem;">
                    <label class="text-muted" style="display:block; margin-bottom: 6px;">Warehouse Name *</label>
                    <input type="text" name="name" class="form-input" placeholder="e.g. North Distribution Center" required>
                </div>
                <div style="margin-bottom: 1.25rem;">
                    <label class="text-muted" style="display:block; margin-bottom: 6px;">Location Address</label>
                    <input type="text" name="location" class="form-input" placeholder="City, State, Zip">
                </div>
                <div style="margin-bottom: 2rem;">
                    <label class="text-muted" style="display:block; margin-bottom: 6px;">Assign Manager</label>
                    <select name="manager_id" class="form-input">
                        <option value="">Select Manager (Optional)</option>
                        <?php if (isset($users) && !empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['full_name']) ?></option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div style="margin-bottom: 2rem;">
                    <label class="text-muted" style="display:block; margin-bottom: 6px;">Status</label>
                    <select name="is_active" class="form-input">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; padding: 0.75rem;">Create Warehouse</button>
                    <a href="/warehouses" class="btn btn-white" style="flex: 1; text-decoration: none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
