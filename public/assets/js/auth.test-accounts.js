document.querySelectorAll(".cred-value").forEach((element) => {
	element.addEventListener("click", async () => {
		const textToCopy = element.innerText;
		try {
			await navigator.clipboard.writeText(textToCopy);
			element.classList.add("copied");

			setTimeout(() => {
				element.classList.remove("copied");
			}, 1000);
		} catch (err) {
			console.error("Failed to copy text:", err);
		}
	});
});
