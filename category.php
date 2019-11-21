<?php
// test
session_start();
include_once("PHP/connectdb.php");
include("PHP/functions.php");

$category = isCategorySet();
$_SESSION['category'] = $category;

// Pagination
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
        <a><h3>Login</h3></a>
        <a><h3>Contact</h3></a>
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
        <div class="card" class="product-container">
            <h2><?php displayCategoryName($connection, $category); ?></h2>

            <!--Pagination-->
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
            <!--End Pagination-->

            <div class="product-container">
                <?php
                    displayCategoryProducts($connection, $category, $offset, $no_of_records_per_page);
                ?>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    <h3>© Copyrights 2019 - World Wide Importers</h3>
</footer>
</body>
</html>
