<?php
session_start();
print_r($_POST);

$_SESSION["lastname"] = $_POST["lastname"];
$_SESSION["firstname"] = $_POST["firstname"];
$_SESSION["address"] = $_POST["address"];
$_SESSION["email"] = $_POST["email"];
$_SESSION["postalcode"] = $_POST["postalcode"];
