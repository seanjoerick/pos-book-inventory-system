<?php

	include '../database.php';
	include '../authentication.php';
	include '../includes.php';
    include 'posmodal.php';
	// THIS IS THE VERY DASHBOARD OF THE ADMIN AFTER LOGGING IN AS ADMIN

		?>


<a href="../logout.php" class="logout">  
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- My CSS -->
    <link rel="icon" href="../images/neustlogo.ico" type="image/x-icon">
	<link rel="stylesheet" href="../style.css">
	<title>Administrator Hub</title>
</head>
<body>
	<style>
    #imageModal .modal-dialog {
        max-width: 20%;
        width: auto; 
		border-radius: 20px;
		border: 2px black;
    }

    #enlargedImg {
        border: 2px solid black;
		border-radius: 10px;
        width: 100%;
        height: auto;
        display: block;
        margin: auto;
    }
</style>
	<?php include 'possidebar.php'; ?>

	<section id="content">

	<?php 

include '../nav.php';

?>
    <!-- INSIDE THE DASHBOARD -->

	<?php

if (isset($_SESSION['cartData'])) {
	$cartData = json_decode($_SESSION['cartData'], true);

?>

    <main>
        <div class="head-title">
            <div class="left">
                <h1>Checkout</h1>
            </div>
        </div>


		<div>
			<label for="isStudent" style="margin-bottom: 5px;">Select Customer:</label>
			<select name="isStudent" id="isStudent" style="margin-bottom: 5px;">
				<option disabled selected value="na">N/A</option>
				<option value="student">Student</option>
				<option value="non-student">Non-Student</option>
			</select>
    	</div>

		<!-- IF OPTION: CUSTOMER TYPE STUDENT -->
		<div id="studentTypeDiv" style="display:none">
			<label for="studentType" style="margin-bottom:5px;">Select Student Type:</label>
			<select name="studentType" id="studentType" style="margin-bottom:5px;">
				<option disabled selected value="na">N/A</option>
				<option value="existing">Registered Student</option>
				<option value="new">Unregistered Student</option>
			</select>
		</div>

		<!-- IF OPTION: REGISTERED STUDENT -->
		<!-- PICK STUDENT -->
		<div id="existingStudentSection" style="display:none;">
			<div>
			<label for="existingStudentNumber" style="margin-bottom:5px;">Select Student Name:
				<input type="text" id="searchInput" oninput="filterOptions()" placeholder="Search..."></label>
			</div>
			<select name="existingStudentNumber" id="existingStudentNumber" style="margin-bottom:5px; margin-left: 176px; position: relative; top: -5px; width: 205px;" size="4">
				<option disabled selected value="na">Select</option>
				<?php
				$sql = "SELECT customer_id, customer_type, student_number, first_name, last_name, email FROM tbl_customers WHERE customer_type = 'Student' ORDER BY first_name";
				$result = mysqli_query($db_connection, $sql);

				if (!$result) {
					die("Query failed: " . mysqli_error($db_connection));
				}

				while ($row = mysqli_fetch_assoc($result)) {
					echo '<option value="' . $row['customer_id'] . '">' . $row['first_name'] . ' ' . $row['last_name'] . '</option>';
				}
				?>
			</select>

			<!-- PICK TEACHER -->
			<label for="advisoryTeacher" style="position: relative; top: -115px;">Select Advisory Teacher(if required):</label>
			<input type="text" id="teacherSearchInput" oninput="filterTeacherOptions()" placeholder="Search..." style="margin-bottom: 5px; position: relative; top: -115px;">
			<select name="advisoryTeacher" id="advisoryTeacher" size="4" style="position: relative; top: -10px; left: -210px; width: 205px;">
					<option disabled selected value="na">Select</option>
					<option value="none">Not Applicable</option>
					<?php

					$sqlTeachers = "SELECT teacher_id, first_name, last_name FROM tbl_teachers ORDER BY first_name";
					$resultTeachers = mysqli_query($db_connection, $sqlTeachers);

					if (!$resultTeachers) {
						die("Query failed: " . mysqli_error($db_connection));
					}
					while ($rowTeacher = mysqli_fetch_assoc($resultTeachers)) {
						echo '<option value="' . $rowTeacher['teacher_id'] . '">' . $rowTeacher['first_name'] . ' ' . $rowTeacher['last_name'] . '</option>';
					}
					?>
				</select>
		</div>
	
        <div id="studentCard" class="card" style="border-radius: 10px; border: 1px solid #ccc; background-color: #f9f9f9; display: none; margin-bottom:5px;">
			<div id="studentDataDisplay" style="border-radius: 10px;">
    			<!-- Students info in here -->
			</div>
		</div>

		<!-- IF OPTION: UNREGISTERED STUDENT -->
		<div id="newStudentSection" style="display:none; margin-top:5px; margin-bottom:5px;">
			<div class="card" style="border-radius:20px;">
				<div class="card-body">
					<h2 class="mb-4">Register New Student</h2>
					<form id="newStudentForm">
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="first_name" class="form-label">First Name:</label>
								<input type="text" name="first_name" class="form-control" placeholder="Michael" required>
							</div>
							<div class="col-md-6">
								<label for="last_name" class="form-label">Last Name:</label>
								<input type="text" name="last_name" class="form-control" placeholder="Jackson" required>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-6">
								<label for="student_number" class="form-label">Student Number:</label>
								<input type="text" name="student_number" class="form-control" placeholder="SUM20XX-XXXXX" required>
							</div>
							<div class="col-md-6">
								<label for="phone_number" class="form-label">Phone Number:</label>
								<div class="input-group">
									<span class="input-group-text">+63</span>
									<input type="text" name="phone_number" class="form-control" pattern="[0-9]{11}" placeholder="09123456789" required>
								</div>
								<small id="phoneHelp" class="form-text text-muted">Please enter a valid 11-digit phone number.</small>
							</div>
						</div>

						<div class="mb-3">
							<label for="email" class="form-label">Email:</label>
							<input type="email" name="email" class="form-control" placeholder="example@email.com" required>
						</div>

						<button type="submit" class="btn btn-primary">Register</button>
					</form>
				</div>
			</div>
		</div>

				<!-- IF OPTION: CUSTOMER TYPE NON-STUDENT -->

		<div id="customerTypeDiv" style="display:none">
			<label for="customerType" style="margin-bottom:5px;">Select Customer Type:</label>
			<select name="customerType" id="customerType" style="margin-bottom:5px;">
				<option disabled selected value="na">N/A</option>
				<option value="existings">Registered Customer</option>
				<option value="news">Unregistered Customer</option>
			</select>
		</div>

		<!-- PICK CUSTOMER -->
		<div id="existingCustomerSection" style="display:none;">
			<div>
				<label for="existingCustomerName" style="margin-bottom:5px;">Select Customer Name:</label>
				<input type="text" id="customerNameSearchInput" oninput="filterCustomerOptions()" placeholder="Search..." style="margin-bottom: 5px;">
			</div>
					<select name="existingCustomerName" id="existingCustomerName" style="margin-bottom:5px; margin-left: 193px; position: relative; top: -5px; width: 205px;" size="4">
						<option disabled selected value="na">Select</option>
						<?php
						$sql = "SELECT customer_id, customer_type, first_name, last_name, email FROM tbl_customers WHERE customer_type = 'Non-Student' ORDER BY first_name";
						$result = mysqli_query($db_connection, $sql);

						if (!$result) {
							die("Query failed: " . mysqli_error($db_connection));
						}

						while ($row = mysqli_fetch_assoc($result)) {
							echo '<option value="' . $row['customer_id'] . '">' . $row['first_name'] . ' ' . $row['last_name'] .'</option>';
						}
						?>
					</select>
		</div>

        <div id="customerCard" class="card" style="border-radius: 10px; border: 1px solid #ccc; background-color: #f9f9f9; display: none; margin-bottom:5px;">
			<div id="customerDataDisplay" style="border-radius: 10px;">
    			<!-- Customer info in here -->
			</div>
		</div>

				<!-- IF OPTION: UNREGISTERED CUSTOMER -->
		<div id="newCustomerSection" style="display:none; margin-top:5px; margin-bottom:5px;">
			<div class="card" style="border-radius:20px;">
				<div class="card-body">
					<h2 class="mb-4">Register New Customer</h2>
					<form id="newCustomerForm">
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="first_name" class="form-label">First Name:</label>
								<input type="text" name="first_name" class="form-control" placeholder="Michael" required>
							</div>
							<div class="col-md-6">
								<label for="last_name" class="form-label">Last Name:</label>
								<input type="text" name="last_name" class="form-control" placeholder="Jackson" required>
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-6">
							<label for="phone_number" class="form-label">Phone Number:</label>
								<div class="input-group">
									<span class="input-group-text">+63</span>
									<input type="text" name="phone_number" class="form-control" pattern="[0-9]{11}" placeholder="09123456789" required>
								</div>
								<small id="phoneHelp" class="form-text text-muted">Please enter a valid 11-digit phone number.</small>
							</div>
							<div class="col-md-6">
								<label for="email" class="form-label">Email:</label>
								<input type="email" name="email" class="form-control" placeholder="example@email.com" required>
							</div>
						</div>

						<button type="submit" class="btn btn-primary">Register</button>
					</form>
				</div>
			</div>
		</div>


		<!-- THE TABLE WHICH CONTAINS THE ITEMS PLANNING TO BUY -->
		<?php } else {
			// echo'you dont have anything.';
			}
			
			$total = 0;
			$totalbooks = 0;
			
			if (!empty($cartData)) {

				$modifiedCartData = [];

				echo '<div class="table-responsive" style="border-width: 10px;">';
				echo '<table class="table table-bordered table-striped" style="border: 1px #ccc;">';
				echo '<thead class="thead-dark">';
				echo '<tr>';
				echo '<th style="width: 10px;"></th>';
				echo '<th>Book Title</th>';
				echo '<th>Quantity</th>';
				echo '<th>Price</th>';
				echo '<th style="width: 70px;">Discount (%)</th>';
				echo '<th>Subtotal</th>';
				echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
			
				foreach ($cartData as $item) {

					// Calculate and display subtotal
					$discountedSubtotal = $item['quantity'] * ($item['price'] * (1 - (isset($item['discount']) ? $item['discount'] : 0) / 100));

					// Add the necessary data to the modified cart data
					// $modifiedCartData[] = [
					// 	'book_id' => $item['book_id'],
					// 	'subtotal' => $discountedSubtotal,
					// 	'discount' => isset($item['discount']) ? $item['discount'] : 0,
					// ];

					echo '<tr>';
					echo '<td><img src="' . $item['image'] . '" alt="Book Image" class="img-fluid preview-img" style="max-width: 60px; max-height: 60px; border: 2px solid #ccc; border-radius: 20px; cursor: pointer;"></td>';
					echo '<td>' . $item['title'] . '</td>';
					echo '<td>' . $item['quantity'] . 'x</td>';
					echo '<td>₱' . $item['price'] . '</td>';
					
					// Discount Input
					echo '<td style="width: 70px;">';
					echo '<input type="text" name="discount[]" class="form-control discount-input" oninput="updateDiscountedPrice(this)" value="' . (isset($item['discount']) ? $item['discount'] : 0) . '" min="0" max="100">';
					echo '</td>';
					

					echo '<td class="subtotal">₱' . $discountedSubtotal . '</td>';
					
					// Accumulate subtotal to calculate total
					$total += $discountedSubtotal;
					$totalbooks += $item['quantity'];
					
					// Add other item details as needed
					echo '</tr>';
				}
			
				// Display the total row
				echo '<tr style="font-weight: bold; border: 2px solid black; background-color: #f9f9f9; ">';
				echo '<td class="text-right" colspan="2">Total:</td>';
				echo '<td>' . $totalbooks . ' books</td>';
				echo '<td></td>';
				echo '<td></td>';
				echo '<td class="total">₱' . $total . '</td>';
				echo '</tr>';
			
				echo '</tbody>';
				echo '</table>';


				echo '<div class="row" style="width:90%;">';
				echo '<div class="col-lg-6 text-right">';
				
				// Payment Amount Input
				echo '<div class="text-right" style="margin-bottom: 20px;">';
				echo '<label for="paymentAmount">Enter Payment Amount: </label>';
				echo '<div class="input-group" style="width: 50%;">';
				echo '<span class="input-group-text">₱</span>';
				echo '<input type="text" name="paymentAmount" id="paymentAmount" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, \'\');" required>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
				
				echo '<div class="col-lg-6 text-left">';
				
				// Receipt Number Input
				echo '<div class="text-left" style="margin-bottom: 20px;">';
				echo '<label for="receiptNumber">Enter Receipt Number: </label>';
				echo '<div class="input-group" style="width: 30%;">';
				echo '<button type="button" class="btn btn-light custom-border" data-toggle="modal" data-target="#receiptInfoModal">?</button>';
				echo '<input type="text" name="receiptNumber" id="receiptNumber" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, \'\');" value="0" required>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			
				echo '</div>';
			} else {
				echo '<p class="text-center">Your cart is empty.</p>';
				echo '<script>window.location.href = "pos.php";</script>';
			}

			echo '<div id="totalAmount" data-total-amount="' . $total . '" style="display:none;"></div>';
			?>

		<div class="row">
			<div class="col-6">
				<a href="#" class="btn btn-danger btn-block" id="cancelTransaction">Cancel/Void Transaction</a>
			</div>
			<div class="col-6">
				<button id="confirmTransactionButton" class="btn btn-success" style="display: none;" type="button" onclick="confirmTransaction()">Confirm Transaction</button>
			</div>
		</div>



    </main>

</section>
<script>
	            const cartData = <?php echo json_encode($cartData); ?>;
</script>
<script src="checkout.js"></script>
</body>
</html>