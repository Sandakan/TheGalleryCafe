function getReservationTimeSlots(BASE_URL, current) {
	const numberOfPeopleInput = document.getElementById('no_of_people');
	const reservationDateInput = document.getElementById('reservation_date');
	const responseContainer = document.getElementById('find-table-model-response-container');

	const numberOfPeople = parseInt(numberOfPeopleInput.value);
	const reservationDate = reservationDateInput.value;

	console.log(numberOfPeople, reservationDate);
	if (numberOfPeople && reservationDate) {
		// check if the reservation date is in the part
		const currentDate = new Date(new Date().toDateString()).getTime();
		const reservationDateObj = new Date(reservationDate).getTime();
		// reservation time options container
		const reservationTimeInput = document.getElementById('reservation_time');

		console.log(currentDate, reservationDateObj, currentDate > reservationDateObj);
		if (currentDate > reservationDateObj) {
			reservationTimeInput.innerHTML =
				'<option value="" selected disabled hidden>Select your preferred reservation time</option>';
			return alert('Please select a future date');
		}

		fetch(`${BASE_URL}/pages/reservations/reservations.server.php`, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: `reason=get_available_time_slots&no_of_people=${numberOfPeople}&reservation_date=${reservationDate}`,
		})
			.then((res) => res.json())
			.then((res) => {
				console.log(res);

				reservationTimeInput.innerHTML =
					'<option value="" selected disabled hidden>Select your preferred reservation time</option>';
				const option = document.createElement('option');
				option.value = '';
				option.textContent = 'Select your preferred reservation time';
				option.disabled = true;
				option.selected = true;
				reservationTimeInput.appendChild(option);

				res.forEach((item) => {
					const option = document.createElement('option');
					option.value = `${item}`;

					const date = new Date(item);
					const time = date.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
					option.textContent = `${time}`;
					reservationTimeInput.appendChild(option);
				});

				responseContainer.innerText =
					res.length === 0
						? 'No time slots available. Try again with different options.'
						: `${res.length} time slots available.`;
			});
	}
}
