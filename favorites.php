<?php
session_start();
include ("PHP/connectdb.php");
include ("PHP/functions.php");
include ("header.php");

if (!isset($_SESSION["favorites_array"])) {
    $_SESSION["favorites_array"] = array();
}
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
        <h1>Favorieten</h1>
        <?php
//        echo "<pre>";
//        print_r($_SESSION["favorites_array"]);
//        echo "</pre>";
        if (empty($_SESSION["favorites_array"])) {
            echo "Er zijn geen favorieten toegevoegd, voeg een paar toe.";
        } else {
            echo "<table class='favorites_table'>";
            foreach ($_SESSION["favorites_array"] as $key => $value) {
                echo "<tr><td><img style='width: 120px;' src='{$value['item_image']}'/></td>
                        <td>{$value['item_name']}</td>
                        <td>{$value['item_description']}</td>
                        <td>$".$value['item_price']."</td>
                        <td>Verwijder|Winkelwagen</td></tr>";
            }
            echo "</table>";
        }
        ?>
    </div>
</div>

</body>