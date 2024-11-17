<?php

	include '../database.php';
	include '../authentication.php';
	include '../includes.php';
    include 'posmodal.php';
	// THIS IS THE VERY DASHBOARD OF THE ADMIN AFTER LOGGING IN AS ADMIN

    if (isset($_GET['clearCart']) && $_GET['clearCart'] === 'true') {
        // Clear the cart or perform other necessary cancellation tasks
        unset($_SESSION['cartData']); // Clearing the cart data
    
    }
    
		?>


<a href="../logout.php" class="logout">  
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- My CSS -->
    <link rel="icon" href="../images/neustlogo.ico" type="image/x-icon">
	<link rel="stylesheet" href="../style.css">
	<title>Administrator Hub</title>
</head>
<body>

<style>

.table-view .card {
    display: flex;
    flex-direction: row;
}

.table-view .card-img-top {
    max-height: 100px;
    width: auto;
    margin-right: 10px;
    display: none;
}

.table-view .card-body {
    flex: 1;
}

#booklist.table-view .row {
    display: table-row;
}

#booklist.table-view .col-md-3 {
    display: table-cell;
}

#booklist.table-view .card {
    border: none;
    height: auto;
}

#booklist.table-view .card-img-top {
    display: none;
}

.button-container {
    display: flex;
    justify-content: space-between;
}

.button-container button {
    flex: 1;
    margin-right: 5px; /* Adjust the margin as needed */
}

</style>
<?php 

    include 'possidebar.php'; 

?>

	<section id="content">

<?php

    include '../nav.php';

?>
    <?php

// Check if any semester is marked as current
$query = "SELECT * FROM tbl_semesters WHERE is_current = 1";
$result = mysqli_query($db_connection, $query);

// If no current semester is found, display JavaScript alert
if (!$result || mysqli_num_rows($result) === 0) {
    echo '<script>';
    echo 'alert("Please select a semester. You need to select a semester from the dashboard before accessing the Point of Sale.");
            window.location.href = "/finalcapstone/admin_dashboard/admin_dashboard.php";';
    echo '</script>';
    exit; // Stop further execution
}

?>

		<!-- INSIDE THE DASHBOARD -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1 text-align>Point of Sale</h1>
                    <ul class="breadcrumb">
						<li>
                        <a>The Point of Sale (POS) section is the central hub for every book transaction, 
                            serving as the control center for managing sales, and guaranteeing smooth book transactions. 
                            Discover the efficiency and user-friendly nature of our POS system, thoughtfully designed to 
                            simplify book sales and transactions.
                        </a>
                    </ul>
                </div>
			</div>


            <div class="row mt-2">
                <!-- Product List Column -->
                <div class="col-md-7">
    <div class="d-flex justify-content-between" style="margin-bottom: 10px;">
        <?php echo '<h2 class="card-title"><b>Available Books</b></h2>'; ?>
        <div>
            <?php
                // echo '<button class="btn btn-secondary" id="toggleViewButton" style="margin-right: 10px;">Toggle View</button>';
            echo '<button class="btn btn-secondary" id="clearSearchButton" style="display: none; margin-right: 10px;">Clear Search</button>';
            echo '<button class="btn btn-primary" data-toggle="modal" data-target="#searchModal">Search Book</button>';

            ?>
        </div>
    </div>
    <div id="booklist" class="table" style="max-height:660px; border-bottom: 1px solid #ccc; border-top: 1px solid #ccc; padding-right:10px; overflow-y:auto; overflow-x: hidden">
        <?php


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

                
            </div>
        </div>


                <!-- Shopping Cart -->
                <div class="col-md-5">
    <div class="card" style="border-radius: 10px;">
        <div class="card-body" style="border: 1px solid #00008B; border-radius: 10px;">
            <h2 class="card-title" style="margin-bottom: 5px;">
                <p style="border-bottom: 1px solid #ccc; margin-bottom: 5px; padding-bottom: 5px;">Shopping Cart</p>
            </h2>
            <div class="table-container" style="max-height: 540px; overflow-y: auto; overflow-x: auto;  padding-right: 10px;">
                <table class="table" id="bookTable">
                    <tbody class="cartItems">
                        <!-- Cart items will be displayed here -->
                    </tbody>
                </table>
            </div>
            <div class="text-right" style="border-top: 1px solid #ccc; padding-top: 10px; ">
                <strong>Total: ₱<span id="totalAmount">0.00</span></strong>
            </div>
            <div class="text-right mt-3">
                <form id="checkoutForm">
                    <input type="hidden" name="cart" id="cartData">
                    <button type="submit" class="btn btn-success">Checkout</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

        </main>
        <!-- MAIN -->
    </section>

    <script src="pos.js"></script>


	<!-- CONTENT -->
</body>
</html>
