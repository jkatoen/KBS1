<?php
session_start();
include("PHP/connectdb.php");
include("PHP/functions.php");
include ("header.php");
$uri = getURI(); // Get uri of page

if (isset($_POST["toevoegen_aan_winkelwagen"])) {
    addToCart();
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
                    //$(".displayResult").text(result);
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

        $(".favorite").click(function(){
            var hidden_id = this.alt;
            $.ajax({
                type: "POST",
                url: "PHP/ajax_favorites.php",
                data: {hidden_id},
                cache: false,
                success: function (result) {
                    //$(".displayResult").text(result);
                    // add to session
                    if (result === "Toegevoegd!") {
                        $(".favorite").each(function () {
                            var alt = $(this).attr("alt");
                            if (alt == hidden_id) {
                                $(this).attr('src', 'IMG/favorite_full.png');
                                $(this).attr('title', 'Verwijderen uit favorieten');
                            }
                        });
                    }
                    // remove from session
                    if (result === "Verwijderd!") {
                        $(".favorite").each(function () {
                            var alt = $(this).attr("alt");
                            if (alt == hidden_id) {
                                $(this).attr('src', 'IMG/favorite_empty.png');
                                $(this).attr('title', 'Toevoegen aan favorieten');
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
        <div class="card">
            <?php
            if (isset($_SESSION["ingelogd"])) {
            ?>
            <h3>Welkom: <?php print($_SESSION["firstname"]); ?></h3>
            <h2>Speciale aanbiedingen</h2>
            <?php
            }
            else {
            ?>
            <h2>Speciale aanbiedingen</h2>
                <p class="displayResult"></p>
            <?php
            }
            ?>
            <div class="product-container">
                <?php
                DisplaySpecialItems($connection);
                ?>
            </div>
        </div>
    </div>
</div>

</body>