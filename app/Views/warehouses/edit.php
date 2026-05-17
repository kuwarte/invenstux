<?php
$warehouse = $warehouse ?? [];
$users = $users ?? [];
?>
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
    .btn-danger { background: var(--danger); color: white; }
    .btn-danger:hover { background: #dc2626; }
    
    .form-input {
        width: 100%; padding: 0.65rem 1rem;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-color);
        font-family: inherit; font-size: 0.9rem;
        color: var(--text-main); background: var(--surface);
        transition: all 0.2s; box-sizing: border-box;
    }
    .form-input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-soft); }

    .text-muted { color: var(--text-muted); font-size: 0.85rem; }
</style>

<div class="fade-in" style="max-width: 600px; margin: 0 auto;">
    <header class="page-header">
        <h1 style="font-size: 1.5rem; font-weight: 600;">Edit Warehouse</h1>
    </header>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="/warehouses/update">
                <input type="hidden" name="id" value="<?= $warehouse['id'] ?? '' ?>">
                
                <div style="margin-bottom: 1.25rem;">
                    <label class="text-muted" style="display:block; margin-bottom: 6px;">Warehouse Name *</label>
                    <input type="text" name="name" class="form-input" value="<?= htmlspecialchars($warehouse['name'] ?? '') ?>" required>
                </div>
                
                <div style="margin-bottom: 1.25rem;">
                    <label class="text-muted" style="display:block; margin-bottom: 6px;">Location Address</label>
                    <input type="text" name="location" class="form-input" value="<?= htmlspecialchars($warehouse['location'] ?? '') ?>">
                </div>
                
                <div style="margin-bottom: 1.25rem;">
                    <label class="text-muted" style="display:block; margin-bottom: 6px;">Assign Manager</label>
                    <select name="manager_id" class="form-input">
                        <option value="">Select Manager (Optional)</option>
                        <?php if (isset($users) && !empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>" <?= ($warehouse['manager_id'] ?? '') == $user['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($user['full_name']) ?>
                            </option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div style="margin-bottom: 2rem;">
                    <label class="text-muted" style="display:block; margin-bottom: 6px;">Status</label>
                    <select name="is_active" class="form-input">
                        <option value="1" <?= ($warehouse['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= ($warehouse['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; padding: 0.75rem;">Update Warehouse</button>
                    <a href="/warehouses" class="btn btn-white" style="text-decoration: none;">Cancel</a>
                    <button type="button" onclick="confirmDelete()" class="btn btn-danger">Delete</button>
                </div>
            </form>
            
            <form id="deleteForm" method="POST" action="/warehouses/delete" style="display: none;">
                <input type="hidden" name="id" value="<?= $warehouse['id'] ?? '' ?>">
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this warehouse? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
