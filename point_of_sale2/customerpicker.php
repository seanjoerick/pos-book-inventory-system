<?php

include '../database.php';

// Check if a student number is provided in the query string
if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];

    // Perform a database query to fetch student data based on the student number
    $sql = "SELECT * FROM tbl_customers WHERE customer_id = '$customer_id'";
    $result = mysqli_query($db_connection, $sql);

    if ($result) {
        $customerData = mysqli_fetch_assoc($result);
        // Check if a student with the given student number was found
        if ($customerData) {
            // Return the student data as JSON
            header('Content-Type: application/json');
            echo json_encode($customerData);
        } else {
            // Return an error message if the student number was not found
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'Student not found']);
        }
    } else {
        // Handle any database query errors
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Database query failed']);
    }
} else {
    // Return an error if no student number is provided in the query string
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Student number not provided']);
}
?>