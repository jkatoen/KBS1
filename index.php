<?php
error_reporting(0);
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

// Pagination
if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}

if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}
if (isset($_POST['rpp'])) {
    $_SESSION['rpp'] = $_POST['rpp'];
} elseif (!isset($_SESSION['rpp'])) {
    $_SESSION['rpp'] = 25;
}

$no_of_records_per_page = $_SESSION['rpp'];
$offset = ($pageno-1) * $no_of_records_per_page;

$total_rows = getCountProductsPagination($connection, $category);
$total_pages = ceil($total_rows / $no_of_records_per_page);
// End pagination
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
                <?php
                while ($row = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                    $group = $row["stockgroupname"];
                    $amount = $row["count(*)"];
                    $ID = $row["stockgroupid"];
                    print("<div class='category-item'>");
                    print("<a href='category.php?category={$ID}'>$group $amount</a>");
                    print("</div>");
                    //print("</div>");
                }
                ?>
            </div>
        </div>
    </div>
    <div class="midcolumn">
        <div class="card">
            <h2>Special Deals</h2>
            <div class="product-container">
                <?php
                //$stmt = $connection->prepare("SELECT StockItemName, UnitPrice, StockItemId  FROM stockitems limit $offset, $no_of_records_per_page");
                $stmt = $connection->prepare("select StockItemName, UnitPrice, StockItemId, Photo , StockGroupId ,TaxRate from stockitems join StockItemStockGroups  using (stockitemid) join stockgroups using(stockgroupid) where stockgroupid in(select stockgroupid from specialdeals) limit $offset, $no_of_records_per_page");
                $stmt->execute();
                $stmt->store_result();
                //if ($stmt->num_rows === 0) exit('No rows');
                $stmt->bind_result($StockItemName, $UnitPrice, $StockItemId, $Photo, $StockGroupID,  $TaxRate);
                while ($stmt->fetch()) {
                    print("<a class='logolink' href='product.php?id=$StockItemId'>");
                    print("<div class='product-item'>");
                    print("<div class=\"fakeimg\" style=\"height:200px;\">Image");
                    echo "<img style='height: 100%; width:100%;' src='IMG/category{$StockGroupID}.png'/>";
                    print("</div>");
                    //print("<div class='grid-item-content'>");
                    print("</br>".$StockItemName." $".  number_format(round(($UnitPrice+(($TaxRate/100)*$UnitPrice)),2),2));
                    print("</div>");
                    print("</a>");
                }
                $stmt->close();
                ?>
            </div>
            <ul class="pagination">
                <li><a href="?pageno=1">First</a></li>
                <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
                    <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
                </li>
                <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                    <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
                </li>
                <li><a href="<?php echo "?pageno={$total_pages}"; ?>">Last</a></li>
            </ul>
            <form action="" method="post">
                <a href="<?php echo getFullURI(); ?>"><input type="submit" value="25" name="rpp"></a>
                <a href="<?php echo getFullURI(); ?>"><input type="submit" value="50" name="rpp"></a>
                <a href="<?php echo getFullURI(); ?>"><input type="submit" value="100" name="rpp"></a>
            </form>
        </div>
    </div>
</div>
<!--
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
--!>
</div>

</body>
</html>