function deleteMenuItem(menuItemId) {
	fetch(`view_menu_items.php`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: `reason=delete_menu_item&menu_item_id=${menuItemId}`,
	}).then(async (response) => {
		alert('Menu item deleted successfully');
		location.reload();
	});
}
