<?php


include '../database.php'; // Include your database connection code

if (isset($_POST['subjectId'])) {
    $subjectId = $_POST['subjectId'];

    // Query to retrieve the book ID associated with the selected subject ID
    $sql = "SELECT book_id FROM tbl_books WHERE subject_id = $subjectId";
    $result = mysqli_query($db_connection, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $bookId = $row['book_id'];
        echo $bookId;
    } else {
        // Handle the case where no matching book ID is found
        echo "No data";
    }
} else {
    // Handle the case where subject ID is not provided
    echo "Subject ID is missing";
}

// Close the database connection if needed
mysqli_close($db_connection);
