<?php
session_start();
include("connectdb.php");
include("functions.php");

if (!isset($_SESSION["favorites_array"])) {
    $_SESSION["favorites_array"] = array();
}

function inFavoriteArray($Id) {
    if (!empty($_SESSION["favorites_array"])) {
        foreach ($_SESSION["favorites_array"] as $key => $result) {
            if (in_array($Id, $_SESSION["favorites_array"][$key])) {
                return TRUE;
            }
        }
        return FALSE;
    }
}

if (isset($_POST["hidden_id"])) {
    if (inFavoriteArray($_POST["hidden_id"])) {
        foreach ($_SESSION["favorites_array"] as $key => $value) {
            if ($value["item_id"] == $_POST["hidden_id"]) {
                unset ($_SESSION["favorites_array"][$key]);
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
            array_push($_SESSION["favorites_array"], $itemArray);
        }
        echo "Toegevoegd!";
    }
}