<?php
session_start();
include ("PHP/connectdb.php");
include ("PHP/functions.php");
include ("header.php");
changeQuantity();
removeIfQuantityBelow();
removeFromCart();
?>


<!DOCTYPE html>
<html>
<head>

</head>
            <?php
            if(!empty($_SESSION["shopping_cart"]))
            {
                echo'
<body>
<br />
<div class="">
    <div style="clear:both"></div>
    <br />
    <h3>Order Details</h3>
    <div class="table-responsive">
        <table class="table-bordered" style="width: 100%">
            <tr>
                <th width="40%">Item Name</th>
                <th width="10%">Quantity</th>
                <th width="20%">Price</th>
                <th width="15%">Total</th>
                <th width="5%">Action</th>
            </tr>
                ';
                $counter = 0;
                $total = 0;
                foreach($_SESSION["shopping_cart"] as $keys => $values) {
                    ?>
                    <tr>
                        <td "><?php echo $values["item_name"] ; ?></td>

                        <td><?php echo $_SESSION['shopping_cart'][$keys]['item_quantity'];  ?>
                            <form method="post" action="cart.php?id=<?php print ($_SESSION['shopping_cart'][$keys]['item_id']); ?>">
                                <input type="submit" name="plus" value="+">
                                <input type="submit" name="min" value="-">
                            </form>
                        </td>


                        <td>$ <?php echo $values["item_price"]; ?></td>
                        <td>$ <?php echo number_format( $_SESSION['shopping_cart'][$keys]['item_quantity'] * $values["item_price"], 2);?></td>
                        <td><a href="cart.php?action=delete&id=<?php echo $_SESSION['shopping_cart'][$keys]['item_id'] ?>"><span style="color: red; text-decoration: none">Remove</span></a></td>
                    </tr>
                    <?php
                    $total = $total + ( $_SESSION['shopping_cart'][$keys]['item_quantity']* $values["item_price"]);
                    $counter++;
                    $_SESSION["total"] = $total;
                }

                ?>
                <tr>
                    <td colspan="3" align="right">Total</td>
                    <td align="right">$ <?php echo number_format($total, 2); ?></td>
                    <td></td>
                </tr>
            </table>
           

            <div class="Checkout">
              <form action="checkout.php?vervoer=bezorgen" method="POST">
                  <input type="hidden" value="<?php echo ($total) ?>" name="total">
                  <input type="submit" value="Volgende" name="Checkout" class="checkout" required/>
             </form>
            </div>
        </div>
    </div>
<br />
</body>
</html>
<?php
            }else{
                echo'
                <div class="alert" style="text-align: center;">
                <alert><h1>Je winkelmand ziet er een beetje leeg uit.</h1></alert>
                <p>Doe hier iets aan!</p>
                </div>
                <div style="text-align: center">
                <img style="width: 25%; height: 25%;" src="https://vintagebakings.com/content/images/empty-cart.gif">
                </div>
                ';
            }
?>