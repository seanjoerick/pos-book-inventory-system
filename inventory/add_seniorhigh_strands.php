<?php

include '../authentication.php';
include '../database.php';
include_once '../includes.php';
include_once 'inventorycommands.php';


$lastInsertedID = isset($_GET['book_id']) ? $_GET['book_id'] : "";

?>
<!DOCTYPE html>
<html lang="en">

<head>
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
                <a>
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="active">
                <a>
                    <i class='bx bxs-book-alt'></i>
                    <span class="text">Book Inventory</span>
                </a>
            </li>
            <li>
                <a>
                    <i class='bx bxs-message-dots'></i>
                    <span class="text">Point of Sale</span>
                </a>
            </li>
            <li>
                <a>
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a>
                    <i class='bx bxs-user-check'></i>
                    <span class="text">Staff and Admins</span>
                </a>
            </li>
            <li>
                <a>
                    <i class='bx bxs-receipt'></i>
                    <span class="text">History</span>
                </a>
            </li>
            <li>
                <a>
                    <i class='bx bxs-archive'></i>
                    <span class="text">Archive</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <!-- Your menu items here -->
            <!-- <li>
                <a>
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li> -->
            <!-- <li>
                <a>
                    <i class='bx bxs-help-circle'></i>
                    <span class="text">Help & Support</span>
                </a>
            </li> -->
            <li>
                <a>
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>

    <section id="content">

        <?php

        include '../nav.php';

        ?>
        <!-- Your main content here -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Book Inventory</h1>
                    <div id="pst" class="pst" style="font-size: 18px; color: #333;"></div>
                    <ul class="breadcrumb">
                        <li>
                            <a href="">Book Inventory section. Here, you can efficiently manage your book catalog,
                                including adding, editing, and deleting books, as well as tracking their availability and details.
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="form-body">
                <div class="row">
                    <div class="form-holder">
                        <div class="form-content">
                            <div class="form-items">
                                <h4>Book Strands</h4>
                                <p><strong>Important:</strong> To maintain precise records, kindly complete the fields related to the book strands.
                                    <br> Include the necessary details corresponding to the books in your inventory. Your cooperation is highly appreciated.
                                </p>
                                <form action="inventorycommands.php" method="POST" id="addseniorstrandsForm" onsubmit="return validateCheckbox()">
                                    <div class="col-md-12">
                                        <strong><label for="seniorID"></label></strong>
                                        <strong>
                                            <label for="lastInsertedID" id="lastInsertedIDLabel" style="font-size: 18px;"><?php echo htmlspecialchars($lastInsertedID); ?></label>
                                        </strong>

                                        <input type="hidden" class="form-control" name="seniorID" id="seniorID" value="<?php echo htmlspecialchars($lastInsertedID); ?>">
                                    </div>
                                    <div class="col-md-12">
                                        <strong><label for="seniorID">Select Strand(s)</label></strong>
                                        <?php
                                        $sql = "SELECT strand_id, strand_name FROM tbl_strands";
                                        $result = mysqli_query($db_connection, $sql);

                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<div class="form-check">';
                                            echo '<input type="checkbox" class="form-check-input" id="strand_' . $row["strand_id"] . '" name="strands[]" value="' . $row["strand_id"] . '">';
                                            echo '<label class="form-check-label" for="strand_' . $row["strand_id"] . '">' . $row["strand_name"] . '</label>';
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>

                                    <div class="form-button mt-3">
                                        <button type="submit" class="btn btn-primary" name="addBookSeniorStrand" id="addsubmitBookSeniorStrand">&plus; Book Strands</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </section>
    <style>
        *,
        body {
            font-weight: 400;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
            -moz-osx-font-smoothing: grayscale;
        }

        html,
        body {
            height: 100%;
            overflow: hidden;
        }


        .form-holder {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            min-height: 60vh;
        }

        .form-holder .form-content {
            position: relative;
            text-align: center;
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-align-items: center;
            align-items: center;
            padding: 60px;
        }

        .form-content .form-items {
            border: 3px solid #fff;
            padding: 40px;
            display: inline-block;
            background-color: #00008B;
            width: 100%;
            min-width: 650px;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            text-align: left;
            -webkit-transition: all 0.4s ease;
            transition: all 0.4s ease;
        }

        .form-items h4,
        .form-items p {
            color: white;
        }

        .form-content label,
        .was-validated .form-check-input:invalid~.form-check-label,
        .was-validated .form-check-input:valid~.form-check-label {
            color: #fff;
        }

        .form-content input[type=text],
        .form-content input[type=password],
        .form-content input[type=email],
        .form-content select {
            width: 100%;
            padding: 9px 20px;
            text-align: left;
            border: 0;
            outline: 0;
            border-radius: 6px;
            background-color: #fff;
            font-size: 15px;
            font-weight: 300;
            color: #8D8D8D;
            -webkit-transition: all 0.3s ease;
            transition: all 0.3s ease;
            margin-top: 16px;
        }

        .form-content textarea {
            position: static !important;
            width: 100%;
            padding: 8px 20px;
            border-radius: 6px;
            text-align: left;
            border: 0;
            font-size: 15px;
            color: #8D8D8D;
            outline: none;
            resize: none;
            height: 150px;
            -webkit-transition: none;
            transition: none;
            margin-bottom: 14px;
        }

        .mv-up {
            margin-top: -9px !important;
            margin-bottom: 8px !important;
        }

        .invalid-feedback {
            color: #ff606e;
        }

        .valid-feedback {
            color: #2acc80;
        }
    </style>
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

        function validateCheckbox() {
            var checkboxes = document.querySelectorAll('input[name="strands[]"]:checked');
            if (checkboxes.length === 0) {
                // Use SweetAlert for a more user-friendly alert
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Please select at strand related to the books.',
                });
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>
</body>

</html>