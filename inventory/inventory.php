<?php
include '../database.php';
include_once '../includes.php';
include_once '../commands.php';
include 'inventorymodals.php';
include_once 'inventoryalerts.php';
inventoryAlerts();
// inventoryAlerts();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<link rel="stylesheet" href="../style.css">
	<title>Administrator Hub</title>
	<style>
		.custom-table {
			width: 100%;
			margin: 0 auto;
			overflow-y: auto;
			border-collapse: collapse;
			/* Optional: To remove spacing between table cells */
		}

		.custom-table tr {
			max-height: 50px;
			/* Adjust the max-height value as needed */
		}

		.custom-table th,
		.custom-table td {
			padding: 5px;
			/* Adjust cell padding as needed */
			text-align: left;
		}
	</style>
</head>

<body>
	<!-- LEFTSIDE NAVBAR FOR ADMIN -->
	<section id="sidebar">
		<a href="" class="brand">
			<img src="../images/neustlogo.png" style="border: 1px solid white; border-radius: 50%;" alt="NEUST Logo" width="60" height="60">
			<span class="text" style="margin-left: 15px;">Administrator</span>
		</a>
		<?php
		if ($_SESSION['role'] === 'admin') {
			$loggedInAdminID = $_SESSION["user_id"];
			$adminNameQuery = "SELECT first_name, last_name FROM tbl_users WHERE user_id = $loggedInAdminID";
			$adminNameResult = mysqli_query($db_connection, $adminNameQuery);

			if ($adminNameResult && $adminData = mysqli_fetch_assoc($adminNameResult)) {
				echo "<div class='alert alert-info mt-0 mb-0 text-center strong'>";
				echo "<strong>" . $adminData['first_name'] . " " . $adminData['last_name'] . "</strong>";
				echo "</div>";
			}
		}
		?>
		<ul class="side-menu top">
			<!-- Your menu items here -->
			<li>
				<a href="../admin_dashboard/admin_dashboard.php">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li class="active">
				<a href="inventory.php">
					<i class='bx bxs-book-alt'></i>
					<span class="text">Book Inventory</span>
				</a>
			</li>
			<li>
				<a href="../point_of_sale/pos.php">
					<i class='bx bxs-message-dots'></i>
					<span class="text">Point of Sale</span>
				</a>
			</li>
			<li>
				<a href="../management.php">
					<i class='bx bxs-cog'></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="../staffadmin.php">
					<i class='bx bxs-user-check'></i>
					<span class="text">Staff and Admins</span>
				</a>
			</li>
			<li>
				<a href="../reports/sales_report.php">
					<i class='bx bxs-chart'></i>
					<span class="text">Sales Report</span>
				</a>
			</li>
			<li>
				<a href="../history.php">
					<i class='bx bxs-receipt'></i>
					<span class="text">History</span>
				</a>
			</li>
			<li>
				<a href="archive.php">
					<i class='bx bxs-archive'></i>
					<span class="text">Archive</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<!-- Your menu items here -->
			<!-- <li>
				<a href="../settings.php" class="logout">
					<i class='bx bxs-cog'></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="../help.php" class="logout">
					<i class='bx bxs-help-circle'></i>
					<span class="text">Help & Support</span>
				</a>
			</li> -->
			<li>
				<a href="../logout.php" class="logout" onclick="return confirmLogout();">
					<i class='bx bxs-log-out-circle'></i>
					<span class="text">Logout</span>
				</a>
			</li>
			<script>
				function confirmLogout() {
					// Using SweetAlert for confirmation
					Swal.fire({
						title: 'Are you sure you want to logout?',
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Yes'
					}).then((result) => {
						if (result.isConfirmed) {
							// If the user clicks "Yes," then you'll logout
							window.location.href = "../logout.php";
						}
					});

					// Prevent the default action of the link
					return false;
				}
			</script>
		</ul>
	</section>

	<section id="content">

		<?php

		include '../nav.php';

		?>
		<!-- Your main content here -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Book Inventory</h1>
					<div id="pst" class="pst" style="font-size: 18px; color: #333;"></div>
					<ul class="breadcrumb">
						<li>
							<a href="">Welcome to the Book Inventory section. Here, you can efficiently manage your book catalog,
								including adding, editing, and deleting books, as well as tracking their availability and details.
							</a>
						</li>
					</ul>
				</div>
			</div>
			</div>
			<div class="col-md-12">
				<br>
				<a href="add_book.php" class="btn btn-primary float-right">&plus; Add Books</a>
			</div>
			<br>
			<p id="noResultsMessage" style="display: none;">No matching results.</p>
			<div class="table-responsive">
				<table class="table table-bordered table-striped custom-table">
					<thead>
						<tr>
							<th>Book ID</th>
							<th>ISBN</th>
							<th>Book Image</th>
							<th>Title</th>
							<th>Author</th>
							<th>Publication Year</th>
							<th>Subject Code</th>
							<th>Year Levels</th>
							<th>Program/Strand</th>
							<th>Total Quantity</th>
							<th>Price</th>
							<!-- <th>Damaged/Condtion</th> -->
							<!-- <th>Book Condition</th> -->
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody id="myTable">
						<?php
						// Define the number of items per page
						$itemsPerPage = 5;

						// Get the current page number from the query string
						if (isset($_GET['page'])) {
							$currentPage = $_GET['page'];
						} else {
							$currentPage = 1;
						}

						// Calculate the OFFSET for the SQL query
						$offset = ($currentPage - 1) * $itemsPerPage;

						// Query to count the total number of items
						$countQuery = "SELECT COUNT(*) as total FROM tbl_books";
						$countResult = mysqli_query($db_connection, $countQuery);
						$countRow = mysqli_fetch_assoc($countResult);
						$totalItems = $countRow['total'];

						// Calculate the total number of pages
						$totalPages = ceil($totalItems / $itemsPerPage);

						$sql = "SELECT cb.book_id, 
						 	cb.isbn, 
						    cb.title, 
						    cb.book_image, 
						    cb.author, 
						    cb.publication_year, 
						    cb.quantity_available, 
						    cb.price, 
							-- cb.quantity_damaged,
							-- cb.book_condition,
						    s.subject_code, 
						    y.year_level_name, 
						    y.year_level_type,
						    cb.status, 
						    GROUP_CONCAT(DISTINCT p.program_name SEPARATOR ',<br>') AS program_names,
						    GROUP_CONCAT(DISTINCT st.strand_name SEPARATOR ',<br>') AS strand_names
						FROM tbl_books cb
						LEFT JOIN tbl_subjects s ON cb.subject_id = s.subject_id
						LEFT JOIN tbl_yearlevels y ON cb.year_level_id = y.year_level_id
						LEFT JOIN tbl_book_programs bp ON cb.book_id = bp.book_id
						LEFT JOIN tbl_programs p ON bp.program_id = p.program_id
						LEFT JOIN tbl_book_strands bs ON cb.book_id = bs.book_id
						LEFT JOIN tbl_strands st ON bs.strand_id = st.strand_id
						WHERE cb.status = 'Active'
						GROUP BY cb.book_id
						LIMIT $itemsPerPage OFFSET $offset";
						$result = mysqli_query($db_connection, $sql);

						if ($result) {
							// Check if there are rows in the result set
							if (mysqli_num_rows($result) > 0) {
								// Loop through and display the book details
								while ($row = mysqli_fetch_assoc($result)) {
									$imagePath = '/finalcapstone/images/' . $row['book_image'];
									$bookCover = $imagePath;
									if (is_file($_SERVER['DOCUMENT_ROOT'] . $imagePath)) {
										$bookCover = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . $imagePath));
									}
									echo "<tr data-book_id='" . $row["book_id"] . "'>";
									echo "<td>" . $row["book_id"] . "</td>";
									// Assuming $row["isbn"] contains an ISBN without hyphens
									$isbn = $row["isbn"];

									// Remove hyphens if they exist
									$cleanIsbn = str_replace('-', '', $isbn);

									// Check the length of the cleaned ISBN
									$isbnLength = strlen($cleanIsbn);

									// Format ISBN based on length
									if ($isbnLength == 10) {
										// ISBN-10 formatting
										$formattedIsbn = substr($cleanIsbn, 0, 1) . '-' . substr($cleanIsbn, 1, 3) . '-' . substr($cleanIsbn, 4, 5) . '-' . substr($cleanIsbn, 9, 1);
										echo "<td>" . $formattedIsbn . "</td>";
									} elseif ($isbnLength == 13) {
										// ISBN-13 formatting
										$formattedIsbn = substr($cleanIsbn, 0, 3) . '-' . substr($cleanIsbn, 3, 1) . '-' . substr($cleanIsbn, 4, 5) . '-' . substr($cleanIsbn, 9, 3) . '-' . substr($cleanIsbn, 12, 1);
										echo "<td>" . $formattedIsbn . "</td>";
									} else {
										// Invalid ISBN length
										echo "<td>Invalid ISBN</td>";
									}
									echo '<td><img src="' . $bookCover . '" style="border-radius: 5px; border: 1px solid grey; height: 130px; width: 130px;"></td>';
									echo "<td>" . $row["title"] . "</td>";
									echo "<td>" . $row["author"] . "</td>";
									echo "<td>" . $row["publication_year"] . "</td>";
									echo "<td>" . $row["subject_code"] . "</td>";
									echo "<td>" . $row["year_level_name"] . "</td>";
									if ($row["year_level_type"] === "High School" && empty($row["program_names"]) && empty($row["strand_names"])) {
										echo "<td>Not Available</td>";
									} else {
										echo "<td>" . $row["program_names"] . $row["strand_names"] . "</td>";
									}
									echo "<td>" . $row["quantity_available"] . "</td>";
									echo "<td>₱" . $row["price"] . "</td>";
									// echo "<td>" . $row["quantity_damaged"] . " - " . $row["book_condition"] . "</td>";
									// echo "<td>" . $row["quantity_damaged"] . "</td>";
									// echo "<td>" . $row["book_condition"] . "</td>";
									// Status toggle button
									echo '<td><button class="btn btn-toggle-status btn-success" data-book-id="' . $row["book_id"] . '">' . $row["status"] . '</button></td>';

									// Buttons for View, Edit, and Delete
									echo '<td>';
									echo '<div class="btn-group" role="group" style="height: 39px;">';

									// View button
									echo '<button class="btn btn-outline-info view-button" data-toggle="modal" data-target="#viewBookModal" title="View" data-book_id="' . $row["book_id"] . '"><i class="bx bxs-show"></i></button>';

									// Edit button
									echo '<button class="btn btn-outline-primary edit-button" data-toggle="modal" data-target="#editBookModal" title="Edit" data-book_id="' . $row["book_id"] . '"><i class="bx bxs-pencil"></i></button>';

									// Delete button
									// echo '<form method="POST" action="../commands.php">';
									echo '<input type="hidden" name="bookTypeID" value="' . $row["book_id"] . '">';
									echo '<button type="button" class="btn btn-outline-danger archive-button" title="Archive" data-book-id="' . $row["book_id"] . '"><i class="bx bxs-book"></i></button>';

									echo '</div>';
									echo '</td>';  // <-- Add this line
								}
							} else {
								// Display "No Books Available" row
								echo "<tr><td colspan='12'>No Books Available.</td></tr>";
							}
						} else {
							// Display a detailed error message
							echo "<tr><td colspan='12'>Error in SQL query: " . mysqli_error($db_connection) . "<br>Query: $sql</td></tr>";
						}
						?>
					</tbody>
				</table>
				<br>
				<?php
				// Output the pagination links
				echo '<ul class="pagination justify-content-center">';
				for ($page = 1; $page <= $totalPages; $page++) {
					echo '<li class="page-item' . ($page == $currentPage ? ' active' : '') . '">';
					echo '<a class="page-link" href="?page=' . $page . '">' . $page . '</a>';
					echo '</li>';
				}
				echo '</ul>';
				?>
			</div>
		</main>

	</section>
	<script>
		// Displays the day, date and time for Philippines        
		function updateTime() {
			const pstElement = document.getElementById('pst');
			const now = new Date();
			const options = {
				timeZone: 'Asia/Manila',
				weekday: 'short',
				year: 'numeric',
				month: 'long',
				day: 'numeric',
				hour: '2-digit',
				minute: '2-digit',
				second: '2-digit',
				hour12: true
			};
			const pstTime = now.toLocaleString('en-US', options);
			pstElement.textContent = "PST (Philippine Standard Time): " + pstTime;
		}
		updateTime();
		setInterval(updateTime, 1000);
		// Attach the archiveBook function to the click event of the Archive button
		$(document).on('click', '.archive-button', function() {
			var bookId = $(this).data('book-id');
			var statusButton = $(this).closest('tr').find('.btn-toggle-status')[0]; // Assuming the status button is in the same row
			toggleStatus(statusButton, bookId); // Call toggleStatus with the status button and bookId
		});
		// Toggle status books
		function toggleStatus(button, bookId) {
			var currentStatus = button.innerText.trim();

			// Use SweetAlert for confirmation
			Swal.fire({
				title: 'Are you sure?',
				text: 'You are about to archive this book. This action cannot be undone.',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, archive it!'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: 'toggle_status.php',
						method: 'POST',
						data: {
							book_id: bookId,
							new_status: (currentStatus === 'Active') ? 'Inactive' : 'Active'
						},
						dataType: 'json',
						success: function(response) {
							if (response.success) {
								// Update the button text and class on success

								// Store the status in local storage
								localStorage.setItem('book_' + bookId, response.newStatus);

								// Show success message
								Swal.fire({
									title: 'Archived!',
									text: 'The book has been archived.',
									icon: 'success',
									showConfirmButton: false, // Hide the default "OK" button
									allowOutsideClick: true,
									timer: 1000 // Set the timer to 4000 milliseconds (4 seconds)
								}).then(() => {
									// Reload the page after the user clicks OK
									location.reload();
								});
							} else {
								// Handle any errors here
								console.log(response.error);
								Swal.fire('Error', 'An error occurred while archiving the book.', 'error');
							}
						},
						error: function(xhr, status, error) {
							// Handle errors here
							console.log(error);
							Swal.fire('Error', 'An error occurred while processing the request.', 'error');
						}
					});
				}
			});
		}
		// View Book Modal 
		$(document).on('click', '.view-button', function() {
			var button = $(this); // Button that triggered the modal
			var BookId = button.data('book_id'); // Extract the college book ID from the button's data attribute

			console.log('View button clicked for Book ID: ' + BookId);
			// Fetch college book type details using AJAX
			$.ajax({
				url: 'book_view.php', // Replace with your PHP script to fetch book type details
				method: 'POST',
				data: {
					BookId: BookId
				},
				dataType: 'json',
				success: function(data) {
					// Update the modal content
					console.log('Data received:', data);

					// Manipulate the program names and strand names before displaying in the modal
					var programNames = data.bookDetails.programNames ? data.bookDetails.programNames.replace(/,(?=[^ ])/g, ',\n') : '';
					var strandNames = data.bookDetails.strandNames ? data.bookDetails.strandNames.replace(/,(?=[^ ])/g, ',\n') : '';
					console.log('Data received:', data);
					$('#bookId').text(data.bookDetails.bookId);
					$('#isBn').text(data.bookDetails.isBn);
					$('#title').text(data.bookDetails.title);
					$('#author').text(data.bookDetails.author);
					$('#publicationYear').text(data.bookDetails.publicationYear);
					$('#quantityAvailable').text(data.bookDetails.quantityAvailable);
					$('#price').text('₱' + data.bookDetails.price);
					$('#subjectCodes').text(data.bookDetails.subjectCodes);
					$('#yearlevelName').text(data.bookDetails.yearlevelName);
					$('#yearlevelType').text(data.bookDetails.yearlevelType);
					$('#programNames').text(programNames);
					// Check the book type and hide the entire row for "Program Name" if it's High School or Senior High
					if (data.bookDetails.yearlevelType === 'High School' || data.bookDetails.yearlevelType === 'Senior High') {
						$('#programNamesRow').hide(); // Hide the entire row
					} else {
						$('#programNamesRow').show(); // Show the entire row
					}
					$('#strandNames').text(strandNames);
					if (data.bookDetails.yearlevelType === 'High School' || data.bookDetails.yearlevelType === 'College') {
						$('#strandNamesRow').hide(); // Hide the entire row
					} else {
						$('#strandNamesRow').show(); // Show the entire row
					}
					$('#status').text(data.bookDetails.status);
				},
				error: function(xhr, status, error) {
					// Handle errors, if necessary
				}
			});
		});

		// Function to show/hide options based on book_type in the edit modal
		function toggleBookTypeOptions(yearlevelType) {
			if (yearlevelType === 'College') {
				$('#collegebookEditOptionGroup').show();
				$('#seniorhighbookEditOptionGroup').hide();
			} else if (yearlevelType === 'Senior High') {
				$('#collegebookEditOptionGroup').hide();
				$('#seniorhighbookEditOptionGroup').show();
			} else {
				// Handle other book types if needed
				$('#collegebookEditOptionGroup').hide();
				$('#seniorhighbookEditOptionGroup').hide();
			}
		}

		// // // Edit option to view the edit program modal
		$(document).ready(function() {
			// Event listener for "Edit Book Program" option
			$('#collegebookEditOption').on('change', function() {
				if ($(this).val() === 'bookEditprogram') {
					// Get the book_id associated with the book you are editing
					var bookId = $('#bookID').val(); // Replace with the correct ID selector

					// Open the program edit modal and pass the book_id
					openProgramEditModal(bookId);
				}
			});

			$('#updateProgramButton').on('click', function() {
				var selectedPrograms = $('input[name="programID[]"]:checked').map(function() {
					return $(this).val();
				}).get();

				if (selectedPrograms.length === 0) {
					Swal.fire({
						icon: 'warning',
						title: 'No Programs Selected',
						text: 'Please select at least one program before updating.',
					}).then(function() {
						$('#programEditModal').modal('hide');
					});
					return;
				}

				var bookId = $('#bookIDProgram').val();

				$.ajax({
					url: 'update_program.php',
					type: 'POST',
					data: {
						bookId: bookId,
						selectedPrograms: selectedPrograms
					},
					success: function(response) {
						$('#programEditModal').modal('hide');

						Swal.fire({
							icon: 'success',
							title: 'Success',
							text: 'Programs updated successfully.',
							showConfirmButton: false, // Hide the "OK" button
							timer: 5000,
							allowOutsideClick: false // Disable closing by clicking outside
						}).then(function() {
							// Code to execute after the SweetAlert is closed, if needed
							// location.reload(); // Reload the page if needed
						});
					},
					error: function(error) {
						console.log('Error updating programs: ' + error);
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'Failed to update programs associations. Please try again.',
						});
					}
				});
			});

			//Edit button to fetch the data from tbl_book_programs related to book id
			function openProgramEditModal(bookId) {
				// Hide the editBookModal
				$('#editBookModal').modal('hide');

				// Set the bookID in the input field in the program edit modal
				$('#bookIDProgram').val(bookId);

				// Show the program edit modal
				$('#programEditModal').modal('show');

				// Fetch program names using Ajax
				$.ajax({
					url: 'get_programs_and_associations.php',
					type: 'GET',
					dataType: 'json',
					data: {
						bookId: bookId
					},
					success: function(programs) {
						var programContainer = $('#programCheckboxes'); // The container for checkboxes

						// Clear existing checkboxes
						programContainer.empty();

						// Add a label for selecting programs
						programContainer.append('<label for="programID">Edit Program(s)</label');

						// Iterate over the programs and create checkboxes
						$.each(programs, function(index, program) {
							var isChecked = program.isAssociated ? 'checked' : ''; // Check if the program is associated

							var checkbox = $('<div class="checkbox"><label><input type="checkbox" name="programID[]" value="' + program.program_id + '" ' + isChecked + '> ' + program.program_name + '</label></div>');

							// Append the checkbox to the container
							programContainer.append(checkbox);
						});
					},
					error: function(error) {
						console.log('Error fetching program names and associations: ' + error);
					}
				});
				// / Reload the page when the programEditModal is hidden
				$('#programEditModal').on('hidden.bs.modal', function(e) {
					location.reload();
				});
			}
		});

		// // // Edit option to view the edit strand modal
		$(document).ready(function() {
			// Event listener for "Edit Book Program" option
			$('#seniorhighbookEditOption').on('change', function() {
				if ($(this).val() === 'bookEditstrand') {
					// Get the book_id associated with the book you are editing
					var bookId = $('#bookID').val(); // Replace with the correct ID selector

					// Open the program edit modal and pass the book_id
					openStrandEditModal(bookId);
				}
			});
			//Update button to edit the strand for tbl_book_strand.php
			$('#updateStrandButton').on('click', function() {
				// Get the selected program IDs (assumed to be checkboxes)
				var selectedStrands = $('input[name="strandID[]"]:checked').map(function() {
					return $(this).val();
				}).get();

				// Check if no checkboxes are selected
				if (selectedStrands.length === 0) {
					// Display SweetAlert warning
					Swal.fire({
						icon: 'warning',
						title: 'No Strands Selected',
						text: 'Please select at least one strand before updating.',
					}).then(function() {
						$('#strandEditModal').modal('hide');
					});
					return; // Prevent further execution
				}
				// Get the book_id associated with the book you are editing
				var bookId = $('#bookIDStrand').val(); // Replace with the correct ID selector

				$.ajax({
					url: 'update_strand.php',
					type: 'POST',
					data: {
						bookId: bookId,
						selectedStrands: selectedStrands
					},
					success: function(response) {
						// Handle the update success or error in the response
						console.log('Update Strand AJAX Success');

						// Close the program edit modal
						$('#strandEditModal').modal('hide');

						// Display success SweetAlert
						Swal.fire({
							icon: 'success',
							title: 'Success',
							text: 'Strands updated successfully.',
							allowOutsideClick: true,
							timer: 4000 // Set the timer to 3000 milliseconds (3 seconds)
						}).then(function() {
							// Code to execute after the SweetAlert is closed, if needed
						});
					},
					error: function(error) {
						console.log('Error updating strands: ' + error);
						// Display error SweetAlert
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'Failed to update strand associations. Please try again.',
						});
					}
				});
			});

			function openStrandEditModal(bookId) {
				// Hide the editBookModal
				$('#editBookModal').modal('hide');

				// Set the bookID in the input field in the program edit modal
				$('#bookIDStrand').val(bookId);

				// Show the program edit modal
				$('#strandEditModal').modal('show');

				// Fetch program names using Ajax
				$.ajax({
					url: 'get_strand_and_associations.php',
					type: 'GET',
					dataType: 'json',
					data: {
						bookId: bookId
					},
					success: function(strands) {
						var strandContainer = $('#strandCheckboxes'); // The container for checkboxes

						// Clear existing checkboxes
						strandContainer.empty();

						// Add a label for selecting programs
						strandContainer.append('<label for="strandID">Edit Strand(s)</label');

						// Iterate over the programs and create checkboxes
						$.each(strands, function(index, strand) {
							var isChecked = strand.isAssociated ? 'checked' : ''; // Check if the program is associated

							var checkbox = $('<div class="checkbox"><label><input type="checkbox" name="strandID[]" value="' + strand.strand_id + '" ' + isChecked + '> ' + strand.strand_name + '</label></div>');

							// Append the checkbox to the container
							strandContainer.append(checkbox);
						});
					},
					error: function(error) {
						console.log('Error fetching strand names and associations: ' + error);
					}
				});
				// / Reload the page when the programEditModal is hidden
				$('#strandEditModal').on('hidden.bs.modal', function(e) {
					location.reload();
				});
			}
		});

		// //Edit Modal to fetch the ID
		$('.edit-button').click(function() {
			var bookId = $(this).data('book_id');
			editBook(bookId);
		});
		//Edit Modal function 
		function editBook(bookId) {
			console.log('Editing book with ID: ' + bookId);

			$.ajax({
				url: 'edit_book.php',
				type: 'POST',
				data: {
					book_id: bookId
				},
				success: function(response) {
					var bookData = JSON.parse(response);

					$('#editBookModal #bookID').val(bookData.book_id);
					$('#editBookModal #bookIsbn').val(bookData.isbn);
					$('#editBookModal #bookTitle').val(bookData.title);
					$('#editBookModal #bookAuthor').val(bookData.author);
					// Edit the bookPublicationYear to be a select element
					var selectElement = $('#editBookModal #bookPublicationYear');
					selectElement.empty();

					// Populate the options from 1880 to 2023
					for (var year = 1880; year <= 2023; year++) {
						var option = $('<option></option>');
						option.text(year);
						option.val(year);
						selectElement.append(option);
					}
					// Set the selected value to the bookData's publication year
					selectElement.val(bookData.publication_year);

					$('#editBookModal #bookQuantity').val(bookData.quantity_available);
					$('#editBookModal #bookPrice').val(bookData.price);

					// Edit the bookSubject to be a select element
					var selectElement = $('#editBookModal #bookSubject');
					selectElement.empty();

					$.ajax({
						url: 'get_subjects.php',
						type: 'GET',
						success: function(subjectCodes) {
							var codes = subjectCodes.split(',');
							for (var i = 0; i < codes.length; i++) {
								var option = $('<option></option>');
								option.text(codes[i]);
								option.val(codes[i]);
								selectElement.append(option);
							}

							selectElement.val(bookData.subject_code);
						}
					});
					// Edit the bookYearlevels to be a select element
					var yearLevelsElement = $('#editBookModal #bookYearlevels');
					yearLevelsElement.empty();
					var yearLevelType = bookData.year_level_type;
					$.ajax({
						url: 'get_yearlevels.php',
						type: 'GET',
						data: {
							year_level_type: yearLevelType
						},
						success: function(yearLevels) {
							var levels = yearLevels.split(',');
							for (var i = 0; i < levels.length; i++) {
								var option = $('<option></option>');
								option.text(levels[i]);
								option.val(levels[i]);
								yearLevelsElement.append(option);
							}

							yearLevelsElement.val(bookData.year_level_name);

							// Show or hide based on the condition
							if (yearLevelType === 'High School' || yearLevelType === 'Senior High' || yearLevelType === 'College') {
								yearLevelsElement.show();
							} else {
								yearLevelsElement.hide();
							}
						}
					});

					// Edit the bookyearLevelType to be a select element
					$('#editBookModal #bookyearLevelType').val(bookData.year_level_type);
					// Initial toggle based on the default book type
					toggleBookTypeOptions($('#bookyearLevelType').val());

					$('#editBookModal').modal('show');
				}
			});
		}

		$('#updateBookButton').click(function() {
			var bookId = $('#bookID').val();
			var bookIsbn = $('#bookIsbn').val();
			// Check if bookTitle is empty
			if (bookIsbn.trim() === '') {
				// Display SweetAlert warning
				Swal.fire({
					icon: 'warning',
					title: 'ISBN is Empty',
					text: 'Please enter a valid ISBN for the book.',
					allowOutsideClick: true
				}).then(function() {
					$('#editBookModal').modal('hide');
				});
				return; // Prevent further execution
			}

			// Check if ISBN is not exactly 10 or 13 digits
			if (!/^\d{10}$|^\d{13}$/.test(bookIsbn)) {
				// Display SweetAlert warning for invalid ISBN length
				Swal.fire({
					icon: 'warning',
					title: 'Invalid ISBN Length',
					text: 'ISBN should be either 10 or 13 digits.',
					allowOutsideClick: true
				}).then(function() {
					$('#editBookModal').modal('hide');
				});
				return; // Prevent further execution
			}
			var bookTitle = $('#bookTitle').val();
			// Check if bookTitle is empty
			if (bookTitle.trim() === '') {
				// Display SweetAlert warning
				Swal.fire({
					icon: 'warning',
					title: 'Invalid Title',
					text: 'Please enter a title for the book.',
					allowOutsideClick: true
				}).then(function() {
					$('#editBookModal').modal('hide');

				});
				return; // Prevent further execution
			}
			var bookAuthor = $('#bookAuthor').val();
			// Check if bookAuthor is empty
			if (bookAuthor.trim() === '') {
				// Display SweetAlert warning
				Swal.fire({
					icon: 'warning',
					title: 'Invalid Author',
					text: 'Please enter an author for the book.',
					allowOutsideClick: true,
				}).then(function() {
					$('#editBookModal').modal('hide');
				});
				return; // Prevent further execution
			}
			var bookPublicationYear = $('#bookPublicationYear').val();
			var bookPrice = $('#bookPrice').val();
			// Check if bookPrice is empty
			if (bookPrice.trim() === '') {
				// Display SweetAlert warning
				Swal.fire({
					icon: 'warning',
					title: 'Invalid Price',
					text: 'Please enter a price for the book.',
					allowOutsideClick: true,
				}).then(function() {
					$('#editBookModal').modal('hide');
				});
				return; // Prevent further execution
			}
			var bookSubject = $('#bookSubject').val();
			var bookYearLevels = $('#bookYearlevels').val();
			var bookQuantity = $('#bookQuantity').val();
			// Check if bookQuantity is 0
			if (parseInt(bookQuantity) === 0) {
				// Display SweetAlert warning
				Swal.fire({
					icon: 'warning',
					title: 'Quantity is Zero',
					text: 'You cannot update a book with zero quantity.',
					allowOutsideClick: true,
				}).then(function() {
					$('#editBookModal').modal('hide');
				});
				return; // Prevent further execution
			}

			// Assuming the form with id 'editBookForm' exists
			var formData = new FormData($('#editBookForm')[0]);
			// Assuming these variables are defined with the correct values
			formData.append('book_id', bookId);
			formData.append('isbn', bookIsbn);
			formData.append('title', bookTitle);
			formData.append('author', bookAuthor);
			formData.append('publication_year', bookPublicationYear);
			formData.append('quantity', bookQuantity);
			formData.append('price', bookPrice);
			formData.append('subject_code', bookSubject);
			formData.append('year_level_name', bookYearLevels);

			if ($('#bookImage')[0].files.length > 0) {
				formData.append('bookImage', $('#bookImage')[0].files[0]);
			}

			console.log(formData);

			$.ajax({
				url: 'update_book.php',
				type: 'POST',
				data: formData,
				contentType: false,
				processData: false,
				success: function(response) {
					console.log(response);
					$('#editBookModal').modal('hide');

					// Display SweetAlert for success
					Swal.fire({
						icon: 'success',
						title: 'Book Updated Successfully',
						showConfirmButton: false,
						timer: 7500 // Close alert after 1.5 seconds
					}).then(function() {
						location.reload(); // Reload the page
					});
				},
				error: function(error) {
					console.log('Error updating book: ' + error);

					// Display SweetAlert for error
					Swal.fire({
						icon: 'error',
						title: 'Error Updating Book',
						text: 'An error occurred while updating the book. Please try again.',
						allowOutsideClick: true, // Allow clicking outside the alert to close it
					}).then(function() {
						$('#editBookModal').modal('hide');
						location.reload(); // Reload the page
					});
				}
			});
		});

		$('#updateBookButton').click(function() {
			var bookId = $('#bookID').val();
			var bookCondition = $('#bookCondition option:selected').text();
			var quantityDamaged = $('#quantityDamaged').val();

			// Additional validation, if needed

			$.ajax({
				url: 'update_damaged_condition.php',
				type: 'POST',
				data: {
					book_id: bookId,
					book_condition: bookCondition,
					quantity_damaged: quantityDamaged
				},
				success: function(response) {
					console.log(response);
					// Handle success, e.g., display a success message
				},
				error: function(error) {
					console.log('Error updating damaged condition: ' + error);
					// Handle error, e.g., display an error message
				}
			});
		});


		// Function to increment quantity
		function incrementQuantity() {
			var currentQuantity = parseInt($('#bookQuantity').val());
			console.log('Before Increment:', currentQuantity);
			$('#bookQuantity').val(currentQuantity + 1);
			console.log('After Increment:', $('#bookQuantity').val());
		}
		// Function to decrement quantity
		function decrementQuantity() {
			var currentQuantity = parseInt($('#bookQuantity').val());
			console.log('Before Decrement:', currentQuantity);
			if (currentQuantity > 0) {
				$('#bookQuantity').val(currentQuantity - 1);
			}
			console.log('After Decrement:', $('#bookQuantity').val());
		}
	</script>
</body>

</html>