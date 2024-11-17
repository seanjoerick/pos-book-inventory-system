<?php

// Include necessary files and configurations
include '../database.php';
include 'admin_dashboard_f.php'; // Include the file with your functions

// Fetch the most sold books data
$mostSoldBooksData = getMostSoldBooksData(5);

// Output JSON
header('Content-Type: application/json');
echo json_encode($mostSoldBooksData);

?>
