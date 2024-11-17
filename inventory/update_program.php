
<?php
include '../database.php'; // Include your database connection

// Get the bookId and selected program IDs from the AJAX request
$bookId = isset($_POST['bookId']) ? $_POST['bookId'] : null;
$selectedPrograms = isset($_POST['selectedPrograms']) ? $_POST['selectedPrograms'] : [];

if ($bookId !== null) {
    // Start a database transaction for data consistency
    $db_connection->begin_transaction();

    // Remove existing program associations for the book
    $deleteQuery = "DELETE FROM tbl_book_programs WHERE book_id = ?";
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
    if (!empty($selectedPrograms)) {
        $insertQuery = "INSERT INTO tbl_book_programs (book_id, program_id) VALUES (?, ?)";
        $insertStmt = $db_connection->prepare($insertQuery);

        foreach ($selectedPrograms as $programId) {
            $insertStmt->bind_param("ii", $bookId, $programId);

            if (!$insertStmt->execute()) {
                // Handle the database error
                $db_connection->rollback(); // Rollback the transaction
                echo json_encode(['error' => 'Failed to update program associations']);
                exit;
            }
        }

        $insertStmt->close();
    }

    // Commit the transaction if all operations were successful
    $db_connection->commit();

    echo json_encode(['success' => 'Program associations updated successfully']);
} else {
    // Handle the case where no bookId is provided
    echo json_encode(['error' => 'Missing bookId']);
}
