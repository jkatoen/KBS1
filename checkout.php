
<?php
session_start();
include("PHP/connectdb.php");
include("PHP/functions.php");
include ("header.php");

checkIfCartEmpty();

$total = $_SESSION["total"];
$shippingCostsFreeLimit = 50;
?>
<head>
    <h1 style="text-align: center">Checkout</h1>
</head>
<script>
    $(document).ready(function(){
        $(".addDiscount").click(function(){
            var discount_code = $(".input_discount").val();
            // AJAX Code To Submit Form.
            $.ajax({
                type: "POST",
                url: "PHP/adddiscount.php",
                data: {discount_code:discount_code},
                cache: false,
                success: function (success) {
                    if (success.length === 0) {
                        $(".input_discount").css("border-color", "red")
                        $(".input_discount").attr("placeholder", "Geen geldige kortingscode!");
                    } else {
                        $(".discountResult").text(success + "% korting!");
                    }
                }
            })
        })
    });
</script>
<body>
<div class="row">
    <div class="leftcolumn" style="margin-left: 5%">
    </div>
    <div class="midcolumn" style="width: 25%;">
                <form action="invoice.php" method="POST">
                    <?php if (!isset($_SESSION["ingelogd"])) { ?>
                        <p><a href="login.php">log in,</a> of voer uw NAW-gegevens in </p>
                        <table>
                            <tr><td class="vervoer">Achternaam:</td><td><input type="text" name="achternaam"/> </td></tr>
                            <tr><td class="vervoer">Voornaam:</td><td><input type="text" name="voornaam"/>   </td></tr>
                            <tr><td class="vervoer">Adres:</td><td><input type="text" name="adres"/>      </td></tr>
                            <tr><td class="vervoer">Emailadres: </td><td><input type="email" name="emailadres"/> </td></tr>
                        </table>
                    <?php }
                    else { ?>
                        <table>
                            <tr><td>Achternaam: </td><td><input type="text" name="achternaam" value="<?php print($_SESSION["lastname"])?>"/>    </td></tr>
                            <tr><td>Voornaam: </td><td><input type="text" name="voornaam" value="<?php print($_SESSION["firstname"])?>"/>     </td></tr>
                            <tr><td>Adres: </td><td><input type="text" name="adres" value="<?php print($_SESSION["address"])?>"/>          </td></tr>
                            <tr><td>Emailadres: </td><td><input type="email" name="emailadres" value="<?php print($_SESSION["email"])?>"/>      </td></tr>
                        </table>
                        <?php
                    }
                    ?>
                </form>
        <div class="Discount">
            <p>Voeg hier je coupon toe!</p>
            <input type="text" name="discount" class="input_discount">
            <button class="addDiscount">Toevoegen code</button>
            <p class="discountResult"></p>
        </div>


    <div class="rightcolumn">
                    <p>Items in je winkelmand</p>
                    <?php
                    $bezorgen = true;
                    if (isset($_SESSION["shopping_cart"])) {
                        echo "<table><th>Item</th><th>Aantal</th><th>Prijs</th>";
                        foreach ($_SESSION["shopping_cart"] as $item) {
                            echo "<tr><td>".$item["item_name"]."</td><td>".$item["item_quantity"]."</td><td>" . "€".number_format($item["item_price"]*$item["item_quantity"],2)."</td></tr>";
                        }
                        if(isset($_GET) && isset($_GET["vervoer"]) && $_GET["vervoer"] == "bezorgen"){
                            $bezorgen = true;
                            echo "<tr><td>Verzendkosten</td><td>1</td><td>€6.95</td></tr>";
                            if($total > $shippingCostsFreeLimit) {
                                echo "<tr><td>Totaal</td><td></td><td>" . "€" . number_format(($total + 6.95), 2) . "</td></tr>";
                            }else{
                                echo "<tr><td>Totaal</td><td></td><td>" . "Gratis!" . "</td></tr>";
                            }

                        }elseif(isset($_GET) && isset($_GET["vervoer"]) && $_GET["vervoer"] == "afhalen"){
                            $bezorgen = false;
                            echo "<tr><td>Totaal</td><td></td><td>" . "€". number_format(($total),2) ."</td></tr>";
                        }
                        echo "</table>";
                        if($bezorgen){
                            $datetime = new DateTime('tomorrow');
                            echo "<br>";
                            echo "Jouw bestelling komt op ". $datetime->format('Y-m-d') . " aan";
                        }
                    }
                    ?>

            <p>Kies uw levertype</p>
            <input class="vervoer" type="submit" name="vervoer" value="bezorgen">
            <input class="vervoer" type="submit" name="vervoer" value="afhalen">

        <br>
        <a style="text-decoration-line: none; color: white" href="payment.php">
        <button class="button">Verder naar betaling</button>
        </a>
    </div>

</div>
</body>
<?php
include ("footer.php");
?>