const preview = document.getElementById('imagepreview');
const imageInput = document.getElementById('imagepreviewinput');

if (preview && imageInput) {
	imageInput.addEventListener('change', () => {
		const file = imageInput.files[0];

		if (file) {
			const reader = new FileReader();

			reader.addEventListener('load', () => {
				preview.src = reader.result;
			});

			reader.readAsDataURL(file);
		}
	});
}
