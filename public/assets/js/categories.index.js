let categorySearchTimeout;

function handleSearchInput() {
	clearTimeout(categorySearchTimeout);
	categorySearchTimeout = setTimeout(applyFilter, 400);
}

function applyFilter() {
	const search = document.getElementById("categorySearch").value.trim();

	const params = new URLSearchParams();
	if (search) params.append("search", search);

	fetch("/categories/filter?" + params.toString())
		.then((response) => response.json())
		.then((categories) => renderCategories(categories, search))
		.catch((error) => console.error("Category filter error:", error));
}

/**
 * Build a nested tree from a flat array of categories.
 * Each item has id, parent_id. Returns root nodes with a `children` array.
 */
function buildTree(flatList) {
	const map = {};
	const roots = [];

	flatList.forEach((cat) => {
		map[cat.id] = { ...cat, children: [] };
	});

	flatList.forEach((cat) => {
		if (cat.parent_id && map[cat.parent_id]) {
			map[cat.parent_id].children.push(map[cat.id]);
		} else {
			roots.push(map[cat.id]);
		}
	});

	return roots;
}

function renderCategories(categories, search) {
	const tbody = document.getElementById("categoryTableBody");
	const noResultsRow = document.getElementById("noResultsRow");

	tbody.querySelectorAll(".category-data-row").forEach((row) => row.remove());
	noResultsRow.style.display = "none";

	if (categories.length === 0) {
		noResultsRow.style.display = "";
		return;
	}

	// Rebuild tree structure from flat SQL result so hierarchy is preserved
	const tree = buildTree(categories);
	const rows = renderTreeRows(tree, 0);
	noResultsRow.insertAdjacentHTML("beforebegin", rows);
}

function renderTreeRows(nodes, level) {
	return nodes
		.map((cat) => {
			const row = renderCategoryRow(cat, level);
			const childRows = cat.children && cat.children.length > 0
				? renderTreeRows(cat.children, level + 1)
				: "";
			return row + childRows;
		})
		.join("");
}

function renderCategoryRow(cat, level) {
	const initials = String(cat.name || "C").substring(0, 2).toUpperCase();
	const productBadge =
		parseInt(cat.product_count) > 0
			? `<span class="product-badge">${parseInt(cat.product_count)} items</span>`
			: "";
	const description = cat.description
		? `<div class="cat-desc" title="${escapeHtml(cat.description)}">${escapeHtml(cat.description)}</div>`
		: "";
	const createdAt = cat.created_at
		? new Date(cat.created_at).toLocaleDateString("en-US", {
				month: "short",
				day: "2-digit",
				year: "numeric",
			})
		: "—";

	const indentConnector = level > 0 ? `<div class="indent-connector"></div>` : "";

	return `
		<tr class="category-data-row" data-name="${escapeHtml((cat.name || "").toLowerCase())}" data-desc="${escapeHtml((cat.description || "").toLowerCase())}">
			<td>
				<div class="cat-cell" data-level="${level}">
					${indentConnector}
					<div class="cat-mono">${escapeHtml(initials)}</div>
					<div>
						<div class="cat-name-container">
							<span class="cat-name">${escapeHtml(cat.name || "")}</span>
							${productBadge}
						</div>
						${description}
					</div>
				</div>
			</td>
			<td class="date-cell">${createdAt}</td>
			<td>
				<div class="action-buttons">
					<button class="btn-icon btn-edit" type="button" onclick="editCategory(${escapeJson(cat)})" title="Edit Category">
						<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
						</svg>
					</button>
					<button class="btn-icon btn-delete" type="button" onclick="confirmDelete(${parseInt(cat.id)}, '${escapeHtml(cat.name || "").replace(/'/g, "\\'")}')" title="Delete Category">
						<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
						</svg>
					</button>
				</div>
			</td>
		</tr>`;
}

function openModal(modalId) {
	document.getElementById(modalId).classList.add("show");
}

function closeModal(modalId) {
	document.getElementById(modalId).classList.remove("show");
}

function editCategory(cat) {
	document.getElementById("edit_id").value = cat.id;
	document.getElementById("edit_name").value = cat.name || "";
	document.getElementById("edit_desc").value = cat.description || "";
	const parentSelect = document.getElementById("edit_parent");
	if (parentSelect) parentSelect.value = cat.parent_id || "";
	openModal("editModal");
}

function confirmDelete(id, name) {
	document.getElementById("delete_id").value = id;
	const nameEl = document.getElementById("delete_cat_name");
	if (nameEl) nameEl.textContent = name;
	openModal("deleteModal");
}

function escapeHtml(text) {
	const div = document.createElement("div");
	div.textContent = String(text);
	return div.innerHTML;
}

function escapeJson(obj) {
	// Strip children array before serializing to avoid huge inline JSON
	const { children, ...rest } = obj;
	return JSON.stringify(rest)
		.replace(/</g, "\\u003c")
		.replace(/>/g, "\\u003e")
		.replace(/&/g, "\\u0026");
}
