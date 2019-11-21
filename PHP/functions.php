<?php
function getURI()
{
    if (!empty($_SERVER['HTTPS']) && ('on' === $_SERVER['HTTPS'])) {
        return 'https://'.$_SERVER['HTTP_HOST' . '/index.php'] ;
    } else {
        return 'http://'.$_SERVER['HTTP_HOST' . '/index.php'];
    }
}

function getFullURI() {
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

function isCategorySet() {
    if (isset($_GET['category'])) {
        return $_GET['category'];
    } elseif (!isset($_SESSION['category'])) {
        echo "404 Page not found";
        exit;
    } else {
        return $_SESSION['category'];
    }
}

function getCountProductsPagination($connection, $category) {
    $total_pages_sql = "SELECT COUNT(*) FROM stockitemstockgroups 
                            JOIN stockitems USING (StockItemID) 
                            WHERE StockGroupId = {$category}";
    $result = mysqli_query($connection, $total_pages_sql);
    $total_rows = mysqli_fetch_array($result)[0];
    return $total_rows;
}
function getCountSearchPagination($connection, $searchinput) {
    $total_pages_sql = "SELECT COUNT(*) FROM stockitems 
                            WHERE SearchDetails LIKE '%{$searchinput}%'";
    $result = mysqli_query($connection, $total_pages_sql);
    $total_rows = mysqli_fetch_array($result)[0];
    return $total_rows;
}

function displayLeftCategories($connection)
{
    $sql2 = "select StockGroupId, stockgroupname, count(*) from stockgroups
         join  stockitemstockgroups using(stockgroupid)
         join stockitems using(stockitemid)
         group by stockgroupname";
    $result2 = mysqli_query($connection, $sql2);

    while ($row = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
        $ID = $row["StockGroupId"];
        $group = $row["stockgroupname"];
        $amount = $row["count(*)"];
        print("<div class='category-item'>");
        print("<a href='category.php?category={$ID}'>$group $amount</a>");
        print("</div>");
    }
}
function displayCategoryName($connection, $category) {
    $stmt = $connection->prepare("SELECT StockGroupName FROM stockgroups WHERE StockGroupID = ?") ;
    $stmt->bind_param("i", $category);
    $stmt->execute();
    $stmt->store_result();
    // Page not found moet nog toegevoegd worden!
    if ($stmt->num_rows === 0) {
        exit('404 Page Not Found');
    }
    $stmt->bind_result($StockGroupName);
    $stmt->fetch();
    print $StockGroupName;
}

function displayCategoryProducts($connection, $category, $offset, $no_of_records_per_page)
{
    $stmt = $connection->prepare("SELECT StockItemName, UnitPrice, TaxRate, StockItemID, StockGroupID, Photo FROM stockitemstockgroups 
                                            JOIN stockitems USING (StockItemID) 
                                            WHERE StockGroupId = ?
                                            LIMIT ?, ?");
    $stmt->bind_param("iii", $category, $offset, $no_of_records_per_page);
    $stmt->execute();
    $stmt->store_result();
    // Page not found moet nog toegevoegd worden!
    if ($stmt->num_rows === 0) {
        exit('404 Page Not Found');
    }
    $stmt->bind_result($StockItemName, $UnitPrice, $TaxRate, $StockItemID, $StockGroupID, $Photo);

    while ($stmt->fetch()) {
        print("<a class='logolink' href='product.php?id=$StockItemID'><div class='product-item'> ");
        if (!empty($Photo)) {
            echo "<img style='height: 200px;' src='data:image/jpeg;base64,".base64_encode( $Photo )."'/>";
        } else {
            echo "<img style='height: 100px; width:100px;' src='IMG/category{$StockGroupID}.png'/>";
        }
        //print("<div class=\"fakeimg\" style=\"height:200px;\">Image</div>");
        print("</br>".$StockItemName." $".  number_format(round(($UnitPrice+(($TaxRate/100)*$UnitPrice)),2),2));
        print("</div></a>");
    }
    $stmt->close();
}

function displayAllProducts($connection)
{
    $stmt = $connection->prepare("SELECT StockItemName, UnitPrice, TaxRate, StockItemID, StockGroupID, Photo FROM stockitemstockgroups 
                                            JOIN stockitems USING (StockItemID)
                                            GROUP BY StockItemId");
    $stmt->execute();
    $stmt->store_result();
    // Page not found moet nog toegevoegd worden!
    if ($stmt->num_rows === 0) {
        exit('404 Page Not Found');
    }
    $stmt->bind_result($StockItemName, $UnitPrice, $TaxRate, $StockItemID, $StockGroupID, $Photo);

    while ($stmt->fetch()) {
        print("<a class='logolink' href='product.php?id=$StockItemID'><div class='product-item'> ");
        if (!empty($Photo)) {
            echo "<img style='height: 200px;' src='data:image/jpeg;base64,".base64_encode( $Photo )."'/>";
        } else {
            echo "<img style='height: 100px; width:100px;' src='IMG/category{$StockGroupID}.png'/>";
        }
        //print("<div class=\"fakeimg\" style=\"height:200px;\">Image</div>");
        print("</br>".$StockItemName." $".  number_format(round(($UnitPrice+(($TaxRate/100)*$UnitPrice)),2),2));
        print("</div></a>");
    }
    $stmt->close();
}

function ifSearched()
{
    if (isset($_GET['submitinput'])) {
        return TRUE;
    }
}

function displaySearchProducts($connection, $searchinput, $offset, $no_of_records_per_page)
{


    $intconvert = (int)$searchinput;

    if($intconvert != 0){
        $search = "$searchinput";
        $stmt = $connection->prepare("SELECT StockItemID, StockItemName, UnitPrice, TaxRate, StockGroupID, Photo FROM stockitemstockgroups 
                                            JOIN stockitems USING (StockItemID)
                                            JOIN stockgroups USING (StockGroupID) 
                                            WHERE StockItemID = ? LIMIT 1 offset 1");
        $stmt->bind_param("s", $search);
        $stmt->execute();
        $stmt->store_result();
        //if ($stmt->num_rows === 0) exit('No rows'); IF NO RESULT SHOW SOMETHING ELSE
        $stmt->bind_result($StockItemID, $StockItemName, $UnitPrice, $TaxRate, $StockGroupID, $Photo);
        while ($stmt->fetch()) {
            print("<a class='logolink' href='product.php?id=$StockItemID'><div class='product-item'> ");
            if (!empty($Photo)) {
                echo "<img style='height: 200px;' src='data:image/jpeg;base64,".base64_encode( $Photo )."'/>";
            } else {
                echo "<img style='height: 100px; width:100px;' src='IMG/category{$StockGroupID}.png'/>";
            }
            //print("<div class=\"fakeimg\" style=\"height:200px;\">Image</div>");
            print("</br>".$StockItemName." $".  number_format(round(($UnitPrice+(($TaxRate/100)*$UnitPrice)),2),2));
            print("</div></a>");
        }
        $stmt->close();
    }elseif($intconvert ==0){
        $search = "%$searchinput%";
        $stmt = $connection->prepare("SELECT StockItemID, StockItemName, UnitPrice, TaxRate, StockGroupID, Photo FROM stockitemstockgroups 
                                            JOIN stockitems USING (StockItemID)
                                            JOIN stockgroups USING (StockGroupID) 
                                            WHERE searchdetails LIKE ? LIMIT ?,?");
        $stmt->bind_param("sii", $search, $offset, $no_of_records_per_page);
        $stmt->execute();
        $stmt->store_result();
        //if ($stmt->num_rows === 0) exit('No rows'); IF NO RESULT SHOW SOMETHING ELSE
        $stmt->bind_result($StockItemID, $StockItemName, $UnitPrice, $TaxRate, $StockGroupID, $Photo);
        while ($stmt->fetch()) {
            print("<a class='logolink' href='product.php?id=$StockItemID'><div class='product-item'> ");
            if (!empty($Photo)) {
                echo "<img style='height: 200px;' src='data:image/jpeg;base64,".base64_encode( $Photo )."'/>";
            } else {
                echo "<img style='height: 100px; width:100px;' src='IMG/category{$StockGroupID}.png'/>";
            }
            //print("<div class=\"fakeimg\" style=\"height:200px;\">Image</div>");
            print("</br>".$StockItemName." $".  number_format(round(($UnitPrice+(($TaxRate/100)*$UnitPrice)),2),2));
            print("</div></a>");
        }
        $stmt->close();
    }



}

function setPage() {
    if (isset($_GET['pageno'])) {
        return $_GET['pageno'];
    } else {
        return 1;
    }
}
function setRecordsPerPageSession(){
    if (isset($_POST['rpp'])) {
        $_SESSION['rpp'] = $_POST['rpp'];
    } elseif (!isset($_SESSION['rpp'])) {
        $_SESSION['rpp'] = 25;
    }
}

function accountAanmaken() {
    $voornaam = $_POST["voornaam"];
    $achternaam = $_POST["achternaam"];
    $mail = $_POST["emailadres"];
    $volnaam = $voornaam.$achternaam;
    $password = password_hash(($_POST["password"]), PASSWORD_DEFAULT);
    var_dump($password);
    var_dump($volnaam);
    $sql1 = "INSERT INTO people (FullName, IsPermitted, HashedPassword, IsSystemUser, IsEmployee, IsSalesperson, EmailAddress)
            VALUES ($volnaam, 1, $password, 1, 0, 0, $mail)";
    /*$sql2 = "INSERT INTO "*/


}
function displayPagination($total_pages, $pageno) {
    if ($total_pages >= 1) {
        // First page button
        $disabled = ($pageno <= 1) ? "disabled" : "";
        print "<a href='?pageno=1'><button {$disabled}>First</button></a>";
        // Previous page button
        $disabled = ($pageno <= 1) ? "disabled" : "";
        print "<a href=?pageno=".($pageno-1)."><button {$disabled}>Prev</button></a>";
        // Next page button
        $disabled = ($pageno >= $total_pages) ? "disabled" : "";
        print "<a href=?pageno=".($pageno+1)."><button {$disabled}>Next</button></a>";
        // Last page button
        print "<a href=?pageno={$total_pages}><button {$disabled}>Last</button></a>";
    }
    print '
    <form action="" method="post">
        <a href="{getFullURI();}"><input type="submit" value="25" name="rpp"></a>
        <a href="{getFullURI();}"><input type="submit" value="50" name="rpp"></a>
        <a href="{getFullURI();}"><input type="submit" value="100" name="rpp"></a>
    </form>';

}