<?php
include_once '../database.php';
include_once '../authentication.php';
include_once '../includes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addBook"])) {
    $YearLeveLID = mysqli_real_escape_string($db_connection, $_POST["YearLeveLID"]);
    $SubjectID = mysqli_real_escape_string($db_connection, $_POST["SubjectID"]);
    $Isbn = mysqli_real_escape_string($db_connection, $_POST["Isbn"]);
    $QuantityAvailable = mysqli_real_escape_string($db_connection, $_POST["QuantityAvailable"]);
    $Price = mysqli_real_escape_string($db_connection, $_POST["Price"]);
    $PublicationYear = mysqli_real_escape_string($db_connection, $_POST["PublicationYear"]);
    $Title = ucwords(trim(mysqli_real_escape_string($db_connection, $_POST["Title"])));
    $Author = ucwords(trim(mysqli_real_escape_string($db_connection, $_POST["Author"])));

    // Ensure the first letter of Title and Author is capitalized
    function capitalizeFirstLetter($str)
    {
        // Use ucwords to capitalize the first letter of each word
        return ucwords(strtolower($str));
    }
    $Title = capitalizeFirstLetter($Title);
    $Author = capitalizeFirstLetter($Author);

    // Handle image upload with a unique identifier
    $uniqueIdentifier = uniqid(); // Generate a unique identifier

    $BookImage = "../images/neustlogo.png"; // Default image path

    if (!empty($_FILES['BookImage']['name'])) {
        $tempName = $_FILES['BookImage']['tmp_name'];
        $location = "../images/";

        // Check if image was uploaded successfully
        if (move_uploaded_file($tempName, $location . $uniqueIdentifier . '_' . $_FILES['BookImage']['name'])) {
            // Validate file extension
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = pathinfo($_FILES['BookImage']['name'], PATHINFO_EXTENSION);

            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                // Handle the error without using Swal
                echo "Invalid file format. Please upload an image with a valid format (jpg, jpeg, png, gif).";
                exit(); // Additional actions (e.g., exit or redirect)
            }

            // Update $BookImage with the actual uploaded filename
            $BookImage = $uniqueIdentifier . '_' . $_FILES['BookImage']['name'];
        } else {
            // Display an error and exit if image upload fails
            echo "Error uploading the image.";
            exit();
        }
    }
    // Fetch year_level_type from year_levels table
    $query = "SELECT year_level_type FROM tbl_yearlevels WHERE year_level_id = ?";
    $stmt = mysqli_prepare($db_connection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $YearLeveLID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $YearLevelType = $row['year_level_type'];

            // Use prepared statements for SQL queries
            $sql = "INSERT INTO tbl_books (year_level_id, subject_id, isbn, title, author, publication_year, quantity_available, price, book_image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($db_connection, $sql);

            if ($stmt) {
                // mysqli_stmt_bind_param($stmt, "ssssssss", $YearLeveLID, $SubjectID, $Isbn, $Title, $Author, $PublicationYear, $QuantityAvailable, $Price, $BookImage);
                mysqli_stmt_bind_param($stmt, "sssssssss", $YearLeveLID, $SubjectID, $Isbn, $Title, $Author, $PublicationYear, $QuantityAvailable, $Price, $BookImage);

                if (mysqli_stmt_execute($stmt)) {
                    $lastInsertedID = mysqli_insert_id($db_connection);
                    recordHistory($db_connection, $lastInsertedID, 'Insert');

                    // Redirect based on Year Level Type
                    switch ($YearLevelType) {
                        case "High School":
                            // Set success message in session
                            header("Location: inventory.php");
                            exit;
                        case "Senior High":
                            header("Location: add_seniorhigh_strands.php?book_id=" . $lastInsertedID);
                            exit;
                        case "College":
                            header("Location: add_college_programs.php?book_id=" . $lastInsertedID);
                            exit;
                        default:
                            echo "Error: Unexpected Year Level Type.";
                            exit;
                    }
                } else {
                    // Handle the error without using Swal
                    echo "Unable to execute the query.";
                }

                mysqli_stmt_close($stmt);
            } else {
                // Handle the error without using Swal
                echo "Unable to prepare the query.";
            }
        } else {
            // Handle the error without using Swal
            exit(); // Additional actions (e.g., exit or redirect)
        }
    } else {
        // Handle the error without using Swal
        echo "Unable to prepare the query.";
    }
}


// FORM FOR ADD COLLEGE BOOKS
if (isset($_POST['programs']) && is_array($_POST['programs'])) {
    $collegeID = $_POST['collegeID'];

    foreach ($_POST['programs'] as $program_id) {

        // Construct the SQL query to insert data
        $sql = "INSERT INTO tbl_book_programs (book_id, program_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($db_connection, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $collegeID, $program_id);
            if (mysqli_stmt_execute($stmt)) {
                echo "Insert Successful for book_id: $collegeID, program_id: $program_id";
            } else {
                echo "Error: " . mysqli_error($db_connection);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error: " . mysqli_error($db_connection);
        }
    }
    // Redirect to inventory.php on success
    header("Location: inventory.php");
    exit;
}

// FORM FOR ADD_SENIORHIGH STRANDS
if (isset($_POST['strands']) && is_array($_POST['strands'])) {
    $seniorID = $_POST['seniorID'];

    foreach ($_POST['strands'] as $strand_id) {

        $sql = "INSERT INTO tbl_book_strands (book_id, strand_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($db_connection, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $seniorID, $strand_id);
            if (mysqli_stmt_execute($stmt)) {
                echo "Insert Successful for book_id: $seniorID, strand_id: $strand_id";
                // $_SESSION["senior_strands"] = "success";
            } else {
                echo "Error: " . mysqli_error($db_connection);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error: " . mysqli_error($db_connection);
        }
    }
    // Redirect to inventory.php on success
    header("Location: inventory.php");
    exit;
}

// Function to record the history for insertion
function recordHistory($connection, $bookId, $changeType)
{
    $userId = $_SESSION['user_id'];

    $sql = "INSERT INTO tbl_books_history (book_id, change_type, user_id)
VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($connection, $sql);
    if (!$stmt) {
        die("Error preparing statement: " . mysqli_error($connection));
    }

    mysqli_stmt_bind_param($stmt, "iss", $bookId, $changeType, $userId);

    if (!mysqli_stmt_execute($stmt)) {
        die("Error executing statement: " . mysqli_error($connection));
    }

    mysqli_stmt_close($stmt);
}
