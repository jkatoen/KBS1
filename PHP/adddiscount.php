<?php
session_start();
// Connect database
include ("connectdb.php");
include ("functions.php");
$discount_code = $_POST['discount_code'];
function checkDiscount($connection, $discount_code) {
    $stmt = mysqli_prepare($connection, "SELECT discountpercentage FROM discount WHERE discountcode = ?");
    mysqli_stmt_bind_param($stmt, "s", $discount_code);
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
checkDiscount($connection, $discount_code);