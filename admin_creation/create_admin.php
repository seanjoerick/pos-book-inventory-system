<?php
// Include the database connection code from your existing database.php file
include("../database.php");

if (isset($_POST['register'])) { // Check if the form was submitted
    // Retrieve user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert admin into the database
    $sql = "INSERT INTO tbl_users (username, first_name, last_name, reset_token, password, role) VALUES ('$username', 'Super', 'Admin', 'forgetfornow', '$hashedPassword', 'admin')";

    if (mysqli_query($db_connection, $sql)) {
        // Registration successful
        session_start();
        $_SESSION['admin_account_created'] = true;

        // Redirect to admin_created.php
        header("Location: admin_created.php?username=$username&password=$password&hashed_password=$hashedPassword");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($db_connection);
    }
}

mysqli_close($db_connection);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Registration</title>
    <link rel="stylesheet" type="text/css" href="../login.css">
</head>
<body>
    <div class="container">
        <div class="login-container">
        <img src="../images/neustlogo.png" alt="Logo" width="100" height="100">
            <h2>Super Admin Registration</h2>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <label for="username">Username:</label>
                <input type="text" name="username" required><br><br>

                <label for="password">Password:</label>
                <input type="password" name="password" required><br><br>

                <input type="submit" class="signin-button" name="register" value="Register">
            </form>
        </div>
    </div>
</body>
</html>