<?php
include_once("connectdb.php");

if (isset($_GET["categorie"])) {
    $categorie = $_GET["categorie"];



}


$stmt = $connection->prepare("SELECT StockItemName, UnitPrice, TaxRate, StockItemID, StockGroupID FROM stockitemstockgroups 
                                            JOIN stockitems SI USING (StockItemID) 
                                            WHERE StockGroupId = ?");
$stmt->bind_param("i", $categorie);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) exit('No rows found');
$stmt->bind_result($StockItemName, $UnitPrice, $TaxRate, $StockItemID, $StockGroupID);

$sql2 = "select StockGroupId, stockgroupname, count(*) from stockgroups
         join  stockitemstockgroups using(stockgroupid)
         join stockitems using(stockitemid)
         group by stockgroupname";
$result2 = mysqli_query($connection,$sql2);



if (isset($_GET["submitinput"])) {
    $search = "%{$_GET["zoekinput"]}%";
    $stmt = $connection->prepare("SELECT StockItemId, StockItemName FROM stockitems WHERE StockItemName LIKE ?");
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 0) exit('No rows found');
    $stmt->bind_result($StockItemId, $StockItemName);
    while ($stmt->fetch()) {
        echo "Id: {$StockItemId}, Name: {$StockItemName} <br/>";
    }
    $stmt->close();
}

?>
<div class="topnav">
    <form class="form" method="get" action="">
        <input class="text" type="text" placeholder="Search here for products" name="zoekinput">
        <input type="submit" name="submitinput" value="Search">
    </form>
</div>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="stijl.css">
</head>
<body>

<div class="header">
    <h1>My Website</h1>
    <p>Resize the browser window to see the effect.</p>
</div>


<div class="row">
    <div class="leftcolumn">
        <div class="card">
            <h2>Category</h2>
            <div class="category-container">
                <?php
                while ($row = mysqli_fetch_array($result2, MYSQLI_ASSOC))
                {
                    $ID = $row["StockGroupId"];
                    $group = $row["stockgroupname"];
                    $amount = $row["count(*)"];
                    print("<div class='category-item'>");
                    //print("<div class=\"fakeimg\" style=\"height:200px;\">Image</div>");
                    //print("<div class='grid-item-content'>");
                    print("<a href='categorie.php?categorie={$ID}'>$group $amount</a>");
                    print("</div>");
                    //print("</div>");
                }
                ?>
            </div>
        </div>
    </div>
    <div class="midcolumn">
        <div class="card">
            <h2>Featured</h2>
            <div class="product-container">
                <?php
                while ($stmt->fetch()) {
                    print("<div class='product-item'>");
                    print("<div class=\"fakeimg\" style=\"height:200px;\">Image</div>");
                    print($StockItemName." $".  number_format(round(($UnitPrice+(($TaxRate/100)*$UnitPrice)),2),2));
                    print("</div>");
                }
                $stmt->close();

                ?>
            </div>
            <p>f</p>
        </div>
    </div>
    <div class="rightcolumn">
        <div class="card">
            <h2>About Me</h2>
            <div class="fakeimg" style="height:100px;">Image</div>
            <p>Some text about me in culpa qui officia deserunt mollit anim..</p>
        </div>
        <div class="card">
            <h3>Popular Post</h3>
            <div class="fakeimg"><p>Image</p></div>
            <div class="fakeimg"><p>Image</p></div>
            <div class="fakeimg"><p>Image</p></div>
        </div>
        <div class="card">
            <h3>Follow Me</h3>
            <p>Some text..</p>
        </div>
    </div>
</div>

<div class="footer">
    <h2>Footer</h2>
</div>

</body>
</html>