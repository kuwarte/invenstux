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
</style>

<div class="form-wrapper">
    <header class="page-header">
        <div class="page-header-group">
            <h1 class="page-title">Create Warehouse</h1>
            <p class="text-secondary">Add a new storage facility to your network.</p>
        </div>
    </header>

    <div class="main-card">
        <div style="padding: 1.5rem;">
            <form method="POST" action="/warehouses/create">
                <div class="form-field">
                    <label>Warehouse Name *</label>
                    <input type="text" name="name" class="form-input" placeholder="e.g. North Distribution Center" required>
                </div>
                <div class="form-field">
                    <label>Location Address</label>
                    <input type="text" name="location" class="form-input" placeholder="City, State, Zip">
                </div>
                <div class="form-field">
                    <label>Assign Manager <span style="color: var(--text-secondary); font-weight: 400;">(Managers only)</span></label>
                    <select name="manager_id" class="form-input">
                        <option value="">Select Manager (Optional)</option>
                        <?php if (isset($managers) && !empty($managers)): ?>
                        <?php foreach ($managers as $manager): ?>
                            <option value="<?= $manager['id'] ?>"><?= htmlspecialchars($manager['full_name']) ?></option>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No managers available</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-field">
                    <label>Status</label>
                    <select name="is_active" class="form-input">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" style="flex: 1; padding: 0.75rem;">Create Warehouse</button>
                    <a href="/warehouses" class="btn btn-white" style="flex: 1; text-decoration: none; text-align: center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
