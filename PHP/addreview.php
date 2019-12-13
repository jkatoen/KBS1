<?php
session_start();
// Connect database
include ("connectdb.php");
include ("functions.php");
// Set variables
$userID = $_SESSION["accountID"];
$itemID = $_POST["item_id"];
$userRating = $_POST["user_rating"];
$userReview = $_POST["user_review"];
//echo "$itemID, $userID, $userRating, $userReview";
// Insert info in database for table 'review'
function insertReview($connection, $itemID, $userID, $userRating, $userReview) {
    $stmt = mysqli_prepare($connection, "INSERT INTO review (StockItemID, AccountID, Rating, Review) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iiis", $itemID, $userID, $userRating, $userReview);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
insertReview($connection, $itemID, $userID, $userRating, $userReview);