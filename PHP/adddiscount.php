<?php
session_start();
// Connect database
include ("connectdb.php");
include ("functions.php");

if(isset($_POST['discount'])){
    echo $_POST['discount'] ;
}
