<?php
include_once("php/connectdb.php");
include ("PHP/functions.php");
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
        <a><h3>Login</h3></a>
        <a><h3>Contact</h3></a>
        <form class="nav-search" method="get" action="">
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
        <?php
        $sql = "SELECT StockItemId, StockItemName, MarketingComments, UnitPrice, TaxRate, Photo, SupplierName, QuantityOnHand
                       FROM stockitems Stock
                       JOIN suppliers USING (SupplierID)
                       JOIN stockitemholdings USING (StockItemID)
                       WHERE StockItemID = {$_GET['id']}";
        $statement = mysqli_prepare($connection, $sql);
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        $StockGroupID_sql = "SELECT StockGroupId FROM stockitemstockgroups WHERE StockItemId = {$_GET['id']}";
        $StockGroupID_stmt = mysqli_prepare($connection, $StockGroupID_sql);
        mysqli_stmt_execute($StockGroupID_stmt);
        $StockGroupID_result = mysqli_stmt_get_result($StockGroupID_stmt);
        $StockGroupID_row = mysqli_fetch_assoc($StockGroupID_result);
        $StockGroupID = $StockGroupID_row['StockGroupId'];
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            /*if (!empty($row['Photo'])) {
                echo "<img style='height: 200px;' src='data:image/jpeg;base64,".base64_encode( $row['Photo'] )."'/>";
            } else {
                echo "<img style='height: 100px; width:100px;' src='IMG/category{$StockGroupID}.png'/>";
            }*/
            $print_name = "<h2>" . "Product: " . $row["StockItemName"] . "</h2>" . "<br>";
            $print_supplier = "Supplier: " . $row["SupplierName"] . "<br>";
            $print_marketingcomments = "Comment: " . $row["MarketingComments"] . "<br>";
            $print_price = "<h1>" . "Price: $" . number_format(round(($row['UnitPrice'] + (($row['TaxRate'] / 100) * $row['UnitPrice'])), 2), 2) . "</h1>" . "<br>";
            $print_photo = $row["Photo"] . "<br>";
            $print_quantity = "There are " . $row["QuantityOnHand"] . " items left" . "<br>";
        }
        ?>
        <div class="card">
            <div class="product-container">
                <?php
                echo $print_name;
                ?>
            </div>
            <div class="productleft">
                <iframe width="600" height="337" src="https://www.youtube.com/embed/XyNlqQId-nk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <div class="product-right-info">
            <?php
            echo $print_price;
            echo $print_supplier;
            echo $print_marketingcomments;
            echo $print_photo;
            echo $print_quantity;
            ?>
                <div class="product-right-add-to-cart">
                    <?php
                     echo "Voeg aan winkelwagen toe";
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<footer class="footer">
    <h3>© Copyrights 2019 - World Wide Importers</h3>
</footer>
</html>