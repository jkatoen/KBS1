<?php
session_start();
// Connect database
include ("connectdb.php");
include ("functions.php");

$todaydate = date("Y-m-d");
$discount_code = $_POST['discount_code'];
function checkDiscount($connection, $discount_code, $todaydate) {
    $stmt = mysqli_prepare($connection, "SELECT discountpercentage FROM discount WHERE discountcode = ? AND ? BETWEEN discountstartdate AND discountendate")or die(mysqli_error($connection));
    mysqli_stmt_bind_param($stmt, "ss", $discount_code, $todaydate);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    // Als er resultaat is, is er korting!
    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $discountpercentage);
        while (mysqli_stmt_fetch($stmt)) {
            $_SESSION["discountPercentage"] = $discountpercentage;
            echo $discountpercentage;
        }
        mysqli_stmt_close($stmt);
    // Geen resultaat, geen korting!
    } else {
        mysqli_stmt_close($stmt);
        echo "";
    }
}
checkDiscount($connection, $discount_code, $todaydate);