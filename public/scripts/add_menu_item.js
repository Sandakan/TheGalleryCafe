let url;

function showMenuItemImage() {
	if (url) URL.revokeObjectURL(url);

	const menuItemImageInput = document.getElementById('menu_item_image');

	if (menuItemImageInput) {
		url = URL.createObjectURL(menuItemImageInput.files[0]);
		const displayImage = document.getElementById('display_image');
		if (displayImage) {
			displayImage.style.display = 'block';
			displayImage.src = url;
		}
	}
}
