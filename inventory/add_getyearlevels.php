<?php
include '../database.php';

if (isset($_POST['yearLevelType'])) {
    $selectedYearLevelType = $_POST['yearLevelType'];
    $sql = "SELECT year_level_id, year_level_name FROM tbl_yearlevels WHERE year_level_type = '$selectedYearLevelType'";
    $result = mysqli_query($db_connection, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row["year_level_id"] . '">' . $row["year_level_name"] . '</option>';
        }
    } else {
        echo '<option value="" disabled selected>Error fetching Year Levels</option>';
    }
} else {
    echo '<option value="" disabled selected>Please select Year Level Type first</option>';
}
