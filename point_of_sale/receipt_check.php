<?php

// Assuming you have a database connection
include '../database.php';

// Check if the receipt number exists in the database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiptNumber = $_POST['receiptNumber'];

    // Sanitize input to prevent SQL injection
    $receiptNumber = mysqli_real_escape_string($db_connection, $receiptNumber);

    // Query to check if the receipt number exists
    $query = "SELECT COUNT(*) as count FROM tbl_receipts WHERE receipt_number = '$receiptNumber'";
    $result = mysqli_query($db_connection, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $exists = $row['count'] > 0;
        echo json_encode(['exists' => $exists]);
    } else {
        // Handle database query error
        echo json_encode(['error' => 'Error executing database query']);
    }
} else {
    // Handle invalid request method
    echo json_encode(['error' => 'Invalid request method']);
}

?>