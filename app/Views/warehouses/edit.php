<?php
$warehouse = $warehouse ?? [];
$managers  = $managers ?? [];
?>
<style>
    .form-wrapper {
        max-width: 600px;
        margin: 0 auto;
        padding: 24px;
        animation: dashIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    @keyframes dashIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .form-field {
        margin-bottom: 1.25rem;
    }

    .form-field label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 6px;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-danger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0.6rem 1.1rem;
        border-radius: var(--radius-md);
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition-base);
        border: none;
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover { background: #dc2626; }
</style>

<div class="form-wrapper">
    <header class="page-header">
        <div class="page-header-group">
            <h1 class="page-title">Edit Warehouse</h1>
            <p class="text-secondary">Update the details for this storage facility.</p>
        </div>
    </header>

    <div class="main-card">
        <div style="padding: 1.5rem;">
            <form method="POST" action="/warehouses/update">
                <input type="hidden" name="id" value="<?= $warehouse['id'] ?? '' ?>">

                <div class="form-field">
                    <label>Warehouse Name *</label>
                    <input type="text" name="name" class="form-input" value="<?= htmlspecialchars($warehouse['name'] ?? '') ?>" required>
                </div>

                <div class="form-field">
                    <label>Location Address</label>
                    <input type="text" name="location" class="form-input" value="<?= htmlspecialchars($warehouse['location'] ?? '') ?>">
                </div>

                <div class="form-field">
                    <label>Assign Manager <span style="color: var(--text-secondary); font-weight: 400;">(Managers only)</span></label>
                    <select name="manager_id" class="form-input">
                        <option value="">Select Manager (Optional)</option>
                        <?php if (!empty($managers)): ?>
                        <?php foreach ($managers as $manager): ?>
                            <option value="<?= $manager['id'] ?>" <?= ($warehouse['manager_id'] ?? '') == $manager['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($manager['full_name']) ?>
                            </option>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No managers available</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-field">
                    <label>Status</label>
                    <select name="is_active" class="form-input">
                        <option value="1" <?= ($warehouse['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= ($warehouse['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" style="flex: 1; padding: 0.75rem;">Update Warehouse</button>
                    <a href="/warehouses" class="btn btn-white" style="text-decoration: none; text-align: center;">Cancel</a>
                    <button type="button" onclick="confirmDelete()" class="btn-danger">Delete</button>
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
