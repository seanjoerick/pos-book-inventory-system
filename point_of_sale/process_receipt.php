<?php
session_start();

// Check if the session variable is not set, create an empty array
if (!isset($_SESSION['receipt_data'])) {
    $_SESSION['receipt_data'] = array();
}


// Store other transaction data in the session
$_SESSION['receipt_data'] = array(
    'receiptNumber' => $_POST['receiptNumber'],
    'existingCustomerName' => $_POST['existingCustomerName'],
    'existingStudentNumber' => $_POST['existingStudentNumber'],
    'advisoryTeacher' => $_POST['advisoryTeacher'],
    'paymentAmount' => $_POST['paymentAmount'],
    'totalAmount' => $_POST['totalAmount'],
    'change' => $_POST['change'],
    'cartData' => json_decode($_POST['cartData'], true),
    
);

echo json_encode(array('success' => true, 'message' => 'Data stored in session successfully.'));
?>