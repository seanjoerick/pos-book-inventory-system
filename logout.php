<?php
// WHEN YOU CLICK LOGOUT, YOU'LL BE REDIRECTED TO THIS, TERMINATING YOUR SESSION THEN IBABALIK KA SA LOGIN PAGE.
// AFTER MATERMINATE, DI KA MAKAKABALIK AGAIN UNLESS YOU LOGIN AGAIN.
session_start();

// Unset the session variable that controls the "Create Admin" button display
unset($_SESSION['create_admin_button_displayed']);

session_unset();
session_destroy();
header("Location: login.php");
