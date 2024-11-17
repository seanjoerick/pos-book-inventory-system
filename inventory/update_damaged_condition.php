<?php
 

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
// // Retrieve book ID and condition from the AJAX request
// $bookId = $_POST["book_id"];
// $bookCondition = $_POST["book_condition"];

// // Validate and sanitize the input if necessary

// // Update the database record with the new book condition
// $query = "UPDATE tbl_books SET book_condition = ? WHERE book_id = ?";

// // Use prepared statements to prevent SQL injection
// $stmt = $db_connection->prepare($query);

// if ($stmt) {
// $stmt->bind_param("si", $bookCondition, $bookId);

// // Execute the prepared statement
// if ($stmt->execute()) {
// // Query executed successfully, you can provide a success response if needed
// echo json_encode(["status" => "success", "message" => "Book condition updated successfully"]);
// } else {
// // Handle errors if the query fails
// echo json_encode(["status" => "error", "message" => "Error executing query: " . $stmt->error]);
// }

// // Close the statement
// $stmt->close();
// } else {
// // Handle errors if prepare fails
// echo json_encode(["status" => "error", "message" => "Error preparing statement: " . $db_connection->error]);
// }
// } else {
// // Handle cases where the request method is not POST
// echo json_encode(["status" => "error", "message" => "Invalid request method"]);
// }

// // Close the database connection
// $db_connection->close();




// if (isset($_POST['book_id'])) {
// $bookId = $_POST['book_id'];
// $quantityDamaged = $_POST['quantity_damaged'];

// // Start a transaction
// mysqli_begin_transaction($db_connection);

// try {
// // Retrieve the current quantity_available, quantity_damaged, and book_condition for the book
// $sqlSelect = "SELECT quantity_available, quantity_damaged, book_condition FROM tbl_books WHERE book_id = ?";
// $stmtSelect = mysqli_prepare($db_connection, $sqlSelect);
// mysqli_stmt_bind_param($stmtSelect, 'i', $bookId);
// mysqli_stmt_execute($stmtSelect);
// mysqli_stmt_bind_result($stmtSelect, $totalQuantity, $quantityDamagedCurrent, $bookCondition);
// mysqli_stmt_fetch($stmtSelect);
// mysqli_stmt_close($stmtSelect);

// if ($totalQuantity !== null) {
// // Calculate the new total quantity and quantity_damaged
// $newTotalQuantity = max(0, $totalQuantity - $quantityDamaged);
// $newQuantityDamaged = $quantityDamagedCurrent + $quantityDamaged;

// // Update the database with the new total quantity and quantity_damaged
// $sqlUpdate = "UPDATE tbl_books
// SET quantity_available = ?,
// quantity_damaged = ?
// WHERE book_id = ?";

// $stmtUpdate = mysqli_prepare($db_connection, $sqlUpdate);

// // Check if the prepare statement failed
// if (!$stmtUpdate) {
// throw new Exception("Error in prepare statement");
// }

// // Bind parameters using bind_param
// mysqli_stmt_bind_param($stmtUpdate, 'iii', $newTotalQuantity, $newQuantityDamaged, $bookId);

// // Execute the statement
// $updateResult = mysqli_stmt_execute($stmtUpdate);

// // Check if the update was successful
// if (!$updateResult) {
// throw new Exception("Error in update statement");
// }

// // Commit the transaction if everything is successful
// mysqli_commit($db_connection);

// // Respond with the updated data
// $responseData = array(
// 'new_quantity_available' => $newTotalQuantity,
// 'new_quantity_damaged' => $newQuantityDamaged,
// 'book_condition' => $bookCondition
// );

// echo json_encode($responseData);
// } else {
// // Book not found or an error occurred
// throw new Exception("Error retrieving data");
// }
// } catch (Exception $e) {
// // An error occurred, rollback the transaction
// mysqli_rollback($db_connection);
// echo json_encode("Error: " . $e->getMessage());
// }
// }