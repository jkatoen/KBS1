<?php
session_start();
include("connectdb.php");
include("functions.php");

if (!isset($_SESSION["favorites_array"])) {
    $_SESSION["favorites_array"] = array();
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
        $stmt = mysqli_prepare($connection, "SELECT StockItemID, StockGroupID, StockItemName, UnitPrice, Photo, MarketingComments, StockGroupId ,TaxRate , (SELECT StockImagePath FROM stockimage WHERE stockitemid = SI.stockitemid LIMIT 1) as StockImagePath
                                        FROM stockitems SI
                                        JOIN StockItemStockGroups  USING (stockitemid) 
                                        JOIN stockgroups USING(stockgroupid) 
                                        WHERE StockItemID = ?
                                        LIMIT 1");
        mysqli_stmt_bind_param($stmt, "i", $_POST["hidden_id"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $StockItemID, $StockGroupID, $StockItemName, $UnitPrice, $Photo, $MarketingComments, $StockGroupId, $TaxRate, $StockImagePath);
        mysqli_stmt_num_rows($stmt);
        while (mysqli_stmt_fetch($stmt)) {
            $pricewithoutsale = number_format(round(($UnitPrice+(($TaxRate/100)*$UnitPrice)),2),2);
            $ProductImage = ($StockImagePath) ? $StockImagePath :  "IMG/category{$StockGroupID}.png";
            $itemArray = array(
                "item_id" => $StockItemID,
                "item_name" => $StockItemName,
                "item_image" => $ProductImage,
                "item_description" => $MarketingComments,
                "item_price" => $pricewithoutsale,
                "item_quantity" => 1);
            array_push($_SESSION["favorites_array"], $itemArray);
        }
        mysqli_stmt_close($stmt);
        echo "Toegevoegd!";
    }
}