function deletePromotion(promotionId) {
	fetch(`view_promotions.php`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: `reason=delete_promotion&promotion_id=${promotionId}`,
	}).then(async (response) => {
		alert('Promotion deleted successfully');
		location.reload();
	});
}
