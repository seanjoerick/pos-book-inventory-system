<?php
include '../database.php';
include '../authentication.php';

if (isset($_POST['book_id'])) {
    $bookId = $_POST['book_id'];
    $bookIsbn = $_POST['isbn'];
    $bookTitle = mb_convert_case($_POST['title'], MB_CASE_TITLE, 'UTF-8');
    $bookAuthor = mb_convert_case($_POST['author'], MB_CASE_TITLE, 'UTF-8');
    $bookPublicationYear = $_POST['publication_year'];
    $bookPrice = $_POST['price'];
    $bookSubject = $_POST['subject_code'];
    $bookYearLevels = $_POST['year_level_name'];
    $bookQuantity = $_POST['quantity'];

    // Get the old data before the update
    $oldData = getOldData($db_connection, $bookId);

    // Handle image upload if a new file is provided
    $BookImage = null;
    if (!empty($_FILES['bookImage']['name'])) {
        $uniqueIdentifier = uniqid();
        $BookImage = $uniqueIdentifier . '_' . basename($_FILES['bookImage']['name']);
        $targetDir = "../images/";
        $targetPath = $targetDir . $BookImage;

        if (!move_uploaded_file($_FILES['bookImage']['tmp_name'], $targetPath)) {
            error_log("Error moving the uploaded file. Target path: $targetPath");
            echo json_encode("Error moving the uploaded file.");
            exit;
        }
    }

    // Perform the update operation in your database based on the provided data
    $sql = "UPDATE tbl_books
        SET isbn = ?,
            title = ?,
            author = ?,
            publication_year = ?,
            price = ?,
            subject_id = (SELECT subject_id FROM tbl_subjects WHERE subject_code = ?),
            year_level_id = (SELECT year_level_id FROM tbl_yearlevels WHERE year_level_name = ?),
            quantity_available = ?";

    // Only append the book_image field to the SQL query if it's provided
    if ($BookImage !== null) {
        $sql .= ", book_image = ?";
    }

    $sql .= " WHERE book_id = ?";

    $stmt = mysqli_prepare($db_connection, $sql);

    // Check if the prepare statement failed
    if (!$stmt) {
        echo json_encode("Error in prepare statement");
        exit;
    }

    // Bind parameters using bind_param
    if ($BookImage !== null) {
        mysqli_stmt_bind_param($stmt, 'sssssssisi', $bookIsbn, $bookTitle, $bookAuthor, $bookPublicationYear, $bookPrice, $bookSubject, $bookYearLevels, $bookQuantity, $BookImage, $bookId);
    } else {
        mysqli_stmt_bind_param($stmt, 'sssssssii', $bookIsbn, $bookTitle, $bookAuthor, $bookPublicationYear, $bookPrice, $bookSubject, $bookYearLevels, $bookQuantity, $bookId);
    }

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Update was successful

        // Get the updated data after the update
        $updatedData = getUpdatedData($db_connection, $bookId);

        // Record the history
        recordHistory($db_connection, $bookId, 'Update', $oldData, $updatedData);

        echo json_encode("Success");
    } else {
        echo json_encode("Error");
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}
// Function to get the old data before the update
function getOldData($connection, $bookId)
{
    $sql = "SELECT title, author, publication_year, price, subject_id, year_level_id, quantity_available, book_image FROM tbl_books WHERE book_id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $bookId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

// Function to get the updated data after the update
function getUpdatedData($connection, $bookId)
{
    $sql = "SELECT title, author, publication_year, price, subject_id, year_level_id, quantity_available, book_image FROM tbl_books WHERE book_id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $bookId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

// Function to record the history
function recordHistory($connection, $bookId, $changeType, $oldData, $updatedData)
{
    $userId = $_SESSION['user_id'];

    $changedFields = array();

    // Compare old data with updated data to find changed fields
    foreach ($updatedData as $field => $updatedValue) {
        if ($oldData[$field] !== $updatedValue) {
            $changedFields[$field] = $updatedValue;
            $oldData2[$field] = $oldData[$field];
        }
    }

    // Insert only if there are changed fields
    if (!empty($changedFields)) {
        $oldDataJson = json_encode($oldData2);
        $newDataJson = json_encode($changedFields);

        $sql = "INSERT INTO tbl_books_history (book_id, change_type, user_id, old_data, new_data)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($connection, $sql);

        if (!$stmt) {
            die("Error preparing statement: " . mysqli_error($connection));
        }

        mysqli_stmt_bind_param($stmt, "issss", $bookId, $changeType, $userId, $oldDataJson, $newDataJson);

        if (!mysqli_stmt_execute($stmt)) {
            die("Error executing statement: " . mysqli_error($connection));
        }

        mysqli_stmt_close($stmt);
    }
}


// sean 