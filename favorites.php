<?php
session_start();
include ("PHP/connectdb.php");
include ("PHP/functions.php");
include ("header.php");

if (!isset($_SESSION["favorites_array"])) {
    $_SESSION["favorites_array"] = array();
}

if (isset($_GET["remove"])) {
    if (inFavoriteArray($_GET["remove"])) {
        foreach ($_SESSION["favorites_array"] as $key => $value) {
            if ($value["item_id"] == $_GET["remove"]) {
                unset ($_SESSION["favorites_array"][$key]);
            }
        }
        header("Refresh:0");
    }
}
?>
<script>
    $(document).ready(function(){

        $(".productCart").click(function(){
            var hidden_id  = this.alt;
            // AJAX Code To Submit Form.
            $.ajax({
                type: "POST",
                url: "PHP/ajax_cart.php",
                data: {hidden_id},
                cache: false,
                success: function (result) {
                    // added
                    if (result === "Toegevoegd!") {
                        $(".productCart").each(function () {
                            var alt = $(this).attr("alt");
                            if (alt == hidden_id) {
                                $(this).attr('src', 'IMG/iconfinder_shopping_cart_delete_61808.png');
                                $(this).attr('title', 'Verwijderen uit winkelwagen');
                            }
                        });
                    }
                    // removed
                    if (result === "Verwijderd!") {
                        $(".productCart").each(function () {
                            var alt = $(this).attr("alt");
                            if (alt == hidden_id) {
                                $(this).attr('src', 'IMG/iconfinder_shopping_cart_add_61807.png');
                                $(this).attr('title', 'Toevoegen aan winkelwagen');
                            }
                        });
                    }
                }
            });
        });

    });
</script>
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
        if (empty($_SESSION["favorites_array"])) {
            echo "Er zijn geen favorieten toegevoegd, voeg een paar toe.";
        } else {
            echo "<table class='favorites_table'>";
            echo "<form method='get'>";
            foreach ($_SESSION["favorites_array"] as $key => $value) {

                // Show shopping cart add or remove button on product display overview
                if (isset($_SESSION["shopping_cart"]) && !empty($_SESSION["shopping_cart"])) {
                    // if in array
                    $found = array_search($value["item_id"], array_column($_SESSION["shopping_cart"], 'item_id'));
                    if ($found !== FALSE) {
                        $imgText = "<img class='productCart' alt='{$value["item_id"]}' title='Verwijderen uit winkelwagen' src='IMG/iconfinder_shopping_cart_delete_61808.png'/>";
                    }
                    // if not in array
                    else {
                        $imgText = "<img class='productCart' alt='{$value["item_id"]}' title='Toevoegen aan winkelwagen' src='IMG/iconfinder_shopping_cart_add_61807.png'/>";
                    }
                } else {
                    $imgText = "<img class='productCart' alt='{$value["item_id"]}' title='Toevoegen aan winkelwagen' src='IMG/iconfinder_shopping_cart_add_61807.png'/>";
                }


                echo "<tr><td><img style='width: 120px;' src='{$value['item_image']}'/></td>
                        <td>{$value['item_name']}</td>
                        <td>{$value['item_description']}</td>
                        <td>$".$value['item_price']."</td>
                      
                        <td><a href='favorites.php?remove={$value["item_id"]}'><img alt='' src='IMG/iconfinder_Remove_32542.png' title='Verwijderen uit favorieten'/></a> | {$imgText}</td></tr>";
            }
            echo "</form>";
            echo "</table>";
        }
        ?>
    </div>
</div>

</body>