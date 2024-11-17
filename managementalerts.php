<?php
function managementalerts()
{
  // I've put all the alerts here for management.php
  /////////////////////////////////////////////////////// SWAL FOR COLLEGE, ADD SWAL
  if (isset($_SESSION["college_added"])) {
    // Display SweetAlert based on the session variable
    if ($_SESSION["college_added"] == "success") {
      echo '<script>
                    Swal.fire({
                      icon: "success",
                      title: "Success",
                      text: "College added successfully.",
                    });
                  </script>';
    } elseif ($_SESSION["college_added"] == "error") {
      echo '<script>
                    Swal.fire({
                      icon: "error",
                      title: "Error",
                      text: "Error adding college: ' . mysqli_error($db_connection) . '",
                    });
                  </script>';
    } elseif ($_SESSION["college_added"] == "exists") {
      echo '<script>
                    Swal.fire({
                      icon: "error",
                      title: "Error",
                      text: "College already exists in the database.",
                    });
                  </script>';
    }

    // Unset the session variable to prevent showing the alert again on page refresh
    unset($_SESSION["college_added"]);
  }
  //////////////////////////////////////////////////////////////////////////////////////// UPDATE SWAL
  if (isset($_SESSION["college_updated"])) {
    // Display SweetAlert based on the session variable
    if ($_SESSION["college_updated"] == "success") {
      echo '<script>
                Swal.fire({
                  icon: "success",
                  title: "Success",
                  text: "College updated successfully.",
                });
              </script>';
    } elseif ($_SESSION["college_updated"] == "error") {
      echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "Error updating college: ' . mysqli_error($db_connection) . '",
                });
              </script>';
    } elseif ($_SESSION["college_updated"] == "unchanged") {
      echo '<script>
                Swal.fire({
                  icon: "info",
                  title: "No Changes",
                  text: "The college name remains unchanged.",
                });
              </script>';
    } elseif ($_SESSION["college_updated"] == "exists") {
      echo '<script>
                Swal.fire({
                  icon: "warning",
                  title: "Already Exists",
                  text: "The college name already exists in the table.",
                });
              </script>';
    }

    // Unset the session variable to prevent showing the alert again on page refresh
    unset($_SESSION["college_updated"]);
  }
  //////////////////////////////////////////////////////////////////////////////////////////////
  if (isset($_SESSION['collegedeleteSuccess'])) {
    // Display the success message
    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'The college has been successfully deleted.',
            icon: 'success',
        });
    </script>";
    unset($_SESSION['collegedeleteSuccess']); // Clear the session variable
  } elseif (isset($_SESSION['collegedeleteFailure'])) {
    // Display an error message
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Failed to delete the college. It may be referenced elsewhere.',
            icon: 'error',
        });
    </script>";
    unset($_SESSION['collegedeleteFailure']); // Clear the session variable
  }

?>
  <script>
    // Function to handle the SweetAlert2 confirmation dialog
    function collegeconfirmDelete(collegeformID) {
      Swal.fire({
        title: 'Confirm Deletion',
        text: 'Are you sure you want to delete this college?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel',
      }).then((result) => {
        if (result.value) {
          // User confirmed, submit the form with the specified form ID
          document.getElementById(collegeformID).submit();
        }
      });
    }

    function collegeconfirmUpdate(collegeId) {
      Swal.fire({
        title: 'Confirm Update',
        text: 'Are you sure you want to update this college?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, update it',
        cancelButtonText: 'No, cancel',
      }).then((result) => {
        if (result.isConfirmed) {
          // If the user confirms, submit the update form with the specific collegeId
          document.getElementById("updateCollegeForm" + collegeId).submit();
        }
      });
    }
  </script>

  <?php ////////////////////////////////////////////// YEAR LEVEL STUFF

  // ADD SWAL YEAR LEVEL

  if (isset($_SESSION["yearlevel_added"])) {
    // Display SweetAlert based on the session variable
    if ($_SESSION["yearlevel_added"] == "success") {
      echo '<script>
                Swal.fire({
                  icon: "success",
                  title: "Success",
                  text: "Year level added successfully.",
                });
              </script>';
    } elseif ($_SESSION["yearlevel_added"] == "error") {
      echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "Error adding year level: ' . mysqli_error($db_connection) . '",
                });
              </script>';
    } elseif ($_SESSION["yearlevel_added"] == "exists") {
      echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "The year level already exists in the database.",
                });
              </script>';
    }

    // Unset the session variable to prevent showing the alert again on page refresh
    unset($_SESSION["yearlevel_added"]);
  }
  // EDIT SWAL YEAR LEVEL
  if (isset($_SESSION["yearlevel_updated"])) {
    // Display SweetAlert based on the session variable
    if ($_SESSION["yearlevel_updated"] == "success") {
      echo '<script>
                Swal.fire({
                  icon: "success",
                  title: "Success",
                  text: "Year level updated successfully.",
                });
              </script>';
    } elseif ($_SESSION["yearlevel_updated"] == "error") {
      echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "Error updating year level: ' . mysqli_error($db_connection) . '",
                });
              </script>';
    } elseif ($_SESSION["yearlevel_updated"] == "unchanged") {
      echo '<script>
                Swal.fire({
                  icon: "info",
                  title: "No Changes",
                  text: "The year level remains unchanged.",
                });
              </script>';
    } elseif ($_SESSION["yearlevel_updated"] == "exists") {
      echo '<script>
                Swal.fire({
                  icon: "warning",
                  title: "Already Exists",
                  text: "The year Level already exists in the table.",
                });
              </script>';
    }

    // Unset the session variable to prevent showing the alert again on page refresh
    unset($_SESSION["yearlevel_updated"]);
  }

  // DELETE SWAL YEAR LEVEL

  if (isset($_SESSION['yearleveldeleteSuccess'])) {
    // Display the success message
    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Year level has been successfully deleted.',
            icon: 'success',
        });
    </script>";
    unset($_SESSION['yearleveldeleteSuccess']); // Clear the session variable
  } elseif (isset($_SESSION['yearleveldeletefailure'])) {
    // Display an error message
    echo "<script>
      Swal.fire({
          title: 'Error!',
          text: 'Failed to delete the year level. It may be referenced elsewhere.',
          icon: 'error',
      });
  </script>";
    unset($_SESSION['yearleveldeleteFailure']); // Clear the session variable
  }
  ?>
  <script>
    // Function to handle the SweetAlert2 confirmation dialog for year level deletion
    function yearlevelconfirmDelete(yearlevelformID) {
      Swal.fire({
        title: 'Confirm Deletion',
        text: 'Are you sure you want to delete this year level?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel',
      }).then((result) => {
        if (result.value) {
          // User confirmed, submit the form with the specified form ID
          document.getElementById(yearlevelformID).submit();
        }
      });
    }

    // Function to handle the SweetAlert2 confirmation dialog for year level update
    function yearlevelconfirmUpdate(yearlevelId) {
      Swal.fire({
        title: 'Confirm Update',
        text: 'Are you sure you want to update this year level?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, update it',
        cancelButtonText: 'No, cancel',
      }).then((result) => {
        if (result.isConfirmed) {
          // If the user confirms, submit the update form with the specific yearlevelId
          document.getElementById("updateYearLevelForm" + yearlevelId).submit();
        }
      });
    }
  </script>
  <?php ///////////////////////////////////////////////////////// PROGRAMS SWAL
  // Check if a Program has been added
  if (isset($_SESSION["program_added"])) {
    // Display SweetAlert based on the session variable
    if ($_SESSION["program_added"] == "success") {
      echo '<script>
                Swal.fire({
                  icon: "success",
                  title: "Success",
                  text: "Bachelor Program added successfully.",
                });
              </script>';
    } elseif ($_SESSION["program_added"] == "error") {
      echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "Error adding Bachelor Program: ' . mysqli_error($db_connection) . '",
                });
              </script>';
    } elseif ($_SESSION["program_added"] == "exists") {
      echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "The Bachelor Program already exists in the database.",
                });
              </script>';
    }

    // Unset the session variable to prevent showing the alert again on page refresh
    unset($_SESSION["program_added"]);
  }

  // Check if a Program has been updated
  if (isset($_SESSION["program_updated"])) {
    // Display SweetAlert based on the session variable
    if ($_SESSION["program_updated"] == "success") {
      echo '<script>
                Swal.fire({
                  icon: "success",
                  title: "Success",
                  text: "Bachelor Program updated successfully.",
                });
              </script>';
    } elseif ($_SESSION["program_updated"] == "error") {
      echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "Error updating Bachelor Program: ' . mysqli_error($db_connection) . '",
                });
              </script>';
    } elseif ($_SESSION["program_updated"] == "unchanged") {
      echo '<script>
                Swal.fire({
                  icon: "info",
                  title: "No Changes",
                  text: "The program name remains unchanged.",
                });
              </script>';
    } elseif ($_SESSION["program_updated"] == "exists") {
      echo '<script>
                Swal.fire({
                  icon: "warning",
                  title: "Already Exists",
                  text: "The Bachelor Program already exists in the table.",
                });
              </script>';
    }

    // Unset the session variable to prevent showing the alert again on page refresh
    unset($_SESSION["program_updated"]);
  }

  // Check if a Program has been deleted
  if (isset($_SESSION['programdeleteSuccess'])) {
    // Display the success message
    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Bachelor Program has been successfully deleted.',
            icon: 'success',
        });
    </script>";
    unset($_SESSION['programdeleteSuccess']); // Clear the session variable
  } elseif (isset($_SESSION['programdeleteFailure'])) {
    // Display an error message
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Failed to delete the bachelor program. It may be referenced elsewhere.',
            icon: 'error',
        });
    </script>";
    unset($_SESSION['programdeleteFailure']); // Clear the session variable
  }

  ?>

  <script>
    // Function to handle the SweetAlert2 confirmation dialog for program deletion
    function programconfirmDelete(programFormID) {
      Swal.fire({
        title: 'Confirm Deletion',
        text: 'Are you sure you want to delete this program?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel',
      }).then((result) => {
        if (result.value) {
          // User confirmed, submit the form with the specified form ID
          document.getElementById(programFormID).submit();
        }
      });
    }

    // Function to handle the SweetAlert2 confirmation dialog for program update
    function programconfirmUpdate(programId) {
      Swal.fire({
        title: 'Confirm Update',
        text: 'Are you sure you want to update this program?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, update it',
        cancelButtonText: 'No, cancel',
      }).then((result) => {
        if (result.isConfirmed) {
          // If the user confirms, submit the update form with the specific programId
          document.getElementById("updateProgramForm" + programId).submit();
        }
      });
    }
  </script>

  <?php
  ///////////////////////////////////////////////////// SEMESTER SWALS
  // Check if a Semester has been added
  if (isset($_SESSION["semester_added"])) {
    // Display SweetAlert based on the session variable
    if ($_SESSION["semester_added"] == "success") {
      echo '<script>
                Swal.fire({
                  icon: "success",
                  title: "Success",
                  text: "Semester added successfully.",
                });
              </script>';
    } elseif ($_SESSION["semester_added"] == "error") {
      echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "Error adding semester: ' . mysqli_error($db_connection) . '",
                });
              </script>';
    } elseif ($_SESSION["semester_added"] == "exists") {
      echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "The semester already exists in the database.",
                });
              </script>';
    }

    // Unset the session variable to prevent showing the alert again on page refresh
    unset($_SESSION["semester_added"]);
  }

  // Check if a Semester has been updated
  if (isset($_SESSION["semester_updated"])) {
    // Display SweetAlert based on the session variable
    if ($_SESSION["semester_updated"] == "success") {
      echo '<script>
                Swal.fire({
                  icon: "success",
                  title: "Success",
                  text: "Semester updated successfully.",
                });
              </script>';
    } elseif ($_SESSION["semester_updated"] == "error") {
      echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "Error updating semester: ' . mysqli_error($db_connection) . '",
                });
              </script>';
    } elseif ($_SESSION["semester_updated"] == "unchanged") {
      echo '<script>
                Swal.fire({
                  icon: "info",
                  title: "No Changes",
                  text: "The semester name remains unchanged.",
                });
              </script>';
    } elseif ($_SESSION["semester_updated"] == "exists") {
      echo '<script>
                Swal.fire({
                  icon: "warning",
                  title: "Already Exists",
                  text: "The semester already exists in the table.",
                });
              </script>';
    }

    // Unset the session variable to prevent showing the alert again on page refresh
    unset($_SESSION["semester_updated"]);
  }

  // Check if a Semester has been deleted
  if (isset($_SESSION['semesterdeleteSuccess'])) {
    // Display the success message
    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Semester has been successfully deleted.',
            icon: 'success',
        });
    </script>";
    unset($_SESSION['semesterdeleteSuccess']); // Clear the session variable
  } elseif (isset($_SESSION['semesterdeleteFailure'])) {
    // Display an error message
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Failed to delete the semester. It may be referenced elsewhere.',
            icon: 'error',
        });
    </script>";
    unset($_SESSION['semesterdeleteFailure']); // Clear the session variable
  }
  ?>

  <script>
    // Function to handle the SweetAlert2 confirmation dialog for semester deletion
    function semesterconfirmDelete(semesterformID) {
      Swal.fire({
        title: 'Confirm Deletion',
        text: 'Are you sure you want to delete this semester?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel',
      }).then((result) => {
        if (result.value) {
          // User confirmed, submit the form with the specified form ID
          document.getElementById(semesterformID).submit();
        }
      });
    }

    // Function to handle the SweetAlert2 confirmation dialog for semester update
    function semesterconfirmUpdate(semesterId) {
      Swal.fire({
        title: 'Confirm Update',
        text: 'Are you sure you want to update this semester?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, update it',
        cancelButtonText: 'No, cancel',
      }).then((result) => {
        if (result.isConfirmed) {
          // If the user confirms, submit the update form with the specific semesterId
          document.getElementById("updateSemesterForm" + semesterId).submit();
        }
      });
    }
  </script>

  <?php
  ///////////////////////////////////////////////////// TEACHER SWALS
  // Check if a Teacher has been added
  if (isset($_SESSION["teacher_added"])) {
    // Display SweetAlert based on the session variable
    if ($_SESSION["teacher_added"] == "success") {
      echo '<script>
                Swal.fire({
                  icon: "success",
                  title: "Success",
                  text: "Teacher added successfully.",
                });
              </script>';
    } elseif ($_SESSION["teacher_added"] == "error") {
      echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "Error adding teacher: ' . mysqli_error($db_connection) . '",
                });
              </script>';
    } elseif ($_SESSION["teacher_added"] == "exists") {
      echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "The teacher already exists in the database.",
                });
              </script>';
    }

    // Unset the session variable to prevent showing the alert again on page refresh
    unset($_SESSION["teacher_added"]);
  }

  // Check if a Teacher has been updated
  if (isset($_SESSION["teacher_updated"])) {
    // Display SweetAlert based on the session variable
    if ($_SESSION["teacher_updated"] == "success") {
      echo '<script>
                Swal.fire({
                  icon: "success",
                  title: "Success",
                  text: "Teacher updated successfully.",
                });
              </script>';
    } elseif ($_SESSION["teacher_updated"] == "error") {
      echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "Error updating teacher: ' . mysqli_error($db_connection) . '",
                });
              </script>';
    } elseif ($_SESSION["teacher_updated"] == "unchanged") {
      echo '<script>
                Swal.fire({
                  icon: "info",
                  title: "No Changes",
                  text: "The teacher details remain unchanged.",
                });
              </script>';
    } elseif ($_SESSION["teacher_updated"] == "exists") {
      echo '<script>
                Swal.fire({
                  icon: "warning",
                  title: "Already Exists",
                  text: "The teacher already exists in the table.",
                });
              </script>';
    }

    // Unset the session variable to prevent showing the alert again on page refresh
    unset($_SESSION["teacher_updated"]);
  }

  // Check if a Teacher has been deleted
  if (isset($_SESSION['teacherDeleteSuccess'])) {
    // Display the success message
    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Teacher has been successfully deleted.',
            icon: 'success',
        });
    </script>";
    unset($_SESSION['teacherDeleteSuccess']); // Clear the session variable
  } elseif (isset($_SESSION['teacherDeleteFailure'])) {
    // Display an error message
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Failed to delete the teacher. It may be referenced elsewhere.',
            icon: 'error',
        });
    </script>";
    unset($_SESSION['teacherDeleteFailure']); // Clear the session variable
  }
  ?>

  <script>
    // Function to handle the SweetAlert2 confirmation dialog for teacher deletion
    function teacherConfirmDelete(teacherFormID) {
      Swal.fire({
        title: 'Confirm Deletion',
        text: 'Are you sure you want to delete this teacher?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel',
      }).then((result) => {
        if (result.value) {
          // User confirmed, submit the form with the specified form ID
          document.getElementById(teacherFormID).submit();
        }
      });
    }

    // Function to handle the SweetAlert2 confirmation dialog for teacher update
    function teacherConfirmUpdate(teacherId) {
      Swal.fire({
        title: 'Confirm Update',
        text: 'Are you sure you want to update this teacher?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, update it',
        cancelButtonText: 'No, cancel',
      }).then((result) => {
        if (result.isConfirmed) {
          // If the user confirms, submit the update form with the specific teacherId
          document.getElementById("updateTeacherForm" + teacherId).submit();
        }
      });
    }
  </script>


  <?php
  ///////////////////////////////////////////////////// SUBJECT SWALS
  // Check if a Subject has been added
  if (isset($_SESSION["subject_added"])) {
    // Display SweetAlert based on the session variable
    if ($_SESSION["subject_added"] == "success") {
      echo '<script>
                Swal.fire({
                  icon: "success",
                  title: "Success",
                  text: "Subject added successfully.",
                });
              </script>';
    } elseif ($_SESSION["subject_added"] == "error") {
      echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "Error adding subject: ' . mysqli_error($db_connection) . '",
                });
              </script>';
    } elseif ($_SESSION["subject_added"] == "exists") {
      echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "The Subject Name and/or Code already exists in the database.",
                });
              </script>';
    }

    // Unset the session variable to prevent showing the alert again on page refresh
    unset($_SESSION["subject_added"]);
  }

  // Check if a Subject has been updated
  if (isset($_SESSION["subject_updated"])) {
    // Display SweetAlert based on the session variable
    if ($_SESSION["subject_updated"] == "success") {
      echo '<script>
                Swal.fire({
                  icon: "success",
                  title: "Success",
                  text: "Subject updated successfully.",
                });
              </script>';
    } elseif ($_SESSION["subject_updated"] == "error") {
      echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "Error updating subject: ' . mysqli_error($db_connection) . '",
                });
              </script>';
    } elseif ($_SESSION["subject_updated"] == "unchanged") {
      echo '<script>
                Swal.fire({
                  icon: "info",
                  title: "No Changes",
                  text: "The subject remains unchanged.",
                });
              </script>';
    } elseif ($_SESSION["subject_updated"] == "code_exists") {
      echo '<script>
                Swal.fire({
                  icon: "warning",
                  title: "Subject Name and/or Code Exists",
                  text: "The Subject Name and/or Code already exists in the table.",
                });
              </script>';
    } elseif ($_SESSION["subject_updated"] == "name_exists") {
      echo '<script>
                Swal.fire({
                  icon: "warning",
                  title: "Subject Name and/or Code Exists",
                  text: "The Subject Name and/or Code already exists in the table.",
                });
              </script>';
    }

    // Unset the session variable to prevent showing the alert again on page refresh
    unset($_SESSION["subject_updated"]);
  }

  // Check if a Subject has been deleted successfully
  if (isset($_SESSION['subjectdeleteSuccess'])) {
    // Display the success message
    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Subject has been successfully deleted.',
            icon: 'success',
        });
    </script>";
    unset($_SESSION['subjectdeleteSuccess']); // Clear the session variable
  } elseif (isset($_SESSION['subjectdeleteFailure'])) {
    // Display an error message
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Failed to delete the subject. It may be referenced elsewhere.',
            icon: 'error',
        });
    </script>";
    unset($_SESSION['subjectdeleteFailure']); // Clear the session variable
  }
  ?>

  <script>
    // Function to handle the SweetAlert2 confirmation dialog for subject deletion
    function subjectconfirmDelete(subjectformID) {
      Swal.fire({
        title: 'Confirm Deletion',
        text: 'Are you sure you want to delete this subject?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel',
      }).then((result) => {
        if (result.value) {
          // User confirmed, submit the form with the specified form ID
          document.getElementById(subjectformID).submit();
        }
      });
    }

    // Function to handle the SweetAlert2 confirmation dialog for subject update
    function subjectconfirmUpdate(subjectId) {
      Swal.fire({
        title: 'Confirm Update',
        text: 'Are you sure you want to update this subject?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, update it',
        cancelButtonText: 'No, cancel',
      }).then((result) => {
        if (result.isConfirmed) {
          // If the user confirms, submit the update form with the specific subjectId
          document.getElementById("updateSubjectForm" + subjectId).submit();
        }
      });
    }
  </script>
  <?php
  if (isset($_SESSION["strand_added"])) {
    // Display SweetAlert based on the session variable
    if ($_SESSION["strand_added"] == "success") {
      echo '<script>
                    Swal.fire({
                      icon: "success",
                      title: "Success",
                      text: "Strand added successfully.",
                    });
                  </script>';
    } elseif ($_SESSION["strand_added"] == "error") {
      echo '<script>
                    Swal.fire({
                      icon: "error",
                      title: "Error",
                      text: "Error adding strand: ' . mysqli_error($db_connection) . '",
                    });
                  </script>';
    } elseif ($_SESSION["strand_added"] == "exists") {
      echo '<script>
                    Swal.fire({
                      icon: "error",
                      title: "Error",
                      text: "Strand already exists in the database.",
                    });
                  </script>';
    }

    // Unset the session variable to prevent showing the alert again on page refresh
    unset($_SESSION["strand_added"]);
  }
  //////////////////////////////////////////////////////////////////////////////////////// UPDATE SWAL
  if (isset($_SESSION["strand_updated"])) {
    // Display SweetAlert based on the session variable
    if ($_SESSION["strand_updated"] == "success") {
      echo '<script>
                Swal.fire({
                  icon: "success",
                  title: "Success",
                  text: "Strand updated successfully.",
                });
              </script>';
    } elseif ($_SESSION["strand_updated"] == "error") {
      echo '<script>
                Swal.fire({
                  icon: "error",
                  title: "Error",
                  text: "Error updating strand: ' . mysqli_error($db_connection) . '",
                });
              </script>';
    } elseif ($_SESSION["strand_updated"] == "unchanged") {
      echo '<script>
                Swal.fire({
                  icon: "info",
                  title: "No Changes",
                  text: "The strand name remains unchanged.",
                });
              </script>';
    } elseif ($_SESSION["strand_updated"] == "exists") {
      echo '<script>
                Swal.fire({
                  icon: "warning",
                  title: "Already Exists",
                  text: "The strand name already exists in the table.",
                });
              </script>';
    }

    // Unset the session variable to prevent showing the alert again on page refresh
    unset($_SESSION["strand_updated"]);
  }
  //////////////////////////////////////////////////////////////////////////////////////////////
  if (isset($_SESSION['stranddeleteSuccess'])) {
    // Display the success message
    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'The strand has been successfully deleted.',
            icon: 'success',
        });
    </script>";
    unset($_SESSION['stranddeleteSuccess']); // Clear the session variable
  } elseif (isset($_SESSION['stranddeleteFailure'])) {
    // Display an error message
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Failed to delete the strand. It may be referenced elsewhere.',
            icon: 'error',
        });
    </script>";
    unset($_SESSION['stranddeleteFailure']); // Clear the session variable
  }

  ?>
  <script>
    // Function to handle the SweetAlert2 confirmation dialog
    function strandconfirmDelete(strandformID) {
      Swal.fire({
        title: 'Confirm Deletion',
        text: 'Are you sure you want to delete this strand?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel',
      }).then((result) => {
        if (result.value) {
          // User confirmed, submit the form with the specified form ID
          document.getElementById(strandformID).submit();
        }
      });
    }

    function strandconfirmUpdate(strandId) {
      Swal.fire({
        title: 'Confirm Update',
        text: 'Are you sure you want to update this strand?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, update it',
        cancelButtonText: 'No, cancel',
      }).then((result) => {
        if (result.isConfirmed) {
          // If the user confirms, submit the update form with the specific strandId
          document.getElementById("updateStrandForm" + strandId).submit();
        }
      });
    }
  </script>
<?php
}
?>