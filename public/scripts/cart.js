function changeItemQuantity(cartItemId, currentQuantity, incrementingValue) {
	const newQuantity = currentQuantity + incrementingValue;
	if (newQuantity <= 0) {
		const isRemovingItem = confirm('Are you sure you want to remove this item from the cart?');
		if (!isRemovingItem) return;
	}

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

function confirmOrder(cartId) {
	// alert(cartId);
	fetch(`cart.php`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: `reason=confirm_order&cart_id=${cartId}`,
	}).then(async (response) => {
		// location.reload();
	});
}
