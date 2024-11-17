<?php
// possearch2.php

include_once '../database.php';

if (isset($_POST['type'])) {
    $selectedType = $_POST['type'];

    // Fetch year levels based on the selected book type
    $query = "SELECT year_level_id, year_level_name FROM tbl_yearlevels WHERE year_level_type = '$selectedType'";
    $result = $db_connection->query($query);

    echo '<option value="all">All</option>';

    if ($result->num_rows > 0) {
        // Output options for year levels
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['year_level_id'] . '">' . $row['year_level_name'] . '</option>';
        }
    } else {
        // No matching year levels found
        echo '<option value="all">All</option>';
    }
} else {
    // Handle case where 'type' is not set in the POST request
    echo '<option value="all">All</option>';
}

// Close the database connection if needed
$db_connection->close();
?>