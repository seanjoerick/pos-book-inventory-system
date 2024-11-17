
<?php

include_once('../database.php');

// Show total books
function getTotalBooks() {
    global $db_connection;

    $totalBooks = 0;

    $tables = array(
        'tbl_books'

    );

    foreach ($tables as $table) {
        $query = "SELECT SUM(quantity_available) AS total FROM $table";
        $result = mysqli_query($db_connection, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $totalBooks += (int)$row['total'];
        }
    }

    return $totalBooks;
}

$totalBooks = getTotalBooks();

// Format the total number with commas
$formattedTotalBooks = number_format($totalBooks);

// Total transactions for today
function getTotalTransactions() {
    global $db_connection;

    $totalTransactions = 0;

    $query = "SELECT COUNT(*) AS total FROM tbl_transactions WHERE is_void = 0 AND DATE(transaction_date) = CURDATE()";
    $result = mysqli_query($db_connection, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $totalTransactions = (int) $row['total'];
    }

    return $totalTransactions;
}

// Total amount of sales for today
function getTotalTransactionAmount() {
    global $db_connection;

    $totalAmount = 0;

    $query = "SELECT SUM(total_amount) AS total FROM tbl_transactions WHERE is_void = 0 AND DATE(transaction_date) = CURDATE()";
    $result = mysqli_query($db_connection, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $totalAmount = (float) $row['total'];
    }

    $formattedTotal = number_format($totalAmount, 2);

    return $formattedTotal;
}

// Function to get the most recent transaction
function getRecentTransaction() {
    global $db_connection;

    $query = "SELECT * FROM tbl_transactions WHERE is_void = 0 ORDER BY transaction_date DESC LIMIT 1";
    $result = mysqli_query($db_connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Fetch customer name using the getCustomerName function
        $row['customer_name'] = getCustomerName($row['customer_id']);

        return $row;
    }

    return null;
}
// Function to get book details for a given transaction_id
function getBookDetails($transactionId) {
    global $db_connection;

    $query = "SELECT td.quantity, b.title
              FROM tbl_transactiondetails td
              JOIN tbl_books b ON td.book_id = b.book_id
              WHERE td.transaction_id = $transactionId";

    $result = mysqli_query($db_connection, $query);

    $bookDetails = array();

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $bookDetails[] = $row;
        }
    }

    return $bookDetails;
}


// Function to get customer name based on customer_id
function getCustomerName($customer_id) {
    global $db_connection;

    $query = "SELECT CONCAT(first_name, ' ', last_name) AS customer_name FROM tbl_customers WHERE customer_id = $customer_id";
    $result = mysqli_query($db_connection, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['customer_name'];
    }

    return 'Unknown Customer';
}

function getLowStockItems() {
    global $db_connection;

    $lowStockItemsCount = 0;
    $lowStockThreshold = 10;

    $query = "SELECT COUNT(*) AS low_stock_count FROM tbl_books WHERE quantity_available <= $lowStockThreshold";
    $result = mysqli_query($db_connection, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $lowStockItemsCount = (int)$row['low_stock_count'];
    }

    return $lowStockItemsCount;
}

function getLowStockBooks() {
    global $db_connection;

    $lowStockBooks = array();
    $lowStockThreshold = 10;

    $query = "SELECT book_id, title, quantity_available FROM tbl_books WHERE quantity_available <= $lowStockThreshold";
    $result = mysqli_query($db_connection, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $lowStockBooks[] = array(
                'book_id' => (int)$row['book_id'],
                'title' => $row['title'],
                'quantity_available' => (int)$row['quantity_available'],
            );
        }
    }

    return $lowStockBooks;
}

function getMostSoldBooksData($limit = 5) {
    global $db_connection;

    $query = "SELECT b.title, SUM(td.quantity) as total_sold
              FROM tbl_transactiondetails td
              JOIN tbl_books b ON td.book_id = b.book_id
              GROUP BY td.book_id
              ORDER BY total_sold DESC
              LIMIT $limit";

    $result = mysqli_query($db_connection, $query);

    $data = array(
        'labels' => array(),
        'data' => array(),
    );

    while ($row = mysqli_fetch_assoc($result)) {
        $data['labels'][] = $row['title'];
        $data['data'][] = (int)$row['total_sold'];
    }

    return $data;
}


?>

