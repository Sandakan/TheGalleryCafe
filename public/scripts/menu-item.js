function incrementQuantity() {
	const quantityInput = document.getElementById('item-quantity');
	if (quantityInput) {
		const currentQuantity = parseInt(quantityInput.value);
		quantityInput.value = currentQuantity + 1;
	}
}

function decrementQuantity() {
	const quantityInput = document.getElementById('item-quantity');
	if (quantityInput) {
		const currentQuantity = parseInt(quantityInput.value);
		if (currentQuantity <= 1) {
			quantityInput.value = 1;
		} else {
			quantityInput.value = currentQuantity - 1;
		}
	}
}
