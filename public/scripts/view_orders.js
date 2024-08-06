function updateOrderStatus(orderId, isOrderCompleted = true) {
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
