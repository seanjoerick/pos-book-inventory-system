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
			<li class="active">
				<a href="pos.php">
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
				<a href="../students.php" class="logout">
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
			<li>
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