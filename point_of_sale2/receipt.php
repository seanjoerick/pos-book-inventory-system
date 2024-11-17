<?php

include '../database.php';
include '../authentication.php';
include '../includes.php';
require_once 'function.php';



?>

<a href="../logout.php" class="logout">  
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="../images/neustlogo.ico" type="image/x-icon">
	<link rel="stylesheet" href="../style.css">
	<title>Administrator Hub</title>
</head>
<body>

<?php

include 'possidebar.php';

?>

<section id="content">

<?php 

include '../nav.php';

?>
		<!-- INSIDE THE DASHBOARD -->
		<main>

        <?php

if (isset($_SESSION['receipt_data'])) {
    // Access the data from the session
    $customer_id = null;

    $transaction_details = isset($_SESSION['receipt_data']['cartData']) ? $_SESSION['receipt_data']['cartData'] : [];
    if (isset($_SESSION['receipt_data']['existingStudentNumber']) && $_SESSION['receipt_data']['existingStudentNumber'] !== 'na') {
        $customer_id = $_SESSION['receipt_data']['existingStudentNumber'];
    } elseif (isset($_SESSION['receipt_data']['existingCustomerName']) && $_SESSION['receipt_data']['existingCustomerName'] !== 'na') {
        $customer_id = $_SESSION['receipt_data']['existingCustomerName'];
    }
    $user_id = $_SESSION["user_id"];
    $receipt_number = isset($_SESSION['receipt_data']['receiptNumber']) ? $_SESSION['receipt_data']['receiptNumber'] : null;
    $teacher_id_before = isset($_SESSION['receipt_data']['advisoryTeacher']) ? $_SESSION['receipt_data']['advisoryTeacher'] : null;
    $paid_amount = isset($_SESSION['receipt_data']['paymentAmount']) ? $_SESSION['receipt_data']['paymentAmount'] : null;
    $total_amount = isset($_SESSION['receipt_data']['totalAmount']) ? $_SESSION['receipt_data']['totalAmount'] : null;
    $change_amount = isset($_SESSION['receipt_data']['change']) ? $_SESSION['receipt_data']['change'] : null;
    // $transaction_details = isset($_SESSION['cartData']) ? json_decode($_SESSION['cartData'], true) : [];
    $teacher_id = ($teacher_id_before === "none" || $teacher_id_before === "na") ? null : $teacher_id_before;
    $transaction_date = date("Y-m-d H:i:s");
    $formatted_date = date('m/d/y', strtotime($transaction_date));
    $semester_id = getCurrentSemesterId();
    $semester_name = getSemesterName($semester_id);
    $agency_name = 'NEUST Marketing Department';
    $payment_method = 'Cash';
// NEW
    $first_name = null;
    $last_name = null;
    if ($customer_id !== null) {
        $query = "SELECT first_name, last_name FROM tbl_customers WHERE customer_id = ?";
        $stmt = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $customer_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $first_name, $last_name);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }

    $customer_name = $first_name . ' ' . $last_name;

    $userDetails = getUserDetails($db_connection, $_SESSION["user_id"]);
    $collectingOfficer = $userDetails ? $userDetails['first_name'] . ' ' . $userDetails['last_name'] : 'Unknown';

    // Insert data into tbl_transactions
    $sql = "INSERT INTO tbl_transactions (customer_id, teacher_id, user_id, semester_id, transaction_date, total_amount, paid_amount, change_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db_connection, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssss", $customer_id, $teacher_id, $user_id, $semester_id, $transaction_date, $total_amount, $paid_amount, $change_amount);
    mysqli_stmt_execute($stmt);
    $transaction_id = mysqli_insert_id($db_connection);
    mysqli_stmt_close($stmt);


    // Insert data into tbl_transactiondetails
    foreach ($transaction_details as $item) {

        $bookId = $item['book_id'];
        $quantity = $item['quantity'];
        $subtotal = $item['subtotal'];

        $sql = "INSERT INTO tbl_transactiondetails (transaction_id, book_id, quantity, subtotal) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($db_connection, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $transaction_id, $bookId, $quantity, $subtotal);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Subtract the quantity_sold from tbl_books
        $sqlUpdateQuantity = "UPDATE tbl_books SET quantity_available = quantity_available - ? WHERE book_id = ?";
        $stmtUpdateQuantity = mysqli_prepare($db_connection, $sqlUpdateQuantity);
        mysqli_stmt_bind_param($stmtUpdateQuantity, "ss", $quantity, $bookId);
        mysqli_stmt_execute($stmtUpdateQuantity);
        mysqli_stmt_close($stmtUpdateQuantity);
    }

    $first_name = null;
    $last_name = null;
    if ($customer_id !== null) {
        $query = "SELECT first_name, last_name FROM tbl_customers WHERE customer_id = ?";
        $stmt = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $customer_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $first_name, $last_name);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }

    $customer_name = $first_name . ' ' . $last_name;

    $userDetails = getUserDetails($db_connection, $_SESSION["user_id"]);
    $collectingOfficer = $userDetails ? $userDetails['first_name'] . ' ' . $userDetails['last_name'] : 'Unknown';
    
?>

<!-- Add these buttons to the end of your HTML body -->
<div class="container receipt-container">
<div class="row">
    <div class="col">
        <a href="pos.php" class="btn btn-primary"><i class="bx bxs-left-arrow-alt"></i> Return to Point of Sale</a>
    </div>
    <div class="col">
        <button id="voidTransaction" class="btn btn-danger"><i class="bx bxs-x-square"></i> Void Transaction</button>
    </div>
    <div class="col text-end">
        <button id="downloadButton" class="btn btn-info"><i class="bx bxs-download"></i> Download as PDF</button>
    </div>
</div>
</div>

<div id="receipt">
<link rel="stylesheet" href="../style.css">
<div class="container receipt-container" style="font-size: 16px;">
    <div class="border-container" style="border: 1px solid #000; padding: 15px;">
        <!-- Receipt Header -->
        <div class="row receipt-header">
            <div class="col-auto">
                <img src="../images/neustlogo.png" alt="Logo" class="logo">
            </div>
            <div class="col">
                <h2>Official Receipt</h2>
<?php
            echo '<p>Receipt Number: ' . htmlspecialchars($receipt_number) . '</p>';
            echo '<p>Date: ' . htmlspecialchars($formatted_date) . '</p>';
            echo '<p>' . htmlspecialchars($semester_name) . '</p>'; 
 ?>
            </div>
        </div>

        <!-- Receipt Details -->
        <div class="row receipt-details">
            <div class="col">
            <?php                
            echo '<p>Agency Name: ' . htmlspecialchars($agency_name) . '</p>';
            echo '<p>Payer Name: ' . htmlspecialchars($customer_name) . '</p>'; 
            ?>
            </div>
        </div>

        <!-- Transaction Details -->
        <div class="row">
            <div class="col">
                <table class="table receipt-table">
                    <thead>
                    <tr>
                        <th>Nature of Collection</th>
                        <th>Item Code</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Assuming $transaction_details contains the array with your cart data
                    $rowCount = count($transaction_details);

                    // Set a minimum of 8 rows
                    $minRows = max(5, $rowCount);
                    $totalAmount = 0; // Initialize outside the loop

                    for ($i = 0; $i < $minRows; $i++) {
                        // Check if there is actual data in the row
                        $hasData = isset($transaction_details[$i]['quantity']) || isset($transaction_details[$i]['title']) || isset($transaction_details[$i]['subtotal']);

                        if ($hasData) {
                            // Get data for the current row from $transaction_details
                            $quantity = isset($transaction_details[$i]['quantity']) ? $transaction_details[$i]['quantity'] : '&nbsp;';
                            $title = isset($transaction_details[$i]['title']) ? $transaction_details[$i]['title'] : '&nbsp;';
                            $subtotal = isset($transaction_details[$i]['subtotal']) ? '₱' . number_format($transaction_details[$i]['subtotal'], 2) : '&nbsp;';
                            $discount = isset($transaction_details[$i]['discount']) ? $transaction_details[$i]['discount'] : 0;

                            // $totalAmount += (float)$transaction_details[$i]['subtotal']; // Accumulate total

                            // Output the row

                            echo '<td>' . $quantity . ' ' . $title . '</td>';
                            echo '<td></td>';
                            if ($discount > 0) {
                                echo '<td>' . $subtotal . ' (' . $discount . '% off)</td>';
                            } else {
                                echo '<td>' . $subtotal . '</td>';
                            }
                            echo '</tr>';
                        } else {
                            // Output an empty row
                            echo '<tr>';
                            echo '<td>&nbsp;</td>';
                            echo '<td></td>';
                            echo '<td>&nbsp;</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    <!-- Add more rows as needed -->
                    <tr>
                        <td colspan="2">Paid Amount:</td>
                        <td>₱<?php echo number_format($paid_amount, 2); ?></td>
                    <tr class="total-amount">
                        <td colspan="2">Total:</td>
                        <td>₱<?php echo number_format($total_amount, 2); ?></td>
                    <tr>
                        <td colspan="2">Change:</td>
                        <td>₱<?php echo number_format($change_amount, 2); ?></td>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- Amount in Words -->
        <div class="row">
            <div class="col">
                <p>Total amount in Words: <?php echo convertNumberToWords($total_amount); ?></p>
            </div>
        </div>

        <!-- Payment Method and Collecting Officer -->
        <div class="row footer">
            <div class="col">
                <p>Payment Method: Cash</p>
                <p>Collecting Officer: <?php echo $collectingOfficer; ?></p>
            </div>
        </div>
    </div>
</div>
                </div>

</body>
</html>
<script>

    
function showSuccessMessage() {
            Swal.fire({
                icon: 'success',
                title: 'Thank You!',
                text: 'Your transaction was successful.',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
        }

    document.addEventListener('DOMContentLoaded', function () {
        showSuccessMessage();

        document.getElementById('downloadButton').addEventListener('click', function () {

            var element = document.getElementById('receipt');

            var options = {
                margin: 10,
                filename: 'receipt.pdf', // Set the filename here
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            html2pdf(element, options);
        });

// Show confirmation dialog on voidTransaction button click
document.getElementById('voidTransaction').addEventListener('click', function () {
    Swal.fire({
        icon: 'warning',
        title: 'Void Transaction',
        text: 'Are you sure you want to void this transaction?',
        showCancelButton: true,
        confirmButtonText: 'Yes, void it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
    }).then((result) => {
        if (result.isConfirmed) {
            // If the user confirms, show the admin credentials modal
            showAdminCredentialsModal();
        }
    });
});

// Function to show the admin credentials modal
function showAdminCredentialsModal() {
    Swal.fire({
        title: 'Admin Authentication',
        html:
            '<input id="swal-username" class="swal2-input" placeholder="Admin Username">' +
            '<input type="password" id="swal-password" class="swal2-input" placeholder="Admin Password">',
        focusConfirm: false,
        preConfirm: () => {
            const adminUsername = Swal.getPopup().querySelector('#swal-username').value;
            const adminPassword = Swal.getPopup().querySelector('#swal-password').value;
            return { adminUsername, adminPassword };
        },
    }).then((result) => {
        if (result.isConfirmed) {
            const { adminUsername, adminPassword } = result.value;
            // Call the voidTransaction function with admin credentials
            voidTransaction(adminUsername, adminPassword);
        }
    });
}

// Function to void the transaction using AJAX
function voidTransaction(adminUsername, adminPassword) {
    var transactionId = <?php echo $transaction_id; ?>; // Assuming $transaction_id is available in your PHP code

    // Make an AJAX request to authenticate and update the is_void column in the database
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                // Check the response from the server
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Transaction Voided',
                        text: 'The transaction has been voided successfully.',
                        showConfirmButton: true,
                        confirmButtonText: 'Return to POS',
                    }).then(() => {
                        // Redirect to POS.php after clicking "Return to POS"
                        window.location.href = 'pos.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.error || 'An error occurred while voiding the transaction.',
                        showConfirmButton: true,
                        confirmButtonText: 'OK',
                    });
                }
            } else {
                // Handle HTTP errors
                console.error('HTTP error: ' + xhr.status);
            }
        }
    };
    xhr.open('POST', 'void_transaction.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('transaction_id=' + transactionId + '&adminUsername=' + encodeURIComponent(adminUsername) + '&adminPassword=' + encodeURIComponent(adminPassword));
}
});

</script>


<?php

    $amount_in_words = isset($totalAmount) ? convertNumberToWords($totalAmount) : '';
    $sqlReceipts = "INSERT INTO tbl_receipts (transaction_id, receipt_number, agency_name, amount_in_words, payment_method) VALUES (?, ?, ?, ?, ?)";
    $stmtReceipts = mysqli_prepare($db_connection, $sqlReceipts);
    mysqli_stmt_bind_param($stmtReceipts, "issss", $transaction_id, $receipt_number, $agency_name, $amount_in_words, $payment_method);
    mysqli_stmt_execute($stmtReceipts);
    mysqli_stmt_close($stmtReceipts);

    // Clear the session variables after processing
    unset($_SESSION['receipt_data']);
    unset($_SESSION['cartData']);
} else {
    echo '<script>window.location.href = "pos.php";</script>';
}

?>
