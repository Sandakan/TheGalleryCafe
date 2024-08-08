function deleteEvent(eventId) {
	const isConfirmed = confirm('Are you sure you want to delete this event?');
	if (!isConfirmed) return;

	fetch(`view_events.php`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: `reason=delete_event&event_id=${eventId}`,
	}).then(async (response) => {
		alert('Event deleted successfully');
		location.reload();
	});
}
