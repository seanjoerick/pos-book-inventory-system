<?php

include '../database.php';

// Check if year_level_type is set in the request
if (isset($_GET['year_level_type'])) {
    $yearLevelType = $_GET['year_level_type'];

    // Write a SQL query to fetch year levels from tbl_yearlevels for the specified type
    $sql = "SELECT year_level_name FROM tbl_yearlevels WHERE year_level_type = ?";

    $stmt = mysqli_prepare($db_connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $yearLevelType); // "s" indicates a string

    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            $yearLevels = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $yearLevels[] = $row['year_level_name'];
            }

            // Convert the array of year levels to a comma-separated string
            $yearLevelsString = implode(',', $yearLevels);

            echo $yearLevelsString;
        } else {
            // Handle the case where the query fails
            echo "Error: Unable to fetch year levels.";
        }
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    // Handle the case where year_level_type is not set in the request
    echo "Error: year_level_type parameter is missing.";
}
