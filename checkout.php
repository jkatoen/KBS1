
<?php
session_start();
include("PHP/connectdb.php");
include("PHP/functions.php");
include ("header.php");
checkIfCartEmpty();
?>
<head>
    <h1 style="text-align: center">Checkout</h1>
</head>
<body>
<div class="row">

    <div class="leftcolumn" style="margin-left: 5%">
    </div>
    <div class="midcolumn" style="width: 25%;">
                <form action="invoice.php" method="POST">
                    <?php if (!isset($_SESSION["ingelogd"])) { ?>
                        <p><a href="login.php">sign in,</a> or enter your name and address details  </p>
                        <table>
                            <tr><td>Name:</td><td><input type="text" name="achternaam"/> </td></tr>
                            <tr><td>Surname:</td><td><input type="text" name="voornaam"/>   </td></tr>
                            <tr><td>Address:</td><td><input type="text" name="adres"/>      </td></tr>
                            <tr><td>Email: </td><td><input type="email" name="emailadres"/> </td></tr>
                        </table>
                    <?php }
                    else { ?>
                        <table>
                            <tr><td>Surname: </td><td><input type="text" name="achternaam" value="<?php print($_SESSION["lastname"])?>"/>    </td></tr>
                            <tr><td>Name: </td><td><input type="text" name="voornaam" value="<?php print($_SESSION["firstname"])?>"/>     </td></tr>
                            <tr><td>Address: </td><td><input type="text" name="adres" value="<?php print($_SESSION["address"])?>"/>          </td></tr>
                            <tr><td>Email: </td><td><input type="email" name="emailadres" value="<?php print($_SESSION["email"])?>"/>      </td></tr>
                        </table>
                        <?php
                    }
                    ?>
                </form>
                </div>
    <div class="Discount">
        <form method="POST">
            <input type="text" value="Voer hier je coupon in" name="discount" class="text">
            <input type="submit" value="Voeg toe" name="ok" required/>
        </form>
    </div>
    <?php
    if(isset($_POST["discount"])){

    }
    ?>
    <div class="rightcolumn">
                    <p>List of items in your shopping basket</p>
                    <?php
                    if (isset($_SESSION["shopping_cart"])) {
                        echo "<table><th>Item</th><th>Quantity</th><th>Price</th>";
                        foreach ($_SESSION["shopping_cart"] as $item) {
                            echo "<tr><td>".$item["item_name"]."</td><td>".$item["item_quantity"]."</td><td>" . "€".number_format($item["item_price"]*$item["item_quantity"],2)."</td></tr>";
                        }
                        echo "<tr><td>Shipping fee</td><td>1</td><td>€6.95</td></tr>";
                        echo "<tr><td>Total</td><td></td><td>" . "€". number_format(($_POST["total"]+6.95),2) ."</td></tr>";
                        echo "</table>";
                    }
                    ?>
        <button class="button">Proceed to payment options</button>
    </div>

</div>
</body>
</html>
