function deleteReservation(reservationId) {
	fetch(`view_reservations.php`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: `reason=delete_reservation&reservation_id=${reservationId}`,
	}).then(async (response) => {
		// alert(await response.text());
		alert('Reservation deleted successfully');
		location.reload();
	});
}
