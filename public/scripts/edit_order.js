function changeItemQuantity(orderItemId, currentQuantity, incrementingValue) {
	fetch(`edit_order.php`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: `reason=change_item_quantity&order_item_id=${orderItemId}&current_quantity=${currentQuantity}&incrementing_value=${incrementingValue}`,
	}).then(async (response) => {
		// alert(await response.text());
		location.reload();
	});
}

function searchItems(BASE_URL, orderId) {
	const searchInput = document.getElementById('items-search');

	if (searchInput) {
		const value = searchInput.value;
		if (value.trim().length === 0) {
			searchResults.innerHTML = '<div class="no-results">Search to display results</div>';
			return;
		}

		fetch(`search_items.server.php`, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: `search_text=${value}`,
		})
			.then((res) => res.json())
			.then((res) => {
				console.log(res);
				const searchResults = document.getElementById('search-results');

				if (searchResults) {
					searchResults.innerHTML = '';
					if (res.length > 0) {
						res.forEach((item) => {
							searchResults.innerHTML += `
								<div class="item-search-result">
									<div class="item-search-result-image">
										<img src="${BASE_URL}/public/images/menu-items/${item.image}" alt="" />
									</div>
									<div class="item-search-result-info">
										<div class="item-search-result-name">${item.name}</div>
										<div class="item-search-result-price">LKR ${item.price}</div>
										<div class="item-search-result-actions">
											<button type="button" class="btn-secondary" onclick="addToOrder(${item.id}, ${orderId})">Add to Order</button>
										</div>
									</div>
								</div>
							`;
						});
					} else searchResults.innerHTML = '<div class="no-results">No results found</div>';
				}
			});
	}
}

function addToOrder(itemId, orderId) {
	fetch(`edit_order.php`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: `reason=add_item_to_order&menu_item_id=${itemId}&order_id=${orderId}`,
	}).then(async (response) => {
		// alert(await response.text());
		location.reload();
	});
}
