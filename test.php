<?php

include 'includes.php';
include 'database.php';
include 'authentication.php';
include 'testfunction.php';

if (isset($_SESSION['receipt_data'])) {
    // Access the data from the session
    $customer_id = null;

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
    $transaction_details = isset($_SESSION['cartData']) ? json_decode($_SESSION['cartData'], true) : [];
    $teacher_id = ($teacher_id_before === "none" || $teacher_id_before === "na") ? null : $teacher_id_before;
    $transaction_date = date("Y-m-d H:i:s");
    $formatted_date = date('m/d/y', strtotime($transaction_date));
    $semester_id = getCurrentSemesterId();

    $semester_name = getSemesterName($semester_id);
}

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

?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
</head>

<body>

    <!-- Add these buttons to the end of your HTML body -->
    <div class="container receipt-container">
        <div class="row">
            <div class="col">
                <a href="pos.php" class="btn btn-primary"><i class="bx bxs-left-arrow-alt"></i> Return to POS</a>
            </div>
            <div class="col text-end">
                <button id="downloadButton" class="btn btn-info"><i class="bx bxs-download"></i> Download as PDF</button>
            </div>
        </div>
    </div>

    <div id="receipt">
        <link rel="stylesheet" href="style.css">
        <div class="container receipt-container" style="font-size: 16px;">
            <div class="border-container" style="border: 1px solid #000; padding: 15px;">
                <!-- Receipt Header -->
                <div class="row receipt-header">
                    <div class="col-auto">
                        <img src="images/neustlogo.png" alt="Logo" class="logo">
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
                        <p>Agency Name: NEUST Marketing Department</p>
                        <?php echo '<p>Payer Name: ' . htmlspecialchars($customer_name) . '</p>'; ?>
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

                                        $totalAmount += (float)$transaction_details[$i]['subtotal']; // Accumulate total

                                        // Output the row
                                        echo '<tr>';
                                        echo '<td>' . $quantity . ' ' . $title . '</td>';
                                        echo '<td></td>';
                                        echo '<td>' . $subtotal . '</td>';
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
                                    <td>₱<?php echo number_format($totalAmount, 2); ?></td>
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
                        <p>Total amount in Words: <?php echo convertNumberToWords($totalAmount); ?></p>
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

    document.addEventListener('DOMContentLoaded', function() {
        showSuccessMessage();

        document.getElementById('downloadButton').addEventListener('click', function() {

            var element = document.getElementById('receipt');

            var options = {
                margin: 10,
                filename: 'receipt.pdf', // Set the filename here
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };

            html2pdf(element, options);
        });
    });
</script>