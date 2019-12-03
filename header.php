<?php
include ("PHP/connectdb.php");
// Als pagina niet search.php is hoeft er geen sessie met search zijn
if (strpos($_SERVER['SCRIPT_NAME'], 'search.php') === false) {
    if (isset($_SESSION['searchinput'])) {
        unset($_SESSION['searchinput']);
    }
}
//print_r($_SESSION);
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
        <a href="cart.php"><img src="IMG/winkelmand.png" width="65" height="59.5"></a>
        <?php
        if (isset($_SESSION["ingelogd"])) {
//            echo "Welcome ".$_SESSION["name"]." !";
            ?>
            <a href="logout.php"><h3>Log uit</h3></a>
            <?php
        }
        else {
            ?>
        <a href="login.php"><h3>Log in</h3></a>
        <a href="accaanmaken.php"><h3>Account aanmaken</h3></a>
        <?php
        }
        ?>
        <a href="contact.php"><h3>Contact</h3></a>
        <form class="nav-search" method="get" action="search.php">
            <input class="text" type="text" name="searchinput">
            <input type="submit" name="submitinput" value="Search">
        </form>
    </div>
</head>
</html>