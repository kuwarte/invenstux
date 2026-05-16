function openPdfModal() {
	document.getElementById("pdfExportModal").classList.add("active");
}

function closePdfModal() {
	document.getElementById("pdfExportModal").classList.remove("active");
}

function generatePdf() {
	const format = document.querySelector('input[name="pdfFormat"]:checked').value;
	const btn = document.querySelector(".btn-export");
	btn.disabled = true;
	btn.textContent = "Generating...";

	let element;
	let filename = "Dashboard_Report.pdf";

	if (format === "summary") {
		element = document.querySelector(".stats-grid");
		filename = "Dashboard_Summary.pdf";
	} else if (format === "revenue") {
		const wrapper = document.createElement("div");
		wrapper.innerHTML = `
                <div style="margin-bottom: 20px;">
                    <h2 style="font-size: 20px; font-weight: 700; margin: 0 0 10px 0;">Top Revenue Generators</h2>
                    <p style="color: #6b7280; margin: 0;">Generated on ${new Date().toLocaleDateString()}</p>
                </div>
            `;
		wrapper.appendChild(document.querySelector(".data-grid").children[0].cloneNode(true));
		element = wrapper;
		filename = "Revenue_Report.pdf";
	} else {
		element = document.querySelector(".dashboard-wrapper");
		filename = "Executive_Summary_Report.pdf";
	}

	const opt = {
		margin: [15, 15, 15, 15],
		filename: filename,
		image: { type: "jpeg", quality: 0.98 },
		html2canvas: { scale: 2, useCORS: true, logging: false },
		jsPDF: { unit: "mm", format: "a4", orientation: "landscape" },
	};

	html2pdf()
		.set(opt)
		.from(element)
		.save()
		.then(() => {
			closePdfModal();
			btn.disabled = false;
			btn.textContent = "Generate PDF";
		})
		.catch(() => {
			btn.disabled = false;
			btn.textContent = "Generate PDF";
		});
}

document.getElementById("exportPdfBtn").addEventListener("click", openPdfModal);

document.getElementById("pdfExportModal").addEventListener("click", function (e) {
	if (e.target === this) {
		closePdfModal();
	}
});
