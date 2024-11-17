<?php
include '../database.php';

// Assuming you have a database connection established in 'database.php'

// Write a SQL query to fetch subject codes from tbl_subjects
$sql = "SELECT subject_code FROM tbl_subjects";

$result = mysqli_query($db_connection, $sql);

if ($result) {
    $subjectCodes = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $subjectCodes[] = $row['subject_code'];
    }

    // Convert the array of subject codes to a comma-separated string
    $subjectCodesString = implode(',', $subjectCodes);

    echo $subjectCodesString;
} else {
    // Handle the case where the query fails
    echo "Error: Unable to fetch subject codes.";
}
