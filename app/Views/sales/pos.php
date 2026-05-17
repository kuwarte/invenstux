<style>
.pos-wrapper {
	width: 100%;
	margin: 0;
	padding: 12px;
	color: var(--text-primary);
	font-family: var(--font-family);
	animation: dashIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
}

@media (min-width: 769px) {
    .pos-wrapper {
        max-width: 1440px;
        margin: 0 auto;
        padding: 24px; 
    }
}

@keyframes dashIn {
	from {
		opacity: 0;
		transform: translateY(12px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to   { transform: translateX(0); opacity: 1; }
}

@keyframes fadeOut {
    to { opacity: 0; transform: translateY(10px); }
}

@keyframes cartItemIn {
    from { opacity: 0; transform: translateX(-8px); }
    to   { opacity: 1; transform: translateX(0); }
}

@keyframes cartDrawerUp {
    from { transform: translateY(100%); }
    to   { transform: translateY(0); }
}

@keyframes badgePop {
    0%   { transform: scale(1); }
    50%  { transform: scale(1.4); }
    100% { transform: scale(1); }
}

@keyframes dotPulse {
    0%, 80%, 100% { transform: scale(0.7); background: var(--border-light); }
    40% { transform: scale(1); background: var(--brand-accent); }
}


.pos-layout {
    display: grid;
    grid-template-columns: 1fr var(--cart-width, 360px);
    gap: 1.25rem;
    align-items: start;
    min-height: calc(100vh - 180px);
}

.catalog-panel {
    display: flex; 
    flex-direction: column; 
    gap: 0;
    background: var(--surface);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.catalog-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border-light);
    display: flex; 
    align-items: center;
    justify-content: space-between; 
    gap: 1rem;
    flex-wrap: wrap;
    background: var(--surface);
    position: sticky; 
    top: 0; 
    z-index: 2;
}

.catalog-title {
    font-size: 0.95rem; 
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
}

.warehouse-select-wrap {
    display: flex; 
    align-items: center; 
    gap: 8px;
    flex: 1; 
    max-width: 260px;
}

.warehouse-select-wrap label {
    font-size: 0.78rem; 
    font-weight: 500;
    color: var(--text-secondary); 
    white-space: nowrap;
}

.catalog-search {
    padding: 0.75rem 1.25rem;
    border-bottom: 1px solid var(--border-light);
    background: var(--input-bg);
}

.search-wrapper { 
    position: relative; 
}

.search-wrapper svg {
    position: absolute; 
    left: 11px; 
    top: 50%;
    transform: translateY(-50%); 
    color: var(--text-muted);
    pointer-events: none;
}

.search-wrapper input { 
    padding-left: 2.4rem; 
}

.product-list {
    max-height: calc(100vh - 280px);
    overflow-y: auto;
    overscroll-behavior: contain;
}

.product-item {
    display: flex; 
    align-items: center;
    justify-content: space-between;
    padding: 0.85rem 1.25rem;
    border-bottom: 1px solid var(--border-light);
    cursor: pointer;
    transition: background var(--transition-base);
    gap: 12px;
}

.product-item:last-child { 
    border-bottom: none; 
}

.product-item:hover { 
    background: var(--input-bg); 
}

.product-item.out-of-stock { 
    opacity: 0.55; 
    cursor: not-allowed; 
}

.product-mono {
    width: 36px; 
    height: 36px; 
    border-radius: var(--radius-sm);
    background: var(--brand-accent-light); 
    color: var(--brand-accent-dark);
    display: flex; 
    align-items: center; 
    justify-content: center;
    font-size: 0.7rem; 
    font-weight: 700; 
    flex-shrink: 0;
    letter-spacing: 0.02em;
}

.product-info { 
    flex: 1; 
    min-width: 0; 
}

.product-name {
    font-size: 0.875rem; 
    font-weight: 500;
    color: var(--text-primary);
    white-space: nowrap; 
    overflow: hidden; 
    text-overflow: ellipsis;
}

.product-meta {
    font-size: 0.75rem; 
    color: var(--text-secondary); 
    margin-top: 2px;
}

.product-right {
    display: flex; 
    flex-direction: column;
    align-items: flex-end; 
    gap: 4px; 
    flex-shrink: 0;
}

.product-price {
    font-size: 0.875rem; 
    font-weight: 600;
    color: var(--text-primary);
}

.stock-badge {
    font-size: 0.7rem; 
    font-weight: 600;
    padding: 2px 7px; 
    border-radius: 20px;
}

.stock-badge.in { 
    background: var(--brand-accent-light); 
    color: var(--brand-accent-dark); 
}

.stock-badge.out { 
    background: var(--error-bg); 
    color: var(--error-text); 
}

.product-item:not(.out-of-stock):active {
    background: var(--brand-accent-light);
    transition: background 0s;
}

.list-state {
    padding: 3rem 1.5rem;
    text-align: center;
    color: var(--text-secondary);
}

.list-state svg { 
    opacity: 0.25; 
    margin-bottom: 0.75rem; 
}

.list-state p { 
    font-size: 0.875rem; 
    font-weight: 500; 
    color: var(--text-primary); 
    margin: 0 0 4px; 
}

.list-state small { 
    font-size: 0.8rem; 
}

.loading-dots {
    display: inline-flex; 
    gap: 5px; 
    padding: 3rem;
    align-items: center; 
    justify-content: center; 
    width: 100%;
}

.loading-dots span {
    width: 7px; 
    height: 7px; 
    border-radius: 50%;
    background: var(--border-light);
    animation: dotPulse 1.2s ease-in-out infinite;
}

.loading-dots span:nth-child(2) { animation-delay: 0.2s; }
.loading-dots span:nth-child(3) { animation-delay: 0.4s; }

.cart-panel {
    position: sticky; 
    top: 1rem;
    background: var(--surface);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    display: flex; 
    flex-direction: column;
}

.cart-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border-light);
    display: flex; 
    align-items: center; 
    justify-content: space-between;
}

.cart-title { 
    font-size: 0.95rem; 
    font-weight: 600; 
    color: var(--text-primary); 
}

.cart-count {
    font-size: 0.75rem; 
    font-weight: 600;
    background: var(--brand-accent-light); 
    color: var(--brand-accent-dark);
    padding: 2px 9px; 
    border-radius: 20px;
    min-width: 24px; 
    text-align: center;
    transition: transform var(--transition-base);
}

.cart-count.pop { 
    animation: badgePop 0.3s ease; 
}

.cart-warehouse {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border-light);
    background: var(--input-bg);
}

.form-label-sm {
    display: block; 
    font-size: 0.75rem;
    font-weight: 500; 
    color: var(--text-secondary);
    margin-bottom: 5px;
}

.cart-items {
    flex: 1;
    max-height: 320px;
    overflow-y: auto;
    overscroll-behavior: contain;
}

.cart-item {
    padding: 0.85rem 1.25rem;
    border-bottom: 1px solid var(--border-light);
    animation: cartItemIn 0.2s ease-out;
}

.cart-item:last-child { 
    border-bottom: none; 
}

.cart-item-top {
    display: flex; 
    align-items: flex-start;
    justify-content: space-between; 
    gap: 8px;
    margin-bottom: 8px;
}

.cart-item-name {
    font-size: 0.875rem; 
    font-weight: 500;
    color: var(--text-primary); 
    line-height: 1.3;
    flex: 1;
}

.cart-item-sku {
    font-size: 0.72rem; 
    color: var(--text-muted);
    margin-top: 2px;
}

.btn-remove {
    color: var(--text-secondary); 
    background: transparent; 
    border: none;
    cursor: pointer; 
    padding: 3px; 
    border-radius: var(--radius-sm);
    line-height: 1; 
    font-size: 1rem; 
    flex-shrink: 0;
    display: flex; 
    align-items: center; 
    justify-content: center;
    width: 22px; 
    height: 22px;
    transition: all var(--transition-base);
}

.btn-remove:hover { 
    color: var(--error-text); 
    background: var(--error-bg); 
}

.cart-item-bottom {
    display: flex; 
    align-items: center;
    justify-content: space-between;
}

.qty-controls {
    display: flex; 
    align-items: center; 
    gap: 8px;
}

.qty-btn {
    width: 26px; 
    height: 26px;
    border: 1px solid var(--border-light);
    background: var(--surface);
    border-radius: var(--radius-sm);
    cursor: pointer; 
    font-weight: 600;
    color: var(--text-primary); 
    font-size: 0.9rem;
    display: flex; 
    align-items: center; 
    justify-content: center;
    transition: all var(--transition-base); 
    font-family: inherit;
}

.qty-btn:hover { 
    background: var(--input-bg); 
    border-color: var(--text-muted); 
}

.qty-display {
    font-size: 0.875rem; 
    font-weight: 600;
    color: var(--text-primary);
    min-width: 22px; 
    text-align: center;
}

.cart-item-subtotal {
    font-size: 0.875rem; 
    font-weight: 600;
    color: var(--text-primary);
}

.cart-empty {
    padding: 2.5rem 1.5rem;
    text-align: center; 
    color: var(--text-secondary);
}

.cart-empty svg { 
    opacity: 0.2; 
    margin-bottom: 0.5rem; 
}

.cart-empty p { 
    font-size: 0.85rem; 
    margin: 0; 
}

.cart-summary {
    padding: 1.25rem;
    border-top: 1px solid var(--border-light);
}

.summary-row {
    display: flex; 
    justify-content: space-between; 
    align-items: center;
    margin-bottom: 0.85rem;
}

.summary-label { 
    font-size: 0.85rem; 
    color: var(--text-secondary); 
    font-weight: 500; 
}

.summary-total {
    font-size: 1.4rem; 
    font-weight: 700;
    color: var(--text-primary); 
    letter-spacing: -0.02em;
}

.payment-row { 
    margin-bottom: 0.85rem; 
}

.peso-input-wrap { 
    position: relative; 
}

.peso-symbol {
    position: absolute; 
    left: 11px; 
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.1rem; 
    font-weight: 600; 
    color: var(--text-secondary);
    pointer-events: none;
}

.peso-input-wrap input {
    padding-left: 1.75rem;
    font-size: 1rem; 
    font-weight: 600;
}

.change-row {
    display: flex; 
    justify-content: space-between; 
    align-items: center;
    padding: 0.85rem 1rem;
    background: var(--input-bg);
    border-radius: var(--radius-md);
    margin-bottom: 1rem;
}

.change-label { 
    font-size: 0.85rem; 
    font-weight: 500; 
    color: var(--text-primary); 
}

.change-amount {
    font-size: 1.15rem; 
    font-weight: 700;
    color: var(--brand-accent);
    transition: color var(--transition-base);
}

.change-amount.negative { 
    color: var(--error-text); 
}

.checkout-btn {
    width: 100%; 
    padding: 0.8rem 1rem;
    font-size: 0.95rem; 
    font-weight: 600;
    border-radius: var(--radius-sm); 
    border: none;
    cursor: pointer; 
    font-family: inherit;
    transition: all var(--transition-base);
    display: flex; 
    align-items: center; 
    justify-content: center; 
    gap: 8px;
}

.checkout-btn.ready { 
    background: var(--brand-accent); 
    color: white; 
}

.checkout-btn.ready:hover { 
    background: var(--brand-accent-hover); 
    box-shadow: var(--shadow-glow);
}

.checkout-btn.confirm { 
    background: var(--text-primary); 
    color: var(--surface); 
}

.checkout-btn.confirm:hover { 
    opacity: 0.9;
}

.checkout-btn:disabled { 
    background: var(--border-light); 
    color: var(--text-disabled); 
    cursor: not-allowed; 
}

.input-error { 
    border-color: var(--error-border) !important; 
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important; 
}

.error-msg { 
    color: var(--error-text); 
    font-size: 0.72rem; 
    margin-top: 5px; 
    display: none; 
    font-weight: 500; 
}

.btn-white { 
    background: var(--surface); 
    border: 1px solid var(--border-light); 
    color: var(--text-primary); 
}

.btn-white:hover { 
    background: var(--input-bg); 
    border-color: var(--text-muted); 
}

.form-input {
    width: 100%; 
    padding: 0.6rem 0.9rem;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-light);
    font-family: inherit; 
    font-size: 0.875rem;
    color: var(--text-primary); 
    background: var(--surface);
    transition: all var(--transition-base); 
    box-sizing: border-box;
}

.form-input:focus { 
    outline: none; 
    border-color: var(--brand-accent); 
    box-shadow: 0 0 0 3px var(--brand-accent-light); 
}

.form-input::placeholder { 
    color: var(--text-muted); 
}

.toast-container {
    position: fixed; 
    bottom: 24px; 
    right: 24px; 
    z-index: 9999;
    display: flex; 
    flex-direction: column; 
    gap: 10px;
    pointer-events: none;
}

.toast {
    min-width: 280px; 
    padding: 14px 16px;
    border-radius: var(--radius-md); 
    background: var(--surface);
    border: 1px solid var(--border-light); 
    box-shadow: var(--shadow-md);
    display: flex; 
    align-items: flex-start; 
    gap: 10px;
    font-size: 0.875rem; 
    font-weight: 500; 
    color: var(--text-primary);
    animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.toast-success { border-left: 4px solid var(--brand-accent); }
.toast-error   { border-left: 4px solid var(--error-text); }

.toast-icon {
    flex-shrink: 0; 
    width: 18px; 
    height: 18px; 
    border-radius: 50%;
    display: flex; 
    align-items: center; 
    justify-content: center;
    color: white; 
    font-size: 10px; 
    margin-top: 1px;
}

.toast-success .toast-icon { background: var(--brand-accent); }
.toast-error   .toast-icon { background: var(--error-text); }

.cart-fab {
    display: none;
    position: fixed; 
    bottom: 24px; 
    left: 50%;
    transform: translateX(-50%);
    z-index: 100;
    background: var(--brand-accent); 
    color: white;
    border: none; 
    border-radius: 40px;
    padding: 0.75rem 1.5rem;
    font-family: inherit; 
    font-size: 0.9rem; 
    font-weight: 600;
    cursor: pointer; 
    box-shadow: var(--shadow-glow);
    align-items: center; 
    gap: 10px;
    transition: background var(--transition-base), transform var(--transition-base);
    white-space: nowrap;
}

.cart-fab:active { 
    transform: translateX(-50%) scale(0.97); 
}

.cart-fab .fab-badge {
    background: var(--surface); 
    color: var(--brand-accent);
    width: 22px; 
    height: 22px; 
    border-radius: 50%;
    font-size: 0.75rem; 
    font-weight: 700;
    display: flex; 
    align-items: center; 
    justify-content: center;
}

.cart-drawer-backdrop {
    display: none; 
    position: fixed; 
    inset: 0;
    background: rgba(15, 23, 42, 0.5);
    z-index: 200;
    backdrop-filter: blur(2px);
}

.cart-drawer-backdrop.show { 
    display: block; 
}

.cart-drawer {
    position: fixed; 
    bottom: 0; 
    left: 0; 
    right: 0;
    z-index: 201;
    background: var(--surface);
    border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    box-shadow: 0 -8px 30px rgba(0,0,0,0.12);
    transform: translateY(100%);
    transition: transform var(--transition-base);
    display: flex; 
    flex-direction: column;
    max-height: 90vh;
}

.cart-drawer.open { 
    transform: translateY(0); 
}

.drawer-handle {
    width: 36px; 
    height: 4px; 
    background: var(--border-light);
    border-radius: 2px; 
    margin: 10px auto 0;
    flex-shrink: 0;
}

.drawer-header {
    display: flex; 
    align-items: center; 
    justify-content: space-between;
    padding: 1rem 1.25rem 0.75rem;
    border-bottom: 1px solid var(--border-light);
    flex-shrink: 0;
}

.drawer-close {
    width: 28px; 
    height: 28px; 
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-light); 
    background: transparent;
    cursor: pointer; 
    color: var(--text-secondary);
    display: flex; 
    align-items: center; 
    justify-content: center;
    font-family: inherit; 
    transition: all var(--transition-base);
}

.drawer-close:hover { 
    background: var(--input-bg); 
}

.drawer-body { 
    overflow-y: auto; 
    flex: 1; 
    overscroll-behavior: contain; 
}

.drawer-footer { 
    flex-shrink: 0; 
    padding: 1.25rem; 
    border-top: 1px solid var(--border-light); 
}

@media (max-width: 900px) {
    :root { --cart-width: 320px; }
}

@media (max-width: 768px) {
    .pos-layout {
        grid-template-columns: 1fr;
    }
    .cart-panel { display: none; } 
    .cart-fab { display: flex; }
    .product-list { max-height: calc(100vh - 240px); }
}

@media (max-width: 480px) {
    .catalog-header { flex-direction: column; align-items: flex-start; }
    .warehouse-select-wrap { max-width: 100%; width: 100%; }
}

.text-muted { color: var(--text-muted); font-size: 0.85rem; }
.text-primary { color: var(--brand-accent); }
.flex-between { display: flex; justify-content: space-between; align-items: center; }
</style>


<div id="toastContainer" class="toast-container"></div>

<div class="cart-drawer-backdrop" id="cartBackdrop" onclick="closeCartDrawer()"></div>

<!-- Mobile Cart Drawer -->
<div class="cart-drawer" id="cartDrawer">
    <div class="drawer-handle"></div>
    <div class="drawer-header">
        <div style="display:flex; align-items:center; gap:10px;">
            <span class="cart-title">Current Cart</span>
            <span class="cart-count" id="cartCountDrawer">0</span>
        </div>
        <button class="drawer-close" onclick="closeCartDrawer()">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    <div class="drawer-body">
        <!-- Branch selector inside drawer on mobile -->
        <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--border-light); background: var(--bg-main);">
            <label class="form-label-sm">Store Branch</label>
            <select id="warehouseSelectDrawer" class="form-input" onchange="syncWarehouse(this.value, 'drawer')">
                <option value="">Select Location...</option>
                <?php if (isset($warehouses) && !empty($warehouses)): ?>
                    <?php foreach ($warehouses as $warehouse): ?>
                        <option value="<?= $warehouse['id'] ?>" <?= (isset($smartWarehouse) && $smartWarehouse['id'] == $warehouse['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($warehouse['name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div id="cartItemsDrawer" style="padding: 0;">
            <div class="cart-empty">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <p>Cart is empty</p>
            </div>
        </div>
    </div>
    <div class="drawer-footer">
        <div class="summary-row" style="margin-bottom: 0.75rem;">
            <span class="summary-label">Total Amount</span>
            <span class="summary-total" id="cartTotalDrawer">₱0.00</span>
        </div>
        <div class="payment-row">
            <label class="form-label-sm">Amount Received</label>
            <div class="peso-input-wrap">
                <span class="peso-symbol">₱</span>
                <input type="number" id="paymentAmountDrawer" class="form-input" step="0.01" placeholder="0.00" oninput="syncPayment(this.value, 'drawer')">
            </div>
            <div id="paymentErrorDrawer" class="error-msg">Insufficient amount provided</div>
        </div>
        <div class="change-row" style="margin-bottom: 0.85rem;">
            <span class="change-label">Change</span>
            <span class="change-amount" id="changeAmountDrawer">₱0.00</span>
        </div>
        <button id="checkoutBtnDrawer" class="checkout-btn" disabled onclick="handleCheckout('drawer')">Pay & Print Receipt</button>
    </div>
</div>

<!-- Main POS Wrapper -->
<div class="pos-wrapper">
<div class="fade-in">

    <header class="page-header">
        <div class="page-header-group">
            <h1 class="page-title">Point of Sale</h1>
            <p class="text-secondary">Record sales, accept payments, and track inventory.</p>
        </div>
    </header>

    <div class="pos-layout">

        <!-- Product Catalog Side -->
        <div class="catalog-panel">
            <div class="catalog-header">
                <span class="catalog-title">Available Products</span>
                <div class="warehouse-select-wrap">
                    <label for="warehouseSelect">Location</label>
                    <select id="warehouseSelect" class="form-input" onchange="syncWarehouse(this.value, 'main')">
                        <option value="">Select Branch...</option>
                        <?php if (isset($warehouses) && !empty($warehouses)): ?>
                            <?php foreach ($warehouses as $warehouse): ?>
                                <option value="<?= $warehouse['id'] ?>" <?= (isset($smartWarehouse) && $smartWarehouse['id'] == $warehouse['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($warehouse['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="catalog-search">
                <div class="search-wrapper">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" id="productSearch" class="form-input" placeholder="Search by name or SKU…" oninput="handleSearch(this.value)">
                </div>
            </div>

            <div class="product-list" id="productList">
                <div class="list-state">
                    <div><svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
                    <p>Select a branch or storage location</p>
                    <small>Items will load once a location is chosen.</small>
                </div>
            </div>
        </div>

        <!-- Desktop Cart Panel -->
        <div class="cart-panel">
            <div class="cart-header">
                <span class="cart-title">Current Cart</span>
                <span class="cart-count" id="cartCountMain">0</span>
            </div>

            <div class="cart-warehouse">
                <label class="form-label-sm">Store Branch</label>
                <select id="warehouseSelectCart" class="form-input" onchange="syncWarehouse(this.value, 'cart')">
                    <option value="">Select Location...</option>
                    <?php if (isset($warehouses) && !empty($warehouses)): ?>
                        <?php foreach ($warehouses as $warehouse): ?>
                            <option value="<?= $warehouse['id'] ?>" <?= (isset($smartWarehouse) && $smartWarehouse['id'] == $warehouse['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($warehouse['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="cart-items" id="cartItemsMain">
                <div class="cart-empty">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    <p>Cart is empty</p>
                </div>
            </div>

            <div class="cart-summary">
                <div class="summary-row">
                    <span class="summary-label">Total Amount</span>
                    <span class="summary-total" id="cartTotalMain">₱0.00</span>
                </div>
                <div class="payment-row">
                    <label class="form-label-sm" style="margin-bottom:5px;">Amount Received</label>
                    <div class="peso-input-wrap">
                        <span class="peso-symbol">₱</span>
                        <input type="number" id="paymentAmountMain" class="form-input" step="0.01" placeholder="0.00" oninput="syncPayment(this.value, 'main')">
                    </div>
                    <div id="paymentErrorMain" class="error-msg">Insufficient cash provided</div>
                </div>
                <div class="change-row">
                    <span class="change-label">Change</span>
                    <span class="change-amount" id="changeAmountMain">₱0.00</span>
                </div>
                <button id="checkoutBtnMain" class="checkout-btn" disabled onclick="handleCheckout('main')">Pay & Print Receipt</button>
            </div>
        </div>

    </div><!-- /.pos-layout -->

</div><!-- /.fade-in -->
</div>

<!-- Mobile FAB -->
<button class="cart-fab" id="cartFab" onclick="openCartDrawer()">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
    View Cart
    <span class="fab-badge" id="fabBadge">0</span>
</button>

<script>
    let currentWarehouse = <?= isset($smartWarehouse) ? "'" . $smartWarehouse['id'] . "'" : "''" ?>;
</script>