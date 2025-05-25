const biocharcount = document.getElementById('biocharcount');
const bioInput = document.getElementById('bioInput');

const bioCharMax = 500;

const bioUpdateCount = () => {
	const total = bioInput.value.length;
	biocharcount.innerHTML = `<span ${
		total >= bioCharMax ? 'style="color: red;"' : ''
	}>${total} / ${bioCharMax}</span>`;
};

if (biocharcount && bioInput) {
	bioUpdateCount();
	bioInput.addEventListener('input', bioUpdateCount);
}
