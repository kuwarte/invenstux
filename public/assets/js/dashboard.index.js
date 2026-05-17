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
	const modal = document.getElementById("pdfExportModal");
	if (modal) modal.classList.toggle("active", show);
};

window.openPdfModal = () => togglePdfModal(true);
window.closePdfModal = () => togglePdfModal(false);

window.generatePdf = () => {
	if (typeof html2pdf === "undefined") return console.error("html2pdf library is missing.");

	const selectedFormat = document.querySelector('input[name="pdfFormat"]:checked');
	if (!selectedFormat) return;

	const btn = document.querySelector(".btn-export");
	if (btn) {
		btn.disabled = true;
		btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Generating...`;
	}

	const format = selectedFormat.value;
	let element, filename;

	switch (format) {
		case "summary":
			element = document.querySelector(".stats-grid");
			filename = "Dashboard_Summary.pdf";
			break;
		case "revenue":
			const dataGrid = document.querySelector(".data-grid");
			if (dataGrid && dataGrid.children[0]) {
				element = document.createElement("div");
				element.innerHTML = `
                    <div style="margin-bottom: 24px; font-family: sans-serif;">
                        <h2 style="font-size: 24px; font-weight: 700; margin: 0 0 8px 0; color: #111827;">Top Revenue Generators</h2>
                        <p style="color: #6b7280; margin: 0;">Generated on ${new Date().toLocaleDateString()}</p>
                    </div>`;
				element.appendChild(dataGrid.children[0].cloneNode(true));
				filename = "Revenue_Report.pdf";
			}
			break;
		default:
			element = document.querySelector(".dashboard-wrapper");
			filename = "Executive_Summary_Report.pdf";
	}

	if (!element) {
		if (btn) {
			btn.disabled = false;
			btn.textContent = "Generate PDF";
		}
		return console.error("Export element not found.");
	}

	html2pdf()
		.set({
			margin: [15, 15, 15, 15],
			filename,
			image: { type: "jpeg", quality: 0.98 },
			html2canvas: { scale: 2, useCORS: true, logging: false },
			jsPDF: { unit: "mm", format: "a4", orientation: "landscape" },
		})
		.from(element)
		.save()
		.finally(() => {
			closePdfModal();
			if (btn) {
				btn.disabled = false;
				btn.textContent = "Generate PDF";
			}
		});
};

document.addEventListener("DOMContentLoaded", () => {
	document.getElementById("exportPdfBtn")?.addEventListener("click", openPdfModal);
	document.getElementById("pdfExportModal")?.addEventListener("click", (e) => {
		if (e.target.id === "pdfExportModal") closePdfModal();
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
