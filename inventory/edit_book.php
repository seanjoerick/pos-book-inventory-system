<?php
include '../database.php';

if (isset($_POST['book_id'])) {
    $bookId = $_POST['book_id'];

    // Use prepared statements to protect against SQL injection
    $sql = "SELECT
                b.*,
                s.subject_code,
                y.year_level_name,
                y.year_level_type
            FROM tbl_books b
            LEFT JOIN tbl_subjects s ON b.subject_id = s.subject_id
            LEFT JOIN tbl_yearlevels y ON b.year_level_id = y.year_level_id
            WHERE b.book_id = ?";

    $stmt = mysqli_prepare($db_connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $bookId); // "i" indicates an integer

    if (mysqli_stmt_execute($stmt)) {

        $result = mysqli_stmt_get_result($stmt);

        if ($result && $bookData = mysqli_fetch_assoc($result)) {
            // Convert the data to JSON and send it as a response
            echo json_encode($bookData);
        }
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}
