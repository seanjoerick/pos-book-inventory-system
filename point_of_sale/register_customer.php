<?php
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // Check if the email or phone number already exists
    $emailCheckQuery = "SELECT COUNT(*) as emailCount FROM tbl_customers WHERE email = ?";
    $phoneCheckQuery = "SELECT COUNT(*) as phoneCount FROM tbl_customers WHERE phone_number = ?";
    $emailPhoneCheckQuery = "SELECT COUNT(*) as emailPhoneCount FROM tbl_customers WHERE email = ? OR phone_number = ?";
    
    $emailCheckStmt = mysqli_prepare($db_connection, $emailCheckQuery);
    mysqli_stmt_bind_param($emailCheckStmt, "s", $email);
    mysqli_stmt_execute($emailCheckStmt);
    mysqli_stmt_bind_result($emailCheckStmt, $emailCount);
    mysqli_stmt_fetch($emailCheckStmt);
    mysqli_stmt_close($emailCheckStmt);

    $phoneCheckStmt = mysqli_prepare($db_connection, $phoneCheckQuery);
    mysqli_stmt_bind_param($phoneCheckStmt, "s", $phone_number);
    mysqli_stmt_execute($phoneCheckStmt);
    mysqli_stmt_bind_result($phoneCheckStmt, $phoneCount);
    mysqli_stmt_fetch($phoneCheckStmt);
    mysqli_stmt_close($phoneCheckStmt);

    $emailPhoneCheckStmt = mysqli_prepare($db_connection, $emailPhoneCheckQuery);
    mysqli_stmt_bind_param($emailPhoneCheckStmt, "ss", $email, $phone_number);
    mysqli_stmt_execute($emailPhoneCheckStmt);
    mysqli_stmt_bind_result($emailPhoneCheckStmt, $emailPhoneCount);
    mysqli_stmt_fetch($emailPhoneCheckStmt);
    mysqli_stmt_close($emailPhoneCheckStmt);

    if ($emailCount > 0 && $phoneCount > 0) {
        // Both email and phone number already exist
        echo json_encode(array('success' => false, 'message' => 'Email and phone number already exist.'));
    } elseif ($emailCount > 0) {
        // Only email already exists
        echo json_encode(array('success' => false, 'message' => 'Email already exists.'));
    } elseif ($phoneCount > 0) {
        // Only phone number already exists
        echo json_encode(array('success' => false, 'message' => 'Phone number already exists.'));
    } elseif ($emailPhoneCount > 0) {
        // Either email or phone number already exists
        echo json_encode(array('success' => false, 'message' => 'Email or phone number already exist.'));
    } else {
        // Insert data into tbl_customers
        $insertQuery = "INSERT INTO tbl_customers (customer_type, first_name, last_name, email, phone_number) VALUES ('Non-Student', ?, ?, ?, ?)";
        $insertStmt = mysqli_prepare($db_connection, $insertQuery);
        mysqli_stmt_bind_param($insertStmt, "ssss", $first_name, $last_name, $email, $phone_number);

        if (mysqli_stmt_execute($insertStmt)) {
            // Registration successful
            echo json_encode(array('success' => true, 'message' => 'Customer registered successfully.'));
        } else {
            // Registration failed
            echo json_encode(array('success' => false, 'message' => 'Failed to register customer.'));
        }

        mysqli_stmt_close($insertStmt);
    }

    mysqli_close($db_connection);
} else {
    // If someone tries to access this file without submitting the form, redirect them
    header("Location: pos.php");
}
?>