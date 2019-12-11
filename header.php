<?php
include ("PHP/connectdb.php");
// Als pagina niet search.php is hoeft er geen sessie met search zijn
if (strpos($_SERVER['SCRIPT_NAME'], 'search.php') === false) {
    if (isset($_SESSION['searchinput'])) {
        unset($_SESSION['searchinput']);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="CSS/mystyle.css">
    <script src="JS/myscript.js"></script>
    <div class="header">
        <a href="index.php"><img src="IMG/wwi-logo.png"></a>
    </div>
    <div class="topnav">
        <a href="cart.php"><img style="margin-top: 3px" src="IMG/winkelmand.png" width="55" height="49.5"></a>
        <a href="contact.php"><h4>Contact</h4></a>
        <?php
        if (isset($_SESSION["ingelogd"])) {
            ?>
            <a href="logout.php"><h4>Log uit</h4></a>
            <?php
        }
        else {
            ?>
        <a href="login.php"><h4>Log in</h4></a>
        <a href="accaanmaken.php"><h4>Account aanmaken</h4></a>
        <?php
        }
        ?>
        <form class="nav-search" method="get" action="search.php">
            <input class="text" type="text" name="searchinput">
            <input type="submit" name="submitinput" value="Search">
        </form>
    </div>
</head>
</html>