document.getElementById("dateFrom").addEventListener("change", applyFilters);
document.getElementById("dateTo").addEventListener("change", applyFilters);

function applyFilters() {
	const dateFrom = document.getElementById("dateFrom").value;
	const dateTo = document.getElementById("dateTo").value;

	const params = new URLSearchParams();
	if (dateFrom) params.append("date_from", dateFrom);
	if (dateTo) params.append("date_to", dateTo);

	fetch("/sales/filter?" + params.toString())
		.then((response) => {
			if (!response.ok) throw new Error("Network response failure.");
			return response.json();
		})
		.then((sales) => renderSales(sales))
		.catch((error) => console.error("Error filtering sales:", error));
}

function renderSales(sales) {
	const tbody = document.getElementById("salesTableBody");

	if (sales.length === 0) {
		tbody.innerHTML = `
            <tr>
                <td colspan="7">
                    <div class="empty-state-container">
                        <div class="empty-state-icon">
                            <svg width="44" height="44" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <h3 class="empty-state-title">No results found</h3>
                        <p class="empty-state-desc">There are no transactions recorded for the selected calendar range.</p>
                    </div>
                </td>
            </tr>
        `;
		return;
	}

	tbody.innerHTML = sales
		.map((sale) => {
			const saleDate = new Date(sale.sale_date);
			const formattedDate =
				saleDate.toLocaleDateString("en-US", {
					month: "short",
					day: "numeric",
					year: "numeric",
				}) +
				" • " +
				saleDate.toLocaleTimeString("en-US", {
					hour: "numeric",
					minute: "2-digit",
					hour12: true,
				});
			const initial = (sale.cashier_name || "S").charAt(0).toUpperCase();

			return `
            <tr>
                <td><span class="monospace-id">#${escapeHtml(sale.sale_id)}</span></td>
                <td style="font-weight: 500; color: var(--text-secondary);">${formattedDate}</td>
                <td>
                    <div class="cashier-badge">
                        <div class="avatar-circle">
                            ${initial}
                        </div>
                        <span style="font-weight: 600; color: var(--text-primary);">${escapeHtml(sale.cashier_name || "System")}</span>
                    </div>
                </td>
                <td class="amount-primary" style="text-align: right;">₱${parseFloat(sale.total_amount || 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td class="amount-secondary" style="text-align: right;">₱${parseFloat(sale.payment_amount || 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td class="amount-accent" style="text-align: right;">₱${parseFloat(sale.change_amount || 0).toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                <td style="text-align: center;">
                    <a href="/sales/view?id=${sale.sale_id}" class="btn-action">
                        View
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                        </svg>
                    </a>
                </td>
            </tr>
        `;
		})
		.join("");
}

function clearFilters() {
	document.getElementById("dateFrom").value = "";
	document.getElementById("dateTo").value = "";
	applyFilters();
}

function escapeHtml(text) {
	const div = document.createElement("div");
	div.textContent = text;
	return div.innerHTML;
}
