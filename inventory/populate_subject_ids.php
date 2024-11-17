<?php
include '../database.php'; // Include your database connection code

// Query to retrieve distinct subject IDs from tbl_books
$sql = "SELECT DISTINCT subject_id FROM tbl_books";
$result = mysqli_query($db_connection, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $subjectIds = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $subjectIds[] = $row['subject_id'];
    }

    // Send the subject IDs as JSON response
    header('Content-Type: application/json');
    echo json_encode($subjectIds);
} else {
    // Handle the case where there are no subject IDs in tbl_books
    echo "No subject IDs found in tbl_books";
}

// Close the database connection if needed
mysqli_close($db_connection);
