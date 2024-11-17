function updateQuantity(input, change) {
    var currentValue = parseInt($(input).val()) || 0;
    var maxQuantity = parseInt($(input).data('max'));

    // Ensure the new value is within the allowed range (0 to maxQuantity)
    var newValue = Math.min(maxQuantity, Math.max(1, currentValue + change));

    $(input).val(newValue);

    // Trigger recalculation of the subtotal
    updateSubtotal(input);
}

function updateSubtotal(input) {
    var row = $(input).closest('tr');
    var price = parseFloat(row.find('.subtotal').data('price'));
    var quantity = parseInt(row.find('.quantityInput').val());

    // Calculate the subtotal based on quantity and price
    var subtotal = price * quantity;

    // Update the subtotal in the HTML
    row.find('.subtotal').text('₱' + subtotal.toFixed(2));

    // Recalculate and update the total amount
    updateTotalAmount();
}

function updateTotalAmount() {
    var totalAmount = 0;

    // Loop through each cart item row and calculate the total amount
    $('.cartItems tr').each(function () {
        var price = parseFloat($(this).find('.subtotal').text().replace('₱', ''));
        var quantity = parseInt($(this).find('.quantityInput').val());

        totalAmount += price;
    });

    // Update the total amount in the HTML
    $('#totalAmount').text(totalAmount.toFixed(2));
}

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

    // Handle "Remove from Cart" button click event
    $(document).on('click', '.removeFromCart', function () {
        console.log("Remove from Cart clicked!");
        var id = $(this).data('id');
    
        // Send an Ajax request to remove the book from the cart
        $.ajax({
            url: 'posremove.php',
            method: 'POST',
            data: { id: id },
            success: function (response) {
                // Remove the corresponding row from the cartItems
                $('#cartItem_' + id).remove();
    
                // Recalculate and update the total amount
                updateTotalAmount();

                Swal.fire({
                    icon: 'error',
                    title: 'Item removed from cart',
                    showConfirmButton: false,
                    timer: 1500, // Close after 1.5 seconds
                    toast: true,
                    position: 'top-end',
                });

            },
            error: function (error) {
                console.log('Error:', error);
            }
        });
    });

    $(document).on('click', '.removeOne', function () {
        updateQuantity($(this).siblings('.quantityInput'), -1);
    });


    $(document).on('click', '.addOne', function () {
        updateQuantity($(this).siblings('.quantityInput'), 1);
    });

    $('.quantityInput').on('input', function () {
        updateQuantity(this, 0);
    });


// Handle checkout form submission
$('#checkoutForm').submit(function (event) {
    event.preventDefault();

    // Extract cart data
    var cartData = [];

    $('.cartItems tr').each(function () {
        var item = {};
        item.book_id = $(this).data('id');
        item.image = $(this).find('img').attr('src');
        item.title = $(this).find('strong').text();
        item.price = parseFloat($(this).find('.subtotal').data('price'));
        item.quantity = parseInt($(this).find('.quantityInput').val());
        item.subtotal = item.price * item.quantity;
        
        cartData.push(item);
    });

    // Check if there is data in cartData
    if (cartData.length > 0) {
        // Set the cart data value in session storage before making the AJAX request
        sessionStorage.setItem('cartData', JSON.stringify(cartData));

        // Show confirmation SweetAlert
        Swal.fire({
            title: 'Checkout with these items?',
            text: '',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Use AJAX to send the cart data to process_checkout.php
                $.ajax({
                    url: 'process_checkout.php', // Update the URL to the correct processing file
                    method: 'POST',
                    data: { cart: JSON.stringify(cartData) },
                    success: function (response) {
                        console.log('Success:', response);
                        // Redirect to checkout.php
                        window.location.href = 'checkout.php';
                    },
                    error: function (error) {
                        console.log('Error:', error);
                    }
                });
            }
        });
    } else {
        // Alert the user that the cart is empty
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Your cart is empty! Add items before checkout.'
        });
    }
});

        
});


		// // // View Information
		$(document).on('click', '.infoIcon', function () {
			var button = $(this); // Button that triggered the modal
			var BookId = button.data('id'); // Extract the college book ID from the button's data attribute

			console.log('View button clicked for Book ID: ' + BookId);
			// Fetch college book type details using AJAX
			$.ajax({
				url: 'posview.php', // Replace with your PHP script to fetch book type details
				method: 'POST',
				data: {
					BookId: BookId
				},
				dataType: 'json',
				success: function(data) {					
					// Update the modal content
					console.log('Data received:', data);

					// Manipulate the program names and strand names before displaying in the modal
					var programNames = data.bookDetails.programNames ? data.bookDetails.programNames.replace(/,(?=[^ ])/g, ',\n') : '';
					var strandNames = data.bookDetails.strandNames ? data.bookDetails.strandNames.replace(/,(?=[^ ])/g, ',\n') : '';
					console.log('Data received:', data);
					$('#bookId').text(data.bookDetails.bookId);
					$('#title').text(data.bookDetails.title);
					$('#author').text(data.bookDetails.author);
					$('#publicationYear').text(data.bookDetails.publicationYear);
					$('#quantityAvailable').text(data.bookDetails.quantityAvailable);
					$('#price').text('₱' + data.bookDetails.price);
					$('#subjectCodes').text(data.bookDetails.subjectCodes);
					$('#yearlevelName').text(data.bookDetails.yearlevelName);
					$('#yearlevelType').text(data.bookDetails.yearlevelType);
					$('#programNames').text(programNames);
					// Check the book type and hide the entire row for "Program Name" if it's High School or Senior High
					if (data.bookDetails.yearlevelType === 'High School' || data.bookDetails.yearlevelType === 'Senior High') {
						$('#programNamesRow').hide(); // Hide the entire row
					} else {
						$('#programNamesRow').show(); // Show the entire row
					}
					$('#strandNames').text(strandNames);
					if (data.bookDetails.yearlevelType === 'High School' || data.bookDetails.yearlevelType === 'College') {
						$('#strandNamesRow').hide(); // Hide the entire row
					} else {
						$('#strandNamesRow').show(); // Show the entire row
					}
					$('#status').text(data.bookDetails.status);
				},
				error: function(xhr, status, error) {
					// Handle errors, if necessary
				}
			});
		});
        
// Detects when a transaction is successful and gives the alert
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const transactionConfirmed = urlParams.get('transactionConfirmed');
    
    const swalShown = sessionStorage.getItem('swalShown');

    if (transactionConfirmed === 'true' && !swalShown) {
        sessionStorage.setItem('swalShown', 'true'); // Set the sessionStorage item
        
        Swal.fire('Transaction completed', '', 'success');
    }
});

//////////////////////////////////
    
// make it for me, the "getYearLevels.php", but rename it to possearch2.php

$(document).ready(function () {

    $("#searchType").change(function () {
        var selectedType = $(this).val();

        $.ajax({
            type: "POST",
            url: "possearch2.php",
            data: { type: selectedType },
            success: function (response) {
                $("#searchYearLevel").html(response);

                if (selectedType === 'all') {
                    $("#searchYearLevel").prepend('<option value="all">All</option>');
                }
            }
        });
    });

    $("#searchButton").click(function () {
        // Update the hidden input with selected year level types
        $("#selectedYearLevelTypes").val($("#searchType").val());

        var searchType = $("#searchType").val();
        var searchTitle = $("#searchTitle").val();
        var searchAuthor = $("#searchAuthor").val();
        var searchPublicationYear = $("#searchPublicationYear").val();
        var searchSubjectCode = $("#searchSubjectCode").val();
        var searchYearLevel = $("#searchYearLevel").val();

        $.ajax({
            type: "POST",
            url: "possearch.php",
            data: {
                searchType: searchType,
                searchTitle: searchTitle,
                searchAuthor: searchAuthor,
                searchPublicationYear: searchPublicationYear,
                searchSubjectCode: searchSubjectCode,
                searchYearLevel: searchYearLevel
            },
            success: function (response) {
                $("#booklist").empty();
                $("#booklist").html(response);
                $("#clearSearchButton").show();
                $("#searchModal").modal("hide");
                
            }
        });
    });

    $("#clearSearchButton").click(function () {

        $("#clearSearchButton").hide();

        $("#searchType").val("all");
        $("#searchTitle").val("");
        $("#searchAuthor").val("");
        $("#searchPublicationYear").val("");
        $("#searchSubjectCode").val("");
        $("#searchYearLevel").val("");
        
        $.ajax({
            type: "POST",
            url: "possearchclear.php",
            success: function (response) {
                $("#booklist").empty();
                $("#booklist").html(response);
            }
        });
    
    $("#searchModal").find("form")[0].reset();

    });
});

