<?php
session_start();
include("PHP/connectdb.php");
include("PHP/functions.php");

$uri = getURI(); // Get uri of page

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="CSS/mystyle.css">
    <div class="header">
        <a href="index.php"><img src="IMG/wwi-logo.png"></a>
    </div>
    <div class="topnav">
<<<<<<< HEAD
        <a href="cart.php"><img src="IMG/winkelmand.png" width="65" height="56"></a>
        <a href="account.php"><h3>Account aanmaken</h3></a>
=======
        <a href="cart.php"><img src="IMG/winkelmand.png" width="65" height="59.5"></a>
        <a href="login.php"><h3>Login</h3></a>
>>>>>>> 09451f54567af19affc4cfd38a977fdf2ab9f650
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
        <div class="card">
            <h2>Special Deals</h2>
            <div class="product-container">
                <?php
                DisplaySpecialItems($connection);

                ?>
            </div>
        </div>
    </div>
</div>

</div>

</body>
</html>