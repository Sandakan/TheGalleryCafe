function changeCartItemQuantity(cartItemId, currentQuantity, incrementingValue) {
	fetch(`cart.php`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: `reason=change_item_quantity&cart_item_id=${cartItemId}&current_quantity=${currentQuantity}&incrementing_value=${incrementingValue}`,
	}).then((response) => {
		location.reload();
	});
}
