// JS minimal pour interactions futures
document.addEventListener('DOMContentLoaded', () => {
	const binds = document.querySelectorAll('[data-select-search]');

	binds.forEach((input) => {
		const targetName = input.getAttribute('data-select-search');
		const select = document.querySelector(`[data-select-target="${targetName}"]`);
		if (!select) {
			return;
		}

		input.addEventListener('input', () => {
			const query = input.value.toLowerCase();
			Array.from(select.options).forEach((option, index) => {
				if (index === 0) {
					option.hidden = false;
					return;
				}
				const text = option.textContent.toLowerCase();
				option.hidden = !text.includes(query);
			});
		});
	});
});
