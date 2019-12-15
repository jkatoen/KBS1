<?php
include ("PHP/connectdb.php");
// Als pagina niet search.php is hoeft er geen sessie met search zijn
if (strpos($_SERVER['SCRIPT_NAME'], 'search.php') === false) {
    if (isset($_SESSION['searchinput'])) {
        unset($_SESSION['searchinput']);
    }
}

if (strpos($_SERVER['SCRIPT_NAME'], 'payment.php') === false) {
    if (isset($_SESSION['discountPercentage'])) {
        unset($_SESSION['discountPercentage']);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="CSS/mystyle.css">
    <link rel="stylesheet" type="text/css" href="CSS/Styling.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="JS/myscript.js"></script>
    <div class="wwi">
        <a href="index.php"><img src="IMG/wwi-logo.png"></a>
    </div>
    <div class="header">
        <div class="head-left">
            <form class="nav-search" method="get" action="search.php">
                <input class="text" type="text" name="searchinput">
                <input class="submit-search" type="submit" name="submitinput" value="Zoeken">
            </form>
        </div>
        <div class="head-right">
            <a href="cart.php"><img style="margin-top: 13px" src="IMG/winkelmand.png" height="50"></a>
            <a href="favorites.php"><h4>Favorieten</h4></a>
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
        </div>
    </div>
</head>
</html>