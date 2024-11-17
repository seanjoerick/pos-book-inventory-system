<?php

include '../database.php';

$query = "SELECT * FROM tbl_books WHERE status = 'active' AND quantity_available > 0";
$result = mysqli_query($db_connection, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $counter = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $imagePath = '/finalcapstone/images/' . $row['book_image'];
        $bookCover = $imagePath;

        if (is_file($_SERVER['DOCUMENT_ROOT'] . $imagePath)) {
            $bookCover = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . $imagePath));
        }

        $quantityAvailable = $row['quantity_available'];
        $buttonClass = ($quantityAvailable <= 10) ? 'btn-warning' : 'btn-primary';
        $buttonText = ($quantityAvailable <= 10) ? 'Few quantity available!' : 'Add to Cart';

        if ($counter % 4 == 0) {
            echo '<div class="row">';
        }
        ?>

        <div class="col-md-3 mb-4 mt-3" >
                <div class="card d-flex flex-column" style="background-color: #00008B; border-radius: 10px; border: 1px solid #00008B; height: 245px; overflow: hidden;">
                    <?php
                    echo '<img src="' . $bookCover . '" style="border-radius: 10px; background-color: white; border: 1px solid #00008B;  max-height: 150px; object-fit: cover;" class="card-img-top" alt="No Image">';
                    echo '<div class="card-body flex-fill" style="overflow: hidden; color: white;">';
                    echo '<h5 class="card-title" style="font-size: 0.9rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: white;">₱' . $row['price'] . ' - ' . $row['title'] . '</h5>';
                    echo '<span class="d-flex justify-content-between align-items-center">';
                    echo '<span style="color: white;">' . $row['quantity_available'] . 'x left</span>';
                    echo '<span>';
                    echo '<button class="btn btn-sm addToCart ' . $buttonClass . '" data-id="' . $row['book_id'] . '" data-quantity="' . $row['quantity_available'] . '" title="' . $buttonText . '">';
                    echo '<i class="bx bxs-cart-add"></i>';
                    echo '</button>';
                    echo '<button class="btn btn-secondary btn-sm infoIcon" data-toggle="modal" data-target="#viewInfo" data-id="' . $row['book_id'] . '" title="Book Information">';
                    echo '<i class="bx bxs-info-circle"></i>';
                    echo '</button>';

                    echo '</span>';
                    echo '</span>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';

                    $counter++;

                if ($counter % 4 == 0) {
                    echo '</div>';
                }
            }

            if ($counter % 4 != 0) {
                echo '</div>';
            }
        } else {
            echo '<div class="text-center" style="margin-top: 10px;">';
            echo '<div class="alert alert-info" role="alert">';
            echo 'No books found.';
            echo '</div>';
            echo '</div>';
        }
        ?>

<script>

$(document).ready(function () {

// not yet working
$("#toggleViewButton").on("click", function() {
    console.log("Button clicked!");
    $("#booklist").toggleClass("table-view");
});

// Handle "Add to Cart" button click event
$('.addToCart').on('click', function () {
    console.log("Add to Cart clicked!");
    var id = $(this).data('id');
    var quantity = $(this).data('quantity'); // Get the quantity from data-quantity

    // Check if the book is already in the cart
    var existingCartItem = $('#cartItem_' + id);

    if (existingCartItem.length) {
        // Book is already in the cart, update the quantity
        var input = existingCartItem.find('.quantityInput');
        updateQuantity(input, 1);
    } else {
        // Book is not in the cart, check if quantity is less than 10
        if (quantity <= 10) {
            // Show SweetAlert confirmation
            Swal.fire({
                title: 'Few quantities available',
                text: 'Proceed to add to cart?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then(function (isConfirmed) {
                if (isConfirmed.value) {
                    addToCart(id);
                }
            });
        } else {
            // Quantity is not less than 10, add to cart directly
            addToCart(id);
        }
    }
});
// Function to handle adding to cart via AJAX
function addToCart(id) {
    $.ajax({
        url: 'posadd.php',
        method: 'POST',
        data: { id: id },
        success: function (response) {
            // Check again if the book is already in the cart
            existingCartItem = $('#cartItem_' + id);

            if (existingCartItem.length) {
                // Book is already in the cart, update the quantity
                var input = existingCartItem.find('.quantityInput');
                updateQuantity(input, 1);
            } else {
                // Book is not in the cart, append a new row
                $('.cartItems').append(response);

                // Recalculate and update the total amount
                updateTotalAmount();

                updateSubtotal($('#cartItem_' + id + ' .quantityInput'));

            // Display a non-intrusive notification
            Swal.fire({
                icon: 'success',
                title: 'Added to Cart!',
                showConfirmButton: false,
                timer: 1500, // Close after 1.5 seconds
                toast: true,
                position: 'top-end',
            });
        }
    },
    error: function (error) {
        console.log('Error:', error);
    }
});
}
});