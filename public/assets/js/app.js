// JS minimal pour interactions futures
document.addEventListener('DOMContentLoaded', () => {
	const themeToggle = document.getElementById('themeToggle');
	const savedTheme = localStorage.getItem('theme');
	const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

	const setTheme = (mode) => {
		const isDark = mode === 'dark';
		document.body.classList.toggle('dark', isDark);
		if (themeToggle) {
			themeToggle.innerHTML = isDark
				? '<i class="fas fa-sun mr-2"></i> Mode clair'
				: '<i class="fas fa-moon mr-2"></i> Mode sombre';
		}
		localStorage.setItem('theme', mode);
	};

	if (savedTheme) {
		setTheme(savedTheme);
	} else {
		setTheme(prefersDark ? 'dark' : 'light');
	}

	if (themeToggle) {
		themeToggle.addEventListener('click', () => {
			const next = document.body.classList.contains('dark') ? 'light' : 'dark';
			setTheme(next);
		});
	}

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
