<?php

include '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $book_id = $_POST['id'];

        // Fetch book details from the database based on $bookId
        $query = "SELECT * FROM tbl_books WHERE book_id = $book_id AND status = 'active'";
        $result = mysqli_query($db_connection, $query);



        if ($result && mysqli_num_rows($result) > 0) {
            $book = mysqli_fetch_assoc($result);

            $imagePath = '/finalcapstone/images/' . $book['book_image'];
            $bookCover = $imagePath;
            $show_id = $book_id;
            // Check if the file exists before trying to display it
            if (is_file($_SERVER['DOCUMENT_ROOT'] . $imagePath)) {
                $bookCover = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . $imagePath));
            }


            echo '<tr id="cartItem_' . $book_id . '" style="margin-bottom: 5px;" data-id="' . $book_id . '">';
            echo '<td><img src="'. $bookCover . '" style="border-radius: 5px; border: 1px solid grey; max-height: 50px;"></td>';
            echo '<td>';
            echo '<strong>' . $book['title'] . '</strong><br>';
            echo '<span>₱' . $book['price'] . '</span>';
            echo '</td>';
            echo '<td>';
            echo '<div class="d-flex justify-content-between">';
            echo '<button class="btn btn-sm btn-danger removeOne">-</button>';
            echo '<input class="quantityInput" type="text" value="1" data-max="' . $book['quantity_available'] . '" style="width: 50px; border: 1px solid #ccc; padding: 5px; text-align: center;" oninput="updateQuantity(this, 0)">';
            echo '<button class="btn btn-sm btn-success addOne">+</button>';
            echo '</div>';
            echo '</td>';
            echo '<td class="subtotal" data-price="' . $book['price'] . '">₱' . number_format($book['price'] * 2) . '</td>';
            echo '<td><button class="btn btn-danger btn-sm removeFromCart" data-id="' . $book_id . '"><i class="bx bxs-trash"></i></button></td>';
            echo '</tr>';
        } else {
            echo 'Book not found or not active.';
        }
    } else {
        echo 'Invalid request';
    }
} else {
    echo 'Invalid request method';
}
?>

