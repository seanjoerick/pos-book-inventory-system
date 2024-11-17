<?php

// THE DATABASE AND FUNCTIONS..

date_default_timezone_set('Asia/Manila');

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'finalcapstone';
global $db_connection;
global $db;
$db_connection = mysqli_connect($host, $user, $password, $dbname) or die('Failed to connect to database server');
$db = mysqli_select_db($db_connection, $dbname);

if ($db_connection) {
} else {
    die(mysqli_error($db_connection));
}

// QUERY MAKER

function GetValue($sql_query)
{
    global $db_connection;
    $result = mysqli_query($db_connection, $sql_query);
    $row = mysqli_fetch_array($result);
    return $row[0];
}

// IF Database Exist, Execute

function isDBTableExist($dbname, $table)
{
    return GetValue("SELECT COUNT(*)
    FROM information_schema.tables
    WHERE table_schema = '" . $dbname . "'
            AND table_name = '" . $table . "'
        LIMIT 1;") + 0;
}

// For counting in staffadmins.php

function countActiveAdminAccounts($db_connection)
{
    $query = "SELECT COUNT(*) AS active_admins_count FROM tbl_users WHERE status = 'active'";
    $result = mysqli_query($db_connection, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($db_connection));
    }

    $row = mysqli_fetch_assoc($result);
    return $row['active_admins_count'];
}

$neustYearLevels = array(
    "Grade 7",
    "Grade 8",
    "Grade 9",
    "Grade 10",
    "Grade 11",
    "Grade 12",
    "1st Year College",
    "2nd Year College",
    "3rd Year College",
    "4th Year College",
    // Add more college names here
);

$neustYearType = array(
    "High School",
    "High School",
    "High School",
    "High School",
    "Senior High",
    "Senior High",
    "College",
    "College",
    "College",
    "College",
);

$neustColleges = array(
    "College of Architecture",
    "College of Criminology",
    "College of Education",
    "College of Engineering",
    "College of Information and Communications Technology",
    "College of Management and Business Technology",
    "College of Public Administration and Disaster Management",
    "College of Arts and Sciences",
    "College of Industrial Technology",
    "College of Nursing",

    // Add more college names here
);

$neustPrograms = array(
    array("Bachelor of Science in Architecture", 1),
    array("Bachelor of Science in Criminology", 2),
    array("Bachelor of Science in Civil Engineering", 4),
    array("Bachelor of Science in Electrical Engineering", 4),
    array("Bachelor of Science in Mechanical Engineering", 4),
    array("Bachelor of Science in Information Technology", 5),
    array("Bachelor of Science in Business Administration", 6),
    array("Bachelor of Science in Entrepreneurship", 6),
    array("Bachelor of Science in Hospitality Management", 6),
    array("Bachelor of Science in Hotel and Restaurant Management", 6),
    array("Bachelor of Science in Tourism Management", 6),
    array("Bachelor of Public Administration", 7),
    array("Bachelor of Public Administration Major in Disaster Management", 7),
    array("Bachelor of Science in Biology", 8),
    array("Bachelor of Science in Food Technology", 8),
    array("Bachelor of Science in Psychology", 8),
    array("Bachelor of Science in Chemistry", 8),
    array("Bachelor of Science in Environmental Science", 8),
    array("Bachelor of Industrial Technology", 9),
    array("Bachelor of Electronics and Communication Engineering Technology", 9),
    array("Bachelor of Science in Nursing", 10),

);

$neustStrands = array(
    "Science, Technology, Engineering and Mathematics",
    "Accountancy, Business and Management",

);

$neustTF = array(
    "Sean",
    "Francisco",
    "Albert",
    // Add more teacher names here
);

$neustTL = array(
    "Rayomaca",
    "Frank",
    "Einstein",

);

// YearLevels 1
if (!isDBTableExist($dbname, 'tbl_yearlevels')) {
    $createTableQuery = "CREATE TABLE tbl_yearlevels (
        year_level_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        year_level_type ENUM('High School', 'Senior High', 'College') NOT NULL,  
        year_level_name VARCHAR(50) NOT NULL)";

    mysqli_query($db_connection, $createTableQuery);

    // Insert Years
    for ($i = 0; $i < count($neustYearLevels); $i++) {
        $yearlevelType = $neustYearType[$i];
        $yearlevelName = $neustYearLevels[$i];

        $insertQuery = "INSERT INTO tbl_yearlevels (year_level_type, year_level_name) VALUES ('$yearlevelType', '$yearlevelName')";
        mysqli_query($db_connection, $insertQuery);
    }
}

// Semesters 2
if (!isDBTableExist($dbname, 'tbl_semesters')) {
    $createTableQuery = "CREATE TABLE tbl_semesters (
        semester_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        semester_name VARCHAR(50) NOT NULL,
        is_current TINYINT(1) NOT NULL DEFAULT 0)";

    mysqli_query($db_connection, $createTableQuery);
}

// Subjects 3
if (!isDBTableExist($dbname, 'tbl_subjects')) {
    $createTableQuery = "CREATE TABLE tbl_subjects (
        subject_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        subject_name VARCHAR(255) NOT NULL,
        subject_code VARCHAR(20) NOT NULL)";

    mysqli_query($db_connection, $createTableQuery);
}

// Colleges 4
if (!isDBTableExist($dbname, 'tbl_colleges')) {
    $createTableQuery = "CREATE TABLE tbl_colleges (
        college_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        college_name VARCHAR(100) NOT NULL)";

    mysqli_query($db_connection, $createTableQuery);

    // Insert College
    foreach ($neustColleges as $collegeName) {
        $insertQuery = "INSERT INTO tbl_colleges (college_name) VALUES ('$collegeName')";
        mysqli_query($db_connection, $insertQuery);
    }
}

// Programs 5
if (!isDBTableExist($dbname, 'tbl_programs')) {
    $createTableQuery = "CREATE TABLE tbl_programs (
        program_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        program_name VARCHAR(100) NOT NULL,
        college_id INT(11),
        FOREIGN KEY (college_id) REFERENCES tbl_colleges(college_id))";

    mysqli_query($db_connection, $createTableQuery);

    foreach ($neustPrograms as $programData) {
        $programName = $programData[0];
        $collegeID = $programData[1];

        $insertQuery = "INSERT INTO tbl_programs (program_name, college_id) VALUES ('$programName', $collegeID)";
        mysqli_query($db_connection, $insertQuery);
    }
}

// Customers 6
if (!isDBTableExist($dbname, 'tbl_customers')) {
    $createTableQuery = "CREATE TABLE tbl_customers (
    customer_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    customer_type ENUM('Student', 'Non-Student') NOT NULL,
    student_number VARCHAR(100) UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    phone_number VARCHAR(100))";
    mysqli_query($db_connection, $createTableQuery);
}

// Teachers 7
if (!isDBTableExist($dbname, 'tbl_teachers')) {
    $createTableQuery = "CREATE TABLE tbl_teachers (
        teacher_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL)";

    mysqli_query($db_connection, $createTableQuery);

    // Insert Teachers
    for ($i = 0; $i < count($neustTF); $i++) {
        $lastname = $neustTL[$i];
        $firstname = $neustTF[$i];

        $insertQuery = "INSERT INTO tbl_teachers (first_name, last_name) VALUES ('$firstname', '$lastname')";
        mysqli_query($db_connection, $insertQuery);
    }
}


// Users 8
if (!isDBTableExist($dbname, 'tbl_users')) {
    $createTableQuery = "CREATE TABLE tbl_users (
        user_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        username VARCHAR(50) NOT NULL,
        reset_token VARCHAR(150) DEFAULT '',
        password VARCHAR(255) NOT NULL,
        role VARCHAR(10) NOT NULL,
        status ENUM('active', 'inactive') DEFAULT 'active')";

    mysqli_query($db_connection, $createTableQuery);
}

// Strands 9
if (!isDBTableExist($dbname, 'tbl_strands')) {
    $createTableQuery = "CREATE TABLE tbl_strands (
        strand_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        strand_name VARCHAR(100) NOT NULL)";

    mysqli_query($db_connection, $createTableQuery);

    // Insert Strands
    foreach ($neustStrands as $strandName) {
        $insertQuery = "INSERT INTO tbl_strands (strand_name) VALUES ('$strandName')";
        mysqli_query($db_connection, $insertQuery);
    }
}


// Books 10
if (!isDBTableExist($dbname, 'tbl_books')) {
    $createTableQuery = "CREATE TABLE tbl_books (
        book_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        isbn VARCHAR(20),
        title TEXT NOT NULL,
        book_image VARCHAR(100),
        author VARCHAR(100) NOT NULL,
        publication_year INT(4),
        quantity_available INT(11), -- changed to total_quantity
        -- total_quantity INT(11), -- Total quantity of books
        quantity_damaged INT(11) DEFAULT 0, -- Quantity of damaged books
        book_condition VARCHAR(50), 
        price DECIMAL(10, 2),
        subject_id INT(11),
        year_level_id INT(11),
        status ENUM('Active', 'Inactive') DEFAULT 'Active',
        FOREIGN KEY (year_level_id) REFERENCES tbl_yearlevels(year_level_id),
        FOREIGN KEY (subject_id) REFERENCES tbl_subjects(subject_id))";

    mysqli_query($db_connection, $createTableQuery);
}


$triggerName = 'update_status_on_zero_quantity';
$checkTriggerQuery = "
SELECT COUNT(*)
FROM information_schema.triggers
WHERE trigger_schema = '$dbname'
    AND trigger_name = '$triggerName';
";

$result = mysqli_query($db_connection, $checkTriggerQuery);
$row = mysqli_fetch_row($result);

if ($row[0] == 0) {
    $createTriggerQuery = "
    CREATE TRIGGER $triggerName
    BEFORE UPDATE ON tbl_books
    FOR EACH ROW
    BEGIN
        IF NEW.quantity_available = 0 THEN
            SET NEW.status = 'Inactive';
        END IF;
    END;
    ";

    mysqli_query($db_connection, $createTriggerQuery);
}

// Book History 11
// Might change to tbl_logs, not sure yet.
if (!isDBTableExist($dbname, 'tbl_books_history')) {
    $createTableQuery = "CREATE TABLE tbl_books_history (
    history_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    book_id INT(11),
    change_type ENUM('Insert', 'Update', 'Delete'),
    change_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT(11),
    old_data JSON, 
    new_data JSON, 
    FOREIGN KEY (book_id) REFERENCES tbl_books(book_id),
    FOREIGN KEY (user_id) REFERENCES tbl_users(user_id))";

    mysqli_query($db_connection, $createTableQuery);
}

// Book Programs 12
if (!isDBTableExist($dbname, 'tbl_book_programs')) {
    $createTableQuery = "CREATE TABLE tbl_book_programs (
        book_id INT(11),
        program_id INT(11),
        FOREIGN KEY (book_id) REFERENCES tbl_books(book_id),
        FOREIGN KEY (program_id) REFERENCES tbl_programs(program_id))";

    mysqli_query($db_connection, $createTableQuery);
}

// Book Strands 13
if (!isDBTableExist($dbname, 'tbl_book_strands')) {
    $createTableQuery = "CREATE TABLE tbl_book_strands (
        book_id INT(11),
        strand_id INT(11),
        FOREIGN KEY (book_id) REFERENCES tbl_books(book_id),
        FOREIGN KEY (strand_id) REFERENCES tbl_strands(strand_id))";

    mysqli_query($db_connection, $createTableQuery);
}

// // NEUST Receipt 14
// if (!isDBTableExist($dbname, 'tbl_receipts')) {
//     $createTableQuery = "CREATE TABLE tbl_receipts (
//         receipt_id INT(11) AUTO_INCREMENT PRIMARY KEY,
//         receipt_number VARCHAR(100),
//         agency_name VARCHAR(100),
//         transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- removed from tbl_transactions
//         total_amount DECIMAL(10, 2), -- removed from tbl_transactions
//         paid_amount DECIMAL(10, 2), -- removed from tbl_transactions
//         change_amount DECIMAL(10, 2),  -- removed from tbl_transactions
//         amount_in_words VARCHAR(100),
//         payment_method VARCHAR(100))";

//     mysqli_query($db_connection, $createTableQuery);
// } 

// // Transaction 15
// if (!isDBTableExist($dbname, 'tbl_transactions')) {
//     $createTableQuery = "CREATE TABLE tbl_transactions (
//         transaction_id INT(11) AUTO_INCREMENT PRIMARY KEY,
//         customer_id INT(11),
//         teacher_id INT(11),
//         user_id INT(11),
//         semester_id INT(11),
//         receipt_id INT(11),
//         is_void TINYINT (1) NOT NULL DEFAULT 0,
//         FOREIGN KEY (customer_id) REFERENCES tbl_customers(customer_id),
//         FOREIGN KEY (teacher_id) REFERENCES tbl_teachers(teacher_id),
//         FOREIGN KEY (user_id) REFERENCES tbl_users(user_id),
//         FOREIGN KEY (semester_id) REFERENCES tbl_semesters(semester_id),
//         FOREIGN KEY (receipt_id) REFERENCES tbl_receipts(receipt_id))";

//     mysqli_query($db_connection, $createTableQuery);
// }

// Transaction 16
if (!isDBTableExist($dbname, 'tbl_transactions')) {
    $createTableQuery = "CREATE TABLE tbl_transactions (
        transaction_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        customer_id INT(11),
        teacher_id INT(11),
        user_id INT(11),
        semester_id INT(11),
        transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        total_amount DECIMAL(10, 2),
        paid_amount DECIMAL(10, 2),
        change_amount DECIMAL(10, 2), 
        is_void TINYINT (1) NOT NULL DEFAULT 0,
        FOREIGN KEY (customer_id) REFERENCES tbl_customers(customer_id),
        FOREIGN KEY (user_id) REFERENCES tbl_users(user_id),
        FOREIGN KEY (semester_id) REFERENCES tbl_semesters(semester_id))";

    mysqli_query($db_connection, $createTableQuery);
}

// NEUST Receipt 18
if (!isDBTableExist($dbname, 'tbl_receipts')) {
    $createTableQuery = "CREATE TABLE tbl_receipts (
        receipt_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        transaction_id INT(11),
        receipt_number VARCHAR(100),
        agency_name VARCHAR(100),
        amount_in_words VARCHAR(100),
        payment_method VARCHAR(100),
        FOREIGN KEY (transaction_id) REFERENCES tbl_transactions(transaction_id))";

    mysqli_query($db_connection, $createTableQuery);
}

// Transaction Details 16
if (!isDBTableExist($dbname, 'tbl_transactiondetails')) {
    $createTableQuery = "CREATE TABLE tbl_transactiondetails (
    transaction_detail_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT(11),
    book_id INT(11),
    quantity INT(11),
    subtotal DECIMAL(10, 2),
    FOREIGN KEY (transaction_id) REFERENCES tbl_transactions(transaction_id),
    FOREIGN KEY (book_id) REFERENCES tbl_books(book_id))";

    mysqli_query($db_connection, $createTableQuery);
}






// THE DATABASE AND FUNCTIONS..
// OLD DATABASE
// date_default_timezone_set('Asia/Manila');

// $host = 'localhost';
// $user = 'root';
// $password = '';
// $dbname = 'finalcapstone';
// global $db_connection;
// global $db;
// $db_connection = mysqli_connect($host,$user,$password,$dbname) or die('Failed to connect to database server');
// $db = mysqli_select_db($db_connection,$dbname);

// if($db_connection) {

// }else {
//     die(mysqli_error($db_connection));
// }

// // QUERY MAKER

// function GetValue($sql_query) {
//     global $db_connection;
//     $result = mysqli_query($db_connection,$sql_query);
//     $row = mysqli_fetch_array($result);
//     return $row[0];
// }

// // IF Database Exist, Execute

// function isDBTableExist($dbname,$table) {
//     return GetValue("SELECT COUNT(*)
//     FROM information_schema.tables
//     WHERE table_schema = '".$dbname."'
//             AND table_name = '".$table."'
//         LIMIT 1;") + 0;
// }

// // For counting in staffadmins.php

//     function countActiveAdminAccounts($db_connection) {
//     $query = "SELECT COUNT(*) AS active_admins_count FROM tbl_users WHERE status = 'active'";
//     $result = mysqli_query($db_connection, $query);

//     if (!$result) {
//         die("Query failed: " . mysqli_error($db_connection));
//     }

//     $row = mysqli_fetch_assoc($result);
//     return $row['active_admins_count'];
// }

// $neustYearLevels = array(
//     "Grade 7",
//     "Grade 8",
//     "Grade 9",
//     "Grade 10",
//     "Grade 11",
//     "Grade 12",
//     "1st Year College",
//     "2nd Year College",
//     "3rd Year College",
//     "4th Year College",
//     // Add more college names here
// );

// $neustYearType = array(
//     "High School",
//     "High School",
//     "High School",
//     "High School",
//     "Senior High",
//     "Senior High",
//     "College",
//     "College",
//     "College",
//     "College",
// );

// $neustColleges= array(
//     "College of Architecture",
//     "College of Criminology",
//     "College of Education",
//     "College of Engineering",
//     "College of Information and Communications Technology",
//     "College of Management and Business Technology",
//     "College of Public Administration and Disaster Management",
//     "College of Arts and Sciences",
//     "College of Industrial Technology",
//     "College of Nursing",

//     // Add more college names here
// );

// $neustPrograms = array(
//     array("Bachelor of Science in Architecture", 1),
//     array("Bachelor of Science in Criminology", 2), 
//     array("Bachelor of Science in Civil Engineering", 4),
//     array("Bachelor of Science in Electrical Engineering", 4),
//     array("Bachelor of Science in Mechanical Engineering", 4),
//     array("Bachelor of Science in Information Technology", 5),
//     array("Bachelor of Science in Business Administration", 6),
//     array("Bachelor of Science in Entrepreneurship", 6),
//     array("Bachelor of Science in Hospitality Management", 6),
//     array("Bachelor of Science in Hotel and Restaurant Management", 6),
//     array("Bachelor of Science in Tourism Management", 6),
//     array("Bachelor of Public Administration", 7),
//     array("Bachelor of Public Administration Major in Disaster Management", 7),
//     array("Bachelor of Science in Biology", 8),
//     array("Bachelor of Science in Food Technology", 8),
//     array("Bachelor of Science in Psychology", 8),
//     array("Bachelor of Science in Chemistry", 8),
//     array("Bachelor of Science in Environmental Science", 8),
//     array("Bachelor of Industrial Technology", 9),
//     array("Bachelor of Electronics and Communication Engineering Technology", 9),
//     array("Bachelor of Science in Nursing", 10),

// );

// $neustStrands = array(
//     "Science, Technology, Engineering and Mathematics",
//     "Accountancy, Business and Management",

// );

// // YearLevels 1
// if (!isDBTableExist($dbname, 'tbl_yearlevels')) {
//     $createTableQuery = "CREATE TABLE tbl_yearlevels (
//         year_level_id INT(11) AUTO_INCREMENT PRIMARY KEY,
//         year_level_type ENUM('High School', 'Senior High', 'College') NOT NULL,  
//         year_level_name VARCHAR(50) NOT NULL)";

//     mysqli_query($db_connection, $createTableQuery);

//     // Insert Years
//     for ($i = 0; $i < count($neustYearLevels); $i++) {
//         $yearlevelType = $neustYearType[$i];
//         $yearlevelName = $neustYearLevels[$i];

//         $insertQuery = "INSERT INTO tbl_yearlevels (year_level_type, year_level_name) VALUES ('$yearlevelType', '$yearlevelName')";
//         mysqli_query($db_connection, $insertQuery);
//     }
// }

// // Semesters 2
// if (!isDBTableExist($dbname, 'tbl_semesters')) {
//     $createTableQuery = "CREATE TABLE tbl_semesters (
//         semester_id INT(11) AUTO_INCREMENT PRIMARY KEY,
//         semester_name VARCHAR(50) NOT NULL,
//         is_current TINYINT(1) NOT NULL DEFAULT 0)";

//     mysqli_query($db_connection, $createTableQuery);
// }

// // Subjects 3
// if (!isDBTableExist($dbname, 'tbl_subjects')) {
//     $createTableQuery = "CREATE TABLE tbl_subjects (
//         subject_id INT(11) AUTO_INCREMENT PRIMARY KEY,
//         subject_name VARCHAR(255) NOT NULL,
//         subject_code VARCHAR(20) NOT NULL)";

//     mysqli_query($db_connection, $createTableQuery);
// }

// // Colleges 4
// if (!isDBTableExist($dbname, 'tbl_colleges')) {
//     $createTableQuery = "CREATE TABLE tbl_colleges (
//         college_id INT(11) AUTO_INCREMENT PRIMARY KEY,
//         college_name VARCHAR(100) NOT NULL)";

//     mysqli_query($db_connection, $createTableQuery);

//     // Insert College
//     foreach ($neustColleges as $collegeName) {
//         $insertQuery = "INSERT INTO tbl_colleges (college_name) VALUES ('$collegeName')";
//         mysqli_query($db_connection, $insertQuery);
//     }
// }

// // Programs 5
// if (!isDBTableExist($dbname, 'tbl_programs')) {
//     $createTableQuery = "CREATE TABLE tbl_programs (
//         program_id INT(11) AUTO_INCREMENT PRIMARY KEY,
//         program_name VARCHAR(100) NOT NULL,
//         college_id INT(11),
//         FOREIGN KEY (college_id) REFERENCES tbl_colleges(college_id))";

//     mysqli_query($db_connection, $createTableQuery);

//     foreach ($neustPrograms as $programData) {
//         $programName = $programData[0];
//         $collegeID = $programData[1];

//         $insertQuery = "INSERT INTO tbl_programs (program_name, college_id) VALUES ('$programName', $collegeID)";
//         mysqli_query($db_connection, $insertQuery);
//     }
// }

// // Customers 7
// if (!isDBTableExist($dbname, 'tbl_customers')) {
//     $createTableQuery = "CREATE TABLE tbl_customers (
//     customer_id INT(11) AUTO_INCREMENT PRIMARY KEY,
//     customer_type ENUM('Student', 'Non-Student') NOT NULL,
//     student_number VARCHAR(100) UNIQUE,
//     first_name VARCHAR(50) NOT NULL,
//     last_name VARCHAR(50) NOT NULL,
//     email VARCHAR(100),
//     phone_number VARCHAR(100))";
// mysqli_query($db_connection, $createTableQuery);
// }

// // Teachers 8
// if (!isDBTableExist($dbname, 'tbl_teachers')) {
//     $createTableQuery = "CREATE TABLE tbl_teachers (
//         teacher_id INT(11) AUTO_INCREMENT PRIMARY KEY,
//         first_name VARCHAR(50) NOT NULL,
//         last_name VARCHAR(50) NOT NULL)";

//     mysqli_query($db_connection, $createTableQuery);
// }

// // Users 9
// if (!isDBTableExist($dbname, 'tbl_users')) {
//     $createTableQuery = "CREATE TABLE tbl_users (
//         user_id INT(11) AUTO_INCREMENT PRIMARY KEY,
//         first_name VARCHAR(100) NOT NULL,
//         last_name VARCHAR(100) NOT NULL,
//         username VARCHAR(50) NOT NULL,
//         reset_token VARCHAR(150) DEFAULT '',
//         password VARCHAR(255) NOT NULL,
//         role VARCHAR(10) NOT NULL,
//         status ENUM('active', 'inactive') DEFAULT 'active')";

//     mysqli_query($db_connection, $createTableQuery);
// }

// // Strands 10
// if (!isDBTableExist($dbname, 'tbl_strands')) {
//     $createTableQuery = "CREATE TABLE tbl_strands (
//         strand_id INT(11) AUTO_INCREMENT PRIMARY KEY,
//         strand_name VARCHAR(100) NOT NULL)";

//     mysqli_query($db_connection, $createTableQuery);

//         // Insert Strands
//         foreach ($neustStrands as $strandName) {
//             $insertQuery = "INSERT INTO tbl_strands (strand_name) VALUES ('$strandName')";
//             mysqli_query($db_connection, $insertQuery);
//         }
//     }


// // Books 11
// if (!isDBTableExist($dbname, 'tbl_books')) {
//     $createTableQuery = "CREATE TABLE tbl_books (
//         book_id INT(11) AUTO_INCREMENT PRIMARY KEY,
//         title TEXT NOT NULL,
//         book_image VARCHAR(100),
//         author VARCHAR(100) NOT NULL,
//         publication_year INT(4),
//         quantity_available INT(11),
//         price DECIMAL(10, 2),
//         subject_id INT(11),
//         year_level_id INT(11),
//         status ENUM('Active', 'Inactive') DEFAULT 'Active',
//         FOREIGN KEY (year_level_id) REFERENCES tbl_yearlevels(year_level_id),
//         FOREIGN KEY (subject_id) REFERENCES tbl_subjects(subject_id))";

//     mysqli_query($db_connection, $createTableQuery);
// }


// $triggerName = 'update_status_on_zero_quantity';
// $checkTriggerQuery = "
// SELECT COUNT(*)
// FROM information_schema.triggers
// WHERE trigger_schema = '$dbname'
//     AND trigger_name = '$triggerName';
// ";

// $result = mysqli_query($db_connection, $checkTriggerQuery);
// $row = mysqli_fetch_row($result);

// if ($row[0] == 0) {
//     $createTriggerQuery = "
//     CREATE TRIGGER $triggerName
//     BEFORE UPDATE ON tbl_books
//     FOR EACH ROW
//     BEGIN
//         IF NEW.quantity_available = 0 THEN
//             SET NEW.status = 'Inactive';
//         END IF;
//     END;
//     ";

//     mysqli_query($db_connection, $createTriggerQuery);
// }

// // Book History 12
// if (!isDBTableExist($dbname, 'tbl_books_history')) {
//     $createTableQuery = "CREATE TABLE tbl_books_history (
//     history_id INT(11) AUTO_INCREMENT PRIMARY KEY,
//     book_id INT(11),
//     change_type ENUM('Insert', 'Update', 'Delete'),
//     change_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     user_id INT(11),
//     old_data JSON, 
//     new_data JSON, 
//     FOREIGN KEY (book_id) REFERENCES tbl_books(book_id),
//     FOREIGN KEY (user_id) REFERENCES tbl_users(user_id))";

//     mysqli_query($db_connection, $createTableQuery);
// }

// // Book Programs 13
// if (!isDBTableExist($dbname, 'tbl_book_programs')) {
//     $createTableQuery = "CREATE TABLE tbl_book_programs (
//         book_id INT(11),
//         program_id INT(11),
//         FOREIGN KEY (book_id) REFERENCES tbl_books(book_id),
//         FOREIGN KEY (program_id) REFERENCES tbl_programs(program_id))";

//     mysqli_query($db_connection, $createTableQuery);
// }

// // Book Strands 14
// if (!isDBTableExist($dbname, 'tbl_book_strands')) {
//     $createTableQuery = "CREATE TABLE tbl_book_strands (
//         book_id INT(11),
//         strand_id INT(11),
//         FOREIGN KEY (book_id) REFERENCES tbl_books(book_id),
//         FOREIGN KEY (strand_id) REFERENCES tbl_strands(strand_id))";

//     mysqli_query($db_connection, $createTableQuery);
// }

// // Transaction 16
// if (!isDBTableExist($dbname, 'tbl_transactions')) {
//     $createTableQuery = "CREATE TABLE tbl_transactions (
//         transaction_id INT(11) AUTO_INCREMENT PRIMARY KEY,
//         customer_id INT(11),
//         teacher_id INT(11),
//         user_id INT(11),
//         semester_id INT(11),
//         transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//         total_amount DECIMAL(10, 2),
//         paid_amount DECIMAL(10, 2),
//         change_amount DECIMAL(10, 2), 
//         is_void TINYINT (1) NOT NULL DEFAULT 0,
//         FOREIGN KEY (customer_id) REFERENCES tbl_customers(customer_id),
//         FOREIGN KEY (user_id) REFERENCES tbl_users(user_id),
//         FOREIGN KEY (semester_id) REFERENCES tbl_semesters(semester_id))";

//     mysqli_query($db_connection, $createTableQuery);
// }

// // Transaction Details 17
// if (!isDBTableExist($dbname, 'tbl_transactiondetails')) {
//     $createTableQuery = "CREATE TABLE tbl_transactiondetails (
//     transaction_detail_id INT(11) AUTO_INCREMENT PRIMARY KEY,
//     transaction_id INT(11),
//     book_id INT(11),
//     quantity INT(11),
//     subtotal DECIMAL(10, 2),
//     FOREIGN KEY (transaction_id) REFERENCES tbl_transactions(transaction_id),
//     FOREIGN KEY (book_id) REFERENCES tbl_books(book_id))";

//     mysqli_query($db_connection, $createTableQuery);
// }

// // NEUST Receipt 18
// if (!isDBTableExist($dbname, 'tbl_receipts')) {
//     $createTableQuery = "CREATE TABLE tbl_receipts (
//         receipt_id INT(11) AUTO_INCREMENT PRIMARY KEY,
//         transaction_id INT(11),
//         receipt_number VARCHAR(100),
//         agency_name VARCHAR(100),
//         amount_in_words VARCHAR(100),
//         payment_method VARCHAR(100),
//         FOREIGN KEY (transaction_id) REFERENCES tbl_transactions(transaction_id))";

//     mysqli_query($db_connection, $createTableQuery);
// }
