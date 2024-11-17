<?php

include 'database.php';
include 'authentication.php';
include_once 'includes.php';
// include 'admin_dashboard_f.php';
// THIS IS THE VERY DASHBOARD OF THE ADMIN AFTER LOGGING IN AS ADMIN

?>



<a href="logout.php" class="logout">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- My CSS -->
        <link rel="stylesheet" href="style.css">

        <title>Administrator Hub</title>
    </head>

    <body>

        <!-- LEFTSIDE NAVBAR FOR ADMIN -->
        <section id="sidebar">
            <a href="" class="brand">
                <img src="images/neustlogo.png" style="border: 1px solid white; border-radius: 50%;" alt="NEUST Logo" width="60" height="60">
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
                <li>
                    <a href="admin_dashboard/admin_dashboard.php">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="inventory/inventory.php">
                        <i class='bx bxs-book-alt'></i>
                        <span class="text">Book Inventory</span>
                    </a>
                </li>

                <li>
                    <a href="point_of_sale/pos.php">
                        <i class='bx bxs-message-dots'></i>
                        <span class="text">Point of Sale</span>
                    </a>
                </li>
                <li>
                    <a href="management.php">
                        <i class='bx bxs-cog'></i>
                        <span class="text">Settings</span>
                    </a>
                </li>
                <li>
                    <a href="staffadmin.php">
                        <i class='bx bxs-user-check'></i>
                        <span class="text">Staff and Admins</span>
                    </a>
                </li>
                <li>
                    <a href="reports/sales_report.php">
                        <i class='bx bxs-chart'></i>
                        <span class="text">Sales Report</span>
                    </a>
                </li>
                <li class="active">
                    <a href="history.php">
                        <i class='bx bxs-receipt'></i>
                        <span class="text">History</span>
                    </a>
                </li>
                <li>
                    <a href="inventory/archive.php">
                        <i class='bx bxs-archive'></i>
                        <span class="text">Archive</span>
                    </a>
                </li>
            </ul>
            <ul class="side-menu">
                <li>
                    <a href="logout.php" class="logout" onclick="return confirmLogout();">
                        <i class='bx bxs-log-out-circle'></i>
                        <span class="text">Logout</span>
                    </a>
                </li>
                <?php //CODE FOR CONFIRMING YOU WANT TO LOG OUT 
                ?>
                <script>
                    function confirmLogout() {
                        // CONFIRMATION
                        var confirmLogout = confirm("Are you sure you want to logout?");

                        // If the user clicks "OK," LOGOUT NA
                        if (confirmLogout) {
                            window.location.href = "logout.php";
                        }

                        // If the user clicks "Cancel," nothing.
                        return false;
                    }
                </script>
            </ul>
        </section>
        <section id="content">

            <?php

            include 'nav.php';

            ?>
            <!-- Your main content here -->
            <main>
                <div class="head-title">
                    <div class="left">
                        <h1>Book History</h1>
                        <div id="pst" class="pst" style="font-size: 18px; color: #333;"></div>
                        <ul class="breadcrumb">
                            <li>
                                <a href="#">Welcome to the Books History section. Here, you can view the history of changes made to your book catalog,
                                    including updates, insertions, and archives. Keep track of the modifications and details over time.
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row justify-content-center mb-3">
                        <div class="col-md-6 pl-md-4 d-flex flex-row justify-content-center align-items-center">
                            <div class="col-md-5">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="d-flex">
                                        <input type="date" id="filterDate" class="form-control text-center">
                                        <button class="btn btn-primary custom-filter-btn ml-2" onclick="filterTable()">Filter</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="d-flex">
                                        <select id="changeType" class="form-control text-center">
                                            <option value="">Change Type</option>
                                            <option value="Update">Update</option>
                                            <option value="Insert">Insert</option>
                                        </select>
                                        <button class="btn btn-primary custom-filter-btn ml-2" onclick="changeTypeTable()">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Book ID</th>
                            <th>Username</th>
                            <th>(Date/Time)</th>
                            <th>Old Data</th>
                            <th>Updated Data</th>
                            <th>Change Type</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php
                        // Define the number of items per page
                        $itemsPerPage = 10;

                        // Get the current page number from the query string
                        if (isset($_GET['page'])) {
                            $currentPage = $_GET['page'];
                        } else {
                            $currentPage = 1;
                        }
                        // Calculate the OFFSET for the SQL query
                        $offset = ($currentPage - 1) * $itemsPerPage;

                        // Get the filter date and change type from the query string
                        $filterDate = isset($_GET['filterDate']) ? mysqli_real_escape_string($db_connection, $_GET['filterDate']) : '';
                        $changeType = isset($_GET['changeType']) ? mysqli_real_escape_string($db_connection, $_GET['changeType']) : '';

                        // Calculate the total number of items
                        $countQuery = "SELECT COUNT(*) as total FROM tbl_books_history";
                        $countResult = mysqli_query($db_connection, $countQuery);
                        $countRow = mysqli_fetch_assoc($countResult);
                        $totalItems = $countRow['total'];

                        // Calculate the total number of pages
                        $totalPages = ceil($totalItems / $itemsPerPage);

                        // Modify your SQL query to include the date and change type filters
                        $sql = "SELECT h.history_id, h.book_id, h.change_type, h.change_timestamp, u.username, h.old_data, h.new_data
        FROM tbl_books_history h
        INNER JOIN tbl_users u ON h.user_id = u.user_id";

                        // Date filter if a date is provided
                        if (!empty($filterDate)) {
                            $sql .= " WHERE DATE(h.change_timestamp) = '$filterDate'";
                        }

                        // Change type filter if a change type is provided
                        if (!empty($changeType)) {
                            // Use WHERE if there's already a condition, otherwise use AND
                            $sql .= empty($filterDate) ? " WHERE" : " AND";
                            $sql .= " h.change_type = '$changeType'";
                        }
                        // // Calculate the OFFSET for the SQL query
                        // $offset = ($currentPage - 1) * $itemsPerPage;
                        // // Get the filter date from the query string
                        // $filterDate = isset($_GET['filterDate']) ? mysqli_real_escape_string($db_connection, $_GET['filterDate']) : '';
                        // $changeType = isset($_GET['changeType']) ? mysqli_real_escape_string($db_connection, $_GET['changeType']) : '';
                        // // Calculate the total number of items
                        // $countQuery = "SELECT COUNT(*) as total FROM tbl_books_history";
                        // $countResult = mysqli_query($db_connection, $countQuery);
                        // $countRow = mysqli_fetch_assoc($countResult);
                        // $totalItems = $countRow['total'];

                        // // Calculate the total number of pages
                        // $totalPages = ceil($totalItems / $itemsPerPage);
                        // // Modify your SQL query to include the date filter
                        // $sql = "SELECT h.history_id, h.book_id, h.change_type, h.change_timestamp, u.username, h.old_data, h.new_data
                        // FROM tbl_books_history h
                        // INNER JOIN tbl_users u ON h.user_id = u.user_id";

                        // // Date filter if a date is provided
                        // if (!empty($filterDate)) {
                        //     $sql .= " WHERE DATE(h.change_timestamp) = '$filterDate'";
                        // }

                        // // Modify your SQL query to include the change type filter
                        // if (!empty($changeType)) {
                        //     // Use WHERE if there's already a condition, otherwise use AND
                        //     $sql .= empty($filterDate) ? " WHERE" : " AND";
                        //     $sql .= " h.change_type = '$changeType'";
                        // }

                        $sql .= " LIMIT $itemsPerPage OFFSET $offset";

                        $result = mysqli_query($db_connection, $sql);

                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Add a class based on the "Change Type" value
                                $rowClass = strtolower($row["change_type"]) . "-row";

                                echo "<tr class='$rowClass'>";
                                echo "<td>" . $row["book_id"] . "</td>";
                                echo "<td>" . $row["username"] . "</td>";
                                echo "<td>" . $row["change_timestamp"] . "</td>";
                                echo "<td>";
                                if ($row["change_type"] == "Insert") {
                                    echo "Inserted in Book Inventory";
                                } else {
                                    $jsonData = json_decode($row["old_data"], true); // Decode JSON into PHP array
                                    // Display each key-value pair with the first letter of each word capitalized and remove underscores
                                    $formattedData = array();
                                    foreach ($jsonData as $key => $value) {
                                        $formattedKey = ucwords(str_replace('_', ' ', $key));
                                        $formattedData[] = $formattedKey . ': ' . $value;
                                    }
                                    echo implode('<br> ', $formattedData);
                                }
                                echo "</td>";
                                echo "<td>";
                                if ($row["change_type"] == "Update") {
                                    $newData = json_decode($row["new_data"], true); // Decode JSON into PHP array
                                    // Display each key-value pair with the first letter of each word capitalized and remove underscores
                                    $formattedData = array();
                                    foreach ($newData as $key => $value) {
                                        $formattedKey = ucwords(str_replace('_', ' ', $key));
                                        $formattedData[] = $formattedKey . ': ' . $value;
                                    }
                                    echo implode('<br> ', $formattedData);
                                } else {
                                    echo "Inserted in Book Inventory";
                                }
                                echo "</td>";
                                echo "<td>";
                                // Display buttons instead of text for "Change Type"
                                echo getButtonType($row["change_type"]);
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "Error in SQL query: " . mysqli_error($db_connection);
                        }
                        ?>
                    </tbody>
                    <?php
                    // Function to get button type
                    function getButtonType($changeType)
                    {
                        switch ($changeType) {
                            case "Update":
                                return "<button class='btn btn-primary custom-btn'>Updated</button>";
                            case "Insert":
                                return "<button class='btn btn-success custom-btn'>Inserted</button>";
                            case "Archive":
                                return "<button class='btn btn-danger custom-btn'>Archive</button>";
                            default:
                                return "";
                        }
                    }
                    ?>
                </table>
                <?php
                echo '<ul class="pagination justify-content-center">';
                for ($page = 1; $page <= $totalPages; $page++) {
                    echo '<li class="page-item' . ($page == $currentPage ? ' active' : '') . '">';
                    $queryParams = "?page=$page";

                    if (!empty($filterDate)) {
                        $queryParams .= '&filterDate=' . urlencode($filterDate);
                    }

                    if (!empty($changeType)) {
                        $queryParams .= '&changeType=' . urlencode($changeType);
                    }

                    echo '<a class="page-link" href="' . $queryParams . '">' . $page . '</a>';
                    echo '</li>';
                }
                echo '</ul>';
                ?>
            </main>
        </section>
        <style>
            .custom-btn {
                width: 100px;
                height: 40px;
            }
        </style>
        <script>
            function filterTable() {
                var filterDate = document.getElementById('filterDate').value;
                window.location.href = '?filterDate=' + filterDate;
            }

            function changeTypeTable() {
                var changeType = document.getElementById('changeType').value;
                window.location.href = '?page=1&changeType=' + changeType;
            }

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
        </script>
    </body>

    </html>