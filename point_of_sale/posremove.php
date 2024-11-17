<?php
// posremove.php

// Start the session
session_start();

// Check if the book ID is provided
if (isset($_POST['id'])) {
    $bookIdToRemove = $_POST['id'];

    // Check if the cart session variable exists
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        // Find the index of the book in the cart array
        $bookIndex = array_search($bookIdToRemove, array_column($_SESSION['cart'], 'id'));

        // If the book is found in the cart, remove it
        if ($bookIndex !== false) {
            unset($_SESSION['cart'][$bookIndex]);
            echo 'success'; // Send a success response
            exit();
        }
    }
}

// If something goes wrong, send an error response
echo 'error';
?>