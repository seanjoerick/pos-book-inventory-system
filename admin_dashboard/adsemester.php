<?php
include_once '../database.php';
include_once '../includes.php';

// When iscurrent=1, thats the active semester, if 0, inactive.
// Fetch the list of semesters from the database
$query = "SELECT semester_id, semester_name, is_current FROM tbl_semesters";
$result = mysqli_query($db_connection, $query);

// Check for query errors and fetch the results into an array
if ($result) {
    $semesters = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    // Handle the database error (e.g., show an error message)
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected semester_id from the form submission
    $selectedSemesterId = $_POST['semester_id'];

    // Find the currently selected semester
    $currentSemesterId = null;
    foreach ($semesters as $semester) {
        if ($semester['is_current'] == 1) {
            $currentSemesterId = $semester['semester_id'];
            break;
        }
    }

    // If the selected semester is different from the current one, update the database
    if ($selectedSemesterId != $currentSemesterId) {
        $updateQuery = "UPDATE tbl_semesters SET is_current = 0 WHERE semester_id = ?";
        $setSelectedSemesterQuery = "UPDATE tbl_semesters SET is_current = 1 WHERE semester_id = ?";
        
        // Use prepared statements to avoid SQL injection
        $updateStatement = mysqli_prepare($db_connection, $updateQuery);
        $setSelectedSemesterStatement = mysqli_prepare($db_connection, $setSelectedSemesterQuery);
        
        if ($updateStatement && $setSelectedSemesterStatement) {
            // Set the current semester to is_current = 0
            mysqli_stmt_bind_param($updateStatement, "i", $currentSemesterId);
            mysqli_stmt_execute($updateStatement);
            
            // Set the selected semester to is_current = 1
            mysqli_stmt_bind_param($setSelectedSemesterStatement, "i", $selectedSemesterId);
            mysqli_stmt_execute($setSelectedSemesterStatement);
        } else {
            // Handle database errors
        }
        
        // Close the prepared statements
        mysqli_stmt_close($updateStatement);
        mysqli_stmt_close($setSelectedSemesterStatement);
    }
    
    // Close the database connection
    mysqli_close($db_connection);
    
    // Check if the selected semester is the same as the current one
    if ($selectedSemesterId == $currentSemesterId) {
        // Set a session variable to indicate that there was no change
        session_start();
        $_SESSION['semester_no_change'] = true;
    } else {
        // Set a session variable to indicate a successful semester change
        session_start();
        $_SESSION['semester_changed'] = true;
    }

    // Redirect to admin_dashboard.php
    header("Location: admin_dashboard.php");
}

?>

<link rel="stylesheet" href="style.css">
<!-- The Modal -->
<div class="modal fade" id="semesterModal" tabindex="-1" aria-labelledby="Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Change Current Semester</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form action="adsemester.php" method="post">
                    <p>Choose a semester from the list below to change the current semester. If you need to add more semesters, please visit the <a href="../management.php">Settings</a> tab to input new semesters.</p>
                    <label for="semesterSelect">Select a Semester:</label>
                    <select name="semester_id" id="semesterSelect" class="form-control">
                        <?php
                        foreach ($semesters as $semester) {
                            $selected = ($semester['is_current'] == 1) ? 'selected' : '';
                            echo '<option value="' . $semester['semester_id'] . '" ' . $selected . '>' . $semester['semester_name'] . '</option>';
                        }
                        ?>
                    </select>

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Change Semester</button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>