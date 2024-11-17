<?php

include 'database.php';
include 'authentication.php';
include_once 'modals.php';
include_once 'includes.php';
include 'managementalerts.php';
// THIS IS THE BOOK MANAGEMENT PAGE
// THIS IS WHERE YOU ADD, EDIT AND DELETE COLLEGES, YEAR LEVELS, SEMESTERS, AND MORE
managementalerts();
?>

<a href="logout.php" class="logout">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- My CSS -->
        <link rel="stylesheet" href="style.css">

        <title>Administrator Hub</title>
    </head>

    <body>

        <!-- LEFTSIDE NAVBAR FOR ADMIN -->
        <section id="sidebar">
            <a href="" class="brand">
                <img src="images/neustlogo.png" style="border: 1px solid white; border-radius: 50%;" alt="NEUST Logo" width="60" height="60">
                <span class="text" style="margin-left: 15px;">Administrator</span>
            </a>
            <?php
            if ($_SESSION['role'] === 'admin') {
                $loggedInAdminID = $_SESSION["user_id"];
                $adminNameQuery = "SELECT first_name, last_name FROM tbl_users WHERE user_id = $loggedInAdminID";
                $adminNameResult = mysqli_query($db_connection, $adminNameQuery);

                if ($adminNameResult && $adminData = mysqli_fetch_assoc($adminNameResult)) {
                    echo "<div class='alert alert-info mt-0 mb-0 text-center strong'>";
                    echo "<strong>" . $adminData['first_name'] . " " . $adminData['last_name'] . "</strong>";
                    echo "</div>";
                }
            }
            ?>
            <ul class="side-menu top">
                <li>
                    <a href="admin_dashboard/admin_dashboard.php">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="inventory/inventory.php">
                        <i class='bx bxs-book-alt'></i>
                        <span class="text">Book Inventory</span>
                    </a>
                </li>
                <li>
                    <a href="point_of_sale/pos.php">
                        <i class='bx bxs-dollar-circle'></i>
                        <span class="text">Point of Sale</span>
                    </a>
                </li>
                <li class="active">
                    <a href="management.php">
                        <i class='bx bxs-cog'></i>
                        <span class="text">Settings</span>
                    </a>
                </li>
                <li>
                    <a href="staffadmin.php">
                        <i class='bx bxs-user-check'></i>
                        <span class="text">Staff and Admins</span>
                    </a>
                </li>
                <li>
                    <a href="reports/sales_report.php">
                        <i class='bx bxs-chart'></i>
                        <span class="text">Sales Report</span>
                    </a>
                </li>
                <li>
                    <a href="history.php">
                        <i class='bx bxs-receipt'></i>
                        <span class="text">History</span>
                    </a>
                </li>
                <li>
                    <a href="inventory/archive.php">
                        <i class='bx bxs-archive'></i>
                        <span class="text">Archive</span>
                    </a>
                </li>
            </ul>
            <ul class="side-menu">
                <!-- <li>
                    <a href="settings.php" class="logout">
                        <i class='bx bxs-cog'></i>
                        <span class="text">Settings</span>
                    </a>
                </li> -->
                <li>
                    <a href="logout.php" class="logout" onclick="return confirmLogout();">
                        <i class='bx bxs-log-out-circle'></i>
                        <span class="text">Logout</span>
                    </a>
                </li>
                <?php //CODE FOR CONFIRMING YOU WANT TO LOG OUT 
                ?>
                <script>
                    function confirmLogout() {
                        // CONFIRMATION
                        var confirmLogout = confirm("Are you sure you want to logout?");

                        // If the user clicks "OK," LOGOUT NA
                        if (confirmLogout) {
                            window.location.href = "logout.php";
                        }

                        // If the user clicks "Cancel," nothing.
                        return false;
                    }
                </script>
            </ul>
        </section>
        <section id="content">

            <?php

            include 'nav.php';

            ?>
            <!-- ETO NA YUNG NASA LOOB!!!!!!!!!!!!! -->
            <main>
                <div class="head-title">
                    <div class="left">
                        <h1>Book Settings</h1>
                        <ul class="breadcrumb">
                            <li>
                                <a>This section empowers you to seamlessly manage colleges, year levels, semesters, bachelor programs,
                                    and subjects. Colleges categorize books into specific academic fields, year levels determine the applicable
                                    academic years, semesters track book usage throughout the year, bachelor programs represent the academic
                                    programs associated with the books, and subjects provide detailed classifications for book content.
                                </a>
                            </li>
                        </ul>
                        <div class="row">
                            <?php

                            // THE TABLES BELOW ARE CONNECTED SA modals.php, PAG KINLICK MO YUNG BUTTON NA PLUS, LILITAW YUNG MODAL, YUNG EDIT BUTTON UNRESPONSIVE PA.
                            // GAGAWIN KO YANG EDIT BUTTON AT DELETE BUTTON, AKO NA BAHALA.

                            //  THIS IS THE COLLEGES TABLE :> 
                            ?>
                            <div class="col-md-6">
                                <h2>Colleges <button class="square-button" data-toggle="modal" data-target="#addCollegeModal" data-tooltip="Add College">+</button></h2>
                                <div class="table-responsive" style="max-height: 593px; overflow-y: auto; border: 2px solid darkblue;">
                                    <table class="table table-bordered table-striped">
                                        <thead style="background-color: #f0ecec; position: sticky; top: -1; z-index: 1;">
                                            <th>ID</th>
                                            <th>College</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody id="myTable">
                                            <?php
                                            // ETO YUNG ROW NA IPAPAKITA YUNG MGA NILAGAY SA TABLE NG COLLEGES
                                            $sql = "SELECT * FROM tbl_colleges";
                                            $result = mysqli_query($db_connection, $sql);

                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $editCollegeModal = 'editCollegeModal' . $row["college_id"];
                                                    $collegeformID = 'deleteCollegeForm' . $row["college_id"];
                                                    echo "<tr>";
                                                    echo "<td>" . $row["college_id"] . "</td>";
                                                    echo "<td>" . $row["college_name"] . "</td>";
                                                    echo "<td>";
                                                    echo "<div class='btn-group' role='group'>";
                                                    echo "<div style='display: flex;'>";
                                                    echo "<button class='btn btn-outline-primary' data-toggle='modal' data-target='#$editCollegeModal' style='white-space: nowrap;'><i class='bx bxs-pencil' style='margin-right: 5px;'></i>Edit</button>";
                                                    echo "<form id='$collegeformID' method='POST' action='commands.php' style='display: inline-block; margin-left: 5px;'>";
                                                    echo "<input type='hidden' name='collegeID3' value='" . $row["college_id"] . "'>";
                                                    echo "<input type='hidden' name='collegeaction3' value='delete'>";
                                                    echo "<button type='button' class='btn btn-outline-danger' onclick=\"collegeconfirmDelete('$collegeformID')\" style='white-space: nowrap;'>";
                                                    echo "<i class='bx bxs-trash' style='margin-right: 5px;'></i>Delete</button>";
                                                    echo "</form>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='3'>No colleges found.</td></tr>";
                                            }
                                            // DULO NG SHOW COLLEGES
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <?php //THIS IS THE TABLE OF YEAR LEVELS NAMAN 
                            ?>

                            <div class="col-md-6">
                                <h2>Year Levels <button class="square-button" data-toggle="modal" data-target="#addYearLevelModal" data-tooltip="Add Year Level">+</button></h2>
                                <div class="table-responsive" style="max-height: 593px; overflow-y: auto; border: 2px solid darkblue;">
                                    <table class="table table-bordered table-striped">
                                        <thead style="background-color: #f0ecec; position: sticky; top: -1; z-index: 1;">
                                            <tr>
                                                <th>ID</th>
                                                <th>Year Level Type</th>
                                                <th>Year Level Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="myTable">
                                            <?php
                                            // RETRIEVE DATA 
                                            $sql = "SELECT * FROM tbl_yearlevels";
                                            $result = mysqli_query($db_connection, $sql);

                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $editYearLevelModal = 'editYearLevelModal' . $row["year_level_id"];
                                                    $yearlevelformID = "deleteYearLevelForm" . $row["year_level_id"];
                                                    echo "<tr>";
                                                    echo "<td>" . $row["year_level_id"] . "</td>";
                                                    echo "<td>" . $row["year_level_type"] . "</td>";
                                                    echo "<td>" . $row["year_level_name"] . "</td>";
                                                    echo "<td>";
                                                    echo "<div class='btn-group' role='group'>";
                                                    echo "<div style='display: flex;'>";
                                                    echo "<button class='btn btn-outline-primary' data-toggle='modal' data-target='#$editYearLevelModal' style='white-space: nowrap;'><i class='bx bxs-pencil' style='margin-right: 5px;'></i>Edit</button>";
                                                    echo "<form id='$yearlevelformID' method='POST' action='commands.php' style='display: inline-block; margin-left: 5px;'>";
                                                    echo "<input type='hidden' name='yearlevelID3' value='" . $row['year_level_id'] . "'>";
                                                    echo "<input type='hidden' name='yearlevelaction3' value='delete'>";
                                                    echo "<button type='button' class='btn btn-outline-danger' onclick=\"yearlevelconfirmDelete('$yearlevelformID')\"style='white-space: nowrap;'><i class='bx bxs-trash' style='margin-right: 5px;'></i>Delete</button>";
                                                    echo "</form>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='3'>No year levels found.</td></tr>";
                                            }
                                            // END DISPLAY
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-6">
                                <h2>Programs
                                    <button class="square-button" data-toggle="modal" data-target="#addProgramModal" data-tooltip="Add Bachelor Program">+</button>
                                </h2>
                                <div class="table-responsive" style="max-height: 593px; overflow-y: auto; border: 2px solid darkblue;">
                                    <table class="table table-bordered table-striped">
                                        <thead style="background-color: #f0ecec; position: sticky; top: -1; z-index: 1;">
                                            <th>ID</th>
                                            <th>College</th>
                                            <th>Program</th> <!-- New column for displaying the college name -->
                                            <th>Action</th>
                                        </thead>
                                        <tbody id="myTable">
                                            <?php
                                            // ETO YUNG ROW NA IPAPAKITA YUNG MGA NILAGAY SA TABLE NG PROGRAMS
                                            // JOINED THEM TABLESs
                                            $sql = "SELECT b.program_id, b.program_name, c.college_name
                FROM tbl_programs b
                 LEFT JOIN tbl_colleges c ON b.college_id = c.college_id";

                                            $result = mysqli_query($db_connection, $sql);

                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    // Define a unique modal ID for each row
                                                    $editProgramModal = 'editProgramModal' . $row["program_id"];
                                                    $programformID = 'deleteProgramForm' . $row["program_id"];
                                                    echo "<tr>";
                                                    echo "<td>" . $row["program_id"] . "</td>";
                                                    echo "<td>" . $row["college_name"] . "</td>";
                                                    echo "<td>" . $row["program_name"] . "</td>";
                                                    echo "<td>";
                                                    echo "<div class='btn-group' role='group'>";
                                                    echo "<div style='display: flex;'>";
                                                    echo "<button class='btn btn-outline-primary' data-toggle='modal' data-target='#$editProgramModal' style='white-space: nowrap;'><i class='bx bxs-pencil' style='margin-right: 5px;'></i>Edit</button>";
                                                    echo "<form id='$programformID' method='POST' action='commands.php' style='display: inline-block; margin-left: 5px;'>";
                                                    echo "<input type='hidden' name='programID3' value='{$row['program_id']}'>";
                                                    echo "<input type='hidden' name='programaction3' value='delete'>";
                                                    echo "<button type='button' class='btn btn-outline-danger' onclick=\"programconfirmDelete('$programformID')\" style='white-space: nowrap;'><i class='bx bxs-trash' style='margin-right: 5px;'></i>Delete</button>";
                                                    echo "</form>";
                                                    echo "</div>"; // End of the container for side-by-side buttons
                                                    echo "</div>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4'>No programs found.</td></tr>";
                                            }
                                            // DULO NG SHOW PROGRAMS
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- SUBJECTS PART -->
                            <div class="col-md-6">
                                <h2>Subjects
                                    <button class="square-button" data-toggle="modal" data-target="#addSubjectModal" data-tooltip="Add Subject">+</button>
                                </h2>
                                <div class="table-responsive" style="max-height: 593px; overflow-y: auto; border: 2px solid darkblue;">
                                    <table class="table table-bordered table-striped">
                                        <thead style="background-color: #f0ecec; position: sticky; top: -1; z-index: 1;">
                                            <th>ID</th>
                                            <th>Subject Name</th>
                                            <th>Subject Code</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody id="myTable">
                                            <?php
                                            // ETO YUNG ROW NA IPAPAKITA YUNG MGA NILAGAY SA TABLE NG SUBJECTS
                                            $sql = "SELECT * FROM tbl_subjects";

                                            $result = mysqli_query($db_connection, $sql);

                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $editSubjectModal = 'editSubjectModal' . $row["subject_id"];
                                                    $subjectformID = 'deleteSubjectForm' . $row["subject_id"];
                                                    echo "<tr>";
                                                    echo "<td>" . $row["subject_id"] . "</td>";
                                                    echo "<td>" . $row["subject_name"] . "</td>";
                                                    echo "<td>" . $row["subject_code"] . "</td>";
                                                    echo "<td>";
                                                    echo "<div class='btn-group' role='group'>";
                                                    echo "<div style='display: flex;'>";
                                                    echo "<button class='btn btn-outline-primary' data-toggle='modal' data-target='#$editSubjectModal' style='white-space: nowrap;'><i class='bx bxs-pencil' style='margin-right: 5px;'></i>Edit</button>";
                                                    echo "<form id='$subjectformID' method='POST' action='commands.php' style='display: inline-block; margin-left: 5px;'>";
                                                    echo "<input type='hidden' name='subjectID3' value='{$row['subject_id']}'>";
                                                    echo "<input type='hidden' name='subjectaction3' value='deleteSubject'>";
                                                    echo "<button type='button' class='btn btn-outline-danger' onclick=\"subjectconfirmDelete('$subjectformID')\" style='white-space: nowrap;'><i class='bx bxs-trash' style='margin-right: 5px;'></i>Delete</button>";
                                                    echo "</form>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4'>No subjects found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php // THIS IS THE TABLE OF STRANDS 
                        ?>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-6">
                                <h2>Strands <button class="square-button" data-toggle="modal" data-target="#addStrandModal" data-tooltip="Add Strand">+</button></h2>
                                <div class="table-responsive" style="max-height: 593px; overflow-y: auto; border: 2px solid darkblue;">
                                    <table class="table table-bordered table-striped">
                                        <thead style="background-color: #f0ecec; position: sticky; top: -1; z-index: 1;">
                                            <tr>
                                                <th>ID</th>
                                                <th>Strand Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="myTable">
                                            <?php
                                            $sql = "SELECT * FROM tbl_strands";

                                            $result = mysqli_query($db_connection, $sql);

                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $editStrandModal = 'editStrandModal' . $row["strand_id"];
                                                    $strandformID = 'deleteStrandForm' . $row["strand_id"];
                                                    echo "<tr>";
                                                    echo "<td>" . $row["strand_id"] . "</td>";
                                                    echo "<td>" . $row["strand_name"] . "</td>";
                                                    echo "<td>";
                                                    echo "<div class='btn-group' role='group'>";
                                                    echo "<div style='display: flex;'>";
                                                    echo "<button class='btn btn-outline-primary' data-toggle='modal' data-target='#$editStrandModal' style='white-space: nowrap;'><i class='bx bxs-pencil' style='margin-right: 5px;'></i>Edit</button>";
                                                    echo "<form id='$strandformID' method='POST' action='commands.php' style='display: inline-block; margin-left: 5px;'>";
                                                    echo "<input type='hidden' name='strandID3' value='{$row['strand_id']}'>";
                                                    echo "<input type='hidden' name='strandaction3' value='delete'>";
                                                    echo "<button type='button' class='btn btn-outline-danger' style='white-space: nowrap;' onclick=\"strandconfirmDelete('$strandformID')\"><i class='bx bxs-trash' style='margin-right: 5px;'></i>Delete</button>";
                                                    echo "</form>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4'>No Strand found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php // THIS IS THE TABLE OF SEMESTERS 
                            ?>
                            <div class="col-md-6">
                                <h2>Semesters <button class="square-button" data-toggle="modal" data-target="#addSemesterModal" data-tooltip="Add Semester">+</button></h2>
                                <div class="table-responsive" style="max-height: 593px; overflow-y: auto; border: 2px solid darkblue;">
                                    <table class="table table-bordered table-striped">
                                        <thead style="background-color: #f0ecec; position: sticky; top: -1; z-index: 1;">
                                            <tr>
                                                <th>ID</th>
                                                <th>Semester Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="myTable">
                                            <?php
                                            // RETRIEVE DATA 
                                            $sql = "SELECT * FROM tbl_semesters";
                                            $result = mysqli_query($db_connection, $sql);

                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $editSemesterModal = 'editSemesterModal' . $row["semester_id"];
                                                    $semesterformID = "deleteSemesterForm" . $row["semester_id"];
                                                    echo "<tr>";
                                                    echo "<td>" . $row["semester_id"] . "</td>";
                                                    echo "<td>" . $row["semester_name"] . "</td>";
                                                    echo "<td>";
                                                    echo "<div class='btn-group' role='group'>";
                                                    echo "<div style='display: flex;'>";
                                                    echo "<button class='btn btn-outline-primary' data-toggle='modal' data-target='#$editSemesterModal' style='white-space: nowrap;'><i class='bx bxs-pencil' style='margin-right: 5px;'></i>Edit</button>";
                                                    echo "<form id='$semesterformID' method='POST' action='commands.php' style='display: inline-block; margin-left: 5px;'>";
                                                    echo "<input type='hidden' name='semesterID3' value='" . $row['semester_id'] . "'>";
                                                    echo "<input type='hidden' name='semesteraction3' value='delete'>";
                                                    echo "<button type='button' class='btn btn-outline-danger' onclick=\"semesterconfirmDelete('$semesterformID')\" style='white-space: nowrap;'><i class='bx bxs-trash' style='margin-right: 5px;'></i>Delete</button>";
                                                    echo "</form>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='3'>No semesters found.</td></tr>";
                                            }
                                            // END DISPLAY
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-6">
                                <h2>Teachers <button class="square-button" data-toggle="modal" data-target="#addTeacherModal" data-tooltip="Add Teacher">+</button></h2>
                                <div class="table-responsive" style="max-height: 593px; overflow-y: auto; border: 2px solid darkblue;">
                                    <table class="table table-bordered table-striped">
                                        <thead style="background-color: #f0ecec; position: sticky; top: -1; z-index: 1;">
                                            <tr>
                                                <th>ID</th>
                                                <th>Teacher Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="myTable">
                                            <?php
                                            $sql = "SELECT * FROM tbl_teachers";

                                            $result = mysqli_query($db_connection, $sql);

                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $editTeacherModal = 'editTeacherModal' . $row["teacher_id"];
                                                    $teacherFormID = "deleteTeacherForm" . $row["teacher_id"];
                                                    echo "<tr>";
                                                    echo "<td>" . $row["teacher_id"] . "</td>";
                                                    echo "<td>" . $row["first_name"] . " " . $row["last_name"] . "</td>";
                                                    echo "<td>";
                                                    echo "<div class='btn-group' role='group'>";
                                                    echo "<div style='display: flex;'>";
                                                    echo "<button class='btn btn-outline-primary' data-toggle='modal' data-target='#$editTeacherModal' style='white-space: nowrap;'><i class='bx bxs-pencil' style='margin-right: 5px;'></i>Edit</button>";
                                                    echo "<form id='$teacherFormID' method='POST' action='commands.php' style='display: inline-block; margin-left: 5px;'>";
                                                    echo "<input type='hidden' name='teacherID3' value='" . $row['teacher_id'] . "'>";
                                                    echo "<input type='hidden' name='teacherAction3' value='deleteTeacher'>";
                                                    echo "<button type='button' class='btn btn-outline-danger' onclick=\"teacherConfirmDelete('$teacherFormID')\" style='white-space: nowrap;'><i class='bx bxs-trash' style='margin-right: 5px;'></i>Delete</button>";
                                                    echo "</form>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4'>No teachers found.</td></tr>";
                                            }
                                            // END DISPLAY
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MAIN -->
        </section>
        <!-- CONTENT -->


    </body>

    </html>