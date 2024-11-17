<?php
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $student_number = $_POST['student_number'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // Check if the student number, email, or phone number already exists
    $studentNumberCheckQuery = "SELECT COUNT(*) as studentNumberCount FROM tbl_customers WHERE student_number = ?";
    $emailCheckQuery = "SELECT COUNT(*) as emailCount FROM tbl_customers WHERE email = ?";
    $phoneCheckQuery = "SELECT COUNT(*) as phoneCount FROM tbl_customers WHERE phone_number = ?";
    $emailPhoneCheckQuery = "SELECT COUNT(*) as emailPhoneCount FROM tbl_customers WHERE email = ? OR phone_number = ?";
    $emailStudentCheckQuery = "SELECT COUNT(*) as emailStudentCount FROM tbl_customers WHERE email = ? OR student_number = ?";
    $phoneStudentCheckQuery = "SELECT COUNT(*) as phoneStudentCount FROM tbl_customers WHERE phone_number = ? OR student_number = ?";
    $allCheckQuery = "SELECT COUNT(*) as allCount FROM tbl_customers WHERE email = ? OR phone_number = ? OR student_number = ?";
    
    $studentNumberCheckStmt = mysqli_prepare($db_connection, $studentNumberCheckQuery);
    mysqli_stmt_bind_param($studentNumberCheckStmt, "s", $student_number);
    mysqli_stmt_execute($studentNumberCheckStmt);
    mysqli_stmt_bind_result($studentNumberCheckStmt, $studentNumberCount);
    mysqli_stmt_fetch($studentNumberCheckStmt);
    mysqli_stmt_close($studentNumberCheckStmt);

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

    $emailStudentCheckStmt = mysqli_prepare($db_connection, $emailStudentCheckQuery);
    mysqli_stmt_bind_param($emailStudentCheckStmt, "ss", $email, $student_number);
    mysqli_stmt_execute($emailStudentCheckStmt);
    mysqli_stmt_bind_result($emailStudentCheckStmt, $emailStudentCount);
    mysqli_stmt_fetch($emailStudentCheckStmt);
    mysqli_stmt_close($emailStudentCheckStmt);

    $phoneStudentCheckStmt = mysqli_prepare($db_connection, $phoneStudentCheckQuery);
    mysqli_stmt_bind_param($phoneStudentCheckStmt, "ss", $phone_number, $student_number);
    mysqli_stmt_execute($phoneStudentCheckStmt);
    mysqli_stmt_bind_result($phoneStudentCheckStmt, $phoneStudentCount);
    mysqli_stmt_fetch($phoneStudentCheckStmt);
    mysqli_stmt_close($phoneStudentCheckStmt);

    $allCheckStmt = mysqli_prepare($db_connection, $allCheckQuery);
    mysqli_stmt_bind_param($allCheckStmt, "sss", $email, $phone_number, $student_number);
    mysqli_stmt_execute($allCheckStmt);
    mysqli_stmt_bind_result($allCheckStmt, $allCount);
    mysqli_stmt_fetch($allCheckStmt);
    mysqli_stmt_close($allCheckStmt);

    if ($studentNumberCount > 0 && $emailCount > 0 && $phoneCount > 0) {
        // Student number, email, and phone number already exist
        echo json_encode(array('success' => false, 'message' => 'Student number, email, and phone number already exist.'));
    } elseif ($studentNumberCount > 0 && $emailCount > 0) {
        // Student number and email already exist
        echo json_encode(array('success' => false, 'message' => 'Student number and email already exist.'));
    } elseif ($studentNumberCount > 0 && $phoneCount > 0) {
        // Student number and phone number already exist
        echo json_encode(array('success' => false, 'message' => 'Student number and phone number already exist.'));
    } elseif ($emailCount > 0 && $phoneCount > 0) {
        // Email and phone number already exist
        echo json_encode(array('success' => false, 'message' => 'Email and phone number already exist.'));
    } elseif ($studentNumberCount > 0) {
        // Only student number already exists
        echo json_encode(array('success' => false, 'message' => 'Student number already exists.'));
    } elseif ($emailCount > 0) {
        // Only email already exists
        echo json_encode(array('success' => false, 'message' => 'Email already exists.'));
    } elseif ($phoneCount > 0) {
        // Only phone number already exists
        echo json_encode(array('success' => false, 'message' => 'Phone number already exists.'));
    } elseif ($emailPhoneCount > 0) {
        // Either email or phone number already exists
        echo json_encode(array('success' => false, 'message' => 'Email or phone number already exist.'));
    } elseif ($emailStudentCount > 0) {
        // Either email or student number already exists
        echo json_encode(array('success' => false, 'message' => 'Email or student number already exist.'));
    } elseif ($phoneStudentCount > 0) {
        // Either phone number or student number already exists
        echo json_encode(array('success' => false, 'message' => 'Phone number or student number already exist.'));
    } elseif ($allCount > 0) {
        // Either email, phone number, or student number already exists
        echo json_encode(array('success' => false, 'message' => 'Email, phone number, or student number already exist.'));
    } else {
        // Insert data into tbl_customers
        $insertQuery = "INSERT INTO tbl_customers (customer_type, first_name, last_name, student_number, email, phone_number) VALUES ('Student', ?, ?, ?, ?, ?)";
        $insertStmt = mysqli_prepare($db_connection, $insertQuery);
        mysqli_stmt_bind_param($insertStmt, "sssss", $first_name, $last_name, $student_number, $email, $phone_number);

        if (mysqli_stmt_execute($insertStmt)) {
            // Registration successful
            echo json_encode(array('success' => true, 'message' => 'Student registered successfully.'));
        } else {
            // Registration failed
            echo json_encode(array('success' => false, 'message' => 'Failed to register student.'));
        }

        mysqli_stmt_close($insertStmt);
    }

    mysqli_close($db_connection);
} else {
    // If someone tries to access this file without submitting the form, redirect them
    header("Location: pos.php");
}
?>