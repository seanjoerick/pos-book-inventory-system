<?php

// process_checkout.php

session_start();

// Retrieve cart data from the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cart'])) {
        $cartData = json_decode($_POST['cart'], true);

        // Store cart data in session
        $_SESSION['cartData'] = json_encode($cartData);

        // Redirect to checkout.php
        header('Location: checkout.php');
        exit();
    }
}

// Handle the case where POST data is not received or cart data is not set
echo 'Invalid request';
exit();