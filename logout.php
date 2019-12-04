<?php
session_start();
unset($_SESSION['ingelogd']);
unset($_SESSION["email"]);
unset($_SESSION["firstname"]);
unset($_SESSION["lastname"]);
unset($_SESSION["address"]);
//session_destroy();
header('Location: index.php');
?>