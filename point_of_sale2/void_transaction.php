<?php
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transactionId = isset($_POST['transaction_id']) ? $_POST['transaction_id'] : null;
    $adminUsername = isset($_POST['adminUsername']) ? $_POST['adminUsername'] : null;
    $adminPassword = isset($_POST['adminPassword']) ? $_POST['adminPassword'] : null;

    if ($transactionId !== null && $adminUsername !== null && $adminPassword !== null) {
        // Authenticate admin
        $sqlAuthenticateAdmin = "SELECT * FROM tbl_users WHERE username = ?";
        $stmtAuthenticateAdmin = mysqli_prepare($db_connection, $sqlAuthenticateAdmin);
        mysqli_stmt_bind_param($stmtAuthenticateAdmin, "s", $adminUsername);
        mysqli_stmt_execute($stmtAuthenticateAdmin);
        $resultAuthenticateAdmin = mysqli_stmt_get_result($stmtAuthenticateAdmin);

        if ($row = mysqli_fetch_assoc($resultAuthenticateAdmin)) {
            // Verify the hashed password
            if (password_verify($adminPassword, $row['password']) && $row['role'] === 'admin') {
                // Admin authenticated, proceed with voiding the transaction

                // Assuming $transaction_id is available
                $sqlTransactionDetails = "SELECT * FROM tbl_transactiondetails WHERE transaction_id = ?";
                $stmtTransactionDetails = mysqli_prepare($db_connection, $sqlTransactionDetails);
                mysqli_stmt_bind_param($stmtTransactionDetails, "i", $transactionId);
                mysqli_stmt_execute($stmtTransactionDetails);
                $resultTransactionDetails = mysqli_stmt_get_result($stmtTransactionDetails);

                // Revert changes in tbl_books
                while ($row = mysqli_fetch_assoc($resultTransactionDetails)) {
                    $bookId = $row['book_id'];
                    $quantity = $row['quantity'];

                    // Revert the changes in tbl_books
                    $sqlRevertBooks = "UPDATE tbl_books SET quantity_available = quantity_available + ? WHERE book_id = ?";
                    $stmtRevertBooks = mysqli_prepare($db_connection, $sqlRevertBooks);
                    mysqli_stmt_bind_param($stmtRevertBooks, "is", $quantity, $bookId);
                    mysqli_stmt_execute($stmtRevertBooks);
                    mysqli_stmt_close($stmtRevertBooks);
                }

                mysqli_stmt_close($stmtTransactionDetails);

                // Update the is_void column in tbl_transactions
                $sqlUpdateVoid = "UPDATE tbl_transactions SET is_void = 1 WHERE transaction_id = ?";
                $stmtUpdateVoid = mysqli_prepare($db_connection, $sqlUpdateVoid);
                mysqli_stmt_bind_param($stmtUpdateVoid, "i", $transactionId);
                $success = mysqli_stmt_execute($stmtUpdateVoid);
                mysqli_stmt_close($stmtUpdateVoid);

                // Send a JSON response indicating success or failure
                echo json_encode(['success' => $success]);
            } else {
                // Admin authentication failed
                echo json_encode(['success' => false, 'error' => 'Authentication failed']);
            }
        } else {
            // Admin not found
            echo json_encode(['success' => false, 'error' => 'Admin not found']);
        }

        mysqli_stmt_close($stmtAuthenticateAdmin);
    } else {
        // Send a JSON response indicating failure if transaction_id, adminUsername, or adminPassword is not provided
        echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    }
} else {
    // Send a JSON response indicating failure for non-POST requests
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>