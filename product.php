<?php
session_start();
include("PHP/connectdb.php");
include("PHP/functions.php");
include("header.php");
// Getting product information
$productResult = productSQL($connection);
?>
<script>
    var slideIndex = 1;
    showSlides(slideIndex);
</script>
<body>
<div class="row">
    <div class="leftcolumn ">
        <div class="card-left">
            <h2>Category</h2>
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
                print "<div class='column'><img class='productImage cursor' src='".$image['StockImagePath']."' onclick='currentSlide({$i})'></div>";
                $i++;
            }  ?>
            <a class="next" onclick="plusSlides(1)">❯</a>
            <?php } // END ELSE STATEMENT ?>
               <!-- <iframe class="productVideo" src="https://www.youtube.com/embed/XyNlqQId-nk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
            <!--End display product image and/or video -->
        </div>

        <div class="product-right-info">
            <?php // GETTING PRODUCT INFO AND DISPLAY IT
            foreach ($productResult as $result) {
                $productId = $result['StockItemId'];
                $productName = $result['StockItemName'];
                $productPrice = number_format(round(($result['UnitPrice'] + (($result['TaxRate'] / 100) * $result['UnitPrice'])), 2), 2); // Misschien nog anders in de sql query berekenen?
                $productComment = $result['MarketingComments'];
                $productQuantity = ($result['QuantityOnHand'] > 1000) ? "Ruim op voorraad" : "Er zijn {$result['QuantityOnHand']} producten op voorraad" ;
            }
            ?>
            <div class="product-description">
                <h1> <?php echo  $productName ?> </h1>
                <h2> <?php echo "$" . $productPrice ?> </h2>
                <p> <?php echo $productComment ?> </p>
                <p> <?php echo $productQuantity ?></p>
        </div>
            <!-- Adding product to cart -->
            <form method="post" action="cart.php?action=add&id=<?php echo $productId; ?>">
                <input type="number" name="quantity" value="1" class="form-control" />
                <input type="hidden" name="hidden_name" value="<?php echo $productName; ?>" />
                <input type="hidden" name="hidden_price" value="<?php echo $productPrice; ?>" />
                <input type="submit" name="add_to_cart" class="cart-btn" value="Add to Cart" />
            </form>
            <!-- End adding product to cart -->

        </div>
        </div>

    </div>
</div>
</body>
<footer class="footer">
    <h3>© Copyrights 2019 - World Wide Importers</h3>
</footer>
</html>