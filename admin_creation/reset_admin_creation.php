<?php
// USE THIS CODE TO RESET ADMIN CREATION
session_start();
$_SESSION['admin_account_created'] = false;
header("Location: ../index.php");
exit();
?>