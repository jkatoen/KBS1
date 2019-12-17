<?php
//session_start();
//include ("PHP/connectdb.php");
//include ("PHP/functions.php");
//include ("header.php");
//removeFromCart();
//?>
<!---->
<!--    <!DOCTYPE html>-->
<!--    <html>-->
<!--    <head>-->
<!---->
<!--    </head>-->
<?php
//if(!empty($_SESSION["shopping_cart"]))
//{
//    echo'
//<body>
//<br />
//<div class="">
//    <div style="clear:both"></div>
//    <br />
//    <h3>Order Details</h3>
//    <div class="table-responsive">
//        <table class="table-bordered" style="width: 100%">
//            <tr>
//                <th width="40%">Item Name</th>
//            </tr>
//                ';
//    $counter = 0;
//    $total = 0;
//    foreach($_SESSION["shopping_cart"] as $keys => $values) {
//        ?>
<!--        <tr>-->
<!--            <td ">--><?php //echo $values["item_name"] ; ?><!--</td>-->
<!--            <td>$ --><?php //echo $values["item_price"]; ?><!--</td>-->
<!--            <td>$ --><?php //echo number_format( $_SESSION['shopping_cart'][$keys]['item_quantity'] * $values["item_price"], 2);?><!--</td>-->
<!--            <td><a href="cart.php?action=delete&id=--><?php //echo $_SESSION['shopping_cart'][$keys]['item_id'] ?><!--"><span style="color: red; text-decoration: none">Remove</span></a></td>-->
<!--        </tr>-->
<!--        --><?php
//        $total = $total + ( $_SESSION['shopping_cart'][$keys]['item_quantity']* $values["item_price"]);
//        $counter++;
//        $_SESSION["total"] = $total;
//    }
//
//    ?>
<!--    <tr>-->
<!--        <td colspan="3" align="right">Total</td>-->
<!--        <td align="right">$ --><?php //echo number_format($total, 2); ?><!--</td>-->
<!--        <td></td>-->
<!--    </tr>-->
<!--    --><?php
//}
//
//else {
//    echo'
//                <div class="alert" style="text-align: center;">
//                <alert><h1>Je hebt nog geen favorieten toegevoegd.</h1></alert>
//                </div>
//                <div style="text-align: center">
//                <img style="width: 25%; height: 25%;" src="IMG/star.gif">
//                </div>
//                ';
//}
//?>