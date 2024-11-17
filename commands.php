<?php
// I'LL PUT ALL THE COMMAND STUFF HERE
// -------------------- PARA MADALING INTINDIHIN, GINAWA KO, 1 FOR ADD, 2 FOR UPDATE, 3 FOR DELETE LAHAT -------------------------
include_once 'database.php';
include_once 'authentication.php';
include_once 'includes.php';
// IF CLICK ADD, THERE IN THE MODAL, MAEEXECUTE TO.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST);
    if (isset($_POST["collegeaction"]) && $_POST["collegeaction"] == "addCollege") {
        // Code for adding a college
        $collegeName = $_POST["collegeName"];
        $collegeName = ucwords(trim(preg_replace('/\s+/', ' ', strtolower($collegeName))));

        // Check if the college already exists in the database
        $existingCollegeQuery = "SELECT * FROM tbl_colleges WHERE college_name = '$collegeName'";
        $existingCollegeResult = mysqli_query($db_connection, $existingCollegeQuery);

        if (mysqli_num_rows($existingCollegeResult) > 0) {
            // College already exists, set session variable
            $_SESSION["college_added"] = "exists";
        } else {
            // College doesn't exist, proceed with insertion
            $sql = "INSERT INTO tbl_colleges (college_name) VALUES ('$collegeName')";

            if (mysqli_query($db_connection, $sql)) {
                // Successful insertion, set session variable
                $_SESSION["college_added"] = "success";
            } else {
                // Error adding college, set session variable
                $_SESSION["college_added"] = "error";
            }
        }

        mysqli_close($db_connection);

        // Redirect to management.php
        header("Location: management.php");
        exit;
    } elseif (isset($_POST["collegeaction2"]) && $_POST["collegeaction2"] == "editCollege") {
        // Code for editing a college
        $collegeID2 = $_POST["collegeID2"];
        $collegeName2 = $_POST["collegeName2"];
        $collegeName2 = ucwords(trim(preg_replace('/\s+/', ' ', strtolower($collegeName2))));

        // Retrieve the current college name from the database
        $currentCollegeQuery = "SELECT college_name FROM tbl_colleges WHERE college_id = '$collegeID2'";
        $currentCollegeResult = mysqli_query($db_connection, $currentCollegeQuery);

        if ($currentCollegeResult && mysqli_num_rows($currentCollegeResult) > 0) {
            $row = mysqli_fetch_assoc($currentCollegeResult);
            $currentCollegeName = $row["college_name"];

            // Check if the college name has changed
            if ($collegeName2 === $currentCollegeName) {
                // College name remains unchanged, set session variable
                $_SESSION["college_updated"] = "unchanged";
            } else {
                // Check if the new college name already exists in the table
                $existingCollegeQuery = "SELECT * FROM tbl_colleges WHERE college_name = '$collegeName2'";
                $existingCollegeResult = mysqli_query($db_connection, $existingCollegeQuery);

                if (mysqli_num_rows($existingCollegeResult) > 0) {
                    // New college name already exists in the table, set session variable
                    $_SESSION["college_updated"] = "exists";
                } else {
                    // New college name is unique, proceed with the update
                    $sql = "UPDATE tbl_colleges SET college_name = '$collegeName2' WHERE college_id = '$collegeID2'";

                    if (mysqli_query($db_connection, $sql)) {
                        // Update was successful, set session variable
                        $_SESSION["college_updated"] = "success";
                    } else {
                        // Error updating college, set session variable
                        $_SESSION["college_updated"] = "error";
                    }
                }
            }
        } else {
            // Handle the case where the current college name couldn't be retrieved
            $_SESSION["college_updated"] = "error";
        }

        // Redirect back to management.php
        header("Location: management.php");
        exit();
    } elseif (isset($_POST['collegeaction3'])) {
        if ($_POST['collegeaction3'] === 'delete') {
            // Get the college ID from the form
            $collegeID3 = $_POST['collegeID3'];

            // Check if the college exists in the database
            $existingCollegeQuery = "SELECT * FROM tbl_colleges WHERE college_id = '$collegeID3'";
            $existingCollegeResult = mysqli_query($db_connection, $existingCollegeQuery);

            if (mysqli_num_rows($existingCollegeResult) > 0) {
                // College exists, check if it's a parent/foreign key in another table
                $isReferencedQuery = "SELECT 1 FROM tbl_programs WHERE college_id = '$collegeID3' LIMIT 1";
                $isReferencedResult = mysqli_query($db_connection, $isReferencedQuery);

                if (mysqli_num_rows($isReferencedResult) > 0) {
                    // College is referenced, set a session variable to indicate the deletion failure
                    session_start();
                    $_SESSION['collegedeleteFailure'] = true;
                } else {
                    // College is not referenced, proceed with deletion
                    $sql = "DELETE FROM tbl_colleges WHERE college_id = '$collegeID3'";
                    $result = mysqli_query($db_connection, $sql);

                    if ($result) {
                        // Successful deletion, set a session variable
                        session_start();
                        $_SESSION['collegedeleteSuccess'] = true;
                    } else {
                        // Error deleting college, handle it as needed
                        echo "Error deleting college: " . mysqli_error($db_connection);
                    }
                }
            } else {
                // College doesn't exist, show an error message or handle it as needed
                echo "College not found.";
            }

            header("Location: management.php");
            exit();
        }
    } elseif (isset($_POST["yearlevelaction"]) && $_POST["yearlevelaction"] == "addYearLevel") {
        // Code for adding a year level
        $yearlevelName = $_POST["yearlevelName"];
        $yearlevelName = ucwords(trim(preg_replace('/\s+/', ' ', strtolower($yearlevelName))));
        $yearlevelType = $_POST["yearlevelType"]; // Add this line to retrieve year level type

        // Check if the year level already exists in the database
        $existingYearLevelQuery = "SELECT * FROM tbl_yearlevels WHERE year_level_name = '$yearlevelName' AND year_level_type = '$yearlevelType'";
        $existingYearLevelResult = mysqli_query($db_connection, $existingYearLevelQuery);

        if (mysqli_num_rows($existingYearLevelResult) > 0) {
            // Year level already exists, set session variable
            $_SESSION["yearlevel_added"] = "exists";
        } else {
            // Year level doesn't exist, proceed with insertion
            $sql = "INSERT INTO tbl_yearlevels (year_level_name, year_level_type) VALUES ('$yearlevelName', '$yearlevelType')";

            if (mysqli_query($db_connection, $sql)) {
                // Successful insertion, set session variable
                $_SESSION["yearlevel_added"] = "success";
            } else {
                // Error adding year level, set session variable
                $_SESSION["yearlevel_added"] = "error";
                echo "Error adding year level: " . mysqli_error($db_connection);
            }
        }

        mysqli_close($db_connection);

        // Redirect to management.php
        header("Location: management.php");
        exit;
    } elseif (isset($_POST["yearlevelaction2"]) && $_POST["yearlevelaction2"] == "editYearLevel") {
        // Code for editing a year level
        $yearlevelID2 = $_POST["yearlevelID2"];
        $yearlevelName2 = $_POST["yearlevelName2"];
        $yearlevelName2 = ucwords(trim(preg_replace('/\s+/', ' ', strtolower($yearlevelName2))));

        // New code to get year_level_type
        $yearlevelType2 = $_POST["yearlevelType2"];

        // Retrieve the current year level details from the database
        $currentYearLevelQuery = "SELECT year_level_name, year_level_type FROM tbl_yearlevels WHERE year_level_id = '$yearlevelID2'";
        $currentYearLevelResult = mysqli_query($db_connection, $currentYearLevelQuery);

        if ($currentYearLevelResult && mysqli_num_rows($currentYearLevelResult) > 0) {
            $row = mysqli_fetch_assoc($currentYearLevelResult);
            $currentYearLevelName = $row["year_level_name"];
            $currentYearLevelType = $row["year_level_type"];

            // Check if the year level details have changed
            if ($yearlevelName2 === $currentYearLevelName && $yearlevelType2 === $currentYearLevelType) {
                // Year level details remain unchanged, set session variable
                $_SESSION["yearlevel_updated"] = "unchanged";
            } else {
                // Check if the new year level name already exists in the table
                $existingYearLevelQuery = "SELECT * FROM tbl_yearlevels WHERE year_level_name = '$yearlevelName2' AND year_level_id != '$yearlevelID2'";
                $existingYearLevelResult = mysqli_query($db_connection, $existingYearLevelQuery);

                if (mysqli_num_rows($existingYearLevelResult) > 0) {
                    // New year level name already exists in the table, set session variable
                    $_SESSION["yearlevel_updated"] = "exists";
                } else {
                    // New year level name is unique, proceed with the update
                    $sql = "UPDATE tbl_yearlevels SET year_level_name = '$yearlevelName2', year_level_type = '$yearlevelType2' WHERE year_level_id = '$yearlevelID2'";

                    if (mysqli_query($db_connection, $sql)) {
                        // Update was successful, set session variable
                        $_SESSION["yearlevel_updated"] = "success";
                    } else {
                        // Error updating year level, set session variable
                        $_SESSION["yearlevel_updated"] = "error";
                        echo "Error updating year level: " . mysqli_error($db_connection);
                    }
                }
            }
        } else {
            // Handle the case where the current year level details couldn't be retrieved
            $_SESSION["yearlevel_updated"] = "error";
            echo "Error updating year level: " . mysqli_error($db_connection);
        }

        // Redirect back to management.php
        header("Location: management.php");
        exit();
    } elseif (isset($_POST['yearlevelaction3'])) {
        // Code for deleting a year level
        $yearlevelID3 = $_POST['yearlevelID3'];

        // Check if the year level is referenced in other tables

        // Check if the year level is referenced in tbl__books
        $isReferencedQueryBooks = "SELECT 1 FROM tbl_books WHERE year_level_id = '$yearlevelID3' LIMIT 1";
        $isReferencedResultBooks = mysqli_query($db_connection, $isReferencedQueryBooks);

        // Check if the year level is referenced in any of the tables
        if (
            mysqli_num_rows($isReferencedResultBooks) > 0
        ) {
            // Year level is referenced, set a session variable to indicate the deletion failure
            session_start();
            $_SESSION['yearleveldeleteFailure'] = true;
        } else {
            // Year level is not referenced, proceed with deletion
            $sql = "DELETE FROM tbl_yearlevels WHERE year_level_id = '$yearlevelID3'";
            $result = mysqli_query($db_connection, $sql);

            if ($result) {
                // Successful deletion, set a session variable
                session_start();
                $_SESSION['yearleveldeleteSuccess'] = true;
            } else {
                // Error deleting year level, handle it as needed
                echo "Error deleting year level: " . mysqli_error($db_connection);
            }
        }
        header("Location: management.php");
        exit();
    } elseif (isset($_POST["programaction"]) && $_POST["programaction"] == "addProgram") {
        // Code for adding a program
        $programName = $_POST["ProgramName"];
        $programName = ucwords(trim(preg_replace('/\s+/', ' ', strtolower($programName))));

        $collegeID = $_POST["CollegeID"]; // Assuming you have a form field for selecting a college.

        // Check if the program already exists in the database regardless of the college
        $existingProgramQuery = "SELECT * FROM tbl_programs WHERE program_name = '$programName'";
        $existingProgramResult = mysqli_query($db_connection, $existingProgramQuery);

        if (mysqli_num_rows($existingProgramResult) > 0) {
            // Check if the program with the same name exists for the selected college
            while ($row = mysqli_fetch_assoc($existingProgramResult)) {
                if ($row['college_id'] == $collegeID) {
                    $_SESSION["program_added"] = "exists";
                    header("Location: management.php");
                    exit();
                }
            }
        }

        // If no matching program was found, proceed with insertion
        $sql = "INSERT INTO tbl_programs (program_name, college_id) VALUES ('$programName', '$collegeID')";

        if (mysqli_query($db_connection, $sql)) {
            $_SESSION["program_added"] = "success";
            header("Location: management.php");
            exit();
        } else {
            $_SESSION["program_added"] = "error";
            header("Location: management.php");
            exit();
        }

        mysqli_close($db_connection);
    } elseif (isset($_POST["programaction2"]) && $_POST["programaction2"] == "editProgram") {
        // Code for editing a program
        $programID2 = $_POST["programID2"];
        $programName2 = $_POST["programName2"];
        $programName2 = ucwords(trim(preg_replace('/\s+/', ' ', strtolower($programName2))));
        $collegeID2 = $_POST["collegeID2"]; // Get the selected college ID from the form

        // Retrieve the current program name and college ID from the database
        $currentProgramQuery = "SELECT program_name, college_id FROM tbl_programs WHERE program_id = '$programID2'";
        $currentProgramResult = mysqli_query($db_connection, $currentProgramQuery);

        if ($currentProgramResult && mysqli_num_rows($currentProgramResult) > 0) {
            $row = mysqli_fetch_assoc($currentProgramResult);
            $currentName = $row["program_name"];
            $currentCollegeID = $row["college_id"];

            // Check if the name and college are unchanged
            if ($programName2 === $currentName && $collegeID2 == $currentCollegeID) {
                $_SESSION["program_updated"] = "unchanged";
                header("Location: management.php");
                exit();
            }
        }

        // Check if the program name already exists for any college, excluding the program being edited
        $existingProgramQuery = "SELECT * FROM tbl_programs WHERE program_name = '$programName2' AND program_id != '$programID2'";
        $existingProgramResult = mysqli_query($db_connection, $existingProgramQuery);

        if (mysqli_num_rows($existingProgramResult) > 0) {
            $_SESSION["program_updated"] = "exists";
            header("Location: management.php");
            exit();
        }

        // Your existing code for updating the program with the new college_id
        $sql = "UPDATE tbl_programs SET program_name = '$programName2', college_id = '$collegeID2' WHERE program_id = '$programID2'";

        if (mysqli_query($db_connection, $sql)) {
            $_SESSION["program_updated"] = "success";
            header("Location: management.php");
            exit();
        } else {
            $_SESSION["program_updated"] = "error";
            header("Location: management.php");
            exit();
        }

        mysqli_close($db_connection);
    } elseif (isset($_POST['programaction3'])) {
        // Code for deleting a program
        $programID3 = $_POST['programID3'];

        // Check if the program is referenced in other tables
        $isReferencedQuery = "SELECT 1 FROM tbl_book_programs WHERE program_id = '$programID3' LIMIT 1";
        $isReferencedResult = mysqli_query($db_connection, $isReferencedQuery);

        if (mysqli_num_rows($isReferencedResult) > 0) {
            // Program is referenced, set a session variable to indicate the deletion failure
            session_start();
            $_SESSION['programdeleteFailure'] = true;
        } else {
            // Program is not referenced, proceed with deletion
            $sql = "DELETE FROM tbl_programs WHERE program_id = '$programID3'";
            $result = mysqli_query($db_connection, $sql);

            if ($result) {
                // Successful deletion, set a session variable
                $_SESSION['programdeleteSuccess'] = true;
            } else {
                // Error deleting program, handle it as needed
                echo "Error deleting program: " . mysqli_error($db_connection);
            }
        }

        header("Location: management.php");
        exit();
    }
    // Code for adding a subject
    elseif (isset($_POST["subjectaction"]) && $_POST["subjectaction"] == "addSubject") {
        $subjectName = $_POST["subjectName"];
        $subjectCode = $_POST["subjectCode"];
        $subjectName = ucwords(trim(preg_replace('/\s+/', ' ', strtolower($subjectName))));
        $subjectCode = strtoupper(trim(preg_replace('/\s+/', ' ', $subjectCode)));

        // Check if the subject name already exists in the database
        $existingNameQuery = "SELECT * FROM tbl_subjects WHERE subject_name = '$subjectName'";
        $existingNameResult = mysqli_query($db_connection, $existingNameQuery);

        // Check if the subject code already exists in the database
        $existingCodeQuery = "SELECT * FROM tbl_subjects WHERE subject_code = '$subjectCode'";
        $existingCodeResult = mysqli_query($db_connection, $existingCodeQuery);

        if (mysqli_num_rows($existingNameResult) > 0 || mysqli_num_rows($existingCodeResult) > 0) {
            // Subject name or code already exists, handle the error and set the session variable
            $_SESSION["subject_added"] = "exists";
            header("Location: management.php");
            exit();
        } else {
            // Subject doesn't exist, proceed with insertion
            $sql = "INSERT INTO tbl_subjects (subject_name, subject_code) VALUES ('$subjectName', '$subjectCode')";

            if (mysqli_query($db_connection, $sql)) {
                // Successful insertion, set the success session variable and redirect to the management page
                $_SESSION["subject_added"] = "success";
                header("Location: management.php");
                exit();
            } else {
                // Error adding subject, set the error session variable
                $_SESSION["subject_added"] = "error";
                echo "Error adding subject: " . mysqli_error($db_connection);
            }
        }

        mysqli_close($db_connection);
    }

    // Code for editing a subject
    elseif (isset($_POST["subjectaction2"]) && $_POST["subjectaction2"] == "editSubject") {
        // Get the data from the form
        $subjectID2 = $_POST["subjectID2"];
        $subjectName2 = $_POST["subjectName2"];
        $subjectCode2 = $_POST["subjectCode2"];

        $subjectName2 = ucwords(trim(preg_replace('/\s+/', ' ', strtolower($subjectName2))));
        $subjectCode2 = strtoupper(trim(preg_replace('/\s+/', ' ', $subjectCode2)));

        // Check if the name has changed
        $sqlCheckUnchanged = "SELECT subject_name, subject_code FROM tbl_subjects WHERE subject_id = '$subjectID2'";
        $resultCheckUnchanged = mysqli_query($db_connection, $sqlCheckUnchanged);

        if (mysqli_num_rows($resultCheckUnchanged) == 1) {
            $row = mysqli_fetch_assoc($resultCheckUnchanged);
            $originalName = $row["subject_name"];
            $originalCode = $row["subject_code"];

            // Check if both the name and code are unchanged
            if ($subjectName2 === $originalName && $subjectCode2 === $originalCode) {
                $_SESSION["subject_updated"] = "unchanged";
                header("Location: management.php"); // Redirect immediately for unchanged
                exit();
            }
        }

        // Check if the subject name already exists
        $existingNameQuery = "SELECT * FROM tbl_subjects WHERE subject_name = '$subjectName2' AND subject_id != '$subjectID2'";
        $existingNameResult = mysqli_query($db_connection, $existingNameQuery);

        // Check if the subject code already exists
        $existingCodeQuery = "SELECT * FROM tbl_subjects WHERE subject_code = '$subjectCode2' AND subject_id != '$subjectID2'";
        $existingCodeResult = mysqli_query($db_connection, $existingCodeQuery);

        if (mysqli_num_rows($existingNameResult) > 0) {
            $_SESSION["subject_updated"] = "name_exists"; // Set name exists session variable
            header("Location: management.php");
            exit();
        } elseif (mysqli_num_rows($existingCodeResult) > 0) {
            $_SESSION["subject_updated"] = "code_exists"; // Set code exists session variable
            header("Location: management.php");
            exit();
        }

        // Your existing code for updating the subject
        $sql = "UPDATE tbl_subjects SET subject_name = '$subjectName2', subject_code = '$subjectCode2' WHERE subject_id = '$subjectID2'";

        if (mysqli_query($db_connection, $sql)) {
            $_SESSION["subject_updated"] = "success"; // Set success session variable
            header("Location: management.php");
            exit();
        } else {
            $_SESSION["subject_updated"] = "error"; // Set error session variable
            header("Location: management.php");
            exit();
        }

        mysqli_close($db_connection);
    } elseif (isset($_POST['subjectaction3'])) {
        // Code for deleting a subject
        $subjectID3 = $_POST['subjectID3'];

        // Check if the subject is referenced in tbl_books
        $isReferencedQueryBooks = "SELECT 1 FROM tbl_books WHERE subject_id = '$subjectID3' LIMIT 1";
        $isReferencedResultBooks = mysqli_query($db_connection, $isReferencedQueryBooks);

        // Check if the subject is referenced in any of the tables
        if (
            mysqli_num_rows($isReferencedResultBooks) > 0
        ) {
            // Subject is referenced, set a session variable to indicate the deletion failure
            session_start();
            $_SESSION['subjectdeleteFailure'] = true;
        } else {
            // Subject is not referenced, proceed with deletion
            $sql = "DELETE FROM tbl_subjects WHERE subject_id = '$subjectID3'";
            $result = mysqli_query($db_connection, $sql);

            if ($result) {
                // Successful deletion, set a session variable
                $_SESSION['subjectdeleteSuccess'] = true;
            } else {
                // Error deleting subject, handle it as needed
                echo "Error deleting subject: " . mysqli_error($db_connection);
            }
        }

        header("Location: management.php");
        exit();
    }
    if (isset($_POST["semesteraction"]) && $_POST["semesteraction"] == "addSemester") {
        // Code for adding a semester
        $semesterName = $_POST["semesterName"];
        $semesterName = ucwords(trim(preg_replace('/\s+/', ' ', strtolower($semesterName))));

        // Check if the semester already exists in the database
        $existingSemesterQuery = "SELECT * FROM tbl_semesters WHERE semester_name = '$semesterName'";
        $existingSemesterResult = mysqli_query($db_connection, $existingSemesterQuery);

        if (mysqli_num_rows($existingSemesterResult) > 0) {
            $_SESSION["semester_added"] = "exists";
            header("Location: management.php");
            exit();
        } else {
            // Semester doesn't exist, proceed with insertion
            $sql = "INSERT INTO tbl_semesters (semester_name) VALUES ('$semesterName')";

            if (mysqli_query($db_connection, $sql)) {
                $_SESSION["semester_added"] = "success";
                header("Location: management.php");
                exit();
            } else {
                $_SESSION["semester_added"] = "error";
                header("Location: management.php");
                exit();
            }
        }

        mysqli_close($db_connection);
    } elseif (isset($_POST["semesteraction2"]) && $_POST["semesteraction2"] == "editSemester") {
        // Code for editing a semester
        $semesterID2 = $_POST["semesterID2"];
        $semesterName2 = $_POST["semesterName2"];
        $semesterName2 = ucwords(trim(preg_replace('/\s+/', ' ', strtolower($semesterName2))));

        // Check if the semester name has changed
        $sqlCheckUnchanged = "SELECT semester_name FROM tbl_semesters WHERE semester_id = '$semesterID2'";
        $resultCheckUnchanged = mysqli_query($db_connection, $sqlCheckUnchanged);

        if (mysqli_num_rows($resultCheckUnchanged) == 1) {
            $row = mysqli_fetch_assoc($resultCheckUnchanged);
            $originalName = $row["semester_name"];

            // Check if the name is unchanged
            if ($semesterName2 === $originalName) {
                $_SESSION["semester_updated"] = "unchanged";
                header("Location: management.php");
                exit();
            }
        }

        // Check if the semester name already exists
        $existingSemesterQuery = "SELECT * FROM tbl_semesters WHERE semester_name = '$semesterName2'";
        $existingSemesterResult = mysqli_query($db_connection, $existingSemesterQuery);

        if (mysqli_num_rows($existingSemesterResult) > 0) {
            $_SESSION["semester_updated"] = "exists";
            header("Location: management.php");
            exit();
        }

        // Your existing code for updating the semester
        $sql = "UPDATE tbl_semesters SET semester_name = '$semesterName2' WHERE semester_id = '$semesterID2'";

        if (mysqli_query($db_connection, $sql)) {
            $_SESSION["semester_updated"] = "success";
            header("Location: management.php");
            exit();
        } else {
            $_SESSION["semester_updated"] = "error";
            header("Location: management.php");
            exit();
        }

        mysqli_close($db_connection);
    } elseif (isset($_POST['semesteraction3'])) {
        // Code for deleting a semester
        $semesterID3 = $_POST['semesterID3'];

        // Check if the semester is referenced in other tables
        $isReferencedQuery = "SELECT 1 FROM tbl_transactions WHERE semester_id = '$semesterID3' LIMIT 1";
        $isReferencedResult = mysqli_query($db_connection, $isReferencedQuery);

        if (mysqli_num_rows($isReferencedResult) > 0) {
            // Semester is referenced, set a session variable to indicate the deletion failure
            session_start();
            $_SESSION['semesterdeleteFailure'] = true;
        } else {
            // Semester is not referenced, proceed with deletion
            $sql = "DELETE FROM tbl_semesters WHERE semester_id = '$semesterID3'";
            $result = mysqli_query($db_connection, $sql);

            if ($result) {
                // Successful deletion, set a session variable
                $_SESSION['semesterdeleteSuccess'] = true;
            } else {
                // Error deleting semester, handle it as needed
                echo "Error deleting semester: " . mysqli_error($db_connection);
            }
        }

        header("Location: management.php");
        exit();
    } elseif (isset($_POST["strandaction"]) && $_POST["strandaction"] == "addStrand") {
        // Code for adding a strand
        $strandName = $_POST["strandName"];
        $strandName = ucwords(trim(preg_replace('/\s+/', ' ', strtolower($strandName))));

        // Check if the strand already exists in the database
        $existingStrandQuery = "SELECT * FROM tbl_strands WHERE strand_name = '$strandName'";
        $existingStrandResult = mysqli_query($db_connection, $existingStrandQuery);

        if (mysqli_num_rows($existingStrandResult) > 0) {
            // Strand already exists, set session variable
            $_SESSION["strand_added"] = "exists";
        } else {
            // Strand doesn't exist, proceed with insertion
            $sql = "INSERT INTO tbl_strands (strand_name) VALUES ('$strandName')";

            if (mysqli_query($db_connection, $sql)) {
                // Successful insertion, set session variable
                $_SESSION["strand_added"] = "success";
            } else {
                // Error adding strand, set session variable
                $_SESSION["strand_added"] = "error";
            }
        }

        mysqli_close($db_connection);

        // Redirect to management.php
        header("Location: management.php");
        exit;
    } elseif (isset($_POST["strandaction2"]) && $_POST["strandaction2"] == "editStrand") {
        // Code for editing a strand
        $strandID2 = $_POST["strandID2"];
        $strandName2 = $_POST["strandName2"];
        $strandName2 = ucwords(trim(preg_replace('/\s+/', ' ', strtolower($strandName2))));

        // Retrieve the current strand name from the database
        $currentStrandQuery = "SELECT strand_name FROM tbl_strands WHERE strand_id = '$strandID2'";
        $currentStrandResult = mysqli_query($db_connection, $currentStrandQuery);

        if ($currentStrandResult && mysqli_num_rows($currentStrandResult) > 0) {
            $row = mysqli_fetch_assoc($currentStrandResult);
            $currentStrandName = $row["strand_name"];

            // Check if the strand name has changed
            if ($strandName2 === $currentStrandName) {
                // Strand name remains unchanged, set session variable
                $_SESSION["strand_updated"] = "unchanged";
            } else {
                // Check if the new strand name already exists in the table
                $existingStrandQuery = "SELECT * FROM tbl_strands WHERE strand_name = '$strandName2'";
                $existingStrandResult = mysqli_query($db_connection, $existingStrandQuery);

                if (mysqli_num_rows($existingStrandResult) > 0) {
                    // New strand name already exists in the table, set session variable
                    $_SESSION["strand_updated"] = "exists";
                } else {
                    // New strand name is unique, proceed with the update
                    $sql = "UPDATE tbl_strands SET strand_name = '$strandName2' WHERE strand_id = '$strandID2'";

                    if (mysqli_query($db_connection, $sql)) {
                        // Update was successful, set session variable
                        $_SESSION["strand_updated"] = "success";
                    } else {
                        // Error updating strand, set session variable
                        $_SESSION["strand_updated"] = "error";
                    }
                }
            }
        } else {
            // Handle the case where the current strand name couldn't be retrieved
            $_SESSION["strand_updated"] = "error";
        }

        // Redirect back to management.php
        header("Location: management.php");
        exit();
    } elseif (isset($_POST['strandaction3'])) {
        if ($_POST['strandaction3'] === 'delete') {

            // Get the strand ID from the form
            $strandID3 = $_POST['strandID3'];

            // Check if the strand is referenced in other tables
            $isReferencedQuery = "SELECT 1 FROM tbl_book_strands WHERE strand_id = '$strandID3' LIMIT 1";
            $isReferencedResult = mysqli_query($db_connection, $isReferencedQuery);

            if (mysqli_num_rows($isReferencedResult) > 0) {
                // Strand is referenced, set a session variable to indicate the deletion failure
                session_start();
                $_SESSION['stranddeleteFailure'] = true;
            } else {
                // Strand is not referenced, proceed with deletion
                $sql = "DELETE FROM tbl_strands WHERE strand_id = '$strandID3'";
                $result = mysqli_query($db_connection, $sql);

                if ($result) {
                    // Successful deletion, set a session variable
                    session_start();
                    $_SESSION['stranddeleteSuccess'] = true;
                } else {
                    // Error deleting strand, handle it as needed
                    echo "Error deleting strand: " . mysqli_error($db_connection);
                }
            }
        } else {
            // Strand doesn't exist, show an error message or handle it as needed
            echo "Strand not found.";
        }

        header("Location: management.php");
        exit();
    } elseif (isset($_POST["teacherAction"]) && $_POST["teacherAction"] == "addTeacher") {
        // Code for adding a teacher
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];

        // Check if the teacher already exists in the database
        $existingTeacherQuery = "SELECT * FROM tbl_teachers WHERE first_name = '$firstName' AND last_name = '$lastName'";
        $existingTeacherResult = mysqli_query($db_connection, $existingTeacherQuery);

        if (mysqli_num_rows($existingTeacherResult) > 0) {
            $_SESSION["teacher_added"] = "exists";
            header("Location: management.php");
            exit();
        } else {
            // Teacher doesn't exist, proceed with insertion
            $sql = "INSERT INTO tbl_teachers (first_name, last_name) VALUES ('$firstName', '$lastName')";

            if (mysqli_query($db_connection, $sql)) {
                $_SESSION["teacher_added"] = "success";
                header("Location: management.php");
                exit();
            } else {
                $_SESSION["teacher_added"] = "error";
                header("Location: management.php");
                exit();
            }
        }

        mysqli_close($db_connection);
    } elseif (isset($_POST["teacherAction2"]) && $_POST["teacherAction2"] == "editTeacher") {
        // Code for editing a teacher
        $teacherID = $_POST["teacherID"];
        $teacherFirstName = $_POST["teacherFirstName"];
        $teacherLastName = $_POST["teacherLastName"];

        // Check if the teacher name has changed
        $sqlCheckUnchanged = "SELECT first_name, last_name FROM tbl_teachers WHERE teacher_id = '$teacherID'";
        $resultCheckUnchanged = mysqli_query($db_connection, $sqlCheckUnchanged);

        if (mysqli_num_rows($resultCheckUnchanged) == 1) {
            $row = mysqli_fetch_assoc($resultCheckUnchanged);
            $originalFirstName = $row["first_name"];
            $originalLastName = $row["last_name"];

            // Check if the name is unchanged
            if ($teacherFirstName === $originalFirstName && $teacherLastName === $originalLastName) {
                $_SESSION["teacher_updated"] = "unchanged";
                header("Location: management.php");
                exit();
            }
        }

        // Check if the teacher already exists
        $existingTeacherQuery = "SELECT * FROM tbl_teachers WHERE first_name = '$teacherFirstName' AND last_name = '$teacherLastName'";
        $existingTeacherResult = mysqli_query($db_connection, $existingTeacherQuery);

        if (mysqli_num_rows($existingTeacherResult) > 0) {
            $_SESSION["teacher_updated"] = "exists";
            header("Location: management.php");
            exit();
        }

        // Your existing code for updating the teacher
        $sql = "UPDATE tbl_teachers SET first_name = '$teacherFirstName', last_name = '$teacherLastName' WHERE teacher_id = '$teacherID'";

        if (mysqli_query($db_connection, $sql)) {
            $_SESSION["teacher_updated"] = "success";
            header("Location: management.php");
            exit();
        } else {
            $_SESSION["teacher_updated"] = "error";
            header("Location: management.php");
            exit();
        }

        mysqli_close($db_connection);
    } elseif (isset($_POST['teacherAction3'])) {
        // Code for deleting a teacher
        $teacherID3 = $_POST['teacherID3'];

        // Check if the teacher is referenced in other tables
        $isReferencedQuery = "SELECT 1 FROM tbl_transactions WHERE teacher_id = '$teacherID3' LIMIT 1";  // Replace 'other_table' with the actual table name
        $isReferencedResult = mysqli_query($db_connection, $isReferencedQuery);

        if (mysqli_num_rows($isReferencedResult) > 0) {
            // Teacher is referenced, set a session variable to indicate the deletion failure
            session_start();
            $_SESSION['teacherDeleteFailure'] = true;
        } else {
            // Teacher is not referenced, proceed with deletion
            $sql = "DELETE FROM tbl_teachers WHERE teacher_id = '$teacherID3'";
            $result = mysqli_query($db_connection, $sql);

            if ($result) {
                // Successful deletion, set a session variable
                $_SESSION['teacherDeleteSuccess'] = true;
            } else {
                // Error deleting teacher, handle it as needed
                echo "Error deleting teacher: " . mysqli_error($db_connection);
            }
        }

        header("Location: management.php");
        exit();
    }
    // ---------------------------------------------- Register Staff and admin Query!!!!! -->
    elseif (isset($_POST['register'])) {
        // Add a flag to ensure the code block is executed only once
        $registrationSuccessful = false;

        /// Retrieve user input
        $firstname = ucwords(strtolower($_POST['firstname'])); // Capitalize the first letter of each word and convert to lowercase
        $lastname = ucwords(strtolower($_POST['lastname'])); // Capitalize the first letter of each word and convert to lowercase
        $username = ucwords(strtolower($_POST['username'])); // Capitalize the first letter of each word and convert to lowercase



        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security
        $role = $_POST['role'];

        // Validate password
        if (strlen($_POST['password']) < 6 || strlen($_POST['password']) > 10) {
            $_SESSION["user_added"] = "error_invalid_password";
            header("Location: staffadmin.php");
            exit;
        }
        // Check if the combination of first name and last name already exists
        $check_query = "SELECT * FROM tbl_users WHERE first_name=? AND last_name=?";

        // Validate first name and last name
        if (!preg_match("/^[a-zA-Z]+$/", $firstname) || !preg_match("/^[a-zA-Z]+$/", $lastname)) {
            $_SESSION["user_added"] = "error_invalid_name";
            header("Location: staffadmin.php");
            exit;
        }
        // Check if the username contains only letters (both uppercase and lowercase)
        if (!preg_match("/^[a-zA-Z]+$/", $username)) {
            $_SESSION["user_added"] = "error_invalid_username";
            header("Location: staffadmin.php");
            exit;
        }
        // Use prepared statement for the check
        $check_statement = mysqli_prepare($db_connection, $check_query);
        mysqli_stmt_bind_param($check_statement, "ss", $firstname, $lastname);
        mysqli_stmt_execute($check_statement);
        $check_result = mysqli_stmt_get_result($check_statement);

        if (!$check_result) {
            echo "Error checking user details: " . mysqli_error($db_connection);
        } elseif (mysqli_num_rows($check_result) > 0) {
            $_SESSION["user_added"] = "error_user_exists";
            header("Location: staffadmin.php");
        } else {
            // Check if the username already exists
            $check_username_query = "SELECT * FROM tbl_users WHERE username=?";

            // Use prepared statement for the username check
            $check_username_statement = mysqli_prepare($db_connection, $check_username_query);
            mysqli_stmt_bind_param($check_username_statement, "s", $username);
            mysqli_stmt_execute($check_username_statement);
            $check_username_result = mysqli_stmt_get_result($check_username_statement);

            if (mysqli_num_rows($check_username_result) > 0) {
                $_SESSION["user_added"] = "error_username_exists";
                header("Location: staffadmin.php");
            } else {
                // Insert user data into the tbl_users table
                $insert_query = "INSERT INTO tbl_users (first_name, last_name, username, password, role) VALUES (?, ?, ?, ?, ?)";

                // Use prepared statement for the insert
                $insert_statement = mysqli_prepare($db_connection, $insert_query);
                mysqli_stmt_bind_param($insert_statement, "sssss", $firstname, $lastname, $username, $password, $role);

                if (mysqli_stmt_execute($insert_statement)) {
                    $registrationSuccessful = true; // Set the flag to true
                    $_SESSION["user_added"] = "success";
                } else {
                    $_SESSION["user_added"] = "error";
                }

                // Close the insert statement
                mysqli_stmt_close($insert_statement);

                // Close the username check statement
                mysqli_stmt_close($check_username_statement);

                // Close the database connection
                mysqli_close($db_connection);

                // Redirect to staffadmin.php
                header("Location: staffadmin.php");
                exit;
            }
        }

        // Close the check statement
        mysqli_stmt_close($check_statement);

        // Close the database connection if registration was not successful
        if (!$registrationSuccessful) {
            mysqli_close($db_connection);
        }
    } else {
        echo "Something's wrong... Hahahahaha";
    }
    // <!-- ----------------------------- -->
    // Deactivation and Reactivation for Admins
    if (isset($_POST['admin_toggle'])) {
        $user_id_to_toggle = $_POST['user_id_to_toggle'];

        // Fetch the current status of the admin
        $current_status_query = "SELECT status FROM tbl_users WHERE user_id = ?";
        $status_statement = mysqli_prepare($db_connection, $current_status_query);
        mysqli_stmt_bind_param($status_statement, "i", $user_id_to_toggle);

        if (mysqli_stmt_execute($status_statement)) {
            mysqli_stmt_bind_result($status_statement, $current_status);
            mysqli_stmt_fetch($status_statement);
            mysqli_stmt_close($status_statement);

            // Toggle the status based on the current status
            $new_status = ($current_status == 'active') ? 'inactive' : 'active';

            // Update the status in the database
            $update_status_query = "UPDATE tbl_users SET status = ? WHERE user_id = ?";
            $update_status_statement = mysqli_prepare($db_connection, $update_status_query);
            mysqli_stmt_bind_param($update_status_statement, "si", $new_status, $user_id_to_toggle);

            if (mysqli_stmt_execute($update_status_statement)) {
                // Status updated successfully
                $_SESSION["admin_status_update_success"] = true;
                $_SESSION["admin_status_update_message"] = "Admin account " . $user_id_to_toggle . " " . $new_status . "d successfully!";
            } else {
                // Status update failed
                $_SESSION["admin_status_update_success"] = false;
                $_SESSION["admin_status_update_message"] = "Error updating admin account status: " . mysqli_error($db_connection);
            }

            mysqli_stmt_close($update_status_statement);
        } else {
            // Query to fetch current status failed
            $_SESSION["admin_status_update_success"] = false;
            $_SESSION["admin_status_update_message"] = "Error fetching current admin account status: " . mysqli_error($db_connection);
        }

        // Redirect to staffadmin.php
        header("Location: staffadmin.php");
        exit; // Terminate the script after the redirect
    }
    // <!-- ----------------------------- -->
    // Deactivation and Reactivation for Admins
    if (isset($_POST['admin_toggle'])) {
        $user_id_to_toggle = $_POST['user_id_to_toggle'];

        // Fetch the current status of the admin
        $current_status_query = "SELECT status FROM tbl_users WHERE user_id = ?";
        $status_statement = mysqli_prepare($db_connection, $current_status_query);
        mysqli_stmt_bind_param($status_statement, "i", $user_id_to_toggle);

        if (mysqli_stmt_execute($status_statement)) {
            mysqli_stmt_bind_result($status_statement, $current_status);
            mysqli_stmt_fetch($status_statement);
            mysqli_stmt_close($status_statement);

            // Toggle the status based on the current status
            $new_status = ($current_status == 'active') ? 'inactive' : 'active';

            // Update the status in the database
            $update_status_query = "UPDATE tbl_users SET status = ? WHERE user_id = ?";
            $update_status_statement = mysqli_prepare($db_connection, $update_status_query);
            mysqli_stmt_bind_param($update_status_statement, "si", $new_status, $user_id_to_toggle);

            if (mysqli_stmt_execute($update_status_statement)) {
                // Status updated successfully
                $_SESSION["admin_status_update_success"] = true;
                $_SESSION["admin_status_update_message"] = "Admin account " . $user_id_to_toggle . " " . $new_status . "d successfully!";
            } else {
                // Status update failed
                $_SESSION["admin_status_update_success"] = false;
                $_SESSION["admin_status_update_message"] = "Error updating admin account status: " . mysqli_error($db_connection);
            }

            mysqli_stmt_close($update_status_statement);
        } else {
            // Query to fetch current status failed
            $_SESSION["admin_status_update_success"] = false;
            $_SESSION["admin_status_update_message"] = "Error fetching current admin account status: " . mysqli_error($db_connection);
        }

        // Include the staffadminalerts function
        include 'staffadminalerts.php';

        // Call the staffadminalerts function to display appropriate alerts
        staffadminalerts();

        // Redirect to staffadmin.php
        header("Location: staffadmin.php");
        exit; // Terminate the script after the redirect
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["adminaction2"]) && $_POST["adminaction2"] == "editAdmin") {
            $adminID2 = $_POST["adminID2"];
            $username2 = $_POST["userName2"];

            // Check if the "New Password" field is not empty
            if (!empty($_POST["newPassword"])) {
                // Hash the new password
                $newPassword = password_hash($_POST["newPassword"], PASSWORD_BCRYPT);

                // Update the username and hashed password in the database
                $sql = "UPDATE tbl_users SET username = '$username2', password = '$newPassword' WHERE user_id = '$adminID2'";

                if (mysqli_query($db_connection, $sql)) {
                    // Set success message in session
                    $_SESSION["update_password_success"] = "Password updated successfully!";
                    // Redirect to your admin list page after successful update
                    header("Location: staffadmin.php");
                    exit();
                } else {
                    // Set error message in session
                    $_SESSION["update_password_error"] = "Error updating user information: " . mysqli_error($db_connection);
                    // Redirect to your admin list page with an error
                    header("Location: staffadmin.php");
                    exit();
                }
            } else {
                // Set error message in session for empty password
                $_SESSION["update_password_error"] = "Password cannot be empty. Please provide a new password.";
                // Redirect to admin page with an error
                header("Location: staffadmin.php");
                exit();
            }
        } elseif (isset($_POST["staffaction2"]) && $_POST["staffaction2"] == "editStaff") {
            $staffID2 = $_POST["staffID2"];
            $username2 = $_POST["userName2"];

            // Check if the "New Password" field is not empty
            if (!empty($_POST["newPassword"])) {
                // Hash the new password
                $newPassword = password_hash($_POST["newPassword"], PASSWORD_BCRYPT);

                // Update the username and hashed password in the database
                $sql = "UPDATE tbl_users SET username = '$username2', password = '$newPassword' WHERE user_id = '$staffID2'";

                if (mysqli_query($db_connection, $sql)) {
                    // Set success message in session
                    $_SESSION["update_password_success"] = "Password updated successfully!";
                    // Redirect to your admin list page after successful update
                    header("Location: staffadmin.php");
                    exit();
                } else {
                    // Set error message in session
                    $_SESSION["update_password_error"] = "Error updating user information: " . mysqli_error($db_connection);
                    // Redirect to your admin list page with an error
                    header("Location: staffadmin.php");
                    exit();
                }
            } else {
                // Set error message in session for empty password
                $_SESSION["update_password_error"] = "Password cannot be empty. Please provide a new password.";
                // Redirect to admin page with an error
                header("Location: staffadmin.php");
                exit();
            }
        }

        mysqli_close($db_connection);
    }
}
