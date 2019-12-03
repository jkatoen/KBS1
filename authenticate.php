<?php
session_start();
include("PHP/functions.php");
include("PHP/connectdb.php");

if(isset($_POST["email"] ) && isset($_POST["password"])) {
    // Check if email and password match,
    // if they match, log in.

    // Get the password from associated to email adress to compare with input password
    $email = $_POST["email"];
    $pass = $_POST["password"];
    $checkSQL = $connection->prepare("SELECT Emailadres, Password FROM gebruikers WHERE Emailadres = ?");
    $checkSQL->bind_param("s", $email);
    $checkSQL->execute();
    $result = mysqli_stmt_get_result($checkSQL);
    foreach ($result as $r) {
        $resultPassword = $r['password'];
    }
    $checkSQL->close();
    // Compare passwords
    if(password_verify($pass, $resultPassword)) {
        // Log in and return to home page
        $_SESSION["ingelogd"] = true;
        $_SESSION["email"] = $_POST["email"];
        header('location: index.php');
        exit();
    } else {
        // Don't log in and return something else?
        header('Location: login.php');
        exit();
    }
    // If they don't match, don't log in and return something.
} else {
    header('Location: login.php');
    exit();
}
?>