<?php
session_start();
unset($_SESSION['ingelogd']);
unset($_SESSION['email']);
header('Location: index.php');
exit();