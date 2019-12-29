<?php
session_start();
include("connectdb.php");
include("functions.php");

if (!isset($_SESSION["shopping_cart"])) {
    $_SESSION["shopping_cart"] = array();
}

function inShoppingArray($Id) {
    $found = array_search($Id, array_column($_SESSION["shopping_cart"], 'item_id'));
    if ($found !== FALSE) {
        return TRUE;
    } else {
        return FALSE;
    }
}

if (isset($_POST["hidden_id"])) {
    if (inShoppingArray($_POST["hidden_id"])) {
        foreach ($_SESSION["shopping_cart"] as $key => $value) {
            if ($value["item_id"] == $_POST["hidden_id"]) {
                unset ($_SESSION["shopping_cart"][$key]);
            }
        }
        echo "Verwijderd!";
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

if (isset($_POST["addCart_id"])) {
    $StockItemID = $_POST["addCart_id"];
    if (!empty($_SESSION["favorites_array"])) {
        if (inShoppingArray($_POST["addCart_id"])) {
            foreach ($_SESSION["shopping_cart"] as $key => $value) {
                if ($value["item_id"] == $_POST["hidden_id"]) {
                    unset ($_SESSION["shopping_cart"][$key]);
                }
            }
            echo "Verwijderd!";
        } else {
            foreach ($_SESSION["favorites_array"] as $key => $value) {
                if ($value["item_id"] == $_POST["addCart_id"]) {
                    $itemArray = array(
                        "item_id" => $value["item_id"],
                        "item_name" => $value["item_name"],
                        "item_price" => $value["item_price"],
                        "item_quantity" => 1);
                    array_push($_SESSION["shopping_cart"], $itemArray);
                    echo "Toegevoegd!";
                }
            }
        }
    }
}