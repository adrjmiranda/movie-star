const descriptioncharcount = document.getElementById('descriptioncharcount');
const descriptionInput = document.getElementById('descriptionInput');

const descriptionCharMax = 5000;

const descriptionUpdateCount = () => {
	const total = descriptionInput.value.length;
	descriptioncharcount.innerHTML = `<span ${
		total >= descriptionCharMax ? 'style="color: red;"' : ''
	}>${total} / ${descriptionCharMax}</span>`;
};

if (descriptioncharcount && descriptionInput) {
	descriptionUpdateCount();
	descriptionInput.addEventListener('input', descriptionUpdateCount);
}
