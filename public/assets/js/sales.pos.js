let cart = [];
let allProducts = [];
let filteredProducts = [];

let confirmTimeout = null;

window.addEventListener("DOMContentLoaded", () => {
	if (currentWarehouse) {
		loadProducts(currentWarehouse);
	}
});

function showToast(message, type = "error") {
	const container = document.getElementById("toastContainer");
	const toast = document.createElement("div");
	toast.className = `toast toast-${type}`;
	toast.innerHTML = `<div class="toast-icon">${type === "success" ? "✓" : "✕"}</div><div style="flex-grow:1;line-height:1.4;">${message}</div>`;
	container.appendChild(toast);
	setTimeout(() => {
		toast.style.animation = "fadeOut 0.3s ease forwards";
		setTimeout(() => toast.remove(), 300);
	}, 3500);
}

function syncWarehouse(value, source) {
	if (value === currentWarehouse) return;
	currentWarehouse = value;

	["warehouseSelect", "warehouseSelectCart", "warehouseSelectDrawer"].forEach((id) => {
		const el = document.getElementById(id);
		if (el && el.value !== value) el.value = value;
	});

	cart = [];
	renderCart();

	if (!value) {
		renderProductsState("empty");
		return;
	}

	loadProducts(value);
}

async function loadProducts(warehouseId) {
	renderProductsState("loading");
	try {
		const res = await fetch(`/pos/get-products-by-warehouse?warehouse_id=${warehouseId}`);
		const data = await res.json();
		if (data.success) {
			allProducts = data.products;
			filteredProducts = [...allProducts];
			document.getElementById("productSearch").value = "";
			renderProducts(filteredProducts);
		} else {
			renderProductsState("error");
		}
	} catch {
		showToast("Failed to load products. Please try again.", "error");
		renderProductsState("error");
	}
}

function renderProductsState(state) {
	const list = document.getElementById("productList");
	if (state === "loading") {
		list.innerHTML = `<div class="loading-dots"><span></span><span></span><span></span></div>`;
	} else if (state === "empty") {
		list.innerHTML = `
            <div class="list-state">
                <div><svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
                <p>Select a warehouse to begin</p>
                <small>Products will appear once a warehouse is selected.</small>
            </div>`;
	} else if (state === "no-results") {
		list.innerHTML = `
            <div class="list-state">
                <div><svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                <p>No products found</p>
                <small>Try a different search term.</small>
            </div>`;
	} else {
		list.innerHTML = `
            <div class="list-state">
                <div><svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
                <p>Failed to load products</p>
                <small>Check your connection and try again.</small>
            </div>`;
	}
}

function renderProducts(items) {
	const list = document.getElementById("productList");
	if (!items.length) {
		renderProductsState("no-results");
		return;
	}

	list.innerHTML = items
		.map((p) => {
			const inStock = p.stock > 0;
			const initials = p.name
				.trim()
				.split(" ")
				.slice(0, 2)
				.map((w) => w[0])
				.join("")
				.toUpperCase();

			const escapedName = p.name.replace(/'/g, "\\'");

			const clickAction = inStock
				? `addToCart(${p.id}, '${escapedName}', ${p.unit_cost}, '${p.sku}', ${p.stock})`
				: "void(0)";

			return `
        <div class="product-item ${inStock ? "" : "out-of-stock"}" onclick="${clickAction}">
            <div class="product-mono">${initials}</div>
            <div class="product-info">
                <div class="product-name">${escHtml(p.name)}</div>
                <div class="product-meta">${escHtml(p.sku)}</div>
            </div>
            <div class="product-right">
                <span class="product-price">₱${parseFloat(p.unit_cost).toLocaleString(undefined, { minimumFractionDigits: 2 })}</span>
                <span class="stock-badge ${inStock ? "in" : "out"}">
                    ${inStock ? p.stock + " in stock" : "Out of stock"}
                </span>
            </div>
        </div>`;
		})
		.join("");
}

function handleSearch(query) {
	const q = query.toLowerCase().trim();
	if (!currentWarehouse) return;
	filteredProducts = q
		? allProducts.filter(
				(p) => p.name.toLowerCase().includes(q) || p.sku.toLowerCase().includes(q)
			)
		: [...allProducts];
	renderProducts(filteredProducts);
}

function addToCart(productId, name, price, sku, stock) {
	if (!currentWarehouse) {
		showToast("Please select a source warehouse first", "error");
		return;
	}
	const existing = cart.find((i) => i.product_id === productId);
	if (existing) {
		if (existing.quantity < existing.max) {
			existing.quantity++;
		} else {
			showToast("Maximum stock limit reached", "error");
			return;
		}
	} else {
		cart.push({ product_id: productId, name, price, sku, quantity: 1, max: stock });
		showToast(`${name} added to cart`, "success");
	}
	renderCart();
	popCartCount();
}

function adjustQty(index, delta) {
	const item = cart[index];
	if (!item) return;
	if (delta > 0 && item.quantity < item.max) item.quantity++;
	if (delta < 0 && item.quantity > 1) item.quantity--;
	renderCart();
}

function removeFromCart(index) {
	cart.splice(index, 1);
	renderCart();
}

function popCartCount() {
	["cartCountMain", "cartCountDrawer"].forEach((id) => {
		const el = document.getElementById(id);
		if (el) {
			el.classList.remove("pop");
			void el.offsetWidth;
			el.classList.add("pop");
		}
	});
}

function renderCart() {
	const count = cart.reduce((s, i) => s + i.quantity, 0);
	const total = cart.reduce((s, i) => s + i.price * i.quantity, 0);
	const totalFmt = "₱" + total.toLocaleString(undefined, { minimumFractionDigits: 2 });

	["cartCountMain", "cartCountDrawer"].forEach((id) => {
		const el = document.getElementById(id);
		if (el) el.textContent = count;
	});
	document.getElementById("fabBadge").textContent = count;

	["cartTotalMain", "cartTotalDrawer"].forEach((id) => {
		const el = document.getElementById(id);
		if (el) el.textContent = totalFmt;
	});

	const html =
		cart.length === 0
			? `<div class="cart-empty">
               <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
               <p>Cart is empty</p>
           </div>`
			: cart
					.map(
						(item, i) => `
            <div class="cart-item">
                <div class="cart-item-top">
                    <div>
                        <div class="cart-item-name">${escHtml(item.name)}</div>
                        <div class="cart-item-sku">${escHtml(item.sku)}</div>
                    </div>
                    <button class="btn-remove" onclick="removeFromCart(${i})" title="Remove">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <div class="cart-item-bottom">
                    <div class="qty-controls">
                        <button class="qty-btn" onclick="adjustQty(${i}, -1)">−</button>
                        <span class="qty-display">${item.quantity}</span>
                        <button class="qty-btn" onclick="adjustQty(${i}, 1)">+</button>
                    </div>
                    <span class="cart-item-subtotal">₱${(item.price * item.quantity).toLocaleString(undefined, { minimumFractionDigits: 2 })}</span>
                </div>
            </div>`
					)
					.join("");

	["cartItemsMain", "cartItemsDrawer"].forEach((id) => {
		const el = document.getElementById(id);
		if (el) el.innerHTML = html;
	});

	// Enable/disable checkout
	const hasItems = cart.length > 0;
	["checkoutBtnMain", "checkoutBtnDrawer"].forEach((id) => {
		const btn = document.getElementById(id);
		if (btn) {
			btn.disabled = !hasItems;
			if (!hasItems) {
				btn.textContent = "Complete Transaction";
				btn.className = "checkout-btn";
				btn.disabled = true;
			} else {
				btn.className = "checkout-btn ready";
			}
		}
	});

	recalcChange();
}

function syncPayment(value, source) {
	// Sync both payment inputs
	const other = source === "main" ? "paymentAmountDrawer" : "paymentAmountMain";
	const otherEl = document.getElementById(other);
	if (otherEl && otherEl.value !== value) otherEl.value = value;
	recalcChange();
}

function recalcChange() {
	const total = cart.reduce((s, i) => s + i.price * i.quantity, 0);
	const payment =
		parseFloat(
			document.getElementById("paymentAmountMain")?.value ||
				document.getElementById("paymentAmountDrawer")?.value ||
				"0"
		) || 0;
	const change = payment - total;
	const insufficient = payment > 0 && change < 0;

	const changeFmt =
		"₱" + Math.max(0, change).toLocaleString(undefined, { minimumFractionDigits: 2 });

	["changeAmountMain", "changeAmountDrawer"].forEach((id) => {
		const el = document.getElementById(id);
		if (!el) return;
		el.textContent = changeFmt;
		el.classList.toggle("negative", insufficient);
	});

	["paymentAmountMain", "paymentAmountDrawer"].forEach((id) => {
		const el = document.getElementById(id);
		if (el) el.classList.toggle("input-error", insufficient);
	});

	["paymentErrorMain", "paymentErrorDrawer"].forEach((id) => {
		const el = document.getElementById(id);
		if (el) el.style.display = insufficient ? "block" : "none";
	});
}

async function handleCheckout(source) {
	const total = cart.reduce((s, i) => s + i.price * i.quantity, 0);
	const paymentEl = document.getElementById(
		source === "main" ? "paymentAmountMain" : "paymentAmountDrawer"
	);
	const payment = parseFloat(paymentEl?.value || "0") || 0;
	const btn = document.getElementById(
		source === "main" ? "checkoutBtnMain" : "checkoutBtnDrawer"
	);

	if (payment < total) {
		showToast("Payment amount is insufficient", "error");
		return;
	}

	if (btn.classList.contains("ready")) {
		btn.textContent = "Tap again to confirm";
		btn.className = "checkout-btn confirm";
		clearTimeout(confirmTimeout);
		confirmTimeout = setTimeout(() => {
			btn.textContent = "Complete Transaction";
			btn.className = "checkout-btn ready";
		}, 3500);
		return;
	}

	btn.disabled = true;
	btn.textContent = "Processing…";
	btn.className = "checkout-btn";

	try {
		const cartWithWarehouse = cart.map((item) => ({
			...item,
			warehouse_id: currentWarehouse,
		}));

		const res = await fetch("/pos/checkout", {
			method: "POST",
			headers: { "Content-Type": "application/json" },
			body: JSON.stringify({
				cart: cartWithWarehouse,
				payment,
				warehouse_id: currentWarehouse,
			}),
		});
		const result = await res.json();
		if (result.success) {
			showToast("Transaction completed successfully!", "success");
			setTimeout(() => (window.location.href = "/sales/receipt?id=" + result.sale_id), 1000);
		} else {
			showToast(result.message || "Transaction failed", "error");
			btn.disabled = false;
			btn.textContent = "Complete Transaction";
			btn.className = "checkout-btn ready";
		}
	} catch {
		showToast("Network error. Please try again.", "error");
		btn.disabled = false;
		btn.textContent = "Complete Transaction";
		btn.className = "checkout-btn ready";
	}
}

function openCartDrawer() {
	document.getElementById("cartDrawer").classList.add("open");
	document.getElementById("cartBackdrop").classList.add("show");
	document.body.style.overflow = "hidden";
}

function closeCartDrawer() {
	document.getElementById("cartDrawer").classList.remove("open");
	document.getElementById("cartBackdrop").classList.remove("show");
	document.body.style.overflow = "";
}

document.addEventListener("keydown", (e) => {
	if (e.key === "Escape") closeCartDrawer();
});

function escHtml(str) {
	return String(str)
		.replace(/&/g, "&amp;")
		.replace(/</g, "&lt;")
		.replace(/>/g, "&gt;")
		.replace(/"/g, "&quot;");
}
