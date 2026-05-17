<style>
    :root {
        --primary: #10b981;
        --primary-hover: #059669;
        --primary-soft: #d1fae5;
        --primary-dark: #065f46;
        --bg-main: #f3f4f6;
        --surface: #ffffff;
        --text-main: #111827;
        --text-muted: #6b7280;
        --border-color: #e5e7eb;
        --border-light: #f9fafb;
        --radius-sm: 8px;
        --radius-md: 10px;
        --radius-lg: 14px;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --font-sans: 'Inter', system-ui, -apple-system, sans-serif;
        --font-heading: 'Plus Jakarta Sans', sans-serif;
    }

    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-header h1 {
        font-family: var(--font-heading);
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-main);
        margin: 0 0 0.25rem 0;
        letter-spacing: -0.02em;
    }
    
    .page-header p {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin: 0;
        font-weight: 400;
    }

    .card {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        transition: all 0.2s ease;
    }
    
    .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--surface);
    }

    .card-title {
        font-family: var(--font-heading);
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0;
    }
    
    .card-body {
        padding: 1.5rem;
    }

    .form-input {
        width: 100%;
        padding: 0.65rem 1rem;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-color);
        font-family: var(--font-sans);
        font-size: 0.9rem;
        color: var(--text-main);
        background: var(--surface);
        transition: all 0.2s ease;
        box-sizing: border-box;
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-soft);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0.6rem 1.1rem;
        border-radius: var(--radius-md);
        font-family: var(--font-sans);
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        text-decoration: none;
        box-sizing: border-box;
    }
    
    .btn-primary {
        background: var(--primary);
        color: white;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
    }
    
    .btn-primary:hover {
        background: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);
    }

    .table-container {
        overflow-x: auto;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table th {
        background: var(--border-light);
        padding: 0.875rem 1.5rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }
    
    .table td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.875rem;
        vertical-align: middle;
        color: var(--text-main);
    }
    
    .table tr:last-child td { border-bottom: none; }
    .table tr { transition: background 0.15s ease; }
    .table tr:hover td { background: var(--bg-main); }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-info {
        background: #f0f9ff;
        color: #0369a1;
        font-weight: 700;
    }

    .threshold-input {
        width: 100%;
        padding: 0.6rem 0.8rem;
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-sm);
        font-size: 0.85rem;
        text-align: center;
        background: var(--surface);
        transition: all 0.2s ease;
    }

    .threshold-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px var(--primary-soft);
    }

    .threshold-cell {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        align-items: flex-start;
    }

    .threshold-input-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .threshold-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-actions {
        margin-top: 2rem;
        display: flex;
        gap: 12px;
        border-top: 1px solid var(--border-color);
        padding-top: 1.5rem;
    }

    .btn-secondary {
        background: var(--surface);
        border: 1px solid var(--border-color);
        color: var(--text-main);
    }

    .btn-secondary:hover {
        background: var(--bg-main);
        border-color: var(--primary);
        color: var(--primary);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
</style>

<div class="fade-in">
    <header class="page-header">
        <h1>Stock Thresholds</h1>
        <p>Configure minimum and maximum stock levels for each product per warehouse</p>
    </header>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Warehouse Stock Thresholds</span>
            <span class="badge badge-info"><?= count($warehouses ?? []) ?> Warehouses</span>
        </div>
        <div class="card-body">
            <form method="POST" action="/stocks/thresholds/update">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <?php if (isset($warehouses) && !empty($warehouses)): ?>
                                <?php foreach ($warehouses as $warehouse): ?>
                                    <th style="text-align: center;"><?= htmlspecialchars($warehouse['name']) ?></th>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($products) && !empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: var(--text-main);"><?= htmlspecialchars($product['name']) ?></div>
                                </td>
                                <td>
                                    <span style="font-family: monospace; background: var(--bg-main); padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; color: var(--text-muted);">
                                        <?= htmlspecialchars($product['sku']) ?>
                                    </span>
                                </td>
                                <?php if (isset($warehouses) && !empty($warehouses)): ?>
                                <?php foreach ($warehouses as $warehouse): ?>
                                    <?php 
                                        $threshold = array_filter($thresholds ?? [], function($t) use ($product, $warehouse) {
                                            return $t['product_id'] == $product['id'] && $t['warehouse_id'] == $warehouse['id'];
                                        });
                                        $threshold = reset($threshold) ?: ['min_stock' => 10, 'max_stock' => 100];
                                    ?>
                                    <td style="text-align: center;">
                                        <div class="threshold-cell">
                                            <div class="threshold-input-group">
                                                <input type="number" name="min_stock[<?= $product['id'] ?>][<?= $warehouse['id'] ?>]" class="threshold-input" value="<?= $threshold['min_stock'] ?>" min="0">
                                                <span class="threshold-label">Min</span>
                                            </div>
                                            <div class="threshold-input-group">
                                                <input type="number" name="max_stock[<?= $product['id'] ?>][<?= $warehouse['id'] ?>]" class="threshold-input" value="<?= $threshold['max_stock'] ?>" min="1">
                                                <span class="threshold-label">Max</span>
                                            </div>
                                        </div>
                                    </td>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Thresholds</button>
                    <a href="/stocks" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
