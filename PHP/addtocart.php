<?php
session_start();
include("connectdb.php");

if (!isset($_SESSION["shopping_cart"])) {
    $_SESSION["shopping_cart"] = array();
}

function inShoppingArray($Id) {
    if (!empty($_SESSION["shopping_cart"])) {
        foreach ($_SESSION["shopping_cart"] as $key => $result) {
            if (in_array($Id, $_SESSION["shopping_cart"][$key])) {
                return TRUE;
            }
        }
        return FALSE;
    }
}

if (isset($_POST)) {
    if (inShoppingArray($_POST["hidden_id"])) {
        echo "Product zit al in winkelwagen!";
    } else {
        $stmt = mysqli_prepare($connection, "SELECT StockItemID, StockItemName, UnitPrice, TaxRate FROM stockitems WHERE StockItemID = ?");
        mysqli_stmt_bind_param($stmt, "i", $_POST["hidden_id"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $StockItemID, $StockItemName, $UnitPrice, $TaxRate);
        mysqli_stmt_num_rows($stmt);
        while (mysqli_stmt_fetch($stmt)) {
            $pricewithoutsale = number_format(round(($UnitPrice+(($TaxRate/100)*$UnitPrice)),2),2);
            $itemArray = array(
                "item_id" => $StockItemID,
                "item_name" => $StockItemName,
                "item_price" => $pricewithoutsale,
                "item_quantity" => 1);
            array_push($_SESSION["shopping_cart"], $itemArray);
        }
        echo "Toegevoegd!";
    }
}
