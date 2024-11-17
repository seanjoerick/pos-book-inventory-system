<?php
// <!---------------------- Toggle status ----------------------->
include '../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["book_id"]) && isset($_POST["new_status"])) {
    $book_id = $_POST["book_id"];
    $new_status = $_POST["new_status"];

    // ... Additional security measures and input validation ...

    if ($db_connection->connect_error) {
        die("Connection failed: " . $db_connection->connect_error);
    }

    // Sanitize and validate the input values to prevent SQL injection
    $book_id = mysqli_real_escape_string($db_connection, $book_id);
    $new_status = mysqli_real_escape_string($db_connection, $new_status);

    $sql = "UPDATE tbl_books SET status = ? WHERE book_id = ?";
    $stmt = $db_connection->prepare($sql);
    $stmt->bind_param("si", $new_status, $book_id);

    if ($stmt->execute()) {
        $response = array(
            'success' => true,
            'newStatus' => $new_status
        );
        echo json_encode($response);
    } else {
        $response = array('success' => false, 'error' => 'Error updating status: ' . $stmt->error);
        echo json_encode($response);
    }
}
