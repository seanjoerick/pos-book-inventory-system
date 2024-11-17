<?php

	include '../database.php';
	include_once '../authentication.php';
	include_once '../includes.php';
	include 'admin_dashboard_f.php';
	include 'adsemester.php';
	include 'adsemesterswal.php';
	// THIS IS THE VERY DASHBOARD OF THE ADMIN AFTER LOGGING IN AS ADMIN
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
			<li class="active">
				<a href="admin_dashboard.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="../inventory/inventory.php">
					<i class='bx bxs-book-alt' ></i>
					<span class="text">Book Inventory</span>
				</a>
			</li>
			<li>
				<a href="../point_of_sale/pos.php">
					<i class='bx bxs-dollar-circle' ></i>
					<span class="text">Point of Sale</span>
				</a>
			</li>
			<li>
				<a href="../management.php">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<!-- <li>
				<a href="students.php" class="logout">
					<i class='bx bxs-group' ></i>
					<span class="text">Students</span>
				</a>
			</li> -->
			<li>
				<a href="../staffadmin.php">
					<i class='bx bxs-user-check' ></i>
					<span class="text">Staff and Admins</span>
				</a>
			</li>
			<li>
				<a href="../reports/sales_report.php">
					<i class='bx bxs-chart'></i>
					<span class="text">Sales Report</span>
				</a>
			</li>
			<li class>
                <a href="../history.php">
                    <i class='bx bxs-receipt'></i>
                    <span class="text">History</span>
                </a>
            </li>
			<li>
				<a href="../inventory/archive.php">
					<i class='bx bxs-archive' ></i>
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
	<section id="content">

<?php

    include '../nav.php';

?>
		<!-- INSIDE THE DASHBOARD -->
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
					<button class="btn btn-success btn-sm" id="changeSemesterButton" style="margin-top: -3px;">Change Semester</button>
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
						<span class="text"><h2 style="margin-top:15px;" ><?php echo $formattedTotalBooks; ?></h2>
						<p style='border-top: 1px solid #ccc; padding-top: 5px;'>Total Books</p>
						</span>
					</li>
					<!-- Total Unvoided Transactions (Today) -->
					<li>
						<i class='bx bxs-bar-chart-square'></i>
						<span class="text">
							<h2 style="margin-top:15px;"><?php echo getTotalTransactions(); ?></h2>
							<p style='border-top: 1px solid #ccc; padding-top: 5px;'>Total Transactions (Today)</p>
						</span>
					</li>

					<!-- Total Sales (Today) -->
					<li>
						<i class='bx bxs-coin-stack'></i>
						<span class="text">
							<h2 style="margin-top:15px;">₱<?php echo getTotalTransactionAmount(); ?></h2>
							<p style='border-top: 1px solid #ccc; padding-top: 5px;'>Total Sales (Today)</p>
						</span>
					</li>
					<!-- Recent Transaction -->
					<li>
						<button class="btn">
						<i class='bx bxs-book-content' data-toggle="modal" data-target="#lowStockModal">?</i>
						</button>
						<span class="text">
							<h2 style="margin-top:15px;"><?php echo getLowStockItems(); ?></h2>
							<p style='border-top: 1px solid #ccc; padding-top: 5px;'>Low-Stock Items</p>
						</span>
					</li>

					<!-- Low-Stock Items Modal -->
					<div class="modal fade" id="lowStockModal" tabindex="-1" role="dialog" aria-labelledby="lowStockModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="lowStockModalLabel">Low-Stock Items</h5>
								</div>
								<div class="modal-body" style="max-height: 300px; overflow-y: auto;">
									<?php
									$lowStockBooks = getLowStockBooks();
									if (!empty($lowStockBooks)) {
										echo "<ul style='list-style-type: none; padding: 0; margin: 0;'>";
										foreach ($lowStockBooks as $book) {
											echo "<li style='margin-bottom: 5px;'>{$book['book_id']} - {$book['title']} (Books Left: {$book['quantity_available']})</li>";
										}
										echo "</ul>";
									} else {
										echo "<p>No low-stock items</p>";
									}
									?>
								</div>
								<div class="modal-body">
									 <p>If you want to add more books to the inventory, you can do so in the <a href="/finalcapstone/inventory/inventory.php">Book Inventory</a> page.</p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>
				</ul>	
				<ul class="box-info2">				
				<li>
					<i class='bx bxs-comment-check'></i>
					<span class="text">
					<?php
						$recentTransaction = getRecentTransaction();
						if ($recentTransaction) {
							// Fetch book details from tbl_transactiondetails
							$bookDetails = getBookDetails($recentTransaction['transaction_id']);

							if (!empty($bookDetails)) {
								$formattedTransaction = "";

								foreach ($bookDetails as $detail) {
									$formattedTransaction .= "<b>{$detail['quantity']}x {$detail['title']}</b>, ";
								}

								// Format the total amount with commas
								$formattedTotalAmount = number_format($recentTransaction['total_amount'], 2); // Assuming 2 decimal places for currency

								$formattedTransaction .= "<b>bought by {$recentTransaction['customer_name']} for ₱{$formattedTotalAmount} at {$recentTransaction['transaction_date']}</b>";
								echo "<p style='position:relative; bottom:-10px;'>{$formattedTransaction}</p>";
							} else {
								echo "<p>No book details found for the transaction</p>";
							}
						} else {
							echo "<p>No recent transactions</p>";
						}
					?>
						<p style='border-top: 1px solid #ccc; padding-top: 5px;'>Recent Transaction</p>
					</span>
				</li>
				</ul>
				<ul class="box-info2">
					<li>
						<span class="text">
							<h3>Total Sales Chart</h3>
							<canvas id="salesChart" width="700" height="350"></canvas>
						</span>
					</li>
					<li style="text-align: center;">
						<span class="text">
						<h3>5 Most Books Sold</h3>
							<canvas id="mostBooksSoldChart" style="position: relative; left: 160px;" width="400" height="200"></canvas>
						</span>
					</li>
				</ul>
				</div>
			</div>
		</main>

		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	<script>
    // When the button is clicked, show the modal
    document.getElementById("changeSemesterButton").addEventListener("click", function() {
        $('#semesterModal').modal('show');
    });

	document.addEventListener('DOMContentLoaded', function() {
    const mostSoldBooksData = <?php echo json_encode(getMostSoldBooksData(5)); ?>;
    
    const mostBooksSoldChartCanvas = document.getElementById('mostBooksSoldChart');
    const mostBooksSoldChartContext = mostBooksSoldChartCanvas.getContext('2d');

    if (mostSoldBooksData) {
        new Chart(mostBooksSoldChartContext, {
            type: 'pie',
            data: {
                labels: mostSoldBooksData.labels,
                datasets: [{
                    data: mostSoldBooksData.data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1,
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    datalabels: {
                        color: '#fff',
                        formatter: (value, context) => {
                            return context.chart.data.labels[context.dataIndex] + ': ' + value;
                        }
                    }
                }
            }
        });
    }
});


	</script>
	<!-- <script src="admin_dashboard.js"></script>	 -->
</body>
</html>
