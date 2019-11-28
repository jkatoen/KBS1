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
            $hiddenprice = $row['UnitPrice'];
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