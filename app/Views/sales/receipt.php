<?php
$sale = $sale ?? [];
?>

<style>
    .pos-wrapper {
        width: 100%;
        margin: 0;
        padding: 2rem 1rem;
        color: var(--text-primary);
        font-family: var(--font-family);
        animation: dashIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        background: var(--bg-main);
    }

    @keyframes dashIn {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .receipt-card {
        background: var(--surface);
        width: 100%;
        max-width: 460px;
        padding: 2.5rem;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        position: relative;
    }

    .receipt-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px dashed var(--border-light);
    }

    .receipt-header h1 {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0;
        letter-spacing: 0.04em;
    }

    .receipt-id-badge {
        display: inline-block;
        margin-top: 0.75rem;
        padding: 6px 14px;
        background: var(--bg-main);
        border-radius: var(--radius-sm);
        font-family: monospace;
        font-size: 0.85rem;
        color: var(--text-secondary);
        font-weight: 600;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
    }

    .info-label { 
        color: var(--text-muted); 
        margin-bottom: 4px; 
        font-weight: 600; 
        font-size: 0.7rem; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
    }
    
    .info-value { 
        color: var(--text-primary); 
        font-weight: 700; 
    }

    .receipt-divider {
        border-top: 1px dashed var(--border-light);
        margin: 1.5rem 0;
    }

    .item-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--bg-main);
    }

    .item-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .item-details { 
        flex: 1; 
    }
    
    .item-name { 
        font-weight: 700; 
        color: var(--text-primary); 
    }
    
    .item-meta { 
        font-size: 0.8rem; 
        color: var(--text-secondary); 
        margin-top: 4px; 
    }

    .summary-section {
        margin-top: 1.5rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
    }

    .summary-row span:first-child {
        color: var(--text-secondary);
        font-weight: 500;
    }

    .summary-row span:last-child {
        color: var(--text-primary);
        font-weight: 700;
    }

    .total-row {
        margin-top: 1rem;
        padding: 1rem 0;
        border-top: 1px solid var(--border-light);
        border-bottom: 1px solid var(--border-light);
        font-size: 1.2rem;
        font-weight: 800;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--brand-accent);
    }

    .change-row {
        margin-top: 0.75rem;
        display: flex;
        justify-content: space-between;
        font-weight: 700;
        color: var(--text-primary);
        font-size: 0.95rem;
    }

    .receipt-footer {
        text-align: center;
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px dashed var(--border-light);
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    .receipt-footer p {
        margin: 4px 0;
        line-height: 1.4;
    }

    .receipt-actions {
        margin-top: 2rem;
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        justify-content: center;
        width: 100%;
        max-width: 460px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all var(--transition-base, 0.2s ease);
        cursor: pointer;
        font-family: inherit;
        flex: 1;
        min-width: 120px;
    }

    .btn-secondary {
        background: var(--surface);
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
    }

    .btn-secondary:hover {
        background: var(--bg-main);
        color: var(--text-primary);
        border-color: var(--text-muted);
    }

    .btn-primary {
        background: var(--brand-accent);
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background: var(--brand-accent-hover);
    }

    @media print {
        .receipt-actions { 
            display: none; 
        }
        .pos-wrapper { 
            padding: 0; 
            background: transparent; 
        }
        .receipt-card { 
            border: none; 
            box-shadow: none; 
            max-width: 100%; 
            padding: 0;
        }
    }
</style>

<div class="pos-wrapper">
    <div class="receipt-card">
        <div class="receipt-header">
            <h1>TRANSACTION RECEIPT</h1>
            <span class="receipt-id-badge">#<?= htmlspecialchars($sale['sale_id']); ?></span>
        </div>

        <div class="info-grid">
            <div>
                <div class="info-label">Date</div>
                <div class="info-value"><?= date('M d, Y', strtotime($sale['created_at'])); ?></div>
            </div>
            <div style="text-align: right;">
                <div class="info-label">Time</div>
                <div class="info-value"><?= date('h:i A', strtotime($sale['created_at'])); ?></div>
            </div>
            <div>
                <div class="info-label">Cashier</div>
                <div class="info-value"><?= htmlspecialchars($sale['cashier_name'] ?? 'System'); ?></div>
            </div>
            <div style="text-align: right;">
                <div class="info-label">Status</div>
                <div class="info-value" style="color: var(--brand-accent);">PAID</div>
            </div>
        </div>

        <div class="receipt-divider"></div>

        <div class="receipt-items">
            <?php foreach ($sale['items'] as $item): ?>
                <div class="item-row">
                    <div class="item-details">
                        <div class="item-name"><?= htmlspecialchars($item['product_name']); ?></div>
                        <div class="item-meta">
                            <?= htmlspecialchars($item['quantity']); ?> x ₱<?= number_format($item['price'], 2); ?>
                        </div>
                    </div>
                    <div class="item-price" style="font-weight: 700; color: var(--text-primary);">
                        ₱<?= number_format($item['subtotal'], 2); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="receipt-divider"></div>

        <div class="summary-section">
            <div class="summary-row">
                <span>Subtotal</span>
                <span>₱<?= number_format($sale['total_amount'], 2); ?></span>
            </div>
            <div class="summary-row">
                <span>Cash Received</span>
                <span>₱<?= number_format($sale['payment_amount'], 2); ?></span>
            </div>
            <div class="total-row">
                <span>TOTAL</span>
                <span>₱<?= number_format($sale['total_amount'], 2); ?></span>
            </div>
            <div class="change-row">
                <span>CHANGE</span>
                <span>₱<?= number_format($sale['change_amount'], 2); ?></span>
            </div>
        </div>

        <div class="receipt-footer">
            <p>Thank you for your business!</p>
            <p>Please keep this receipt for your records.</p>
        </div>
    </div>

    <div class="receipt-actions">
        <a href="/pos" class="btn btn-primary">New Sale</a>
        <button onclick="window.print()" class="btn btn-secondary">Print Receipt</button>
        <a href="/sales" class="btn btn-secondary">View History</a>
    </div>
</div>