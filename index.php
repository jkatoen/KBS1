<?php
session_start();
include("PHP/connectdb.php");
include("PHP/functions.php");

$uri = getURI(); // Get uri of page
include ("header.php");

//$username = $_POST["naam"];
//$checkuname = $connection -> prepare("SELECT FirstName FROM Gebruikers WHERE FirtName = ?");
//$checkuname->bind_param('s', $username);
//$checkuname->execute();
//$result = mysqli_stmt_get_result($checkuname);
//foreach ($result as $r) {
//    $resultUsername = $r['FirstName'];
//}
//$_SESSION = $username;
//$checkuname->close();


?>

<body>
<div class="row">
    <div class="leftcolumn">
        <div class="card-left">
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

</body>
<footer class="footer">
    <h3>© Copyrights 2019 - World Wide Importers</h3>
</footer>
</html>