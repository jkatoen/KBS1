<?php
session_start();
// Connect database
include ("connectdb.php");
include ("functions.php");
$discount_code = $_POST['discount_code'];
function checkDiscount($connection, $discount_code) {
    $stmt = mysqli_prepare($connection, "SELECT discountpercentage FROM discount WHERE discountcode = ?");
    mysqli_stmt_bind_param($stmt, "i", $discount_code);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) != 0) {
        mysqli_stmt_bind_result($stmt, $discountpercentage);
        while (mysqli_stmt_fetch($stmt)) {
            echo $discountpercentage;
        }
    }
}
checkDiscount($connection, $discount_code);