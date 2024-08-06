function deleteMenu(menuId) {
	fetch(`view_menus.php`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: `reason=delete_menu&menu_id=${menuId}`,
	}).then(async (response) => {
		alert('Menu deleted successfully');
		location.reload();
	});
}
