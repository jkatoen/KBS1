<?php
session_start();
if(isset($_GET["email"] ) &&
     isset($_GET["passwd"])){
    $_SESSION["ingelogd"] = true;
    $_SESSION["email"] = $_GET["email"];
    header('location: index.php');
    exit();
} else {
    header('Location: login.php');
    exit();
}
?>