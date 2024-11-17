<?php
include '../database.php'; // Include your database connection script

// Check if the book ID is provided via POST
if (isset($_POST['BookId']) && is_numeric($_POST['BookId'])) {
    $BookId = intval($_POST['BookId']);

    // Create the SQL query to fetch book details
    $sql = "SELECT cb.book_id, 
                cb.title, 
                cb.author, 
                cb.publication_year, 
                cb.quantity_available, 
                cb.price, 
                s.subject_code, 
                y.year_level_name, 
                y.year_level_type,
                cb.status, 
                GROUP_CONCAT(DISTINCT p.program_name) AS program_names,
                GROUP_CONCAT(DISTINCT st.strand_name) AS strand_names
            FROM tbl_books cb
            LEFT JOIN tbl_subjects s ON cb.subject_id = s.subject_id
            LEFT JOIN tbl_yearlevels y ON cb.year_level_id = y.year_level_id
            LEFT JOIN tbl_book_programs bp ON cb.book_id = bp.book_id
            LEFT JOIN tbl_programs p ON bp.program_id = p.program_id
            LEFT JOIN tbl_book_strands bs ON cb.book_id = bs.book_id
            LEFT JOIN tbl_strands st ON bs.strand_id = st.strand_id
            WHERE cb.book_id = ?
            GROUP BY cb.book_id";

    // Prepare the statement
    $stmt = $db_connection->prepare($sql);

    if (!$stmt) {
        die(json_encode(['error' => 'Error preparing the statement: ' . $db_connection->error]));
    }

    // Bind the parameter
    $stmt->bind_param("i", $BookId);

    // Execute the query
    if ($stmt->execute()) {
        // Bind the result variables
        $stmt->bind_result(
            $bookId,
            $title,
            $author,
            $publication_year,
            $quantity_available,
            $price,
            $subject_code,
            $year_level_name,
            $year_level_type,
            $status,
            $program_names,
            $strand_names
        );

        // Fetch the data
        if ($stmt->fetch()) {

            // Build the response array
            $response = [
                'bookId' => $bookId,
                'title' => $title,
                'author' => $author,
                'publicationYear' => $publication_year,
                'quantityAvailable' => $quantity_available,
                'price' => $price,
                'subjectCodes' => $subject_code,
                'yearlevelName' => $year_level_name,
                'yearlevelType' => $year_level_type,
                'status' => $status,
                'programNames' => $program_names,
                'strandNames' => $strand_names
            ];

            // Send the response as JSON
            echo json_encode(['bookDetails' => $response]);
        } else {
            echo json_encode(['error' => 'Book not found']);
        }
    } else {
        echo json_encode(['error' => 'Error executing the query: ' . $stmt->error]);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request']);
}

// Close the database connection
$db_connection->close();
