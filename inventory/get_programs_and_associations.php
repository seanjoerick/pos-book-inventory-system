<?php

include '../database.php'; // Include your database connection
// Get the bookId from the AJAX request
$bookId = isset($_GET['bookId']) ? $_GET['bookId'] : null;

if ($bookId) {
    // Initialize an empty array to store program data
    $programs = [];

    // Query to retrieve program data and associations
    $query = "SELECT p.program_id, p.program_name, IFNULL(bpa.book_id, 0) AS isAssociated
              FROM tbl_programs p
              LEFT JOIN tbl_book_programs bpa ON p.program_id = bpa.program_id AND bpa.book_id = ?
              ORDER BY p.program_name";

    $stmt = $db_connection->prepare($query);
    $stmt->bind_param("i", $bookId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Fetch program data and associations
        while ($row = $result->fetch_assoc()) {
            $programs[] = $row;
        }

        // Return the data as JSON
        echo json_encode($programs);
    } else {
        // Handle the database error, if any
        echo json_encode(['error' => 'Failed to retrieve data']);
    }

    $stmt->close();
} else {
    // Handle the case where no bookId is provided
    echo json_encode(['error' => 'Missing bookId']);
}
