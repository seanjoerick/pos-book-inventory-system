<?php
include '../database.php';

// Fetch transactions from the database where is_void = 0
$query = "SELECT transaction_date, total_amount FROM tbl_transactions WHERE is_void = 0";
$result = mysqli_query($db_connection, $query);

// Check if there are results
if ($result) {
    // Fetch the results as an associative array
    $transactions = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $transactions[] = $row;
    }

    // Output the transactions as JSON
    header('Content-Type: application/json');
    echo json_encode($transactions);
} else {
    echo json_encode(['error' => 'No transactions found']);
}

// Close the database connection
mysqli_close($db_connection);
?>