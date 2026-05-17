<?php
$product = $product ?? [];
$categories = $categories ?? [];
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
        --font-sans: "Inter", system-ui, -apple-system, sans-serif;
    }

    /* General Layout & Animations */
    .fade-in {
        animation: fadeIn 0.4s ease-out;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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

    .card-title {
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--text-main);
    }
    .card-body {
        padding: 1.5rem;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        font-family: inherit;
    }

    .btn-white {
        background: var(--surface);
        border: 1px solid var(--border-color);
        color: var(--text-main);
    }
    .btn-white:hover {
        background: var(--bg-main);
        border-color: #cbd5e1;
    }
    .btn-primary {
        background: var(--primary);
        color: white;
    }
    .btn-primary:hover {
        background: var(--primary-hover);
    }
    .btn-primary:disabled {
        background: #94a3b8;
        cursor: not-allowed;
    }
    
    .btn-danger {
        background: var(--danger);
        color: white;
    }
    .btn-danger:hover {
        background: #dc2626;
    }

    /* Inputs */
    .form-input {
        width: 100%;
        padding: 0.65rem 1rem;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-color);
        font-family: inherit;
        font-size: 0.9rem;
        color: var(--text-main);
        background: var(--surface);
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-soft);
    }

    /* Tables */
    .table-container {
        overflow-x: auto;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    .table th {
        background: var(--bg-main);
        padding: 0.875rem 1.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }
    .table td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-light);
        font-size: 0.875rem;
        vertical-align: middle;
    }
    .table tr:last-child td {
        border-bottom: none;
    }
    .table tr:hover td {
        background: var(--bg-main);
    }

    /* Toast Notifications - Minimal Design */
    .toast-container {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 12px;
        pointer-events: none;
    }

    .toast {
        min-width: 300px;
        padding: 16px;
        border-radius: var(--radius-md);
        background: var(--surface);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-toast);
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-main);
        animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .toast-success {
        border-left: 4px solid var(--success);
    }
    .toast-error {
        border-left: 4px solid var(--danger);
    }
    .toast-icon {
        flex-shrink: 0;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        margin-top: 2px;
    }
    .toast-success .toast-icon {
        background: var(--success);
    }
    .toast-error .toast-icon {
        background: var(--danger);
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes fadeOut {
        to {
            opacity: 0;
            transform: translateY(10px);
        }
    }

    /* Utilities */
    .text-muted {
        color: var(--text-muted);
        font-size: 0.85rem;
    }
    .text-primary {
        color: var(--primary);
    }
    .flex-between {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Products Page Specific */
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0;
        letter-spacing: -0.02em;
    }
    .page-subtitle {
        margin: 4px 0 0 0;
    }

    .card-max {
        max-width: 800px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 8px;
    }

    .form-input-mono {
        font-family: monospace;
        background: var(--bg-main);
    }

    .form-textarea {
        resize: vertical;
        min-height: 80px;
    }

    .form-description-group {
        margin-bottom: 20px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        border-top: 1px solid var(--border-color);
        padding-top: 24px;
    }

    .btn-link {
        text-decoration: none;
    }

</style>

<div class="page-header fade-in">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin: 0; letter-spacing: -0.02em;">Edit Product</h1>
        <p class="text-muted" style="margin: 4px 0 0 0;">Update product information and inventory specifications</p>
    </div>
</div>

<div class="card fade-in" style="max-width: 800px;">
    <div class="card-body">
        <form method="POST" action="/products/update">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 8px;">SKU *</label>
                    <input type="text" name="sku" class="form-input" value="<?= htmlspecialchars($product['sku'] ?? '') ?>" required style="font-family: monospace; background: var(--bg-main);">
                </div>
                
                <div class="form-group">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 8px;">Product Name *</label>
                    <input type="text" name="name" class="form-input" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 8px;">Category</label>
                    <select name="category_id" class="form-input">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 8px;">Unit of Measure</label>
                    <input type="text" name="unit_of_measure" class="form-input" value="<?= htmlspecialchars($product['unit_of_measure'] ?? '') ?>" placeholder="e.g. pcs, kg, box">
                </div>
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 8px;">Description</label>
                <textarea name="description" class="form-input" rows="3" style="resize: vertical; min-height: 80px;"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 8px;">Unit Cost ($)</label>
                    <input type="number" step="0.01" name="unit_cost" class="form-input" value="<?= $product['unit_cost'] ?? 0 ?>">
                </div>
                
                <div class="form-group">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 8px;">Status</label>
                    <select name="is_active" class="form-input">
                        <option value="1" <?= ($product['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= ($product['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            
           
            
            <div style="display: flex; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 24px;">
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="/products" class="btn btn-white" style="text-decoration: none;">Cancel</a>
                <button type="button" onclick="confirmDelete()" class="btn btn-danger" style="background: var(--danger); color: white;">Delete</button>
            </div>
        </form>
        
        <form id="deleteForm" method="POST" action="/products/delete" style="display: none;">
            <input type="hidden" name="id" value="<?= $product['id'] ?? '' ?>">
        </form>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
