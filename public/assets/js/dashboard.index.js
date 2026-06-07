window.revenueChartInstance = null;
window.productsPieChartInstance = null;

const THEME = {
	colors: {
		palette: ["#065f46", "#059669", "#10b981", "#34d399", "#6ee7b7"],
		primary: "#10b981",
		target: "#e5e7eb",
	},
};

const rangeUiConfigs = {
	today: {
		orderLabel: "Orders (Today)",
		revenueLabel: "Revenue (Today)",
		pillText: "Today",
		targetText: "Target: ₱20,000.00",
		targetVal: 20000.0,
		chartLabel: "Actual Revenue Today",
	},
	"7days": {
		orderLabel: "Orders (Last 7 Days)",
		revenueLabel: "Revenue (Last 7 Days)",
		pillText: "7 Days",
		targetText: "Target: ₱140,000.00",
		targetVal: 140000.0,
		chartLabel: "Actual Revenue (7 Days)",
	},
	"30days": {
		orderLabel: "Orders (Last 30 Days)",
		revenueLabel: "Revenue (Last 30 Days)",
		pillText: "30 Days",
		targetText: "Target: ₱600,000.00",
		targetVal: 600000.0,
		chartLabel: "Actual Revenue (30 Days)",
	},
};

const formatCurrency = (val) =>
	`₱${parseFloat(val || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
const formatNumber = (val) => parseInt(val || 0).toLocaleString();
const escapeHtml = (str) =>
	String(str || "").replace(
		/[&<>"']/g,
		(m) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;" })[m]
	);

const togglePdfModal = (show) => {
	const modal = document.getElementById("exportModal");
	if (modal) modal.classList.toggle("active", show);
};

window.openExportModal  = () => togglePdfModal(true);
window.closeExportModal = () => togglePdfModal(false);

// Keep old names as aliases so any stray references don't break
window.openPdfModal  = window.openExportModal;
window.closePdfModal = window.closeExportModal;

window.runExport = () => {
	const format  = document.querySelector('input[name="exportFormat"]:checked')?.value  ?? 'csv';
	const dataset = document.querySelector('input[name="exportDataset"]:checked')?.value ?? 'top_products';
	const d       = window.dashboardData || {};
	const range   = d.range || 'today';
	const ts      = new Date().toISOString().slice(0, 10);

	let rows = [];
	let filename = '';

	if (dataset === 'top_products') {
		filename = `top_products_${range}_${ts}`;
		rows = (d.topProducts || []).map((p, i) => ({
			rank:          i + 1,
			name:          p.name        ?? '',
			sku:           p.sku         ?? '',
			units_sold:    p.total_sold  ?? 0,
			total_revenue: p.total_revenue ?? 0,
		}));
	} else if (dataset === 'low_stock') {
		filename = `inventory_risk_${ts}`;
		rows = (d.lowStockItems || []).map(item => ({
			product:       item.product_name  ?? '',
			sku:           item.sku           ?? '',
			warehouse:     item.warehouse_name ?? '',
			current_stock: item.quantity       ?? 0,
			min_threshold: item.minimum_threshold ?? 0,
			status:        item.stock_status  ?? 'CRITICAL',
		}));
	} else {
		filename = `dashboard_summary_${range}_${ts}`;
		rows = [{
			range:           range,
			total_products:  d.totalProducts  ?? 0,
			total_warehouses:d.totalWarehouses ?? 0,
			total_sales:     d.totalSales      ?? 0,
			total_revenue:   d.totalRevenue    ?? 0,
			target_revenue:  d.targetRevenue   ?? 0,
			exported_at:     new Date().toISOString(),
		}];
	}

	if (format === 'csv') {
		downloadCsv(rows, filename + '.csv');
	} else {
		downloadJson(rows, filename + '.json');
	}

	closeExportModal();
};

function downloadCsv(rows, filename) {
	if (!rows.length) return;
	const headers = Object.keys(rows[0]);
	const escape  = (v) => {
		const s = String(v ?? '');
		return s.includes(',') || s.includes('"') || s.includes('\n')
			? '"' + s.replace(/"/g, '""') + '"'
			: s;
	};
	const csv = [headers.join(','), ...rows.map(r => headers.map(h => escape(r[h])).join(','))].join('\r\n');
	triggerDownload('data:text/csv;charset=utf-8,' + encodeURIComponent(csv), filename);
}

function downloadJson(rows, filename) {
	const json = JSON.stringify(rows, null, 2);
	triggerDownload('data:application/json;charset=utf-8,' + encodeURIComponent(json), filename);
}

function triggerDownload(href, filename) {
	const a = document.createElement('a');
	a.href     = href;
	a.download = filename;
	a.style.display = 'none';
	document.body.appendChild(a);
	a.click();
	document.body.removeChild(a);
}

document.addEventListener("DOMContentLoaded", () => {
	document.getElementById("exportBtn")?.addEventListener("click", openExportModal);
	document.getElementById("exportModal")?.addEventListener("click", (e) => {
		if (e.target.id === "exportModal") closeExportModal();
	});

	if (typeof Chart === "undefined" || !window.dashboardData) return;

	const revCtx = document.getElementById("revenueChart")?.getContext("2d");
	if (revCtx) {
		window.revenueChartInstance = new Chart(revCtx, {
			type: "bar",
			data: {
				labels: ["Revenue Performance"],
				datasets: [
					{
						label: "Actual Revenue",
						data: [window.dashboardData.totalRevenue || 0],
						backgroundColor: THEME.colors.primary,
						borderRadius: 6,
					},
					{
						label: "Target",
						data: [window.dashboardData.targetRevenue || 0],
						backgroundColor: THEME.colors.target,
						borderRadius: 6,
					},
				],
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				scales: { y: { beginAtZero: true } },
				plugins: { legend: { position: "top" } },
			},
		});
	}

	const pieCtx = document.getElementById("productsPieChart")?.getContext("2d");
	if (pieCtx) {
		let pieLabels = window.dashboardData.topProductsLabels || [];
		let pieData = window.dashboardData.topProductsSold || [];
		let pieColors = THEME.colors.palette;

		if (pieData.length === 0 || pieData.every((val) => val === 0)) {
			pieLabels = ["No Data Available"];
			pieData = [1];
			pieColors = ["#e5e7eb"];
		}

		window.productsPieChartInstance = new Chart(pieCtx, {
			type: "doughnut",
			data: {
				labels: pieLabels,
				datasets: [
					{
						data: pieData,
						backgroundColor: pieColors,
						borderWidth: 0,
					},
				],
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				cutout: "65%",
				plugins: {
					legend: { position: "right" },
					tooltip: {
						callbacks: {
							label: function (context) {
								if (context.label === "No Data Available") return " No stats yet";
								return " " + context.raw + " units";
							},
						},
					},
				},
			},
		});
	}

	document.getElementById("dateFilter")?.addEventListener("change", async (e) => {
		try {
			const config = rangeUiConfigs[e.target.value] || rangeUiConfigs["today"];
			const response = await fetch(
				`/dashboard/filter?range=${encodeURIComponent(e.target.value)}`
			);
			if (!response.ok) throw new Error(`HTTP Error: ${response.status}`);

			const data = await response.json();

			const viewAllLink = document.querySelector('a[href*="/dashboard/top-revenue"]');
			if (viewAllLink) {
				viewAllLink.href = `/dashboard/top-revenue?range=${encodeURIComponent(e.target.value)}`;
			}

			const updateCard = (idx, label, pill, valText, footerText = null) => {
				const card = document.querySelector(`.stat-card[data-card-index="${idx}"]`);
				if (!card) return;
				card.querySelector(".card-label").textContent = label;
				card.querySelector(".footer-pill").textContent = pill;
				card.querySelector(".stat-value").textContent = valText;
				if (footerText) card.querySelector(".footer-text").textContent = footerText;
			};

			if (data.salesStats) {
				updateCard(
					2,
					config.orderLabel,
					config.pillText,
					formatNumber(data.salesStats.total_sales)
				);
				updateCard(
					3,
					config.revenueLabel,
					config.pillText,
					formatCurrency(data.salesStats.total_revenue),
					config.targetText
				);

				if (window.revenueChartInstance) {
					window.revenueChartInstance.data.datasets[0].label = config.chartLabel;
					window.revenueChartInstance.data.datasets[0].data = [
						parseFloat(data.salesStats.total_revenue || 0),
					];
					window.revenueChartInstance.data.datasets[1].data = [config.targetVal];
					window.revenueChartInstance.update();
				}
			}

			const tableBody = document.querySelector("#topRevenueTable tbody");
			const emptyState = document.getElementById("topRevenueEmpty");
			const table = document.getElementById("topRevenueTable");

			if (tableBody && data.topProducts) {
				tableBody.innerHTML = "";
				const hasData = data.topProducts.length > 0;

				table.style.display = hasData ? "table" : "none";
				if (emptyState) emptyState.style.display = hasData ? "none" : "flex";

				if (hasData) {
					tableBody.innerHTML = data.topProducts
						.map(
							(p, i) => `
                        <tr style="animation: dashIn 0.3s ease forwards; animation-delay: ${i * 0.05}s; opacity: 0;">
                            <td>
                                <div class="product-cell">
                                    <span class="rank-num rank-${i + 1}">${i + 1}</span>
                                    <div class="product-info">
                                        <div class="product-name" title="${escapeHtml(p.name)}">${escapeHtml(p.name)}</div>
                                        <div class="product-sku">${escapeHtml(p.sku)}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="td-units">${formatNumber(p.total_sold)}</td>
                            <td class="td-revenue">${formatCurrency(p.total_revenue)}</td>
                        </tr>
                    `
						)
						.join("");
				}
			}

			if (window.productsPieChartInstance && Array.isArray(data.topProducts)) {
				let newPieData = data.topProducts.map((p) => parseInt(p.total_sold || 0));
				let newPieLabels = data.topProducts.map((p) => p.name);
				let newPieColors = THEME.colors.palette;

				if (newPieData.length === 0 || newPieData.every((val) => val === 0)) {
					newPieData = [1];
					newPieLabels = ["No Data Available"];
					newPieColors = ["#e5e7eb"];
				}

				window.productsPieChartInstance.data.labels = newPieLabels;
				window.productsPieChartInstance.data.datasets[0].data = newPieData;
				window.productsPieChartInstance.data.datasets[0].backgroundColor = newPieColors;
				window.productsPieChartInstance.update();
			}
		} catch (err) {
			console.error("Dashboard filter update failed:", err);
			alert("Failed to update dashboard. Please try again.");
		}
	});
});
