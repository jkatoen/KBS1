<?php
session_start();
include("PHP/connectdb.php");
include("PHP/functions.php");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="CSS/mystyle.css">
    <div class="header">
        <a href="index.php"><img src="IMG/wwi-logo.png"></a>
    </div>
    <div class="topnav">
        <a href="cart.php"><img src="IMG/winkelmand.png" width="65" height="56"></a>
        <a href="account.php"><h3>Login</h3></a>
        <a href="contact.php"><h3>Contact</h3></a>

        <form class="nav-search" method="get" action="search.php">
            <input class="text" type="text" name="searchinput">
            <input type="submit" name="submitinput" value="Search">
        </form>
    </div>
</head>
<body>
<div class="row">

    <div class="leftcolumn">
        <div class="card">
            <h2>Category</h2>
            <div class="category-container">
                <?php displayLeftCategories($connection); ?>
            </div>
        </div>
    </div>
    <div class="midcolumn">
        <div class="inlog" class="inlog">
            <h1>Account aanmaken</h1>
            <form action="account.php" method="POST">
                Voornaam <br><input type="text" name="voornaam" class="textinbox" value="" required/><br><br>
                Achternaam <br><input type="text" name="achternaam" class="textinbox" value="" required/><br><br>
                Adres <br><input type="text" name="adres" class="textinbox" value="" required/><br><br>
                Wachtwoord <br><input type="password" name="ww" class="textinbox" value="" required/><br><br>
                Emailadres <br><input type="email" name="emailadres" class="textinbox" value="" required/><br><br><br>
                <input type="submit" value="Account aanmaken" name="register" class="button" required/>
            </form>
            <div class="product-container">
                <?php
                if (isset($_POST['register'])) {
                    echo "dsf";
                    if (isset($_POST["voornaam"]) && isset($_POST["achternaam"]) && isset($_POST["adres"]) && isset($_POST["ww"]) && isset($_POST["emailadres"])) {
                        print("Account aangemaakt!");
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="footer">
    <h2>Footer</h2>
</div>

</body>
</html>
