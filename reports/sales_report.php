<?php

include '../database.php';
include_once '../authentication.php';
include_once '../includes.php';

// Retrieve sales data from the database
$salesQuery = "SELECT t.transaction_date, t.total_amount
	FROM tbl_transactions t
	ORDER BY t.transaction_date DESC"; // You can modify the query based on your specific needs
$salesResult = mysqli_query($db_connection, $salesQuery);

?>




<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- My CSS -->
	<link rel="stylesheet" href="../style.css">

	<title>Administrator Hub</title>
</head>

<body>


	<!-- LEFTSIDE NAVBAR FOR ADMIN -->
	<section id="sidebar">
		<a href="#" class="brand">
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
			<li>
				<a href="../admin_dashboard/admin_dashboard.php">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="../inventory/inventory.php">
					<i class='bx bxs-book-alt'></i>
					<span class="text">Book Inventory</span>
				</a>
			</li>
			<li>
				<a href="../point_of_sale/pos.php">
					<i class='bx bxs-dollar-circle'></i>
					<span class="text">Point of Sale</span>
				</a>
			</li>
			<li>
				<a href="../management.php">
					<i class='bx bxs-cog'></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<!-- <li>
				<a href="../students.php" class="logout">
					<i class='bx bxs-group' ></i>
					<span class="text">Students</span>
				</a>
			</li> -->
			<li>
				<a href="../staffadmin.php">
					<i class='bx bxs-user-check'></i>
					<span class="text">Staff and Admins</span>
				</a>
			</li>
			<li class="active">
				<a href="sales_report.php">
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
				<a href="../inventory/archive.php">
					<i class='bx bxs-archive'></i>
					<span class="text">Archive</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<!-- <li>
				<a href="../settings.php" class="logout">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li> -->
			<!-- <li>
				<a href="../help.php" class="logout">
					<i class='bx bxs-help-circle' ></i>
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

		<main>
			<div class="head-title">
				<div class="left">
					<h1 text-align>Sales Report</h1>
					<ul class="breadcrumb">
						<li>
							<a>The Sales Report section is the central hub for every book transaction,
								serving as the control center for tracking sales.
							</a>
					</ul>
				</div>
			</div>
			<div class="container mt-5">
				<div class="d-flex justify-content-center">
					<div class="card">
						<div class="card-header">
							<h5>Select a date range to filter sales data</h5>
						</div>
						<div class="card-body">
							<form action="sales_report.php" method="POST">
								<div class="row">
									<div class="col-md-5">
										<div class="form-group">
											<label for="startDate">Start Date</label>
											<input type="date" name="startDate" id="startDate" class="form-control" required>
										</div>
									</div>
									<div class="col-md-5">
										<div class="form-group">
											<label for="endDate">End Date</label>
											<input type="date" name="endDate" id="endDate" class="form-control" required>
										</div>
									</div>
									<div class="col-md-2">
										<button type="submit" name="filter" class="btn btn-primary">Filter</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>

				<?php
				// Initialize variables to store the date range filter
				$dateFilter = "";

				// Check if the form is submitted and the start date is set
				if (isset($_POST['filter']) && isset($_POST['startDate'])) {
					// Get the start date from the form
					$startDate = $_POST['startDate'];

					// Check if the end date is set
					if (isset($_POST['endDate'])) {
						// Get the end date from the form
						$endDate = $_POST['endDate'];
					} else {
						// If the end date is not set, use the start date as the default
						$endDate = $startDate;
					}

					// Increment the end date by 1 day to include it in the range
					$endDate = date('Y-m-d', strtotime($endDate . ' + 1 day'));

					// Create the date range filter for the SQL query
					$dateFilter = "WHERE t.transaction_date BETWEEN '$startDate' AND '$endDate'";
				}

				// Modify the sales query to include the date range filter
				$salesQuery = "SELECT t.transaction_id, t.transaction_date, t.total_amount, r.receipt_number,
                       GROUP_CONCAT(td.quantity, ' ', b.title SEPARATOR '<br>') as product_details
               FROM tbl_transactions t
               JOIN tbl_transactiondetails td ON t.transaction_id = td.transaction_id
               JOIN tbl_books b ON td.book_id = b.book_id
               JOIN tbl_receipts r ON t.transaction_id = r.transaction_id
               $dateFilter
               GROUP BY t.transaction_id
               ORDER BY t.transaction_date DESC";

				$salesResult = mysqli_query($db_connection, $salesQuery);

				// Display the sales data
				if ($salesResult && mysqli_num_rows($salesResult) > 0) {
					echo "<table class='table table-striped mt-4'>";
					echo "<thead>";
					echo "<tr>";
					echo "<th>Date</th>";
					echo "<th>Receipt Number</th>";
					echo "<th>Product(s)</th>";
					echo "<th>Total Sales</th>";
					echo "</tr>";
					echo "</thead>";
					echo "<tbody>";

					while ($row = mysqli_fetch_assoc($salesResult)) {
						echo "<tr>";
						echo "<td>" . $row['transaction_date'] . "</td>";
						echo "<td>" . $row['receipt_number'] . "</td>";
						echo "<td>" . $row['product_details'] . "</td>";
						echo "<td>₱" . number_format($row['total_amount'], 2) . "</td>";
						echo "</tr>";
					}

					echo "</tbody>";
					echo "</table>";
				} else {
					// Handle the case where there is no sales data for the selected date range
					echo "<p>No sales data available for the selected date range.</p>";
				}
				?>


			</div>