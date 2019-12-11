
<?php
session_start();
include("PHP/connectdb.php");
include("PHP/functions.php");
include ("header.php");
checkIfCartEmpty();
?>

<body>
<div class="row">

    <div class="midcolumn">
            <div class="Checkout" class="Checkout">
                <h1>Checkout</h1>
                <form action="invoice.php" method="POST">
                <?php if (!isset($_SESSION["ingelogd"])) { ?>
                <p>NAW-gegevens, <a href="login.php">log in</a> of voer in leveradres</p>
                <table>
                    <tr><td>Voornaam:   </td><td><input type="text" name="voornaam"/>   </td></tr>
                    <tr><td>Achternaam: </td><td><input type="text" name="achternaam"/> </td></tr>
                    <tr><td>Adres:      </td><td><input type="text" name="adres"/>      </td></tr>
                    <tr><td>Emailadres: </td><td><input type="email" name="emailadres"/> </td></tr>
                </table>
                <?php }
                else { ?>
                <table>
                    <tr><td>Voornaam:   </td><td><input type="text" name="voornaam" value="<?php print($_SESSION["firstname"])?>"/>     </td></tr>
                    <tr><td>Achternaam: </td><td><input type="text" name="achternaam" value="<?php print($_SESSION["lastname"])?>"/>    </td></tr>
                    <tr><td>Adres:      </td><td><input type="text" name="adres" value="<?php print($_SESSION["address"])?>"/>          </td></tr>
                    <tr><td>Emailadres: </td><td><input type="email" name="emailadres" value="<?php print($_SESSION["email"])?>"/>      </td></tr>
                </table>
                <?php
                }
                ?>
                <p>Lijst van producten in winkelwagen en prijs</p>
                <?php
                if (isset($_SESSION["shopping_cart"])) {
                    echo "<table><th>Product</th><th>Aantal</th><th>Prijs</th>";
                    foreach ($_SESSION["shopping_cart"] as $item) {
                        echo "<tr><td>".$item["item_name"]."</td><td>".$item["item_quantity"]."</td><td>".number_format($item["item_price"]*$item["item_quantity"],2)."</td></tr>";
                    }
                    echo "</table>";
                }
                ?>
                <button class="button">Volgende</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
