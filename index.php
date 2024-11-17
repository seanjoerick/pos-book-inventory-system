<!DOCTYPE html>
<?php
include("includes.php");
include_once 'database.php';
session_start(); // Start the session
$adminCreatedFlagFilePath = __DIR__ . '/admin_creation/admin_created.txt';

// Check if the admin account creation process has been completed
$adminAccountCreated = isset($_SESSION['admin_account_created']) && $_SESSION['admin_account_created'] === true;

// Define a flag to control the display of the "Create Admin" button
$displayCreateAdminButton = (!$adminAccountCreated && !file_exists($adminCreatedFlagFilePath)) || (isset($_SESSION['show_create_admin_button']) && $_SESSION['show_create_admin_button']);

if (file_exists($adminCreatedFlagFilePath) && !isset($_SESSION['credentials_displayed'])) {
    // Admin account has already been created, and credentials have not been displayed
    // Redirect to admin_created.php in the admin_creation folder
    header("Location: admin_creation/admin_created.php");
    exit();
}

// Check if there are any records in the database table
if (!$adminAccountCreated && !file_exists($adminCreatedFlagFilePath)) {
    $query = "SELECT COUNT(*) FROM tbl_users";
    $rowCount = GetValue($query);

    if ($rowCount > 0) {
        // Records exist in the database, do not display the "Create Admin" button
        $displayCreateAdminButton = false;
    }
}
if (isset($_SESSION["user_id"])) {
    $role = $_SESSION["role"];
    header("Location: " . ($role === "admin" ? "admin_dashboard/admin_dashboard.php" : "staff_dashboard/staff_dashboard.php"));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare a common statement for both admin and staff
    $stmt = $db_connection->prepare("SELECT user_id, username, password, role, status FROM tbl_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $errorMessage = "Invalid Username or Password.";
    }

    if (isset($row)) {
        if ($row["status"] === "active" && password_verify($password, $row["password"])) {
            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["role"] = $row["role"];
            $role = $_SESSION["role"];
            header("Location: " . ($role === "admin" ? "admin_dashboard/admin_dashboard.php" : "staff_dashboard/staff_dashboard.php"));
            exit();
        } elseif ($row["status"] === "inactive") {
            $errorMessage = "Your account is inactive. Please contact an administrator for assistance.";
        } else {
            $errorMessage = "Invalid username or password.";
        }
    }

    $stmt->close();
    $db_connection->close();
}
?>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>

<body>
    <div class="login-container">
        <?php
        if ($displayCreateAdminButton) {
        ?>
            <form action="admin_creation/create_admin.php" method="POST">
                <button type="submit" name="createAdmin">Create Admin</button>
            </form>
        <?php
        } else {
        ?>
            <img src="images/neustlogo.png" alt="Logo" width="100" height="100">
            <h1 style="margin-top: 5px;"><b>Book Inventory and POS System</b></h1>
            <form action="login.php" method="POST">
                <div class="input-container">
                    <input type="text" id="username" name="username" required>
                    <label for="username">Username</label>
                </div>
                <div class="input-container">
                    <input type="password" id="password" name="password" required>
                    <label for="password">Password</label>
                </div>
                <button type="submit" class="signin-button">Login</button>
            </form>
            <div>
                <div class="text-center" style="margin-top:16px;">
                    <!-- <a>Forgot Password? </a><a href="#" id="forgotPasswordLink" data-toggle="modal" data-target="#forgotPasswordModal">Click Here</a> -->
                </div>
            </div>
            <?php if (isset($errorMessage)) {
                echo "<p class='error-message'>$errorMessage</p>";
            } ?>
    </div>

    <!-- "Forgot Password" Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Forgot Password</h5>
                </div>
                <div class="modal-body">
                    <form id="forgotPasswordForm">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="forgotPasswordForm">Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- "Reset Token" Modal -->
    <div class="modal fade" id="resetTokenModal" tabindex="-1" role="dialog" aria-labelledby="resetTokenModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetTokenModalLabel">Reset Token</h5>
                </div>
                <div class="modal-body">
                    <!-- Add your reset token form here -->
                    <form id="resetTokenForm">
                        <div class="form-group">
                            <label for="resetToken">Reset Token:</label>
                            <input type="text" class="form-control" id="resetToken" name="resetToken" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="resetTokenForm">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#forgotPasswordForm').on('submit', function(e) {
                e.preventDefault();
                const name = $('#name').val();
                // Check if the provided name exists (You can use AJAX or server-side logic here)
                const nameExists = true; // Replace with your logic
                if (nameExists) {
                    // Close the "Forgot Password" modal
                    $('#forgotPasswordModal').modal('hide');
                    // Open the "Reset Token" modal
                    $('#resetTokenModal').modal('show');
                } else {
                    alert('Name does not exist. Please try again.');
                }
            });
        });
    </script>
<?php
        }
?>
</div>
</body>

</html>