<?php
session_start();
include ("PHP/connectdb.php");
include ("PHP/functions.php");

if (isset($_GET['searchinput'])) {
    $_SESSION['searchinput'] = $_GET['searchinput'];
    $searchinput = $_GET['searchinput'];
} elseif (!isset($_SESSION['searchinput'])) {
    // error
    echo "error no searchinput session";
} else {
    $searchinput = $_SESSION['searchinput'];
}

// Pagination
$pageno = setPage();
setRecordsPerPageSession();

$no_of_records_per_page = $_SESSION['rpp'];
$offset = ($pageno-1) * $no_of_records_per_page;

$total_rows = getCountSearchPagination($connection, $searchinput);
$total_pages = ceil($total_rows / $no_of_records_per_page);
// End pagination

include("header.php");

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
        <div class="card" class="product-container">
            <h2><?php echo "Gezocht op: {$searchinput}"; ?></h2>
            <?php
            if(displaySearchRows($connection,$searchinput) != 0){
                print("<p>");
                print("Aantal gevonden producten: ");
                print(displaySearchRows($connection,$searchinput));
                print("</p>");
            }else {
                print("<p>");
                print("er zijn geen producten gevonden");
                print("</p>");
            }
            ?>

            <!--Pagination and filter on amount per page-->
            <?php displayPagination($total_pages, $pageno); ?>

            <div class="product-container">
                <?php
                    displaySearchProducts($connection, $searchinput, $offset, $no_of_records_per_page);
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
