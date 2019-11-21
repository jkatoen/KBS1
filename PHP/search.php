<?php
include_once("connectdb.php");

if(isset($_GET["submit"])) {
    $search = "%{$_GET["search"]}%";
    $stmt = $connection->prepare("SELECT StockItemId, StockItemName FROM stockitems WHERE StockItemName LIKE ?");
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows === 0) exit('No rows');
    $stmt->bind_result($StockItemId, $StockItemName);
    while ($stmt->fetch()) {
        echo "Id: {$StockItemId}, Name: {$StockItemName} <br/>";
    }
    $stmt->close();
}
?>
<form method="GET" action="">
<input type="search" name="search">
<input type="submit" name="submit">
</form>