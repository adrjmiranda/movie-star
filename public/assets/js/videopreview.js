const videoPreview = document.getElementById('videopreview');
const trailerInput = document.getElementById('trailer');

if (videoPreview && trailerInput) {
	trailerInput.addEventListener('input', () => {
		const url = trailerInput.value.trim();
		if (url) {
			videoPreview.src = url;
		} else {
			videoPreview.src =
				'hhttps://www.youtube.com/embed/BNAAmkjHlKA?si=6p9UCpRFJaazLKd6';
		}
	});
}
