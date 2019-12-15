<?php
session_start();
include ("PHP/connectdb.php");
include ("PHP/functions.php");

// Setting category id, from get or session
$category = isCategorySet();
$_SESSION['category'] = $category;

// Pagination
$pageno = setPage();
setRecordsPerPageSession();

$no_of_records_per_page = $_SESSION['rpp'];
$offset = ($pageno-1) * $no_of_records_per_page;

$total_rows = getCountProductsPagination($connection, $category);
$total_pages = ceil($total_rows / $no_of_records_per_page);
// End pagination

include ("header.php");
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
            <h2><?php displayCategoryName($connection, $category); ?></h2>
            <!--Pagination and filter on amount per page-->
            <?php displayPagination($total_pages, $pageno); ?>

            <div class="product-container">
                <?php
                displayCategoryProducts($connection, $category, $offset, $no_of_records_per_page);
                ?>
            </div>
        </div>
    </div>
</div>
</body>
