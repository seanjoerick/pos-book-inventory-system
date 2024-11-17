<!DOCTYPE html>
<html>
<head>
    <title>Admin Created</title>
    <link rel="stylesheet" type="text/css" href="admin_creation.css"> <!-- You can create a CSS file for styling if needed -->
</head>
<body>
    <div class="admin-created-container">
        <?php
        // Retrieve the admin credentials from query parameters
        $adminUsername = $_GET['username'];
        $adminPassword = $_GET['password'];
        // $hashedPassword = $_GET['hashed_password'];

        echo "Admin account created successfully.<br>";
        echo "Username: $adminUsername<br>";
        echo "Password: $adminPassword<br>";
        // echo "Hashed Password: $hashedPassword";
        ?>
        <p>You can now log in using the admin credentials.</p>

        <form action="../index.php">
            <button type="submit">Back to Login</button>
        </form>
    </div>
</body>
</html>