<?php
include_once '../authentication.php';
include '../database.php';
include_once '../includes.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" type="text/css" href="bookstyle.css">
    <title>Administrator Hub</title>
    <style>
        .selected-subject {
            margin-left: 7px;
            /* Adjust the margin as needed */
            font-weight: 500;
            color: #fff;
            /* Change the color as needed */
        }

        .selected-publication {
            margin-left: 7px;
            /* Adjust the margin as needed */
            font-weight: 500;
            color: #fff;
            /* Change the color as needed */
        }
    </style>
</head>

<body>
    <!-- LEFTSIDE NAVBAR FOR ADMIN -->
    <section id="sidebar">
        <a href="" class="brand">
            <img src="../images/neustlogo.png" style="border: 1px solid white; border-radius: 50%;" alt="NEUST Logo" width="60" height="60">
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
            <!-- Your menu items here -->
            <li>
                <a href="../admin_dashboard/admin_dashboard.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="active">
                <a href="inventory.php">
                    <i class='bx bxs-book-alt'></i>
                    <span class="text">Book Inventory</span>
                </a>
            </li>
            <li>
                <a href="../point_of_sale/pos.php">
                    <i class='bx bxs-message-dots'></i>
                    <span class="text">Point of Sale</span>
                </a>
            </li>
            <li>
                <a href="../management.php">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="../staffadmin.php">
                    <i class='bx bxs-user-check'></i>
                    <span class="text">Staff and Admins</span>
                </a>
            </li>
            <li>
                <a href="../reports/sales_report.php">
                    <i class='bx bxs-chart'></i>
                    <span class="text">Sales Report</span>
                </a>
            </li>
            <li>
                <a href="../history.php">
                    <i class='bx bxs-receipt'></i>
                    <span class="text">History</span>
                </a>
            </li>
            <li>
                <a href="../inventory/archive.php">
                    <i class='bx bxs-archive'></i>
                    <span class="text">Archive</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <!-- Your menu items here -->
            <li>
                <a href="../settings.php" class="logout">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <!-- <li>
                <a href="../help.php" class="logout">
                    <i class='bx bxs-help-circle'></i>
                    <span class="text">Help & Support</span>
                </a>
            </li> -->
            <li>
                <a href="../logout.php" class="logout" onclick="return confirmLogout();">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>

    <section id="content">

        <?php

        include '../nav.php';

        ?>
        <!-- Your main content here -->
        <main>
            <div class="form-body">
                <div class="row">
                    <div class="form-holder">
                        <div class="form-content">
                            <div class="form-items">
                                <h4>Book Inventory</h4>
                                <p><strong>Important:</strong> To maintain accurate records, please complete the fields related to the books you wish to add.<br>
                                    Provide the necessary details corresponding to the books in your inventory. Your cooperation is appreciated.</p>
                                <form action="inventorycommands.php" method="POST" id="addbookForm" enctype="multipart/form-data">

                                    <div class="col-md-12  mb-2">
                                        <label for="isbn">International Standard Book Number</label>
                                        <input class="form-control" type="text" id="isbn" name="Isbn" placeholder="Enter ISBN" required autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                        <div class="valid-feedback">ISBN field is valid!</div>
                                        <div class="invalid-feedback">ISBN field cannot be blank!</div>
                                    </div>
                                    <div class="col-md-12  mb-2">
                                        <label for="title">Title</label>
                                        <input class="form-control no-margin" type="text" id="title" name="Title" placeholder="Enter Title" required autocomplete="off">
                                        <div class="valid-feedback">Title field is valid!</div>
                                        <div class="invalid-feedback">Title field cannot be blank!</div>
                                    </div>
                                    <div class="col-md-12  mb-2">
                                        <label for="author">Author</label>
                                        <input class="form-control" type="text" id="author" name="Author" placeholder="Enter Author" required autocomplete="off">
                                        <div class="valid-feedback">Author field is valid!</div>
                                        <div class="invalid-feedback">Author field cannot be blank!</div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="price">Price(₱)</label>
                                        <input class="form-control" type="text" id="price" name="Price" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Enter Price" required autocomplete="off">
                                        <div class="valid-feedback">Price field is valid!</div>
                                        <div class="invalid-feedback">Price field cannot be blank!</div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="quantityAvailable">Total Quantity</label>
                                        <input class="form-control" type="text" id="quantityAvailable" name="QuantityAvailable" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Enter Quantity" required autocomplete="off">
                                        <div class="valid-feedback">Quantity field is valid!</div>
                                        <div class="invalid-feedback">Quantity field cannot be blank!</div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="yearLevelType">Year Levels</label>
                                        <select class="form-select" id="yearLevelType" name="yearLevelType" required>
                                            <option value="" disabled selected>Select Year Type</option>
                                            <option value="High School">High School</option>
                                            <option value="Senior High">Senior High</option>
                                            <option value="College">College</option>
                                        </select>
                                        <div class="valid-feedback">You selected a year type!</div>
                                        <div class="invalid-feedback">Please select a year type!</div>
                                    </div>
                                    <div class="col-md-12  mb-2">
                                        <select class="form-select mt-3" id="yearLevelSelect" name="YearLeveLID" required>
                                            <option value="" disabled selected>Select Year Level</option>
                                        </select>
                                        <div class="valid-feedback">You selected a year level!</div>
                                        <div class="invalid-feedback">Please select a year level!</div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="customFile">Book Image</label>
                                        <input type="file" class="form-control" id="customFile" name="BookImage" />
                                        <div class="valid-feedback">Image field is valid!</div>
                                        <div class="invalid-feedback">Image field cannot be blank!</div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="subjectSelect">Select Subject</label><span class="selected-subject"></span>
                                        <select class="form-select" size="3" id="subjectSelect" name="SubjectID" required>
                                            <?php
                                            $sql = "SELECT subject_id, subject_code FROM tbl_subjects ORDER BY subject_code";
                                            $result = mysqli_query($db_connection, $sql);

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo '<option value="' . $row["subject_id"] . '">' . $row["subject_code"] . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <div class="valid-feedback">You selected a subject!</div>
                                        <div class="invalid-feedback">Please select a subject!</div>
                                    </div>
                                    <div class="col-md-12  mb-2">
                                        <label for="publicationYear">Select Publication year</label><span class="selected-publication"></span>
                                        <select class="form-control" size="3" id="publicationYear" name="PublicationYear" required>
                                            <?php
                                            $currentYear = date("Y");
                                            for ($year = 1880; $year <= $currentYear; $year++) {
                                                echo "<option value=\"$year\">$year</option>";
                                            }
                                            ?>
                                        </select>
                                        <div class="valid-feedback">Year field is valid!</div>
                                        <div class="invalid-feedback">Year field cannot be blank!</div>
                                    </div>
                                    <!-- Confirmation Checkbox -->
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                            <label class="form-check-label" for="invalidCheck">I confirm that all data are correct</label>
                                            <div class="invalid-feedback">Please confirm that the entered data are all correct!</div>
                                        </div>
                                    </div>
                                    <div class="form-button mt-3">
                                        <button type="submit" class="btn btn-primary" onclick="goToInventory()">Back to Inventory</button>
                                        <!-- <button type="submit" class="btn btn-primary" name="addBook" id="addsubmitBook">&plus; Add Books</button> -->
                                        <button type="submit" class="btn btn-primary" name="addBook" id="addsubmitBook" onclick="validateForm()">Add Books</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </section>
    <script>
        // Displays the day, date and time for Philippines        
        function updateTime() {
            const pstElement = document.getElementById('pst');
            const now = new Date();
            const options = {
                timeZone: 'Asia/Manila',
                weekday: 'short',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };
            const pstTime = now.toLocaleString('en-US', options);
            pstElement.textContent = "PST (Philippine Standard Time): " + pstTime;
        }

        updateTime();
        setInterval(updateTime, 1000);
        // BUTTON TO BACK INVENTORY IN ADD BOOK
        function goToInventory() {
            window.location.href = 'inventory.php';
        }
        // Use jQuery to handle the change event of the Year Level Type dropdown
        $(document).ready(function() {
            $('#yearLevelType').change(function() {
                var selectedYearLevelType = $(this).val();

                // Make an AJAX request to fetch Year Levels based on the selected Year Level Type
                $.ajax({
                    url: 'add_getyearlevels.php', // Replace with the actual URL for fetching Year Levels
                    type: 'POST',
                    data: {
                        yearLevelType: selectedYearLevelType
                    },
                    dataType: 'html',
                    success: function(response) {
                        // Update the Year Levels dropdown with the fetched data
                        $('#yearLevelSelect').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            });
        });
        // Function to validate the form before submission
        function validateForm() {
            // Check if the Select Subject and Select Year fields have values
            var subjectSelected = $('#subjectSelect').val();
            var yearSelected = $('#publicationYear').val();
            var yearlevelSelected = $('#yearLevelType').val();
            var yearLevelValue = $('#yearLevelSelect').val();

            // Check if any of the required fields is not selected
            if (!subjectSelected || !yearSelected || !yearlevelSelected || !yearLevelValue || yearSelected === '0') {
                // Display SweetAlert error message for missing fields
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please complete all required fields before submitting the form.',
                });

                // Prevent the form from being submitted
                return false;
            }

            // Example: Ensure ISBN is 10 or 13 digits
            var isbnValue = document.getElementById("isbn").value;
            var cleanIsbn = isbnValue.replace(/\D/g, '');

            if (cleanIsbn.length !== 10 && cleanIsbn.length !== 13) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid ISBN Length',
                    text: 'Please enter a valid ISBN with 10 or 13 digits.',
                });
                return false; // Prevent form submission
            }

            // If validation passed, continue with form submission
            return true;
        }

        // Use jQuery to handle the form submission
        $(document).ready(function() {
            $('#addbookForm').submit(function(event) {
                // Validate the form before submission
                if (!validateForm()) {
                    // Prevent the default form submission behavior
                    event.preventDefault();
                }
            });
        });

        //SPAN IN SUBJECT AND PUBLICATION 
        $(document).ready(function() {
            $('#subjectSelect').on('change', function() {
                const selectedSubject = $(this).find('option:selected').text();
                $('.selected-subject').text(selectedSubject);
            });
        });
        $(document).ready(function() {
            $('#publicationYear').on('change', function() {
                const selectedYear = $(this).find('option:selected').text();
                $('.selected-publication').text(selectedYear);
            });
        });
    </script>
</body>

</html>