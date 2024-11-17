<?php
// THIS LINE OF CODE AUTHENTICATES AND STOPS THE USER FROM ENTERING THE OTHER PAGE. IF YOU SIGN IN AS STAFF, DUN KA LANG SA STAFF
// IF YOU SIGN IN AS ADMIN, DUN KA LANG SA ADMIN. SIMPLE.

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: /finalcapstone/login.php");
    exit();
}

$role = $_SESSION["role"];
$disallowed_pages = [];
$disallowed_folders = [];

if ($role == "admin") {
    // Define disallowed pages and folders for admin
    $disallowed_pages = [
        "/finalcapstone/staff_dashboard/staff_dashboard.php",
    ];
    $disallowed_folders = [
        "/finalcapstone/staff_dashboard/",
        "/finalcapstone/point_of_sale2/",
    ];
    $redirect = "/finalcapstone/admin_dashboard/admin_dashboard.php";
} elseif ($role == "staff") {
    // Define disallowed pages and folders for staff
    $disallowed_pages = [
        "/finalcapstone/point_of_sale/pos.php",
        "/finalcapstone/inventory/inventory.php",
        "/finalcapstone/admin_dashboard/admin_dashboard.php",
        "/finalcapstone/management.php",
        "/finalcapstone/staffadmin.php",
    ];
    $disallowed_folders = [
        "/finalcapstone/point_of_sale/",
        "/finalcapstone/inventory/",
        "/finalcapstone/admin_dashboard/",
    ];
    $redirect = "/finalcapstone/staff_dashboard.php";
}

$requested_page = $_SERVER['REQUEST_URI'];

// Check if the requested page is in disallowed pages
if (in_array($requested_page, $disallowed_pages)) {
    header("Location: $redirect");
    exit;
}

// Check if the requested page is within any disallowed folder
foreach ($disallowed_folders as $folder) {
    if (strpos($requested_page, $folder) === 0) {
        // The requested page is in a disallowed folder
        $redirect = str_replace($folder, "/finalcapstone/{$role}_dashboard/", $requested_page);
        header("Location: $redirect");
        exit;
    }
}
