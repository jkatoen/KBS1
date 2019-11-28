<?php
session_start();
include("PHP/connectdb.php");
include("PHP/functions.php");

$uri = getURI(); // Get uri of page

if ($_POST['ingelogd'] == true) {
    include ("header2.php");
}
else {
    include ("header.php");
}

?>

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
                DisplaySpecialItems($connection); ?>
            </div>
        </div>
    </div>
</div>
</div>

</body>
</html>