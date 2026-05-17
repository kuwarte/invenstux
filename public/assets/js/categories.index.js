function openModal(modalId) {
	document.getElementById(modalId).classList.add("show");
}

function closeModal(modalId) {
	document.getElementById(modalId).classList.remove("show");
}

function editCategory(cat) {
	document.getElementById("edit_id").value = cat.id;
	document.getElementById("edit_name").value = cat.name;
	document.getElementById("edit_parent").value = cat.parent_id || "";
	document.getElementById("edit_desc").value = cat.description || "";
	openModal("editModal");
}

function confirmDelete(id, name) {
	document.getElementById("delete_id").value = id;
	document.getElementById("delete_cat_name").innerText = name;
	openModal("deleteModal");
}

function filterTable() {
	const query = document.getElementById("categorySearch").value.toLowerCase();
	const rows = document.querySelectorAll("#categoryTableBody tr:not(#noResultsRow)");
	let hasVisibleRow = false;

	rows.forEach((row) => {
		const name = row.getAttribute("data-name") || "";
		const desc = row.getAttribute("data-desc") || "";

		if (name.includes(query) || desc.includes(query)) {
			row.style.display = "";
			hasVisibleRow = true;
		} else {
			row.style.display = "none";
		}
	});

	const noResultsRow = document.getElementById("noResultsRow");
	if (noResultsRow) {
		noResultsRow.style.display = hasVisibleRow || rows.length === 0 ? "none" : "";
	}
}
