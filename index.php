<?php
session_start();
include_once("php/connectdb.php");
include("PHP/functions.php");

$sql2 = "select stockgroupid, stockgroupname, count(*) from stockgroups
         join  stockitemstockgroups using(stockgroupid)
         join stockitems using(stockitemid)
         group by stockgroupname";
$result2 = mysqli_query($connection,$sql2);

$category = isCategorySet();
$_SESSION['category'] = $category;


if (!empty($_SERVER['HTTPS']) && ('on' === $_SERVER['HTTPS'])) {
    $uri = 'https://';
} else {
    $uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];

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