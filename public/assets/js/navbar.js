const navbarToggle = document.querySelector('.__navbar_toggle');
const searchForm = document.querySelector('.__navbar_form');
const navbarMenu = document.querySelector('.__navbar_menu');

if (navbarToggle && searchForm && navbarMenu) {
	navbarToggle.addEventListener('click', () => {
		searchForm.classList.toggle('show');
		navbarMenu.classList.toggle('show');

		const closeItems = this.document.querySelector('.__navbar_close');
		const showItems = this.document.querySelector('.__navbar_show');

		closeItems && closeItems.classList.toggle('hidden');
		showItems && showItems.classList.toggle('hidden');
	});
}
