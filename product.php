<?php
session_start();
include("PHP/connectdb.php");
include("PHP/functions.php");
// If you go to product.php without giving an id, redirect to index.php
if (!isset($_GET["id"])) {
    header("location:index.php");
    exit;
}
$item_id = $_GET["id"];
if (isset($_POST["add_to_cart"])) {
    addToCart();
}
// Getting product information
$productResult = productSQL($connection);
include("header.php");
?>
<script>
    $(document).ready(function(){
        $(".addReview").click(function(){
            if (!$.trim($(".user_review").val())) {
                $(".displayResult").text("Review is leeg");
            } else {
                var review = $(".user_review").val();
                var rating = $(".user_rating").serialize().split("=").pop();
                var itemid = this.value;
                // AJAX Code To Submit Form.
                $.ajax({
                    type: "POST",
                    url: "PHP/addreview.php",
                    data: {item_id:itemid, user_rating:rating, user_review:review},
                    cache: false,
                    success: function (result) {
                        location.reload();
                    }
                });
            }
        });
    });

</script>
<body>
<?php
if(isset($_GET) && isset($_GET["alert"]) && $_GET["alert"] == "2"){
  ?>
    <div class="alert">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
             Product bevindt zich al in de winkelwagen!.
    </div>
    <?php
}elseif(isset($_GET) && isset($_GET["alert"]) && $_GET["alert"] == "1") {
    ?>
    <div class="alert">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
         Product is succesvol aan winkelwagen toegevoegd!.
    </div>
    <?php
}
?>
<div class="row">
    <div class="leftcolumn ">
        <div class="card-left">
            <h2>Categorieën</h2>
            <div class="category-container">
                <?php displayLeftCategories($connection); ?>
            </div>
        </div>
    </div>

    <div class="midcolumn">
        <div class="card">
        <div class="product-left-info">
            <!--Display product image and/or video -->
            <div class="productDisplayImage">
                <?php
                $imageResult = imageSQL($connection);
                if ($imageResult->num_rows===0) { // ALS ER GEEN IMAGES BIJ PRODUCT IS TOON DAN DEFAULT CATEGORY IMAGE
                    foreach ($productResult as $result) {
                        $categoryId = $result['StockGroupId'];
                    }
                    echo "<img src='IMG/category{$categoryId}.png'/></div>";
                } else { // BEGIN ELSE STATEMENT
                $i = 0; // Eerste image tonen
                foreach ($imageResult as $image) {
                    $display = ($i === 0) ? $display = "Block" : "None" ;
                    print "<div style='display: {$display};' class='mySlides'><img src='".$image['StockImagePath']."'></div>";
                    $i++;
                }
                ?>
            </div>
            <a class="prev" onclick="plusSlides(-1)">❮</a>
            <?php
            $i = 0;
            foreach ($imageResult as $image) {
                print "<div class='column' style='width:70px!important;'><img class='productImage cursor' src='".$image['StockImagePath']."' onclick='currentSlide({$i})'></div>";
                $i++;
            }  ?>
            <a class="next" onclick="plusSlides(1)">❯</a>
            <?php } // END ELSE STATEMENT ?>


        </div>
        <div class="product-right-info">
            <?php // GETTING PRODUCT INFO AND DISPLAY IT
            foreach ($productResult as $result) {
                $UnitPrice = $result['UnitPrice'];
                $TaxRate = $result['TaxRate'];
                $productId =($result['StockItemId']);
                $productName = $result['StockItemName'];
                $productPrice = number_format(round((($UnitPrice)+(($TaxRate/100)*$UnitPrice)),2),2); // Misschien nog anders in de sql query berekenen?
                $productComment = $result['MarketingComments'];
                $productQuantity = ($result['QuantityOnHand'] > 1000) ? "Ruim op voorraad" : "Er zijn {$result['QuantityOnHand']} producten op voorraad" ;
            }
            ?>


            <div class="product-description">
                <h1> <?php echo  $productName; ?> </h1>
                <h2> <?php echo "€" . $productPrice; ?> </h2>
                <p> <?php echo $productComment; ?> </p>
                <p> <?php echo $productQuantity; ?></p>
            </div>
            <!-- Adding product to cart -->
            <form method="post" action="">
                <input type="number" name="quantity" value="1" class="form-control" />
                <input type="hidden" name="hidden_id" value="<?php echo $productId; ?>" />
                <input type="hidden" name="hidden_name" value="<?php echo htmlspecialchars($productName); ?>" />
                <input type="hidden" name="hidden_price" value="<?php echo $productPrice; ?>" />
                <input type="submit" name="add_to_cart" class="cart-btn" value="Voeg toe aan winkelwagen" />
            </form>
            <!-- End adding product to cart -->
            <?php
            getReviewScoreTotal($connection, $item_id);
            displayReview($connection, $item_id);
            ?>
            <p class="displayResult"></p>
            <?php
            if (isset($_SESSION["ingelogd"])) {
                // Als user al een review heeft gemaakt kan hij het niet nog een keer doen
                // TRUE als user al een review heeft gemaakt bij dit product
                if (!checkUserMadeReview($connection, $_SESSION["accountID"], $item_id)) {
                    for ($i = 1; $i < 6; $i++) {
                        $checked = ($i === 5) ? "checked" : "" ;
                        echo "<input name='user_rating' class='user_rating' type='radio' value='{$i}' $checked/>";
                        // volle sterren
                        for ($y = 0; $y < $i; $y++) {
                            echo "<img class='review_star' src='IMG/fullstar.png'>";
                        }
                        // daarna lege sterren
                        for ($x = 5; $x > $i; $x--) {
                            echo "<img class='review_star' src='IMG/emptystar.png'>";
                        }
                        echo "<br>";
                    }
                    ?>
                    <p>Schrijf een review:</p>
                    <textarea name="user_review" placeholder="Review" class="user_review"></textarea><br>
                    <button class="addReview" value="<?php echo $item_id; ?>">Toevoegen Review</button>
                    <?php
                } else {
                    ?><p>Je hebt al een review gemaakt!</p><?php
                }
            } else {
                ?><p>Je moet ingelogd zijn om een review te schrijven</p><?php
            }
            ?>
            <iframe class="productVideo" src="https://www.youtube.com/embed/XyNlqQId-nk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>


        </div>
        </div>
    </div>
</div>
</body>