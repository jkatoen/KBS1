<?php
include_once("php/connectdb.php");
include ("PHP/functions.php");
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="CSS/mystyle.css">
    <div class="header">
        <a href="index.php"><img src="IMG/wwi-logo.png"></a>
    </div>
    <div class="topnav">
<<<<<<< HEAD
        <a href="cart.php"><img src="IMG/winkelmand.png" width="65" height="56"></a>
        <a href="account.php"><h3>Account aanmaken</h3></a>
        <a><h3>Contact</h3></a>
=======
        <a href="cart.php"><img src="IMG/winkelmand.png" width="65" height="59.5"></a>
        <a href="login.php"><h3>Login</h3></a>
        <a href="contact.php"><h3>Contact</h3></a>
>>>>>>> 09451f54567af19affc4cfd38a977fdf2ab9f650
        <form class="nav-search" method="get" action="search.php">
            <input class="text" type="text" name="searchinput">
            <input type="submit" name="submitinput" value="Search">
        </form>
    </div>
</head>
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
                       FROM stockitems Stock
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
            /*if (!empty($row['Photo'])) {
                echo "<img style='height: 200px;' src='data:image/jpeg;base64,".base64_encode( $row['Photo'] )."'/>";
            } else {
                echo "<img style='height: 100px; width:100px;' src='IMG/category{$StockGroupID}.png'/>";
            }*/
            $print_name = "<h2>" . "Product: " . $row["StockItemName"] . "</h2>" . "<br>";
            $print_marketingcomments = "Aantekening: " . $row["MarketingComments"] . "<br>";
            $print_price = "<h1>" . "Prijs: $" . number_format(round(($row['UnitPrice'] + (($row['TaxRate'] / 100) * $row['UnitPrice'])), 2), 2) . "</h1>" . "<br>";
            $print_photo = $row["Photo"] . "<br>";
            $print_quantity = "";
            if ($row["QuantityOnHand"] > 1000) {
                $print_quantity =  "Ruim op vooraad";
            } else {
                $print_quantity = "Er zijn  " . $row["QuantityOnHand"] . " producten op voorraad" . "<br>";
            }

        }
        ?>
        <div class="card">
            <div class="product-container">
                <?php
                echo $print_name;
                ?>
            </div>
            <div class="productleft">
                <iframe width="600" height="337" src="https://www.youtube.com/embed/XyNlqQId-nk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <div class="product-right-info">
            <?php
            echo $print_price;
            echo $print_marketingcomments;
            echo $print_photo;
            echo $print_quantity;

            ?>
                <a href=""><div class="product-right-add-to-cart">
                        Voeg aan winkelwagen toe
                    </div></a>
            </div>
                <h2 style="text-align:left">Afbeeldingen</h2>
                <div class="container">
                    <div class="mySlides">
                        <div class="numbertext">1 / 4</div>
                        <img src="IMG/voorkant.jpg" style="width:20%">
                    </div>
                    <div class="mySlides">
                        <div class="numbertext">2 / 4</div>
                        <img src="IMG/achterkant.jpg" style="width:20%">
                    </div>
                    <div class="mySlides">
                        <div class="numbertext">3 / 4</div>
                        <img src="IMG/Dichtbij.jpg" style="width:20%">
                    </div>
                    <a class="prev" onclick="plusSlides(-1)">❮</a>
                    <a class="next" onclick="plusSlides(1)">❯</a>
                    <div class="caption-container">
                        <p id="caption"></p>
                    </div>
                    <div class="row">
                        <div class="column">
                            <img class="demo cursor" src="IMG/voorkant.jpg" style="width:50%" onclick="currentSlide(1)" alt="Voorkant">
                        </div>
                        <div class="column">
                            <img class="demo cursor" src="IMG/achterkant.jpg" style="width:50%" onclick="currentSlide(2)" alt="Achterkant">
                        </div>
                        <div class="column">
                            <img class="demo cursor" src="IMG/Dichtbij.jpg" style="width:50%" onclick="currentSlide(3)" alt="Borstzakje">
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