function updateOrderStatus(orderId, isOrderCompleted = true) {
	const isConfirmed = confirm(
		`Are you sure you want to mark this order as ${isOrderCompleted ? 'completed' : 'cancelled'}?`
	);
	if (!isConfirmed) return;

	fetch(`view_orders.php`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: `reason=change_order_status&order_id=${orderId}&order_status=${isOrderCompleted ? 'COMPLETED' : 'CANCELLED'}`,
	}).then(async (response) => {
		alert(`Order ${isOrderCompleted ? 'completed' : 'cancelled'} successfully`);
		location.reload();
	});
}

function deleteOrder(orderId) {
	const isConfirmed = confirm('Are you sure you want to delete this order?');
	if (!isConfirmed) return;

	fetch(`view_orders.php`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: `reason=delete_order&order_id=${orderId}`,
	}).then(async (response) => {
		// alert(await response.text());
		alert('Order deleted successfully');
		location.reload();
	});
}
