
<?php
session_start();
include("../PHP/connectdb.php");
include("../PHP/functions.php");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../CSS/mystyle.css">
    <div class="header">
        <a href="../index.php"><img src="../IMG/wwi-logo.png"></a>
    </div>
    <div class="topnav">
        <a href="cart.php"><img src="../IMG/winkelmand.png" width="65" height="56"></a>
        <a href="../AccountManegement/accaanmaken.php"><h3>Login</h3></a>
        <a href="../contact.php"><h3>Contact</h3></a>

        <form class="nav-search" method="get" action="../search.php">
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

        <div class="Checkout" class="Checkout">
            <h1>Checkout</h1>
            <form action="https://www.ideal.nl/" method="POST">
                Voornaam <br><input type="text" name="voornaam" class="textinbox" value="" required/><br><br>
                Achternaam <br><input type="text" name="achternaam" class="textinbox" value="" required/><br><br>
                Adres <br><input type="text" name="adres" class="textinbox" value="" required/><br><br>
                Emailadres <br><input type="email" name="emailadres" class="textinbox" value="" required/><br><br><br>

                <link rel="stylesheet" type="text/css" href="../CSS/mystyle.css">
                <div class="header">
                   <img src="../IMG/iDeal.png" width="20%"
                <br><br><br>
                <input type="submit" value="Pay with iDeal"  class="button" required/>

            </form>




            </div>
        </div>
    </div>
</div>

<div class="footer">
    <h2>Footer</h2>
</div>

</body>
</html>
