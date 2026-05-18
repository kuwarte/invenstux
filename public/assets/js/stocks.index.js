let stockSearchTimeout;

document.addEventListener("DOMContentLoaded", function () {
	const searchFilter = document.getElementById("searchFilter");
	if (searchFilter) {
		searchFilter.addEventListener("input", function () {
			clearTimeout(stockSearchTimeout);
			stockSearchTimeout = setTimeout(applyFilters, 500);
		});
	}
});

async function applyFilters() {
	const warehouseId = document.getElementById("warehouseFilter")?.value ?? "";
	const status = document.getElementById("statusFilter")?.value ?? "";
	const search = document.getElementById("searchFilter")?.value ?? "";

	const params = new URLSearchParams();
	if (warehouseId) params.append("warehouse_id", warehouseId);
	if (status) params.append("status", status);
	if (search) params.append("search", search);

	try {
		const response = await fetch("/stocks/filter?" + params.toString());
		const data = await response.json();

		if (data.success) {
			renderStockItems(data.stockItems);
			const countEl = document.getElementById("recordCount");
			if (countEl) countEl.textContent = data.stockItems.length + " records";
		}
	} catch (error) {
		console.error("Stock filter error:", error);
	}
}

function renderStockItems(items) {
	const container = document.getElementById("ledgerItems");

	if (items.length === 0) {
		container.innerHTML = `
			<div style="padding: 2rem; text-align: center; color: var(--text-muted);">
				<p>No stock items found. Try adjusting your filters.</p>
			</div>`;
		return;
	}

	container.innerHTML = items
		.map((item) => {
			const isCritical = item.quantity <= item.min_stock;
			const isFull = item.quantity >= item.max_stock;
			const statusBadge = isCritical
				? '<span class="badge badge-danger">Critical</span>'
				: isFull
					? '<span class="badge badge-warning">Full</span>'
					: '<span class="badge badge-success">Optimal</span>';

			return `
			<div class="ledger-item">
				<div class="ledger-product">
					<div class="ledger-product-name">${escapeHtml(item.product_name)}</div>
					<div class="ledger-product-sku">SKU: ${escapeHtml(item.sku || "N/A")}</div>
				</div>
				<div class="ledger-warehouse">${escapeHtml(item.warehouse_name)}</div>
				<div class="ledger-quantity">
					<div class="ledger-quantity-value ${isCritical ? "ledger-quantity-critical" : "ledger-quantity-optimal"}">
						${Number(item.quantity).toLocaleString()}
					</div>
				</div>
				<div class="ledger-thresholds">
					<div class="ledger-threshold-label">Min / Max</div>
					<div class="ledger-threshold-values">${item.min_stock} / ${item.max_stock}</div>
				</div>
				<div class="ledger-status">
					${statusBadge}
				</div>
			</div>`;
		})
		.join("");
}

function clearFilters() {
	const warehouseFilter = document.getElementById("warehouseFilter");
	const statusFilter = document.getElementById("statusFilter");
	const searchFilter = document.getElementById("searchFilter");
	if (warehouseFilter) warehouseFilter.value = "";
	if (statusFilter) statusFilter.value = "";
	if (searchFilter) searchFilter.value = "";
	applyFilters();
}

function escapeHtml(text) {
	const div = document.createElement("div");
	div.textContent = String(text);
	return div.innerHTML;
}
