<?php
session_start();
include("PHP/connectdb.php");
include("PHP/functions.php");
include ("header.php");
$uri = getURI(); // Get uri of page
print_r($_SESSION);

if (isset($_POST["toevoegen_aan_winkelwagen"])) {
    addToCart();
}
?>
<script>
    $(document).ready(function(){
        $(".addProductToCart").click(function(){
            var hidden_id  = this.value;
            // AJAX Code To Submit Form.
            $.ajax({
                type: "POST",
                url: "PHP/addtocart.php",
                data: {hidden_id},
                cache: false,
                success: function (result) {
                    $(".displayResult").text(result);
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
            <h3>Welkom: <?php print($_SESSION["firstname"]);?></h3>
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