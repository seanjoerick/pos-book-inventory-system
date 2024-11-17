<?php

include 'database.php';
include 'authentication.php';
include 'modals.php';
include 'staffadminalerts.php';

staffadminalerts();
?>
<a href="logout.php" class="logout">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include 'includes.php'; ?>
        <link rel="stylesheet" type="text/css" href="staff_admin.css">
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
                <li>
                    <a href="point_of_sale/pos.php">
                        <i class='bx bxs-message-dots'></i>
                        <span class="text">Point of Sale</span>
                    </a>
                </li>
                <li>
                    <a href="management.php">
                        <i class='bx bxs-cog'></i>
                        <span class="text">Settings</span>
                    </a>
                </li>
                <li class="active">
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
                        <h1>Management Accounts</h1>
                        <div id="pst" class="pst" style="font-size: 18px; color: #333;"></div>
                        <ul class="breadcrumb">
                            <li>
                                <a href="">
                                    <p><strong>Note:</strong> Admins have limited permissions. They can create new staff accounts, change passwords, and update account details.</p>
                                    <p><strong>Super Admin Privilege:</strong> Only the Super Admin has the authority to add, edit, and disable other Admin accounts. This ensures that the highest level of security and control is maintained over the system.</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php
                // Check if the currently logged in user (with a session "user_id") is an admin
                if ($_SESSION['role'] === 'admin') {
                    // Check if the logged in admin has the lowest ID
                    $loggedInAdminID = $_SESSION['user_id'];

                    // Check if the logged in admin has the lowest ID
                    $lowestAdminQuery = "SELECT MIN(user_id) AS lowest_id FROM tbl_users";
                    $lowestAdminResult = mysqli_query($db_connection, $lowestAdminQuery);

                    if ($lowestAdminResult && $row = mysqli_fetch_assoc($lowestAdminResult)) {
                        $lowestAdminID = $row['lowest_id'];
                ?>
                        <div class="form-body">
                            <div class="row">
                                <div class="form-holder">
                                    <div class="form-content">
                                        <div class="form-items">
                                            <p><strong>Important:</strong> To successfully complete your registration and maintain accurate records, please fill in the required fields below.<br> Provide the necessary details for your account creation. Your cooperation is highly appreciated.</p>
                                            <h4>Registration</h4>
                                            <form action="commands.php" method="POST" id="registrationForm" novalidate onsubmit="validatePasswordMatch(event)">
                                                <div class="col-md-12">
                                                    <label for="firstname">Firstname</label>
                                                    <input class="form-control" type="text" id="firstname" name="firstname" placeholder="First Name" required autocomplete="off">
                                                    <div class="valid-feedback">Firstname field is valid!</div>
                                                    <div class="invalid-feedback">Firstname field cannot be blank!</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="lastname">Lastname</label>
                                                    <input class="form-control" type="text" id="lastname" name="lastname" placeholder="Last Name" required autocomplete="off">
                                                    <div class="valid-feedback">Lastname field is valid!</div>
                                                    <div class="invalid-feedback">Lastname field cannot be blank!</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="username">Username</label>
                                                    <input class="form-control" type="text" id="username" name="username" placeholder="Username" required autocomplete="off">
                                                    <div class="valid-feedback">Username field is valid!</div>
                                                    <div class="invalid-feedback">Username field cannot be blank!</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="password">Password</label>
                                                    <div class="input-group">
                                                        <input class="form-control" type="password" id="password" name="password" placeholder="Password" required autocomplete="off">
                                                        <div class="input-group-append">
                                                            <!-- <span class="input-group-text" id="togglePassword" style="cursor: pointer; position: relative;">
                                                                <i class='bx bxs-show' id="eye-icon"></i>
                                                            </span> -->
                                                        </div>
                                                        <div class="valid-feedback">Password field is valid!</div>
                                                        <div class="invalid-feedback">Password field cannot be blank!</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="conpassword">Confirm Password</label>
                                                    <div class="input-group">
                                                        <input class="form-control" type="password" id="conpassword" name="conpassword" placeholder="Confirm password" required autocomplete="off">
                                                        <div class="input-group-append">
                                                            <!-- <span class="input-group-text" id="togglePasswordConfirm" style="cursor: pointer; position: relative;">
                                                                <i class='bx bxs-show' id="eye-icon-confirm"></i>
                                                            </span> -->
                                                        </div>
                                                        <div class="valid-feedback">Confirm Password field is valid!</div>
                                                        <div class="invalid-feedback">Confirm Password field cannot be blank!</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="role">Roles</label>
                                                    <select class="form-select" id="role" name="role" required>
                                                        <?php if ($loggedInAdminID == $lowestAdminID) : ?>
                                                            <option value="staff">Staff</option>
                                                            <option value="admin">Admin</option>
                                                        <?php else : ?>
                                                            <option value="staff">Staff</option>
                                                        <?php endif; ?>
                                                    </select>
                                                    <div class="valid-feedback">You selected a position!</div>
                                                    <div class="invalid-feedback">Please select a position!</div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                                        <label class="form-check-label" for="invalidCheck">I confirm that all data are correct</label>
                                                        <div class="invalid-feedback">Please confirm that the entered data are all correct!</div>
                                                    </div>
                                                </div>
                                                <div class="form-button mt-3">
                                                    <button type="submit" class="btn btn-primary" name="register" id="submitButton">Register</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                <?php
                    }
                } else {
                    // The logged in user is not an admin, display a message or redirect as needed
                    echo "<p>You are not authorized to create admin accounts.</p>";
                }
                ?>
                <!-- BOTTOM OF FORM REGISTRATION -->
                <?php
                $itemsPerPage = 5;

                // Get the current page number from the URL, default to 1 if not set
                $page = isset($_GET['page']) ? $_GET['page'] : 1;

                // Calculate the offset for the SQL query
                $offset = ($page - 1) * $itemsPerPage;

                // Query to fetch admin data with pagination
                $admin_query = "SELECT user_id id, first_name, last_name, username, password, role, status FROM tbl_users ORDER BY CAST(id AS UNSIGNED) ASC LIMIT $itemsPerPage OFFSET $offset";
                $admin_result = mysqli_query($db_connection, $admin_query);

                if (!$admin_result) {
                    die("Query failed: " . mysqli_error($db_connection));
                }

                // Calculate the total number of pages
                $totalPagesQuery = "SELECT CEIL(COUNT(*) / $itemsPerPage) AS totalPages FROM tbl_users";
                $totalPagesResult = mysqli_query($db_connection, $totalPagesQuery);
                $totalPages = mysqli_fetch_assoc($totalPagesResult)['totalPages'];
                // Check if the currently logged in user (with a session "user_id") is an admin
                if ($_SESSION['role'] === 'admin') {
                    // Check if the logged in admin has the lowest ID
                    $loggedInAdminID = $_SESSION['user_id'];

                    // Check if the logged in admin has the lowest ID
                    $lowestAdminQuery = "SELECT MIN(user_id) AS lowest_id FROM tbl_users";
                    $lowestAdminResult = mysqli_query($db_connection, $lowestAdminQuery);

                    if ($lowestAdminResult && $row = mysqli_fetch_assoc($lowestAdminResult)) {
                        $lowestAdminID = $row['lowest_id'];

                        // Display the table of accounts
                ?>
                        <div class="col-md-12 mt-4">
                            <h2>Accounts</h2>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Roles</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = mysqli_fetch_assoc($admin_result)) {
                                            $editAdminModal = 'editAdminModal' . $row["id"]; // Define the edit modal ID for each admin

                                            echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                                            echo "<td>" . $row['username'] . "</td>";
                                            // echo "<td>" . $row['role'] . "</td>";
                                            echo "<td>" . ucfirst($row['role']) . "</td>";
                                            echo "<td class='status-buttons'>";

                                            if ($loggedInAdminID == $lowestAdminID && $row['id'] == $lowestAdminID) {
                                                // Display "Active" as a non-clickable button for the lowest admin
                                                echo "<button type='button' class='btn btn-success' disabled>Active</button>";
                                            } elseif ($loggedInAdminID != $row['id'] && $row['role'] === 'staff') {
                                                // Toggle Activation/Deactivation button
                                                echo "<form method='POST' action='commands.php'>";
                                                echo "<input type='hidden' name='user_id_to_toggle' value='" . $row['id'] . "'>";
                                                $button_text = ($row['status'] === 'active') ? 'Active' : 'Inactive';
                                                $button_class = ($row['status'] === 'active') ? 'btn btn-success' : 'btn btn-danger';
                                                echo "<button type='submit' class='" . $button_class . "' data-toggle='tooltip' data-placement='top' title='" . $button_text . "' name='admin_toggle'><i class='bx bxs-check'></i>" . $button_text . "</button>";
                                                echo "</form>";
                                            } elseif ($loggedInAdminID == $lowestAdminID) {
                                                // If the logged-in admin has the lowest ID, show the "Disable" button
                                                echo "<form method='POST' action='commands.php'>";
                                                echo "<input type='hidden' name='user_id_to_toggle' value='" . $row['id'] . "'>";
                                                $button_text = ($row['status'] === 'active') ? 'Active' : 'Inactive';
                                                $button_class = ($row['status'] === 'active') ? 'btn btn-success' : 'btn btn-danger';
                                                echo "<button type='submit' class='" . $button_class . "' data-toggle='tooltip' data-placement='top' title='" . $button_text . "' name='admin_toggle'><i class='bx bxs-check'></i>" . $button_text . "</button>";
                                                echo "</form>";
                                            }

                                            echo "</td>";
                                            echo "<td class='action-buttons'>";

                                            if ($loggedInAdminID != $row['id'] && $row['role'] === 'staff') {
                                                echo "<button class='btn btn-primary' data-toggle='modal' data-target='#" . $editAdminModal . "'><i class='bx bxs-pencil'></i> Update</button>";
                                            } elseif ($loggedInAdminID == $lowestAdminID) {
                                                echo "<button class='btn btn-primary' data-toggle='modal' data-target='#" . $editAdminModal . "'><i class='bx bxs-pencil'></i> Update</button>";
                                            }
                                            echo "</td>";
                                            echo "</tr>";

                                            // Edit Admin Modal
                                            echo "<div class='modal fade' id='" . $editAdminModal . "' tabindex='-1' aria-labelledby='" . $editAdminModal . "Label' aria-hidden='true'>";
                                            echo "<div class='modal-dialog'>";
                                            echo "<div class='modal-content'>";
                                            echo "<div class='modal-header'>";
                                            echo "<h5 class='modal-title' id='" . $editAdminModal . "Label'>Edit Admin</h5>";
                                            echo "</div>";
                                            echo "<div class='modal-body'>";
                                            echo "<form action='commands.php' method='POST'>";
                                            echo "<div class='form-group'>";
                                            echo "<label for='adminID2'>ID:</label>";
                                            echo "<input type='text' class='form-control' id='adminID2' name='adminID2' value='" . $row["id"] . "' readonly>";
                                            echo "</div>";
                                            echo "<div class='form-group'>";
                                            echo "<label for='userName2'>Username:</label>";
                                            echo "<input type='text' class='form-control' id='userName2' name='userName2' value='" . $row["username"] . "' required>";
                                            echo "</div>";
                                            echo "<div class='form-group'>";
                                            echo "<label for='newPassword'>New Password:</label>";
                                            echo "<input type='password' class='form-control' id='newPassword' name='newPassword'>";
                                            echo "</div>";
                                            echo "<input type='hidden' name='adminID2' value='" . $row["id"] . "'>";
                                            echo "<input type='hidden' name='adminaction2' value='editAdmin'>";
                                            echo "</div>";
                                            echo "<div class='modal-footer'>";
                                            echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
                                            // Check if the logged-in admin has the lowest ID to show the update button
                                            if ($loggedInAdminID == $lowestAdminID) {
                                                echo "<button type='submit' class='btn btn-primary'>Update</button>";
                                            }
                                            echo "</form>";
                                            echo "</div>";
                                            echo "</div>";
                                            echo "</div>";
                                            echo "</div>";
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <?php
                        // Display pagination links

                        echo "<div class='col-md-12 mt-4'>";
                        echo '<ul class="pagination justify-content-center">';
                        for ($i = 1; $i <= $totalPages; $i++) {
                            $activeClass = ($i == $page) ? 'active' : '';
                            echo "<li class='page-item $activeClass'><a class='page-link' href='?page=$i'>$i</a></li>";
                        }
                        echo "</ul>";
                        echo "</div>";
                        ?>
                <?php
                    }
                } else {
                    // The logged-in user is not an admin, display a message or redirect as needed
                    echo "<p>You are not authorized to view admin accounts.</p>";
                }

                ?>
            </main>
        </section>
        <style>
        </style>
        <script>
            (function() {
                'use strict'
                const forms = document.querySelectorAll('.requires-validation')
                Array.from(forms)
                    .forEach(function(form) {
                        form.addEventListener('submit', function(event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }

                            form.classList.add('was-validated')
                        }, false)
                    })
            })()
            document.addEventListener('DOMContentLoaded', function() {
                const passwordInput = document.getElementById('password');
                const confirmPasswordInput = document.getElementById('conpassword');
                const eyeIcon = document.getElementById('eye-icon');
                const eyeIconConfirm = document.getElementById('eye-icon-confirm');

                eyeIcon.addEventListener('click', function() {
                    togglePasswordVisibility(passwordInput, eyeIcon);
                });

                eyeIconConfirm.addEventListener('click', function() {
                    togglePasswordVisibility(confirmPasswordInput, eyeIconConfirm);
                });

                function togglePasswordVisibility(inputField, icon) {
                    const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
                    inputField.setAttribute('type', type);
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                }
            });

            function validatePasswordMatch(event) {
                var password = document.getElementById("password").value;
                var confirmPassword = document.getElementById("conpassword").value;

                if (password !== confirmPassword) {
                    // Use SweetAlert to display an alert
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Password and Confirm Password do not match!',
                    });

                    event.preventDefault(); // Prevent the form from being submitted
                }
            }
        </script>

    </body>

    </html>