<?php
include_once 'commands.php';
include_once 'authentication.php';
include_once 'includes.php';
?>

<!-- ADD COLLEGE MODAL -->
<!-- ETO YUNG MODAL, BASIC LANG. -->
<div class="modal fade" id="addCollegeModal" tabindex="-1" aria-labelledby="addCollegeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCollegeModalLabel">Add College</h5>
            </div>
            <div class="modal-body">
                <!-- ETO, MAY FORM SIYA, ETO YUNG NAG EENABLE, O NAGSESEND NG SIGNAL SA commands.php PARA MAEXECUTE YUNG NILAGAY DITO SA LOOB NG FORM -->
                <form action="commands.php" method="POST">
                    <div class="form-group">
                        <label for="collegeName">College Name</label>
                        <input type="text" class="form-control" id="collegeName" name="collegeName" placeholder="e.g. College of Information and Communications Technology" required>
                    </div>
                    <input type="hidden" name="collegeaction" value="addCollege">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
                </form>
                <!-- ETO YUNG DULO NG FORM, KASI KELANGAN ETONG "ADD" BUTTON SA LOOB NG FORM KASI SIYA YUNG NAGPAPAADD/NAGCOCONFIRM NG DATA SA TABLE -->
            </div>
        </div>
    </div>
</div>
<?php
// ------------------------------------------------- EDIT COLLEGE MODAL ---------------------------------------------
$sql = "SELECT * FROM tbl_colleges";
$result = mysqli_query($db_connection, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $editCollegeModal = 'editCollegeModal' . $row["college_id"];
?>

        <div class="modal fade" id="<?php echo $editCollegeModal; ?>" tabindex="-1" aria-labelledby="<?php echo $editCollegeModal; ?>Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?php echo $editCollegeModal; ?>Label">Edit Year Level</h5>
                    </div>
                    <div class="modal-body">
                        <form action="commands.php" method="POST" id="updateCollegeForm<?php echo $row["college_id"]; ?>">
                            <div class="form-group">
                                <label for="collegeID2">College ID:</label>
                                <input type="text" class="form-control" id="collegeID2" name="collegeID2" value="<?php echo $row["college_id"]; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="collegeName2">College Name:</label>
                                <input type="text" class="form-control" id="collegeName2" name="collegeName2" value="<?php echo $row["college_name"]; ?>" required>
                            </div>
                            <input type="hidden" name="collegeID2" value="<?php echo $row["college_id"]; ?>">
                            <input type="hidden" name="collegeaction2" value="editCollege">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="collegeconfirmUpdate('<?php echo $row["college_id"]; ?>')">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>

<div class="modal fade" id="addYearLevelModal" tabindex="-1" aria-labelledby="addYearLevelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addYearLevelModalLabel">Add Year Level</h5>
            </div>
            <div class="modal-body">
                <form action="commands.php" method="POST">
                    <div class="form-group">
                        <label for="yearlevelType">Year Level Type</label>
                        <select class="form-control" id="yearlevelType" name="yearlevelType" required>
                            <option value="" disabled selected>Select Year Level Type</option>
                            <option value="High School">High School</option>
                            <option value="Senior High">Senior High</option>
                            <option value="College">College</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="yearlevelName">Year Level Name</label>
                        <input type="text" class="form-control" id="yearlevelName" name="yearlevelName" placeholder="e.g. Grade 11" required>
                    </div>
                    <input type="hidden" name="yearlevelaction" value="addYearLevel">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// -------------------------------------------- EDIT YEAR LEVEL MODAL ---------------------------------------------
$sql = "SELECT * FROM tbl_yearlevels";
$result = mysqli_query($db_connection, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $editYearLevelModal = 'editYearLevelModal' . $row["year_level_id"];
?>

        <div class="modal fade" id="<?php echo $editYearLevelModal; ?>" tabindex="-1" aria-labelledby="<?php echo $editYearLevelModal; ?>Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?php echo $editYearLevelModal; ?>Label">Edit Year Level</h5>
                    </div>
                    <div class="modal-body">
                        <form action="commands.php" method="POST" id="updateYearLevelForm<?php echo $row["year_level_id"]; ?>">
                            <div class="form-group">
                                <label for="yearlevelID2">Year Level ID:</label>
                                <input type="text" class="form-control" id="yearlevelID2" name="yearlevelID2" value="<?php echo $row["year_level_id"]; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="yearlevelType2">Year Level Type:</label>
                                <select class="form-control" id="yearlevelType2" name="yearlevelType2">
                                    <option value="High School" <?php echo ($row["year_level_type"] == "High School") ? "selected" : ""; ?>>High School</option>
                                    <option value="Senior High" <?php echo ($row["year_level_type"] == "Senior High") ? "selected" : ""; ?>>Senior High</option>
                                    <option value="College" <?php echo ($row["year_level_type"] == "College") ? "selected" : ""; ?>>College</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="yearlevelName2">Year Level:</label>
                                <input type="text" class="form-control" id="yearlevelName2" name="yearlevelName2" value="<?php echo $row["year_level_name"]; ?>" required>
                            </div>
                            <input type="hidden" name="yearlevelID2" value="<?php echo $row["year_level_id"]; ?>">
                            <input type="hidden" name="yearlevelType2" value="<?php echo $row["year_level_type"]; ?>">
                            <input type="hidden" name="yearlevelaction2" value="editYearLevel">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="yearlevelconfirmUpdate('<?php echo $row["year_level_id"]; ?>')">Update</button>
                    </div>
                </div>
            </div>
        </div>

<?php
    }
}
?>

<!------------------------------------------------ ADD PROGRAMS MODAL -->
<div class="modal fade" id="addProgramModal" tabindex="-1" aria-labelledby="addProgramModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProgramModalLabel">Add Bachelor Program</h5>
            </div>
            <div class="modal-body">
                <form action="commands.php" method="POST">
                    <div class="form-group">
                        <label for="collegeSelect">College</label>
                        <select class="form-control" id="collegeSelect" name="CollegeID" required>
                            <option value="" disabled selected>Select College</option>
                            <?php
                            $sql = "SELECT college_id, college_name FROM tbl_colleges";
                            $result = mysqli_query($db_connection, $sql);

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<option value="' . $row["college_id"] . '">' . $row["college_name"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ProgramName">Program Name</label>
                        <input type="text" class="form-control" id="ProgramName" name="ProgramName" placeholder="e.g. Bachelor in Science and Information Technology" required>
                    </div>
                    <input type="hidden" name="programaction" value="addProgram">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
// -------------------------------------------- EDIT PROGRAM MODAL
$sql = "SELECT * FROM tbl_programs";
$result = mysqli_query($db_connection, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $editProgramModal = 'editProgramModal' . $row["program_id"];
?>

        <div class="modal fade" id="<?php echo $editProgramModal; ?>" tabindex="-1" aria-labelledby="<?php echo $editProgramModal; ?>Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?php echo $editProgramModal; ?>Label">Edit Program</h5>
                    </div>
                    <div class="modal-body">
                        <form action="commands.php" method="POST" id="updateProgramForm<?php echo $row["program_id"]; ?>">
                            <div class="form-group">
                                <label for="programID2">Program ID:</label>
                                <input type="text" class="form-control" id="programID2" name="programID2" value="<?php echo $row["program_id"]; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="collegeSelect">College</label>
                                <select class="form-control" id="collegeSelect" name="collegeID2" required>
                                    <?php
                                    $collegeID = $row["college_id"]; // Selected college_id for the current program
                                    $sqlColleges = "SELECT college_id, college_name FROM tbl_colleges";
                                    $resultColleges = mysqli_query($db_connection, $sqlColleges);

                                    while ($collegeRow = mysqli_fetch_assoc($resultColleges)) {
                                        $selected = ($collegeID == $collegeRow["college_id"]) ? "selected" : "";
                                        echo '<option value="' . $collegeRow["college_id"] . '" ' . $selected . '>' . $collegeRow["college_name"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="programName2">Program:</label>
                                <input type="text" class="form-control" id="programName2" name="programName2" value="<?php echo $row["program_name"]; ?>" required>
                            </div>
                            <input type="hidden" name="programID2" value="<?php echo $row["program_id"]; ?>">
                            <input type="hidden" name="programaction2" value="editProgram">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="programconfirmUpdate('<?php echo $row["program_id"]; ?>')">Update</button>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>
<!-- ADD SEMESTERS MODAL -->
<div class="modal fade" id="addSemesterModal" tabindex="-1" aria-labelledby="addSemesterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSemesterModalLabel">Add Semester</h5>
            </div>
            <div class="modal-body">
                <form action="commands.php" method="POST">
                    <div class="form-group">
                        <label for="semesterName">Semester</label>
                        <input type="text" class="form-control" id="semesterName" name="semesterName" placeholder="e.g. 1st Semester 2020-2021" required>
                    </div>
                    <input type="hidden" name="semesteraction" value="addSemester">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- EDIT SEMESTERS MODAL -->
<?php
$sql = "SELECT * FROM tbl_semesters";
$result = mysqli_query($db_connection, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $editSemesterModal = 'editSemesterModal' . $row["semester_id"];
?>

        <div class="modal fade" id="<?php echo $editSemesterModal; ?>" tabindex="-1" aria-labelledby="<?php echo $editSemesterModal; ?>Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?php echo $editSemesterModal; ?>Label">Edit Semester</h5>
                    </div>
                    <div class="modal-body">
                        <form action="commands.php" method="POST" id="updateSemesterForm<?php echo $row["semester_id"]; ?>">
                            <div class="form-group">
                                <label for="semesterID2">Semester ID:</label>
                                <input type="text" class="form-control" id="semesterID2" name="semesterID2" value="<?php echo $row["semester_id"]; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="semesterName2">Semester:</label>
                                <input type="text" class="form-control" id="semesterName2" name="semesterName2" value="<?php echo $row["semester_name"]; ?>" required>
                            </div>
                            <input type="hidden" name="semesterID2" value="<?php echo $row["semester_id"]; ?>">
                            <input type="hidden" name="semesteraction2" value="editSemester">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>
<!-- ADD SUBJECTS MODAL -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel">Add Subject</h5>
            </div>
            <div class="modal-body">
                <form action="commands.php" method="POST">
                    <div class="form-group">
                        <label for="subjectName">Subject Name</label>
                        <input type="text" class="form-control" id="subjectName" name="subjectName" placeholder="e.g. Subject One" required>
                    </div>
                    <div class="form-group">
                        <label for="subjectCode">Subject Code</label>
                        <input type="text" class="form-control" id="subjectCode" name="subjectCode" placeholder="e.g. SBJ-1" required>
                    </div>
                    <input type="hidden" name="subjectaction" value="addSubject">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// -------------------------------------------- EDIT SUBJECT MODAL
$sql = "SELECT * FROM tbl_subjects";
$result = mysqli_query($db_connection, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $editSubjectModal = 'editSubjectModal' . $row["subject_id"];
?>

        <div class="modal fade" id="<?php echo $editSubjectModal; ?>" tabindex="-1" aria-labelledby="<?php echo $editSubjectModal; ?>Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?php echo $editSubjectModal; ?>Label">Edit Subject</h5>
                    </div>
                    <div class="modal-body">
                        <form action="commands.php" method="POST" id="updateSubjectForm<?php echo $row["subject_id"]; ?>">
                            <div class="form-group">
                                <label for="subjectID2">Subject ID:</label>
                                <input type="text" class="form-control" id="subjectID2" name="subjectID2" value="<?php echo $row["subject_id"]; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="subjectName2">Subject Name:</label>
                                <input type="text" class="form-control" id="subjectName2" name="subjectName2" value="<?php echo $row["subject_name"]; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="subjectCode2">Subject Code:</label>
                                <input type="text" class="form-control" id="subjectCode2" name="subjectCode2" value="<?php echo $row["subject_code"]; ?>" required>
                            </div>
                            <input type="hidden" name="subjectID2" value="<?php echo $row["subject_id"]; ?>">
                            <input type="hidden" name="subjectaction2" value="editSubject">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="subjectconfirmUpdate('<?php echo $row["subject_id"]; ?>')">Update</button>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>
<!---------------------------------------------- ADD STRAND MODAL ------------------------------------------->
<div class="modal fade" id="addStrandModal" tabindex="-1" aria-labelledby="addStrandModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStrandModalLabel">Add Strand</h5>
            </div>
            <div class="modal-body">
                <form action="commands.php" method="POST">
                    <div class="form-group">
                        <label for="strandName">Strand Name</label>
                        <input type="text" class="form-control" id="strandName" name="strandName" placeholder="e.g. General Academic Strand" required>
                    </div>
                    <input type="hidden" name="strandaction" value="addStrand">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!---------------------------------------------- EDIT STRAND MODAL ------------------------------------------->
<?php

$sql = "SELECT * FROM tbl_strands";
$result = mysqli_query($db_connection, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $editStrandModal = 'editStrandModal' . $row["strand_id"];
?>

        <div class="modal fade" id="<?php echo $editStrandModal; ?>" tabindex="-1" aria-labelledby="<?php echo $editStrandModal; ?>Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?php echo $editStrandModal; ?>Label">Edit Strand</h5>
                    </div>
                    <div class="modal-body">
                        <form action="commands.php" method="POST" id="updateStrandForm<?php echo $row["strand_id"]; ?>">
                            <div class="form-group">
                                <label for="strandID2">Strand ID:</label>
                                <input type="text" class="form-control" id="strandID2" name="strandID2" value="<?php echo $row["strand_id"]; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="strandName2">Strand Name:</label>
                                <input type="text" class="form-control" id="strandName2" name="strandName2" value="<?php echo $row["strand_name"]; ?>" required>
                            </div>
                            <!-- Additional fields specific to strands go here -->
                            <input type="hidden" name="strandID2" value="<?php echo $row["strand_id"]; ?>">
                            <input type="hidden" name="strandaction2" value="editStrand">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="strandconfirmUpdate('<?php echo $row["strand_id"]; ?>')">Update</button>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>
<!-- Edit Admin -->
<?php
// -------------------------------------------- EDIT ADMIN LIST
$sql = "SELECT * FROM tbl_users";
$result = mysqli_query($db_connection, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $editAdminModal = 'editAdminModal' . $row["user_id"];
?>

        <div class="modal fade" id="<?php echo $editAdminModal; ?>" tabindex="-1" aria-labelledby="<?php echo $editAdminModal; ?>Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?php echo $editAdminModal; ?>Label">Edit Admin</h5>
                    </div>
                    <div class="modal-body">
                        <form action="commands.php" method="POST">
                            <div class="form-group">
                                <label for="adminID2">ID:</label>
                                <input type="text" class="form-control" id="adminID2" name="adminID2" value="<?php echo $row["user_id"]; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="userName2">Username:</label>
                                <input type="text" class="form-control" id="userName2" name="userName2" value="<?php echo $row["username"]; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="newPassword">New Password:</label>
                                <input type="password" class="form-control" id="newPassword" name="newPassword">
                            </div>
                            <input type="hidden" name="adminID2" value="<?php echo $row["user_id"]; ?>">
                            <input type="hidden" name="adminaction2" value="editAdmin">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<?php
    }
}
?>
<!-- Edit Staff -->
<?php
// -------------------------------------------- EDIT STAFF LIST
$sql = "SELECT * FROM tbl_users";
$result = mysqli_query($db_connection, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $editStaffModal = 'editStaffModal' . $row["user_id"];

?>

        <div class="modal fade" id="<?php echo $editStaffModal; ?>" tabindex="-1" aria-labelledby="<?php echo $editStaffModal; ?>Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?php echo $editStaffModal; ?>Label">Edit Staff</h5>
                    </div>
                    <div class="modal-body">
                        <form action="commands.php" method="POST">
                            <div class="form-group">
                                <label for="staffID2">ID:</label>
                                <input type="text" class="form-control" id="staffID2" name="staffID2" value="<?php echo $row["user_id"]; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="userName2">Username:</label>
                                <input type="text" class="form-control" id="userName2" name="userName2" value="<?php echo $row["username"]; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="newPassword">New Password:</label>
                                <input type="password" class="form-control" id="newPassword" name="newPassword">
                            </div>
                            <input type="hidden" name="staffID2" value="<?php echo $row["user_id"]; ?>">
                            <input type="hidden" name="staffaction2" value="editStaff">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<?php
    }
}
?>

<!-- ADD TEACHER MODAL -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTeacherModalLabel">Add Teacher</h5>
            </div>
            <div class="modal-body">
                <form action="commands.php" method="POST">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name" required>
                    </div>
                    <input type="hidden" name="teacherAction" value="addTeacher">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- EDIT TEACHERS MODAL -->
<?php
$sql = "SELECT * FROM tbl_teachers";
$result = mysqli_query($db_connection, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $editTeacherModal = 'editTeacherModal' . $row["teacher_id"];
?>

        <div class="modal fade" id="<?php echo $editTeacherModal; ?>" tabindex="-1" aria-labelledby="<?php echo $editTeacherModal; ?>Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?php echo $editTeacherModal; ?>Label">Edit Teacher</h5>
                    </div>
                    <div class="modal-body">
                        <form action="commands.php" method="POST" id="updateTeacherForm<?php echo $row["teacher_id"]; ?>">
                            <div class="form-group">
                                <label for="teacherID">Teacher ID:</label>
                                <input type="text" class="form-control" id="teacherID" name="teacherID" value="<?php echo $row["teacher_id"]; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="teacherFirstName">First Name:</label>
                                <input type="text" class="form-control" id="teacherFirstName" name="teacherFirstName" value="<?php echo $row["first_name"]; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="teacherLastName">Last Name:</label>
                                <input type="text" class="form-control" id="teacherLastName" name="teacherLastName" value="<?php echo $row["last_name"]; ?>" required>
                            </div>
                            <input type="hidden" name="teacherID" value="<?php echo $row["teacher_id"]; ?>">
                            <input type="hidden" name="teacherAction2" value="editTeacher">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>