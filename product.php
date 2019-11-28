<?php
session_start();
include("PHP/connectdb.php");
include("PHP/functions.php");
include("header.php");

?>
<body>
<script>
    var slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("demo");
        var captionText = document.getElementById("caption");
        if (n > slides.length) {slideIndex = 1}
        if (n < 1) {slideIndex = slides.length}
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex-1].style.display = "block";
        dots[slideIndex-1].className += " active";
        captionText.innerHTML = dots[slideIndex-1].alt;
    }

</script>
</div>
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
        <?php
        $sql = "SELECT StockItemId, StockItemName, MarketingComments, UnitPrice, TaxRate, Photo, SupplierName, QuantityOnHand
                       FROM stockitems
                       JOIN suppliers USING (SupplierID)
                       JOIN stockitemholdings USING (StockItemID)
                       WHERE StockItemID = {$_GET['id']}";
        $statement = mysqli_prepare($connection, $sql);
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);

        $StockGroupID_sql = "SELECT StockGroupId FROM stockitemstockgroups WHERE StockItemId = {$_GET['id']}";
        $StockGroupID_stmt = mysqli_prepare($connection, $StockGroupID_sql);
        mysqli_stmt_execute($StockGroupID_stmt);
        $StockGroupID_result = mysqli_stmt_get_result($StockGroupID_stmt);
        $StockGroupID_row = mysqli_fetch_assoc($StockGroupID_result);
        $StockGroupID = $StockGroupID_row['StockGroupId'];
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $hiddenid = $row['StockItemId'];
            $hiddenname = $row['StockItemName'];
            $hiddenprice =  number_format(round(($row['UnitPrice'] + (($row['TaxRate'] / 100) * $row['UnitPrice'])), 2), 2);
            $print_name = "<h2>" . "Product: " . $row["StockItemName"] . "</h2>" . "<br>";
            $print_marketingcomments = "Aantekening: " . $row["MarketingComments"] . "<br>";
            $print_price = "<h1>" . "Prijs: $" . number_format(round(($row['UnitPrice'] + (($row['TaxRate'] / 100) * $row['UnitPrice'])), 2), 2) . "</h1>" . "<br>";
            if ($row["QuantityOnHand"] > 1000) {
                $print_quantity =  "Ruim op vooraad";
            } else {
                $print_quantity = "Er zijn  " . $row["QuantityOnHand"] . " producten op voorraad" . "<br>";
            }
        }
        ?>
        <div class="card">
            <form method="post" action="cart.php?action=add&id=<?php echo $hiddenid; ?>">
                    <h4 class="product-container"><?php echo $print_name; ?></h4>

                <div class="productleft">
                    <iframe width="600" height="337" src="https://www.youtube.com/embed/XyNlqQId-nk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="product-right-info">
                     <?php echo $print_price;
                      echo $print_marketingcomments;
                      echo $print_quantity;?>

                    <input type="text" name="quantity" value="1" class="form-control" />

                    <input type="hidden" name="hidden_name" value="<?php echo $hiddenname; ?>" />

                    <input type="hidden" name="hidden_price" value="<?php echo $hiddenprice; ?>" />

                    <input type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-success" value="Add to Cart" />
                </div>
            </form>
        </div>
        <div class="container">
            <?php
            $imageSQL = "SELECT StockItemID, StockImagePath
                       FROM stockitems
                       JOIN stockimage USING (StockItemID)
                       WHERE StockItemID = {$_GET['id']}";
            $stmt = mysqli_prepare($connection, $imageSQL);
            mysqli_stmt_execute($stmt);
            $imageResult = mysqli_stmt_get_result($stmt);

            foreach ($imageResult as $image) {
                print "<div class='mySlides'>
                    <img src='".$image['StockImagePath']."' style='width:40%'>
                </div>";
            }
            ?>

            <a class="prev" onclick="plusSlides(-1)">❮</a>
            <a class="next" onclick="plusSlides(1)">❯</a>
            <div class="caption-container">
                <p id="caption"></p>
            </div>
            <div class="row">
                <?php
                    foreach ($imageResult as $image) {
                    print "<div class='column'>
                        <img src='".$image['StockImagePath']."' onclick='currentSlide(1)'>
                    </div>";
                }
                ?>
                <div class="column">
                    <iframe src="https://www.youtube.com/embed/XyNlqQId-nk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<footer class="footer">
    <h3>© Copyrights 2019 - World Wide Importers</h3>
</footer>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tutorial</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet">
    <!-- CSS -->
    <link href="style.css" rel="stylesheet">
    <meta name="robots" content="noindex,follow" />

</head>

<body>
<main class="container">

    <!-- Left Column / Headphones Image -->
    <div class="left-column">
        <img data-image="black" src="images/black.png" alt="">
        <img data-image="blue" src="images/blue.png" alt="">
        <img data-image="red" class="active" src="images/red.png" alt="">
    </div>


    <!-- Right Column -->
    <div class="right-column">

        <!-- Product Description -->
        <div class="product-description">
            <span>Headphones</span>
            <h1>Beats EP</h1>
            <p>The preferred choice of a vast range of acclaimed DJs. Punchy, bass-focused sound and high isolation. Sturdy headband and on-ear cushions suitable for live performance</p>
        </div>

        <!-- Product Configuration -->
        <div class="product-configuration">

            <!-- Product Color -->
            <div class="product-color">
                <span>Color</span>

                <div class="color-choose">
                    <div>
                        <input data-image="red" type="radio" id="red" name="color" value="red" checked>
                        <label for="red"><span></span></label>
                    </div>
                    <div>
                        <input data-image="blue" type="radio" id="blue" name="color" value="blue">
                        <label for="blue"><span></span></label>
                    </div>
                    <div>
                        <input data-image="black" type="radio" id="black" name="color" value="black">
                        <label for="black"><span></span></label>
                    </div>
                </div>

            </div>

            <!-- Cable Configuration -->
            <div class="cable-config">
                <span>Cable configuration</span>

                <div class="cable-choose">
                    <button>Straight</button>
                    <button>Coiled</button>
                    <button>Long-coiled</button>
                </div>

                <a href="#">How to configurate your headphones</a>
            </div>
        </div>

        <!-- Product Pricing -->
        <div class="product-price">
            <span>148$</span>
            <a href="#" class="cart-btn">Add to cart</a>
        </div>
    </div>
</main>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js" charset="utf-8"></script>
<script src="script.js" charset="utf-8"></script>
</body>
</html>
