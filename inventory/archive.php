<?php

include '../authentication.php';
include '../database.php';
include_once '../includes.php';
include 'inventorymodals.php';

?>
<a href="logout.php" class="logout">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- My CSS -->
        <link rel="stylesheet" href="../style.css">

        <title>Administrator Hub</title>
    </head>

    <body>

        <!-- LEFTSIDE NAVBAR FOR ADMIN -->
        <section id="sidebar">
            <a href="" class="brand">
                <img src="../images/neustlogo.png" style="border: 1px solid white; border-radius: 50%;" alt="NEUST Logo" width="60" height="60">
                <span class="text" style="margin-left: 15px;">Administrator</span>
            </a>
            <?php
            if ($_SESSION['role'] === 'admin') {
                $loggedInAdminID = $_SESSION["user_id"];
                $adminNameQuery = "SELECT first_name, last_name FROM tbl_users WHERE user_id = $loggedInAdminID";
                $adminNameResult = mysqli_query($db_connection, $adminNameQuery);

                if ($adminNameResult && $adminData = mysqli_fetch_assoc($adminNameResult)) {
                    echo "<div class='alert alert-info mt-0 mb-0 text-center strong'>";
                    echo "<strong>" . $adminData['first_name'] . " " . $adminData['last_name'] . "</strong>";
                    echo "</div>";
                }
            }
            ?>
            <ul class="side-menu top">
                <!-- Your menu items here -->
                <li>
                    <a href="../admin_dashboard/admin_dashboard.php">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="inventory.php">
                        <i class='bx bxs-book-alt'></i>
                        <span class="text">Book Inventory</span>
                    </a>
                </li>
                <li>
                    <a href="../point_of_sale/pos.php">
                        <i class='bx bxs-message-dots'></i>
                        <span class="text">Point of Sale</span>
                    </a>
                </li>
                <li>
                    <a href="../management.php">
                        <i class='bx bxs-cog'></i>
                        <span class="text">Settings</span>
                    </a>
                </li>
                <li>
                    <a href="../staffadmin.php">
                        <i class='bx bxs-user-check'></i>
                        <span class="text">Staff and Admins</span>
                    </a>
                </li>
                <li>
                    <a href="../reports/sales_report.php">
                        <i class='bx bxs-chart'></i>
                        <span class="text">Sales Report</span>
                    </a>
                </li>
                <li>
                    <a href="../history.php">
                        <i class='bx bxs-receipt'></i>
                        <span class="text">History</span>
                    </a>
                </li>
                <li class="active">
                    <a href="archive.php">
                        <i class='bx bxs-archive'></i>
                        <span class="text">Archive</span>
                    </a>
                </li>
            </ul>
            <ul class="side-menu">
                <li>
                    <a href="../logout.php" class="logout" onclick="return confirmLogout();">
                        <i class='bx bxs-log-out-circle'></i>
                        <span class="text">Logout</span>
                    </a>
                </li>
                <script>
                    function confirmLogout() {
                        // Using SweetAlert for confirmation    
                        Swal.fire({
                            title: 'Are you sure you want to logout?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // If the user clicks "Yes," then you'll logout
                                window.location.href = "../logout.php";
                            }
                        });

                        // Prevent the default action of the link
                        return false;
                    }
                </script>
            </ul>
        </section>
        <section id="content">
            <!-- Your main content here -->

            <?php

            include '../nav.php';

            ?>
            <main>
                <div class="head-title">
                    <div class="left">
                        <h1>Archived Books</h1>
                        <!-- <div id="pst" class="pst" style="font-size: 18px; color: #333;">Inactive</div> -->
                        <ul class="breadcrumb">
                            <li>
                                <a href="">Welcome to the Archived Books section. Here, you can view and manage your inactive book catalog,
                                    including restoring, if needed.
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                </div>
                <div class="col-md-2">
                    <br>
                    <!-- PUT SEARCH BUTTON HERE -->
                </div>
                <br>
                <p id="noResultsMessage" style="display: none;">No matching results.</p>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped custom-table">
                        <thead>
                            <tr>
                                <th>Book ID</th>
                                <th>ISBN</th>
                                <th>Book Image</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Publication Year</th>
                                <th>Subject Code</th>
                                <th>Year Levels</th>
                                <th>Program/Strand</th>
                                <th>Total Quantity</th>
                                <th>Price</th>
                                <!-- <th>Damaged/Condtion</th> -->
                                <!-- <th>Book Condition</th> -->
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php
                            // Define the number of items per page
                            $itemsPerPage = 5;

                            // Get the current page number from the query string
                            if (isset($_GET['page'])) {
                                $currentPage = $_GET['page'];
                            } else {
                                $currentPage = 1;
                            }

                            // Calculate the OFFSET for the SQL query
                            $offset = ($currentPage - 1) * $itemsPerPage;

                            // Query to count the total number of items
                            $countQuery = "SELECT COUNT(*) as total FROM tbl_books";
                            $countResult = mysqli_query($db_connection, $countQuery);
                            $countRow = mysqli_fetch_assoc($countResult);
                            $totalItems = $countRow['total'];

                            // Calculate the total number of pages
                            $totalPages = ceil($totalItems / $itemsPerPage);

                            $sql = "SELECT cb.book_id, 
						 	cb.isbn, 
						    cb.title, 
						    cb.book_image, 
						    cb.author, 
						    cb.publication_year, 
						    cb.quantity_available, 
						    cb.price, 
							-- cb.quantity_damaged,
							-- cb.book_condition,
						    s.subject_code, 
						    y.year_level_name, 
						    y.year_level_type,
						    cb.status, 
						    GROUP_CONCAT(DISTINCT p.program_name SEPARATOR ',<br>') AS program_names,
						    GROUP_CONCAT(DISTINCT st.strand_name SEPARATOR ',<br>') AS strand_names
						FROM tbl_books cb
						LEFT JOIN tbl_subjects s ON cb.subject_id = s.subject_id
						LEFT JOIN tbl_yearlevels y ON cb.year_level_id = y.year_level_id
						LEFT JOIN tbl_book_programs bp ON cb.book_id = bp.book_id
						LEFT JOIN tbl_programs p ON bp.program_id = p.program_id
						LEFT JOIN tbl_book_strands bs ON cb.book_id = bs.book_id
						LEFT JOIN tbl_strands st ON bs.strand_id = st.strand_id
						WHERE cb.status = 'Inactive'
						GROUP BY cb.book_id
						LIMIT $itemsPerPage OFFSET $offset";
                            $result = mysqli_query($db_connection, $sql);

                            if ($result) {
                                // Check if there are rows in the result set
                                if (mysqli_num_rows($result) > 0) {
                                    // Loop through and display the book details
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $imagePath = '/finalcapstone/images/' . $row['book_image'];
                                        $bookCover = $imagePath;
                                        if (is_file($_SERVER['DOCUMENT_ROOT'] . $imagePath)) {
                                            $bookCover = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . $imagePath));
                                        }
                                        echo "<tr data-book_id='" . $row["book_id"] . "'>";
                                        echo "<td>" . $row["book_id"] . "</td>";
                                        // Assuming $row["isbn"] contains an ISBN without hyphens
                                        $isbn = $row["isbn"];

                                        // Remove hyphens if they exist
                                        $cleanIsbn = str_replace('-', '', $isbn);

                                        // Check the length of the cleaned ISBN
                                        $isbnLength = strlen($cleanIsbn);

                                        // Format ISBN based on length
                                        if ($isbnLength == 10) {
                                            // ISBN-10 formatting
                                            $formattedIsbn = substr($cleanIsbn, 0, 1) . '-' . substr($cleanIsbn, 1, 3) . '-' . substr($cleanIsbn, 4, 5) . '-' . substr($cleanIsbn, 9, 1);
                                            echo "<td>" . $formattedIsbn . "</td>";
                                        } elseif ($isbnLength == 13) {
                                            // ISBN-13 formatting
                                            $formattedIsbn = substr($cleanIsbn, 0, 3) . '-' . substr($cleanIsbn, 3, 1) . '-' . substr($cleanIsbn, 4, 5) . '-' . substr($cleanIsbn, 9, 3) . '-' . substr($cleanIsbn, 12, 1);
                                            echo "<td>" . $formattedIsbn . "</td>";
                                        } else {
                                            // Invalid ISBN length
                                            echo "<td>Invalid ISBN</td>";
                                        }
                                        echo '<td><img src="' . $bookCover . '" style="border-radius: 5px; border: 1px solid grey; height: 130px; width: 130px;"></td>';
                                        echo "<td>" . $row["title"] . "</td>";
                                        echo "<td>" . $row["author"] . "</td>";
                                        echo "<td>" . $row["publication_year"] . "</td>";
                                        echo "<td>" . $row["subject_code"] . "</td>";
                                        echo "<td>" . $row["year_level_name"] . "</td>";
                                        if ($row["year_level_type"] === "High School" && empty($row["program_names"]) && empty($row["strand_names"])) {
                                            echo "<td>Not Available</td>";
                                        } else {
                                            echo "<td>" . $row["program_names"] . $row["strand_names"] . "</td>";
                                        }
                                        echo "<td>" . $row["quantity_available"] . "</td>";
                                        echo "<td>₱" . $row["price"] . "</td>";
                                        // echo "<td>" . $row["quantity_damaged"] . " - " . $row["book_condition"] . "</td>";
                                        // echo "<td>" . $row["quantity_damaged"] . "</td>";
                                        // echo "<td>" . $row["book_condition"] . "</td>";
                                        // Status toggle button
                                        echo '<td><button class="btn btn-toggle-status btn-success" data-book-id="' . $row["book_id"] . '">' . $row["status"] . '</button></td>';

                                        // Buttons for View, Edit, and Delete
                                        echo '<td>';
                                        echo '<div class="btn-group" role="group" style="height: 39px;">';

                                        // View button
                                        // echo '<button class="btn btn-outline-info view-button" data-toggle="modal" data-target="#viewBookModal" title="View" data-book_id="' . $row["book_id"] . '"><i class="bx bxs-show"></i></button>';

                                        // Edit button
                                        echo '<button class="btn btn-outline-primary edit-button" data-toggle="modal" data-target="#editBookModal" title="Edit" data-book_id="' . $row["book_id"] . '"><i class="bx bxs-pencil"></i></button>';

                                        // Delete button
                                        // echo '<form method="POST" action="../commands.php">';
                                        echo '<input type="hidden" name="bookTypeID" value="' . $row["book_id"] . '">';
                                        echo '<button type="button" class="btn btn-outline-danger archive-button" title="Archive" data-book-id="' . $row["book_id"] . '"><i class="bx bxs-book"></i></button>';

                                        echo '</div>';
                                        echo '</td>';  // <-- Add this line
                                    }
                                } else {
                                    // Display "No Books Available" row
                                    echo "<tr><td colspan='12'>No Books Available.</td></tr>";
                                }
                            } else {
                                // Display a detailed error message
                                echo "<tr><td colspan='12'>Error in SQL query: " . mysqli_error($db_connection) . "<br>Query: $sql</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
            <style>
                /* Style for disabled options */
                select:disabled {
                    color: #555;
                    /* Change the color to your desired color */
                }
            </style>
        </section>
        <script>
            // Displays the day, date and time for Philippines        
            function updateTime() {
                const pstElement = document.getElementById('pst');
                const now = new Date();
                const options = {
                    timeZone: 'Asia/Manila',
                    weekday: 'short',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                };
                const pstTime = now.toLocaleString('en-US', options);
                pstElement.textContent = "PST (Philippine Standard Time): " + pstTime;
            }

            updateTime();
            setInterval(updateTime, 1000);

            // On page load, check local storage for book status
            window.onload = function() {
                // Loop through each book button
                $('.btn-toggle-status').each(function() {
                    var BookId = $(this).data('book-id');
                    var storedStatus = localStorage.getItem('book_' + BookId);
                    if (storedStatus === 'Inactive') {
                        // Update the button based on the stored status
                        $(this).text(storedStatus);
                        $(this).removeClass('btn-success').addClass('btn-danger');
                    } else {
                        // Set the status to 'Inactive' in local storage
                        localStorage.setItem('book_' + BookId, 'Inactive');
                        // Update the button based on the stored status
                        $(this).text('Inactive');
                        $(this).removeClass('btn-success').addClass('btn-danger');
                    }
                });
            }

            // Attach the archiveBook function to the click event of the Archive button
            $(document).on('click', '.archive-button', function() {
                var bookId = $(this).data('book-id');
                var statusButton = $(this).closest('tr').find('.btn-toggle-status')[0]; // Assuming the status button is in the same row
                var quantityAvailable = parseInt($(this).closest('tr').find('.quantity-available').text().trim());

                toggleStatus(statusButton, bookId, quantityAvailable); // Call toggleStatus with the status button, bookId, and quantityAvailable
            });

            // Toggle status books
            function toggleStatus(button, bookId, quantityAvailable) {
                var currentStatus = button.innerText.trim();

                // Check if total quantity is greater than 0
                if (currentStatus === 'Inactive' && quantityAvailable === 0) {
                    Swal.fire('Error', 'This book cannot be restored because the quantity is not available.', 'error');
                    return;
                }

                // Use SweetAlert for confirmation
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You are about to restore this book. This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, restore it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'toggle_status.php',
                            method: 'POST',
                            data: {
                                book_id: bookId,
                                new_status: (currentStatus === 'Active') ? 'Inactive' : 'Active'
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    // Store the status in local storage
                                    localStorage.setItem('book_' + bookId, response.newStatus);

                                    // Show success message
                                    Swal.fire({
                                        title: 'Restored!',
                                        text: 'The book has been restored in the Inventory.',
                                        icon: 'success',
                                        showConfirmButton: false, // Hide the default "OK" button
                                        allowOutsideClick: true,
                                        timer: 1000 // Set the timer to 4000 milliseconds (4 seconds)
                                    }).then(() => {
                                        // Reload the page after the user clicks OK
                                        location.reload();
                                    });
                                } else {
                                    // Handle any errors here
                                    console.log(response.error);
                                    Swal.fire('Error', 'An error occurred while restore the book.', 'error');
                                }
                            },
                            error: function(xhr, status, error) {
                                // Handle errors here
                                console.log(error);
                                Swal.fire('Error', 'An error occurred while processing the request.', 'error');
                            }
                        });
                    }
                });
            }


            // Function to show/hide options based on book_type in the edit modal
            function toggleBookTypeOptions(yearlevelType) {
                if (yearlevelType === 'College') {
                    $('#collegebookEditOptionGroup').hide();
                    $('#seniorhighbookEditOptionGroup').hide();
                } else if (yearlevelType === 'Senior High') {
                    $('#collegebookEditOptionGroup').hide();
                    $('#seniorhighbookEditOptionGroup').hide();
                } else {
                    // Handle other book types if needed
                    $('#collegebookEditOptionGroup').hide();
                    $('#seniorhighbookEditOptionGroup').hide();
                }
            }

            // //Edit Modal to fetch the ID
            $('.edit-button').click(function() {
                var bookId = $(this).data('book_id');
                editBook(bookId);
            });
            // Edit Modal function 
            function editBook(bookId) {
                console.log('Editing book with ID: ' + bookId);

                $.ajax({
                    url: 'edit_book.php',
                    type: 'POST',
                    data: {
                        book_id: bookId
                    },
                    success: function(response) {
                        var bookData = JSON.parse(response);

                        $('#editBookModal #bookID').val(bookData.book_id).prop('disabled', true);
                        $('#editBookModal #bookIsbn').val(bookData.isbn).prop('disabled', true);
                        $('#editBookModal #bookTitle').val(bookData.title).prop('disabled', true);
                        $('#editBookModal #bookAuthor').val(bookData.author).prop('disabled', true);
                        $('#editBookModal #bookCondition').val(bookData.book_condition).prop('disabled', true);
                        $('#editBookModal #quantityDamaged').val(bookData.quantity_damaged).prop('disabled', true);
                        // Edit the bookPublicationYear to be a select element
                        var selectElementPublicationYear = $('#editBookModal #bookPublicationYear');
                        selectElementPublicationYear.empty();

                        // Populate the options from 1880 to 2023
                        for (var year = 1880; year <= 2023; year++) {
                            var option = $('<option></option>');
                            option.text(year);
                            option.val(year);
                            selectElementPublicationYear.append(option);
                        }
                        // Set the selected value to the bookData's publication year
                        selectElementPublicationYear.val(bookData.publication_year).prop('disabled', true);

                        $('#editBookModal #bookQuantity').val(bookData.quantity_available).prop('readonly', false);
                        $('#editBookModal #bookPrice').val(bookData.price).prop('disabled', true);

                        // Edit the bookSubject to be a select element
                        var selectElementSubject = $('#editBookModal #bookSubject');
                        selectElementSubject.empty();
                        selectElementSubject.empty().prop('disabled', true).css('color', 'black');
                        $.ajax({
                            url: 'get_subjects.php',
                            type: 'GET',
                            success: function(subjectCodes) {
                                var codes = subjectCodes.split(',');
                                for (var i = 0; i < codes.length; i++) {
                                    var option = $('<option></option>');
                                    option.text(codes[i]);
                                    option.val(codes[i]);
                                    selectElementSubject.append(option);
                                }

                                selectElementSubject.val(bookData.subject_code).prop('disabled', true);
                            }
                        });

                        // Edit the bookYearlevels to be a select element
                        var yearLevelsElement = $('#editBookModal #bookYearlevels');
                        yearLevelsElement.empty().prop('disabled', true).css('color', 'black');
                        // yearLevelsElement.empty();
                        var yearLevelType = bookData.year_level_type;
                        $.ajax({
                            url: 'get_yearlevels.php',
                            type: 'GET',
                            data: {
                                year_level_type: yearLevelType
                            },
                            success: function(yearLevels) {
                                var levels = yearLevels.split(',');
                                for (var i = 0; i < levels.length; i++) {
                                    var option = $('<option></option>');
                                    option.text(levels[i]);
                                    option.val(levels[i]);
                                    yearLevelsElement.append(option);
                                }

                                yearLevelsElement.val(bookData.year_level_name).prop('disabled', true);

                                // Show or hide based on the condition
                                if (yearLevelType === 'High School' || yearLevelType === 'Senior High' || yearLevelType === 'College') {
                                    yearLevelsElement.show();
                                } else {
                                    yearLevelsElement.hide();
                                }
                            }
                        });

                        $('#editBookModal #bookImage').hide();
                        $('#editBookModal label[for="bookImage"]').hide();
                        // Edit the bookyearLevelType to be a select element
                        $('#editBookModal #bookyearLevelType').val(bookData.year_level_type).prop('disabled', true);;
                        // Initial toggle based on the default book type
                        toggleBookTypeOptions($('#bookyearLevelType').val());

                        $('#editBookModal').modal('show');
                    }
                });
            }
            $('#updateBookButton').click(function() {
                var bookId = $('#bookID').val();
                var bookTitle = $('#bookTitle').val();
                // Check if bookTitle is empty
                if (bookTitle.trim() === '') {
                    // Display SweetAlert warning
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Title',
                        text: 'Please enter a title for the book.',
                        allowOutsideClick: true,
                    }).then(function() {
                        $('#editBookModal').modal('hide');
                    });
                    return; // Prevent further execution
                }
                var bookAuthor = $('#bookAuthor').val();
                // Check if bookAuthor is empty
                if (bookAuthor.trim() === '') {
                    // Display SweetAlert warning
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Author',
                        text: 'Please enter an author for the book.',
                        allowOutsideClick: true,
                    }).then(function() {
                        $('#editBookModal').modal('hide');
                    });
                    return; // Prevent further execution
                }
                var bookPublicationYear = $('#bookPublicationYear').val();
                var bookPrice = $('#bookPrice').val();
                // Check if bookPrice is empty
                if (bookPrice.trim() === '') {
                    // Display SweetAlert warning
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Price',
                        text: 'Please enter a price for the book.',
                        allowOutsideClick: true,
                    }).then(function() {
                        $('#editBookModal').modal('hide');
                    });
                    return; // Prevent further execution
                }
                var bookSubject = $('#bookSubject').val();
                var bookYearLevels = $('#bookYearlevels').val();
                var bookQuantity = $('#bookQuantity').val();
                // Check if bookQuantity is 0
                if (parseInt(bookQuantity) === 0) {
                    // Display SweetAlert warning
                    Swal.fire({
                        icon: 'warning',
                        title: 'Quantity is Zero',
                        text: 'You cannot update a book with zero quantity.',
                        allowOutsideClick: true,
                    }).then(function() {
                        $('#editBookModal').modal('hide');
                    });
                    return; // Prevent further execution
                }
                var formData = new FormData($('#editBookForm')[0]);
                formData.append('book_id', bookId);
                formData.append('title', bookTitle);
                formData.append('author', bookAuthor);
                formData.append('publication_year', bookPublicationYear);
                formData.append('price', bookPrice);
                formData.append('subject_code', bookSubject);
                formData.append('year_level_name', bookYearLevels);
                formData.append('quantity', bookQuantity);

                if ($('#bookImage')[0].files.length > 0) {
                    formData.append('bookImage', $('#bookImage')[0].files[0]);
                }

                console.log(formData);

                $.ajax({
                    url: 'update_book.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);
                        $('#editBookModal').modal('hide');

                        // Display SweetAlert for success
                        Swal.fire({
                            icon: 'success',
                            title: 'Book Updated Successfully',
                            showConfirmButton: false,
                            timer: 1500 // Close alert after 1.5 seconds
                        }).then(function() {
                            location.reload(); // Reload the page
                        });
                    },
                    error: function(error) {
                        console.log('Error updating book: ' + error);

                        // Display SweetAlert for error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Updating Book',
                            text: 'An error occurred while updating the book. Please try again.',
                            allowOutsideClick: true, // Allow clicking outside the alert to close it
                        }).then(function() {
                            $('#editBookModal').modal('hide');
                        });
                    }
                });
            });
            // Function to increment quantity
            function incrementQuantity() {
                var currentQuantity = parseInt($('#bookQuantity').val());
                console.log('Before Increment:', currentQuantity);
                $('#bookQuantity').val(currentQuantity + 1);
                console.log('After Increment:', $('#bookQuantity').val());
            }

            // Function to decrement quantity
            function decrementQuantity() {
                var currentQuantity = parseInt($('#bookQuantity').val());
                console.log('Before Decrement:', currentQuantity);
                if (currentQuantity > 0) {
                    $('#bookQuantity').val(currentQuantity - 1);
                }
                console.log('After Decrement:', $('#bookQuantity').val());
            }
        </script>
    </body>

    </html>