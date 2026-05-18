let warehouseSearchTimeout;
// showInactive is injected by the view as a global before this script runs

document.addEventListener("DOMContentLoaded", function () {
	const searchInput = document.getElementById("searchInput");
	if (searchInput) {
		searchInput.addEventListener("input", function () {
			clearTimeout(warehouseSearchTimeout);
			warehouseSearchTimeout = setTimeout(applyFilters, 500);
		});
	}
});

function applyFilters() {
	const search = document.getElementById("searchInput")?.value ?? "";

	const params = new URLSearchParams();
	if (search) params.append("search", search);
	if (typeof showInactive !== "undefined" && showInactive) params.append("show_inactive", "1");

	fetch("/warehouses/filter?" + params.toString())
		.then((response) => response.json())
		.then((warehouses) => renderWarehouses(warehouses))
		.catch((error) => console.error("Warehouse filter error:", error));
}

function renderWarehouses(warehouses) {
	const tbody = document.getElementById("warehouseTableBody");
	const countEl = document.getElementById("warehouseCount");

	if (countEl) countEl.textContent = warehouses.length + " locations";

	if (warehouses.length === 0) {
		tbody.innerHTML = `
			<tr>
				<td colspan="5">
					<div class="empty-state">
						<div class="empty-icon">
							<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
						</div>
						<h3>No Warehouses Found</h3>
						<p class="text-secondary">No warehouses match your search criteria.</p>
					</div>
				</td>
			</tr>`;
		return;
	}

	tbody.innerHTML = warehouses
		.map(
			(warehouse) => `
		<tr>
			<td style="font-weight: 600;">${escapeHtml(warehouse.name)}</td>
			<td style="color: var(--text-secondary);">${escapeHtml(warehouse.location || "—")}</td>
			<td>${escapeHtml(warehouse.manager_name || "Unassigned")}</td>
			<td>
				${warehouse.is_active
					? '<span class="status-badge badge-active">Active</span>'
					: '<span class="status-badge badge-inactive">Inactive</span>'}
			</td>
			<td class="text-right">
				<a href="/warehouses/update?id=${warehouse.id}" class="btn-icon btn-edit" title="Edit">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
				</a>
			</td>
		</tr>`
		)
		.join("");
}

function clearFilters() {
	const searchInput = document.getElementById("searchInput");
	if (searchInput) searchInput.value = "";
	applyFilters();
}

function escapeHtml(text) {
	const div = document.createElement("div");
	div.textContent = String(text);
	return div.innerHTML;
}
