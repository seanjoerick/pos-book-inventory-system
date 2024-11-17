<?php

    include_once '../database.php'; 

    function getCurrentSemesterId() {
        global $db_connection;
    
        // Query to get the current semester id
        $sql = "SELECT semester_id FROM tbl_semesters WHERE is_current = 1";
        $result = mysqli_query($db_connection, $sql);
    
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['semester_id'];
        } else {
            // Handle the case where the query didn't return any rows
            return null;
        }
    }

    function getSemesterName($semester_id) {
        global $db_connection;
    
        // Query to get the semester name based on semester_id
        $sql = "SELECT semester_name FROM tbl_semesters WHERE semester_id = ?";
        $stmt = mysqli_prepare($db_connection, $sql);
        mysqli_stmt_bind_param($stmt, "i", $semester_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $semester_name);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    
        return $semester_name;
    }
    
    function convertNumberToWords($number)
{
    $words = '';

    $digits = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
    $tens = ['', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
    $teens = ['', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];

    $num = (int)$number;

    if ($num === 0) {
        return 'Zero Pesos';
    }

    // Split the number into groups of three digits
    $billions = floor($num / 1000000000);
    $millions = floor(($num - $billions * 1000000000) / 1000000);
    $thousands = floor(($num - $billions * 1000000000 - $millions * 1000000) / 1000);
    $remainder = $num - $billions * 1000000000 - $millions * 1000000 - $thousands * 1000;

    if ($billions > 0) {
        $words .= convertThreeDigitGroup($billions) . ' Billion ';
    }

    if ($millions > 0) {
        $words .= convertThreeDigitGroup($millions) . ' Million ';
    }

    if ($thousands > 0) {
        $words .= convertThreeDigitGroup($thousands) . ' Thousand ';
    }

    if ($remainder > 0) {
        $words .= convertThreeDigitGroup($remainder);
    }

    return trim($words) . ' Pesos';
}

function convertThreeDigitGroup($num)
{
    $words = '';

    $digits = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
    $tens = ['', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
    $teens = ['', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];

    $tempNum = (int)$num;

    $hundreds = floor($tempNum / 100);
    $tensAndOnes = $tempNum % 100;

    if ($hundreds > 0) {
        $words .= $digits[$hundreds] . ' Hundred ';
    }

    if ($tensAndOnes > 0) {
        if ($tensAndOnes < 10) {
            $words .= $digits[$tensAndOnes];
        } elseif ($tensAndOnes < 20) {
            $words .= $teens[$tensAndOnes - 10];
        } else {
            $words .= $tens[floor($tensAndOnes / 10)] . ' ' . $digits[$tensAndOnes % 10];
        }
    }

    return trim($words);
}

function getUserDetails($db_connection, $user_id) {
    $query = "SELECT first_name, last_name FROM tbl_users WHERE user_id = ?";
    $stmt = $db_connection->prepare($query);
    
    // Check if the prepare statement was successful
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($row = $result->fetch_assoc()) {
            return $row;
        } else {
            return false; // User not found
        }
    } else {
        return false; // Error in preparing the statement
    }
}