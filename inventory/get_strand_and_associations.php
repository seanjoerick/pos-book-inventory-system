<?php

include '../database.php'; // Include your database connection
// Get the bookId from the AJAX request
$bookId = isset($_GET['bookId']) ? $_GET['bookId'] : null;

if ($bookId) {
    // Initialize an empty array to store program data
    $strands = [];

    // Query to retrieve program data and associations
    $query = "SELECT s.strand_id, s.strand_name, IFNULL(bsa.book_id, 0) AS isAssociated
              FROM tbl_strands s
              LEFT JOIN tbl_book_strands bsa ON s.strand_id = bsa.strand_id AND bsa.book_id = ?
              ORDER BY s.strand_name";

    $stmt = $db_connection->prepare($query);
    $stmt->bind_param("i", $bookId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Fetch program data and associations
        while ($row = $result->fetch_assoc()) {
            $strands[] = $row;
        }

        // Return the data as JSON
        echo json_encode($strands);
    } else {
        // Handle the database error, if any
        echo json_encode(['error' => 'Failed to retrieve data']);
    }

    $stmt->close();
} else {
    // Handle the case where no bookId is provided
    echo json_encode(['error' => 'Missing bookId']);
}
