<?php

	if (isset($_SESSION['semester_no_change']) && $_SESSION['semester_no_change'] === true) {
		// Display the Swal notification for no change
		echo '<script>
			Swal.fire({
				icon: "info",
				title: "No Change",
				text: "You selected the same semester as the current one."
			});
		</script>';
		// Reset the session variable
		$_SESSION['semester_no_change'] = false;
	}

	// Check if the session variable is set to indicate a successful change
	if (isset($_SESSION['semester_changed']) && $_SESSION['semester_changed'] === true) {
		// Display the Swal notification for a successful change
		echo '<script>
			Swal.fire({
				icon: "success",
				title: "Semester Changed",
				text: "The semester has been successfully changed."
			});
		</script>';
		// Reset the session variable
		$_SESSION['semester_changed'] = false;
	}

?>