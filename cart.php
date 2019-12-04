<?php
session_start();
include ("PHP/connectdb.php");
include ("PHP/functions.php");
include ("header.php");

if (isset($_SESSION["shopping_cart"])) {
    foreach ($_SESSION["shopping_cart"] as $keys => $values) {
        if (isset($_GET["id"])) {
            if ($_GET["id"] == $_SESSION['shopping_cart'][$keys]['item_id']) {
                if (isset($_POST["plus"])) {
                    $_SESSION['shopping_cart'][$keys]['item_quantity']++;
                } elseif (isset($_POST["min"])) {
                    $_SESSION['shopping_cart'][$keys]['item_quantity']--;
                }
            }
        }
    }
}

if (isset($_SESSION["shopping_cart"])) {
    foreach ($_SESSION["shopping_cart"] as $keys => $values) {
        if ($values["item_quantity"] <= 0) {
            unset($_SESSION["shopping_cart"][$keys]);
        }
    }
}

if (isset($_POST["add_to_cart"])) {
    if (isset($_SESSION["shopping_cart"])) {
        $item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
        if (!in_array($_GET["id"], $item_array_id)) {
            $count = count($_SESSION["shopping_cart"]);
            $item_array = array(
                'item_id' => $_GET["id"],
                'item_name' => $_POST["hidden_name"],
                'item_price' => $_POST["hidden_price"],
                'item_quantity' => $_POST["quantity"]
            );
            array_push($_SESSION["shopping_cart"], $item_array);
        } else {
            echo '<script>alert("Item Already Added")</script>';
        }
    } else {
        $item_array = array(
            'item_id' => $_GET["id"],
            'item_name' => $_POST["hidden_name"],
            'item_price' => $_POST["hidden_price"],
            'item_quantity' => $_POST["quantity"]
        );
        $_SESSION["shopping_cart"][0] = $item_array;
    }
}

if (isset($_GET["action"])) {
    if ($_GET["action"] == "delete") {
        foreach ($_SESSION["shopping_cart"] as $keys => $values) {
            if ($values["item_id"] == $_GET["id"]) {
                unset($_SESSION["shopping_cart"][$keys]);
                //echo '<script>alert("Item Removed")</script>';
                echo '<script>window.location="cart.php"</script>';
            }
        }
    }
}
?>

<body>
<h1>Mandje</h1>

<!DOCTYPE html>
<html>
<head>
    <title>Webslesson Demo | Simple PHP Mysql Shopping Cart</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>


</div>
</form>
</div>

<br />
<div class="">
    <div style="clear:both"></div>
    <br />
    <h3>Order Details</h3>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th width="40%">Item Name</th>
                <th width="10%">Quantity</th>
                <th width="20%">Price</th>
                <th width="15%">Total</th>
                <th width="5%">Action</th>
            </tr>
            <?php

            if(!empty($_SESSION["shopping_cart"]))
            {
                $counter = 0;
                $total = 0;
                foreach($_SESSION["shopping_cart"] as $keys => $values) {
                    ?>
                    <tr>
                        <td><?php echo $values["item_name"] ; ?></td>

                        <td><?php echo $_SESSION['shopping_cart'][$keys]['item_quantity'];  ?>
                            <form method="post" action="cart.php?id=<?php print ($_SESSION['shopping_cart'][$keys]['item_id']); ?>">
                                <input type="submit" name="plus" value="+">
                                <input type="submit" name="min" value="-">
                            </form>
                        </td>


                        <td>$ <?php echo $values["item_price"]; ?></td>
                        <td>$ <?php echo number_format( $_SESSION['shopping_cart'][$keys]['item_quantity'] * $values["item_price"], 2);?></td>
                        <td><a href="cart.php?action=delete&id=<?php echo $_SESSION['shopping_cart'][$keys]['item_id'] ?>"><span class="text-danger">Remove</span></a></td>
                    </tr>
                    <?php
                    $total = $total + ( $_SESSION['shopping_cart'][$keys]['item_quantity']* $values["item_price"]);
                    $counter++;
                }

                ?>
                <tr>
                    <td colspan="3" align="right">Total</td>
                    <td align="right">$ <?php echo number_format($total, 2); ?></td>
                    <td></td>
                </tr>
                <?php
            }
            ?>
        </table>

        <div class="Checkout" class="Checkout">
            <form action="checkout.php" method="POST">
                <input type="submit" value="Volgende" name="Checkout" class="button" required/>
            </form>
        </div>
    </div>
</div>
<br />
</body>
</html>