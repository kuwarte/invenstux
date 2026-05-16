function openSidebar() {
	document.getElementById("sidebar").classList.add("open");
	document.getElementById("sidebarOverlay").classList.add("show");
	document.body.classList.add("sidebar-open");
}

function closeSidebar() {
	document.getElementById("sidebar").classList.remove("open");
	document.getElementById("sidebarOverlay").classList.remove("show");
	document.body.classList.remove("sidebar-open");
}

document.addEventListener("keydown", function (e) {
	if (e.key === "Escape") closeSidebar();
});

function toggleUserMenu() {
	document.getElementById("userDropdown").classList.toggle("show");
}

window.addEventListener("click", function (e) {
	if (!e.target.closest(".sidebar-footer")) {
		document.getElementById("userDropdown").classList.remove("show");
	}
});
