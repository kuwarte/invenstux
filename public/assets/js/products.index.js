let productSearchTimeout;
// showInactive is injected by the view as a global before this script runs
// e.g. <script>const showInactive = true;</script>

document.addEventListener("DOMContentLoaded", function () {
	const searchInput = document.getElementById("searchInput");
	const categoryFilter = document.getElementById("categoryFilter");

	if (searchInput) {
		searchInput.addEventListener("input", function () {
			clearTimeout(productSearchTimeout);
			productSearchTimeout = setTimeout(applyFilters, 500);
		});
	}

	if (categoryFilter) {
		categoryFilter.addEventListener("change", applyFilters);
	}
});

function applyFilters() {
	const search = document.getElementById("searchInput")?.value ?? "";
	const categoryId = document.getElementById("categoryFilter")?.value ?? "";

	const params = new URLSearchParams();
	if (search) params.append("search", search);
	if (categoryId) params.append("category_id", categoryId);
	if (typeof showInactive !== "undefined" && showInactive) params.append("show_inactive", "1");

	fetch("/products/filter?" + params.toString())
		.then((response) => response.json())
		.then((products) => renderProducts(products))
		.catch((error) => console.error("Product filter error:", error));
}

function renderProducts(products) {
	const tbody = document.getElementById("productTableBody");
	const countEl = document.getElementById("productCount");

	if (countEl) countEl.textContent = products.length + " items";

	if (products.length === 0) {
		tbody.innerHTML = `
			<tr>
				<td colspan="7">
					<div class="empty-state">
						<div class="empty-icon">
							<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
						</div>
						<h3>No Products Found</h3>
						<p class="text-secondary">No products match your search criteria.</p>
					</div>
				</td>
			</tr>`;
		return;
	}

	tbody.innerHTML = products
		.map(
			(product) => `
		<tr>
			<td><span class="sku-code">${escapeHtml(product.sku)}</span></td>
			<td style="font-weight: 600;">${escapeHtml(product.name || "")}</td>
			<td style="color: var(--text-secondary);">${escapeHtml(product.category_name || "—")}</td>
			<td style="font-weight: 600;">₱${parseFloat(product.unit_cost).toFixed(2)}</td>
			<td style="color: var(--text-secondary);">${escapeHtml(product.unit_of_measure || "")}</td>
			<td>
				${product.is_active
					? '<span class="status-badge badge-active">Active</span>'
					: '<span class="status-badge badge-inactive">Inactive</span>'}
			</td>
			<td class="text-right">
				<a href="/products/update?id=${product.id}" class="btn-icon btn-edit" title="Edit">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
				</a>
			</td>
		</tr>`
		)
		.join("");
}

function clearFilters() {
	const searchInput = document.getElementById("searchInput");
	const categoryFilter = document.getElementById("categoryFilter");
	if (searchInput) searchInput.value = "";
	if (categoryFilter) categoryFilter.value = "";
	applyFilters();
}

function escapeHtml(text) {
	const div = document.createElement("div");
	div.textContent = String(text);
	return div.innerHTML;
}
