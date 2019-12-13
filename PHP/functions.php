<?php
/**
 * Gets the server link and returns it, in this case it also returns going back to index.php
 * Either HTTP or HTTPS
 * Frans Tuinstra
 * @return string
 */
function getURI()
{
    if (!empty($_SERVER['HTTPS']) && ('on' === $_SERVER['HTTPS'])) {
        return 'https://' . $_SERVER['HTTP_HOST'] . '/index.php';
    } else {
        return 'http://' . $_SERVER['HTTP_HOST'] . '/index.php';
    }
}
/**
 * Gets the full server link and returns it, this including things like 'categoryid=1'
 * Either HTTP or HTTPS
 * Frans Tuinstra
 * @return string
 */
function getFullURI() {
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

/**
 * Check if category is set to display category products,
 * if none is set it'll redirect to the home page,
 * else it will check for the uri and will change accordingly
 * Frans Tuinstra
 * @return mixed
 */
function isCategorySet() {
    if (isset($_GET['category'])) {
        return $_GET['category'];
    } elseif (!isset($_SESSION['category'])) {
        header('location:index.php');
        exit;
    } else {
        return $_SESSION['category'];
    }
}

/**
 * Pagination sets the page
 * @return int|mixed
 */
function setPage() {
    if (isset($_GET['pageno'])) {
        return $_GET['pageno'];
    } else {
        return 1;
    }
}

/**
 * Pagination sets the amount of found items each page
 */
function setRecordsPerPageSession(){
    if (isset($_POST['rpp'])) {
        $_SESSION['rpp'] = $_POST['rpp'];
    } elseif (!isset($_SESSION['rpp'])) {
        $_SESSION['rpp'] = 25;
    }
}

/**
 * Pagination returns total found rows to adjust pagination
 * Can't we just make this into 1 function with getCountSearchPagination?
 * Frans Tuinstra
 * @param $connection
 * @param $category
 * @return int
 */
function getCountProductsPagination($connection, $category) {
    $total_rows = 0;
    $total_pages_sql = "SELECT COUNT(*) FROM stockitemstockgroups 
                            JOIN stockitems USING (StockItemID) 
                            WHERE StockGroupId = {$category}";
    $result = mysqli_query($connection, $total_pages_sql);
    if ($result) {
        $total_rows = mysqli_fetch_array($result)[0];
    }
    return $total_rows;
}

/**
 * Pagination returns total found rows to adjust pagination
 * Can't we just make this into 1 function with getCountProductsPagination?
 * Frans Tuinstra
 * @param $connection
 * @param $searchinput
 * @return int
 */
function getCountSearchPagination($connection, $searchinput) {
    $total_rows = 0;
    $total_pages_sql = "SELECT COUNT(*) FROM stockitems 
                            WHERE SearchDetails LIKE '%{$searchinput}%'";
    $result = mysqli_query($connection, $total_pages_sql);
    if ($result) {
        $total_rows = mysqli_fetch_array($result)[0];
    }
    return $total_rows;
}

/**
 * Displays the categories in the left-hand menu
 * Bas Hendriks
 * @param $connection
 */
function displayLeftCategories($connection) {
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

/**
 * Displays the current category name for the category.php page
 * Frans Tuinstra
 * @param $connection
 * @param $category
 */
function displayCategoryName($connection, $category) {
    $stmt = $connection->prepare("SELECT StockGroupName FROM stockgroups WHERE StockGroupID = ?") ;
    $stmt->bind_param("i", $category);
    $stmt->execute();
    $stmt->store_result();
    // Page not found moet nog toegevoegd worden!
    if ($stmt->num_rows === 0) {
        header('location:index.php');
        exit;
    }
    $stmt->bind_result($StockGroupName);
    $stmt->fetch();
    print $StockGroupName;
}

/**
 * Displays products from set categories with adjusted price (incl tax)
 * Frans Tuinstra, Julian
 * @param $connection
 * @param $category
 * @param $offset
 * @param $no_of_records_per_page
 */
function displayCategoryProducts($connection, $category, $offset, $no_of_records_per_page) {
    $stmt = $connection->prepare("SELECT StockItemName, UnitPrice, TaxRate, StockItemID, StockGroupID, (SELECT StockImagePath FROM stockimage WHERE stockitemid = SI.stockitemid LIMIT 1) as StockImagePath
                                            FROM stockitemstockgroups 
                                            JOIN stockitems SI USING (StockItemID) 
                                            WHERE StockGroupId = ?
                                            LIMIT ?, ?");
    $stmt->bind_param("iii", $category, $offset, $no_of_records_per_page);
    $stmt->execute();
    $stmt->store_result();
    // Page not found moet nog toegevoegd worden!
    if ($stmt->num_rows === 0) {
        header('location:index.php');
        exit;
    }
    $stmt->bind_result($StockItemName, $UnitPrice, $TaxRate, $StockItemID, $StockGroupID, $StockImagePath);

    while ($stmt->fetch()) {
        print("<a class='logolink' href='product.php?id=$StockItemID'><div class='product-item'> ");
        if (!empty($StockImagePath)) {
            print("<div class=\"fakeimg\" >");
            echo "<img class='img' src='{$StockImagePath}'/>";
            print("</div>");
        } else {
            print("<div class=\"fakeimg\" >");
            echo "<img class='img' src='IMG/category{$StockGroupID}.png'/>";
            print("</div>");
        }
        //print("<div class=\"fakeimg\" style=\"height:200px;\">Image</div>");
        print("</br>".$StockItemName." €".  number_format(round(($UnitPrice+(($TaxRate/100)*$UnitPrice)),2),2));
        print("</div></a>");
    }
    $stmt->close();
}

/**
 * Displays found products on search.php from the $searchinput done by the user
 * Also checks if searching for articleid (is integer) or just keywords (string)
 * Bas Hendriks
 * @param $connection
 * @param $searchinput
 * @param $offset
 * @param $no_of_records_per_page
 */
function displaySearchProducts($connection, $searchinput, $offset, $no_of_records_per_page) {
    $intconvert = (int)$searchinput;
    if($intconvert != 0) {
        $search = "$searchinput";
        $stmt = $connection->prepare("SELECT StockItemID, StockItemName, UnitPrice, TaxRate, StockGroupID, (SELECT StockImagePath FROM stockimage WHERE stockitemid = SI.stockitemid LIMIT 1) as StockImagePath
                                            FROM stockitemstockgroups 
                                            JOIN stockitems SI USING (StockItemID)
                                            JOIN stockgroups USING (StockGroupID) 
                                            WHERE StockItemID = ? LIMIT 1 offset 1");
            $stmt->bind_param("s", $search);
            $stmt->execute();
            $stmt->store_result();
            //if ($stmt->num_rows === 0) exit('No rows'); IF NO RESULT SHOW SOMETHING ELSE
            $stmt->bind_result($StockItemID, $StockItemName, $UnitPrice, $TaxRate, $StockGroupID, $StockImagePath);
            while ($stmt->fetch()) {
                print("<a class='logolink' href='product.php?id=$StockItemID'><div class='product-item'> ");
                if (!empty($StockImagePath)) {
                    print("<div class=\"fakeimg\" >");
                    echo "<img class='img' src='{$StockImagePath}'/>";
                    print("</div>");
                } else {
                    print("<div class=\"fakeimg\" >");
                    echo "<img class='img' src='IMG/category{$StockGroupID}.png'/>";
                    print("</div>");
                }
                //print("<div class=\"fakeimg\" style=\"height:200px;\">Image</div>");
                print("</br>".$StockItemName." €".  number_format(round(($UnitPrice+(($TaxRate/100)*$UnitPrice)),2),2));
                print("</div></a>");
            }

        $stmt->close();
    }elseif($intconvert ==0) {
        $search = "%$searchinput%";
        $stmt = $connection->prepare("SELECT StockItemID, StockItemName, UnitPrice, TaxRate, StockGroupID, (SELECT StockImagePath FROM stockimage WHERE stockitemid = SI.stockitemid LIMIT 1) as StockImagePath
                                            FROM stockitemstockgroups 
                                            JOIN stockitems SI USING (StockItemID)
                                            JOIN stockgroups USING (StockGroupID) 
                                            WHERE searchdetails LIKE ? group by stockitemid LIMIT ?,?");
            $stmt->bind_param("sii", $search, $offset, $no_of_records_per_page);
            $stmt->execute();
            $stmt->store_result();
            //if ($stmt->num_rows === 0) exit('No rows'); IF NO RESULT SHOW SOMETHING ELSE
            $stmt->bind_result($StockItemID, $StockItemName, $UnitPrice, $TaxRate, $StockGroupID, $StockImagePath);
            while ($stmt->fetch()) {
                print("<a class='logolink' href='product.php?id=$StockItemID'><div class='product-item'> ");
                if (!empty($StockImagePath)) {
                    print("<div class=\"fakeimg\" >");
                    echo "<img class='img' src='{$StockImagePath}'/>";
                    print("</div>");
                } else {
                    print("<div class=\"fakeimg\" >");
                    echo "<img class='img' src='IMG/category{$StockGroupID}.png'/>";
                    print("</div>");
                }
                //print("<div class=\"fakeimg\" style=\"height:200px;\">Image</div>");
                print("</br>".$StockItemName." €".  number_format(round(($UnitPrice+(($TaxRate/100)*$UnitPrice)),2),2));
                print("</div></a>");
            }
        $stmt->close();
    }
}

/**
 * Displays on deal items on home page
 * Bas Hendriks
 * @param $connection
 */
function DisplaySpecialItems($connection) {
    //$stmt = $connection->prepare("SELECT StockItemName, UnitPrice, StockItemId  FROM stockitems limit $offset, $no_of_records_per_page");
    $stmt = $connection->prepare("select StockItemName, UnitPrice, StockItemId, Photo , StockGroupId ,TaxRate , (SELECT StockImagePath FROM stockimage WHERE stockitemid = SI.stockitemid LIMIT 1) as StockImagePath
                                        from stockitems SI
                                        join StockItemStockGroups  using (stockitemid) 
                                        join stockgroups using(stockgroupid) 
                                        where stockgroupid in(select stockgroupid from specialdeals)");
    $stmt->execute();
    $stmt->store_result();
    //if ($stmt->num_rows === 0) exit('No rows');
    $stmt->bind_result($StockItemName, $UnitPrice, $StockItemId, $Photo, $StockGroupID, $TaxRate, $StockImagePath);
    while ($stmt->fetch()) {
        $pricewithoutsale = number_format(round(($UnitPrice+(($TaxRate/100)*$UnitPrice)),2),2);
        $saleprice = '€'. number_format(round((($UnitPrice*1.25)+(($TaxRate/100)*$UnitPrice)),2),2);
        print("<a class='logolink' href='product.php?id=$StockItemId'>");
        print("<div class='product-item'>");
        if (!empty($StockImagePath)) {
            print("<div class=\"fakeimg\" >");
            echo "<img class='img' src='{$StockImagePath}'/>";
            print("</div>");
        } else {
            print("<div class=\"fakeimg\" >");
            echo "<img class='img' src='IMG/category{$StockGroupID}.png'/>";
            print("</div>");
        }

        print("</br>".$StockItemName."   <span class='strikeout'>$saleprice</span>€<bold style= color:;> $pricewithoutsale</bold>");
        //print("<div class='grid-item-content'>");
        print("</div>");
        print("</a>");
    }
    $stmt->close();
}

function accountAanmaken($connection) {
    $voornaam = $_POST["voornaam"];
    $achternaam = $_POST["achternaam"];
    $address = $_POST["adres"];
    $ww = password_hash(($_POST["ww"]), PASSWORD_DEFAULT);
    $mail = $_POST["emailadres"];
    $sqlinsert1 = ("INSERT INTO user (FirstName, LastName, Address, Password, Emailadres)
                        VALUES (?,?,?,?,?)");
    if ($stmt = $connection->prepare($sqlinsert1)) {
        $stmt->bind_param('sssss', $voornaam, $achternaam, $address, $ww, $mail);
        $stmt->execute();
        //printf("Registreren gelukt!", $stmt->affected_rows);
        $stmt->close();
        $connection->close();
        $_SESSION["ingelogd"] = true;
        $_SESSION["email"] = $mail;
        $_SESSION["firstname"] = $voornaam;
        $_SESSION["lastname"] = $achternaam;
        $_SESSION["address"] = $address;
        header('location: index.php');
        exit();
    }
}

function logIn($connection) {
}

function displayPagination($total_pages, $pageno) {
    // css
    echo "<div class='page-border'>";
    if ($total_pages >= 1) {
        // First page button
        $disabled = ($pageno <= 1) ? "disabled" : "";
        print "<a href='?pageno=1'><button class='page-btn' {$disabled}>First</button></a>";
        // Previous page button
        $disabled = ($pageno <= 1) ? "disabled" : "";
        print "<a href=?pageno=".($pageno-1)."><button class='page-btn' {$disabled}>Prev</button></a>";
        // Next page button
        $disabled = ($pageno >= $total_pages) ? "disabled" : "";
        print "<a href=?pageno=".($pageno+1)."><button class='page-btn' {$disabled}>Next</button></a>";
        // Last page button
        print "<a href=?pageno={$total_pages}><button class='page-btn' {$disabled}>Last</button></a>";
    }
    if ($total_pages > 1 || $_SESSION['rpp'] != 25) {
        print '
            <form style="float: right; margin-left: 20px" action="" method="post">
                <a  href="{getFullURI();}"><input class=\'page-btn\' type="submit" value="25" name="rpp"></a>
                <a  href="{getFullURI();}"><input class=\'page-btn\' type="submit" value="50" name="rpp"></a>
                <a  href="{getFullURI();}"><input class=\'page-btn\' type="submit" value="100" name="rpp"></a>
            </form> </div>';
    }
}

/**
 * @param $connection
 * @param $searchinput
 * @return mixed
 */
function displaySearchRows($connection, $searchinput)
{
    $intconvert = (int)$searchinput;
    if ($intconvert != 0) {
        $search = "$searchinput";
        $stmt = $connection->prepare("SELECT StockItemID, StockItemName, UnitPrice, TaxRate, StockGroupID, Photo FROM stockitemstockgroups 
                                            JOIN stockitems USING (StockItemID)
                                            JOIN stockgroups USING (StockGroupID) 
                                            WHERE StockItemID = ? LIMIT 1 offset 1");
    } elseif ($intconvert == 0) {
        $search = "%$searchinput%";
        $stmt = $connection->prepare("SELECT StockItemID, StockItemName, UnitPrice, TaxRate, StockGroupID, Photo FROM stockitemstockgroups 
                                            JOIN stockitems USING (StockItemID)
                                            JOIN stockgroups USING (StockGroupID) 
                                            WHERE searchdetails LIKE ? group by stockitemid");
    }
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $stmt->store_result();
    $amountRows = $stmt->num_rows;
    $stmt->close();
    return $amountRows;
}

function productSQL($connection) {
    $productSQL = "SELECT StockGroupId, StockItemId, StockItemName, MarketingComments, UnitPrice, TaxRate, QuantityOnHand
        FROM stockitems
        JOIN stockitemstockgroups USING (StockItemId)
        JOIN stockitemholdings USING (StockItemId)
        WHERE StockItemId = {$_GET['id']}
        LIMIT 1;"; // Limit 1 omdat er meerdere categorieën bij een product kan zijn
    $productStmt = mysqli_prepare($connection, $productSQL);
    mysqli_stmt_execute($productStmt);
    return mysqli_stmt_get_result($productStmt);
}

function imageSQL($connection) {
    $imageSQL = "SELECT StockItemID, StockImagePath
                       FROM stockitems
                       JOIN stockimage USING (StockItemID)
                       WHERE StockItemID = {$_GET['id']}";
    $stmt = mysqli_prepare($connection, $imageSQL);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function checkIfAlreadyExists($inputEmail, $connection) {
    $stmt = $connection->prepare("SELECT Emailadres FROM user WHERE Emailadres = ?");
    $stmt->bind_param("s", $inputEmail);
    $stmt->execute();
    $stmt->store_result();
    return ($stmt->num_rows === 0) ? FALSE : TRUE;
}

function changeQuantity() {
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
}

function removeIfQuantityBelow() {
    if (isset($_SESSION["shopping_cart"])) {
        foreach ($_SESSION["shopping_cart"] as $keys => $values) {
            if ($values["item_quantity"] <= 0) {
                unset($_SESSION["shopping_cart"][$keys]);
            }
        }
    }
}

function addToCart() {
    if (isset($_POST["add_to_cart"]) && isset($_GET['id'])) {
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
                echo '<script>window.location="product.php?id='. $_GET["id"].'&alert=1"</script>';
            } else {
                echo '<script>window.location="product.php?id='. $_GET["id"].'&alert=2"</script>';

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
}

function removeFromCart() {
    if (isset($_GET["action"])) {
        if ($_GET["action"] == "delete") {
            foreach ($_SESSION["shopping_cart"] as $keys => $values) {
                if ($values["item_id"] == $_GET["id"]) {
                    unset($_SESSION["shopping_cart"][$keys]);
                    echo '<script>window.location="cart.php"</script>';
                }
            }
        }
    }
}

function checkIfCartEmpty() {
    if (empty($_SESSION['shopping_cart'])) {
        header('location: index.php');
    }
}

function checkUserMadeReview($connection, $user_id, $item_id) {
    // if the user_id already made a review for the item_id return true
    $stmt = mysqli_prepare($connection, "SELECT * FROM review WHERE StockItemID = ? AND AccountID = ?");
    mysqli_stmt_bind_param($stmt, "ii", $item_id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if(mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        return TRUE;
    } else {
        mysqli_stmt_close($stmt);
        return FALSE;
    }
}

function getReviewScoreTotal($connection, $item_id) {
    $stmt = mysqli_prepare($connection, "SELECT Rating FROM review WHERE StockItemID = ?");
    mysqli_stmt_bind_param($stmt, "i", $item_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $Rating);
    $amountReviews = mysqli_stmt_num_rows($stmt);

    if ($amountReviews === 0) {
        echo "No rating has been made yet!";
    } else {
        $totalRating = 0;
        while (mysqli_stmt_fetch($stmt)) {
            $totalRating += $Rating;
        }
        $totalScore = $totalRating / $amountReviews;
        $avgScore = ($totalScore *2) / 2;
        $wholeStar = floor($avgScore);
        $amountColoredStars = 0;
        for ($i = 0; $i < $wholeStar; $i++) {
            echo "<img class='review_star' src='IMG/fullstar.png'>";
            $amountColoredStars++;
        }
        if ($wholeStar < $avgScore) {
            echo "<img class='review_star' src='IMG/halfstar.png'>";
            $amountColoredStars++;
        }
        if ($amountColoredStars != 5) {
            $x = 5 - $amountColoredStars;
            for ($z = 0; $z < $x; $z++) {
                echo "<img class='review_star' src='IMG/emptystar.png'>";
            }
        }
    }
}

function displayReview($connection, $item_id) {
    $stmt = mysqli_prepare($connection, "SELECT Rating, Review, FirstName, LastName FROM review JOIN user USING (AccountID) WHERE StockItemID = ?");
    mysqli_stmt_bind_param($stmt, "i", $item_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) != 0) {
        mysqli_stmt_bind_result($stmt, $Rating, $Review, $FirstName, $LastName);
        echo "<table class='display_reviews'><th>Rating</th><th>Door</th>";
        while (mysqli_stmt_fetch($stmt)) {
            echo "<tr><td>";
            for ($i = 0; $i < $Rating; $i++) {
                echo "<img class='review_star' src='IMG/fullstar.png'>";
            }
            echo "<tr><td>{$Review}</td></td><td>{$FirstName} {$LastName}</td></tr></tr>";
        }
        echo "</table>";
    }
}
?>