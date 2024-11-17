<?php

include '../database.php'; // Include your database connection

// Get the bookId and selected program IDs from the AJAX request
$bookId = isset($_POST['bookId']) ? $_POST['bookId'] : null;
$selectedStrands = isset($_POST['selectedStrands']) ? $_POST['selectedStrands'] : [];

if ($bookId !== null) {
    // Start a database transaction for data consistency
    $db_connection->begin_transaction();

    // Remove existing program associations for the book
    $deleteQuery = "DELETE FROM tbl_book_strands WHERE book_id = ?";
    $deleteStmt = $db_connection->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $bookId);

    if (!$deleteStmt->execute()) {
        // Handle the database error
        $db_connection->rollback(); // Rollback the transaction
        echo json_encode(['error' => 'Failed to update program associations']);
        exit;
    }

    $deleteStmt->close();

    // Add new program associations
    if (!empty($selectedStrands)) {
        $insertQuery = "INSERT INTO tbl_book_strands (book_id, strand_id) VALUES (?, ?)";
        $insertStmt = $db_connection->prepare($insertQuery);

        foreach ($selectedStrands as $strandId) {
            $insertStmt->bind_param("ii", $bookId, $strandId);

            if (!$insertStmt->execute()) {
                // Handle the database error
                $db_connection->rollback(); // Rollback the transaction
                echo json_encode(['error' => 'Failed to update strand associations']);
                exit;
            }
        }

        $insertStmt->close();
    }

    // Commit the transaction if all operations were successful
    $db_connection->commit();

    echo json_encode(['success' => 'Strand associations updated successfully']);
} else {
    // Handle the case where no bookId is provided
    echo json_encode(['error' => 'Missing bookId']);
}
