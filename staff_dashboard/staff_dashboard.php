<?php

	include '../database.php';
	include '../authentication.php';
	include '../includes.php';
	include 'staff_dashboard_f.php';
	// THIS IS THE STAFF DASHBOARD!
	// NOTE: TO BE ABLE TO ACCESS THIS PAGE, RUN THE create_staff.php.
	// THE PLACEHOLDER USER AND PASS SHOULD BE: staff
	// FOR NOW, WAG MUNA PAKIELAMAN, YUNG ADMIN MUNA. 
		?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- My CSS -->
	<link rel="stylesheet" href="../style.css">

	<title>Staff Hub</title>
</head>
<body>
	<!-- LEFTSIDE NAVBAR-->
	<section id="sidebar">
		<a href="#" class="brand">
			<img src="../images/neustlogo.png" style="margin-left: 12px;" alt="NEUST Logo" width="60" height="60">
			<span class="text" style="margin-left: 20px;">User Staff</span>
		</a>
		<?php
    if ($_SESSION['role'] === 'staff') {
        $loggedInStaffID = $_SESSION["user_id"];
        $staffNameQuery = "SELECT first_name, last_name FROM tbl_users WHERE user_id = $loggedInStaffID";
        $staffNameResult = mysqli_query($db_connection, $staffNameQuery);
        
        if ($staffNameResult && $staffData = mysqli_fetch_assoc($staffNameResult)) {
            echo "<div class='alert alert-info mt-0 mb-0 text-center strong'>";
            echo "<strong>" . $staffData['first_name'] . " " . $staffData['last_name'] . "</strong>";
            echo "</div>";
        }
    }
    ?>
		<ul class="side-menu top">
			<li class="active">
				<a href="staff_dashboard.php">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="../point_of_sale2/pos.php">
					<i class='bx bxs-dollar-circle' ></i>
					<span class="text">Point of Sale</span>
				</a>
			</li>
			<li>
				<a href="../inventory/inventory.php">
					<i class='bx bxs-book-alt' ></i>
					<span class="text">Book Inventory</span>
				</a>
			</li>
			<!-- <li>
				<a href="customers.php" class="logout">
					<i class='bx bxs-group' ></i>
					<span class="text">Customers List</span>
				</a>
			</li> -->
			<li>
				<a href="../history.php">
					<i class='bx bxs-receipt' ></i>
					<span class="text">History</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<li>
				<a href="../logout.php" class="logout" onclick="return confirmLogout();">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		<?php //CODE FOR CONFIRMING YOU WANT TO LOG OUT ?>
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

	<!-- CONTENT -->
	<section id="content">

<?php 

include '../nav.php';

?>

		<!-- MAIN -->
		<main>
		<div class="head-title">
			<div class="left">
				<h1>Book Inventory & Point of Sale System</h1>
				<!-- <div id="pst" class="pst" style="font-size: 18px; color: #333;"></div> -->
				<div>
					<span style="font-size: 18px; color: #333;">Current Semester:</span>
					<span id="currentSemester" style="font-size: 18px;  color: #007bff;">
						<?php
						// Retrieve the name of the current semester from your database
						$query = "SELECT semester_name FROM tbl_semesters WHERE is_current = 1";
						$result = mysqli_query($db_connection, $query);

						if ($result && mysqli_num_rows($result) > 0) {
							$row = mysqli_fetch_assoc($result);
							echo $row['semester_name'];
						} else {
							echo "No current semester selected";
						}
						?>
					</span>
					<!-- <button class="btn btn-success btn-sm" id="changeSemesterButton" style="margin-top: -3px;">Change Semester</button> -->
				</div>
				<ul class="breadcrumb">
					<li>
						<a href="#">This dynamic dashboard is your personal window into our extensive book inventory and point of sale system. It offers real-time statistics and insights on book titles, and sales data, allowing you to efficiently manage your book operations.</a>
					</li>
				</ul>
			</div>
		</div>
			<ul class="box-info1">
					<li>
						<i class='bx bxs-book' ></i>
						<span class="text"><h2 style="margin-top:15px;" ><?php echo getTotalBooks(); ?></h2>
						<p>Total Books</p>
						</span>
					</li>
					<li>
						<i class='bx bxs-bar-chart-square' ></i>
						<span class="text"><h2 style="margin-top:15px;" ><?php echo getTotalTransactions(); ?></h2>
							<p>Total Unvoided Transactions</p>
						</span>
					</li>
					<li>
						<i class='bx bxs-coin-stack' ></i>
						<span class="text"><h2 style="margin-top:15px;" >₱<?php echo getTotalTransactionAmount(); ?></h2>
							<p>Total Sales</p>
						</span>
					</li>
					<li>
						<i class='bx bxs-comment-check' ></i>
						<span class="text">
							<p>Recent Transaction</p>
						</span>
					</li>
				</ul>
				<ul class="box-info2">
					<li>
						<i class='bx bxs-coin'></i>
						<span class="text">
							<canvas id="salesChart" width="600" height="200"></canvas>
							<p>Sales Chart</p>
						</span>
					</li>
                <!-- <li>
					<i class='bx bxs-coin' ></i>
					<span class="text">
						<h3></h3>
						<p>NOTE: ALL THINGS ARE SUBJECT TO CHANGE</p>
					</span>
				</li> -->
			</ul>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script.js"></script>
</body>
</html>
