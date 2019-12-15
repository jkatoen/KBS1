<?php
session_start();
include("PHP/connectdb.php");
include("PHP/functions.php");
addToCart();
include ("header.php");
$uri = getURI(); // Get uri of page

?>

<body>
<div class="row">
    <div class="leftcolumn">
        <div class="card-left">
            <h2>Categorieën</h2>
            <div class="category-container">
                <?php displayLeftCategories($connection); ?>
            </div>
        </div>
    </div>
    <div class="midcolumn">
        <div class="card">
            <?php
            if (isset($_SESSION["ingelogd"])) {
            ?>
            <h3>Welkom: <?php print($_SESSION["firstname"]);?></h3>
            <h2>Speciale aanbiedingen</h2>
            <?php
            }
            else {
            ?>
            <h2>Speciale aanbiedingen</h2>
            <?php
            }
            ?>
            <div class="product-container">
                <?php
                DisplaySpecialItems($connection);
                ?>
            </div>
        </div>
    </div>
</div>

</body>