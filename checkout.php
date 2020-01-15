
<?php
session_start();
include("PHP/connectdb.php");
include("PHP/functions.php");
include ("header.php");
checkIfCartEmpty();

$total = $_SESSION["total"];
$shippingCostsFreeLimit = 50;

?>

<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="sweetalert2.all.min.js"></script>
    <!-- Optional: include a polyfill for ES6 Promises for IE11 -->
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>
    <h1 style="text-align: center">Checkout</h1>
</head>

<script>
    $(document).ready(function(){
        $(".addDiscount").click(function(){
            var discount_code = $(".input_discount").val();
            // AJAX Code To Submit Form.
            $.ajax({
                type: "POST",
                url: "PHP/adddiscount.php",
                data: {discount_code:discount_code},
                cache: false,
                success: function (success) {
                    if (success.length === 0) {
                        $(".input_discount").css("border-color", "red");
                        $(".input_discount").attr("placeholder", "Geen geldige kortingscode!");
                    } else {
                        $(".discountResult").text(success + "% korting!");

                        // Now display the discount <tr>, change the style visibilty to visible and display to contents,
                        $(".hidden_discount_tr").css("visibility", "visible");
                        $(".hidden_discount_tr").css("display", "contents");

                        // Make it so the buttons dissapear when its filled, so that the user can't use the discount again
                        $(".input_discount").css("display", "none");
                        $(".add_invis").css("display", "none");
                        $(".addDiscount").css("display", "none", "visibility", "hidden");

                        // change the text of the td to display percentage
                        $(".hidden_discount_td").text(success + "% korting!");
                        var oldPriceInt = Number($(".total_price").html().replace(/[^0-9.-]+/g,""));
                        var newPriceInt = oldPriceInt*((100-success)/100);
                        $(".total_price").html("€"+newPriceInt);
                    }
                }
            })
        })
    });
    $(document).ready(function(){
        $(".add_naw").click(function(){
            var lastname = $(".lastname").val();
            var firstname = $(".firstname").val();
            var address = $(".address").val();
            var email = $(".email").val();
            // AJAX Code To Submit Form.
            $.ajax({
                type: "POST",
                url: "PHP/addnaw.php",
                data: {lastname, firstname, address, email},
                cache: false,
                success: function (result) {
                }
            });
        });
    });
</script>
<body>

<div class="row">
    <div class="leftcolumn" style="margin-left: 5%">
    </div>
    <div class="midcolumn" style="width: 25%;">
<!--                <form action="--><?php //getURI() ?><!--" method="get">-->
                    <?php if (!isset($_SESSION["ingelogd"])) { ?>
                        <p><a href="login.php">log in,</a> of voer uw NAW-gegevens in </p>
                        <table>
                            <tr><td class="vervoer">Achternaam:</td><td><input class="lastname" type="text"> </td></tr>
                            <tr><td class="vervoer">Voornaam:</td><td><input class="firstname" type="text" >   </td></tr>
                            <tr><td class="vervoer">Adres:</td><td><input class="address" type="text">      </td></tr>
                            <tr><td class="vervoer">Emailadres: </td><td><input class="email" type="email" > </td></tr>
                        <tr><td class="vervoer">submit: </td><td><button class="add_naw" > toevoegen </button></td></tr>
                        </table>
                    <?php }
                    else { ?>
                        <table>
                            <tr><td class="vervoer">Achternaam: </td><td><input type="text" name="achternaam" value="<?php print($_SESSION["lastname"])?>"/>    </td></tr>
                            <tr><td class="vervoer">Voornaam: </td><td><input type="text" name="voornaam" value="<?php print($_SESSION["firstname"])?>"/>     </td></tr>
                            <tr><td class="vervoer">Adres: </td><td><input type="text" name="adres" value="<?php print($_SESSION["address"])?>"/>          </td></tr>
                            <tr><td class="vervoer">Emailadres: </td><td><input type="email" name="emailadres" value="<?php print($_SESSION["email"])?>"/>      </td></tr>
                            <input type="hidden" name="accountid" value="<?php print($_SESSION["accountID"])?>"/>
                        </table>
                        <?php
                    }
                    ?>
<!--                </form>-->
    </div>
    <div class="rightcolumn" >
                    <p>Items in je winkelmand</p>
                    <?php
                    $bezorgen = true;
                    if (isset($_SESSION["shopping_cart"])) {
                        echo "<table><th>Item</th><th>Aantal</th><th>Prijs</th>";
                        foreach ($_SESSION["shopping_cart"] as $item) {
                            echo "<tr><td>".$item["item_name"]."</td><td>".$item["item_quantity"]."</td><td>" . "€".number_format($item["item_price"]*$item["item_quantity"],2)."</td></tr>";
                        }
                        // Discount
                        if (isset($discountpercentage)) {
                            echo "<tr><td>Korting</td><td>$discountpercentage</td><td>leeg</td></tr>";
                        } else {
                            echo "<tr class='hidden_discount_tr' style='visibility:hidden; display:none;'<td></td><td class='hidden_discount_td'></td><td></td></tr>";
                        }
                        // End Discount

                        // Shipping costs?
                        if (isset($_GET) && isset($_GET["vervoer"]) && $_GET["vervoer"] == "bezorgen"){
                            $bezorgen = true;
                            echo "<tr><td>Verzendkosten</td><td>1</td><td>€6.95</td></tr>";
                            if ($total > $shippingCostsFreeLimit) {
                                $totaal = number_format(($total + 6.95), 2);
                                //echo "<tr><td>Totaal</td><td></td><td class='total_price'>" . "€" . number_format(($total + 6.95), 2) . "</td></tr>";
                            } else {
                                $totaal = number_format(($total),2);
                                //echo "<tr><td>Totaal</td><td></td><td>" . "Gratis!" . "</td></tr>";
                            }
                        } elseif (isset($_GET) && isset($_GET["vervoer"]) && $_GET["vervoer"] == "afhalen"){
                            $bezorgen = false;
                            $totaal = number_format(($total),2);
                            //echo "<tr><td>Totaal</td><td></td><td class='total_price'>" . "€". number_format(($total),2) ."</td></tr>";
                        }else{
                            $totaal = $total;
                        }
                        // End Shipping costs

                        // Display total
                        echo "<tr><td>Totaal</td><td></td><td class='total_price'>€".$totaal."</td></tr>";
                        // End display
                        echo "</table>";
                        if($bezorgen){
                            $datetime = new DateTime('tomorrow');
                            echo "<br>";
                            echo "Jouw bestelling komt op ". $datetime->format('Y-m-d') . " aan";
                        }
                    }
                    ?>
        <form action="<?php 'http://'.$_SERVER['PHP_SELF']; ?>?vervoer=<?php echo $_GET["vervoer"] ?>" method="get">
            <p>Kies uw levertype</p>
            <input class="vervoer" type="submit" name="vervoer" value="bezorgen">
            <input class="vervoer" type="submit" name="vervoer" value="afhalen">
        </form>

        <div class="Discount">
            <p class="add_invis">Voeg hier je coupon toe!</p>
            <input type="text" name="discount" class="input_discount">
            <button class="addDiscount">Toevoegen code</button>
            <p class="discountResult"></p>
        </div>

        <br>
        <form action="<?php getURI() ?>" method="post">
            <input style="width: 200px" class="vervoer" type="submit" value="Betaal" name="betaal">
            <br>
            <?php

            if(isset($_POST["betaal"])) {
                echo '
                <script type="text/javascript">
let timerInterval
Swal.fire({
  title: \'Betaling is gelukt!\',
  html: \'Je wordt in <b></b> seconden naar de home pagina geleid.\',
  timer: 10000,
  timerProgressBar: true,
  onOpen: () => {
    Swal.showLoading()
    timerInterval = setInterval(() => {
      Swal.getContent().querySelector(\'b\')
        .textContent = Math.ceil(Swal.getTimerLeft() / 1000)
    }, 100)
  },
  onClose: () => {
    clearInterval(timerInterval)
    window.location.href = "index.php";
  }
}).then((result) => {
  if (
    /* Read more about handling dismissals below */
    result.dismiss === Swal.DismissReason.timer
  ) {
    console.log(\'I was closed by the timer\') // eslint-disable-line
  }
})
            </script>';
            if (isset($_SESSION["ingelogd"])) {
                foreach ($_SESSION["shopping_cart"] as $item) {
                    $item_id = $item["item_id"];
                    $accountid = $_SESSION["accountID"];
                    $firstname = $_SESSION["firstname"];
                    $lastname = $_SESSION["lastname"];
                    $email = $_SESSION["email"];
                    $address = $_SESSION["address"];
                    $cost = $item["item_price"];
                    $quantity = $item["item_quantity"];
                    $stmt = mysqli_prepare($connection, "
                    insert into paymentnew (stockitemid, AccountID, FirstName, LastName, Email, Address, PayAmount, PayQuantity)
                    values(?,?,?,?,?,?,?,?);
                    ");
                    mysqli_stmt_bind_param($stmt, "iissssii",$item_id , $accountid, $firstname, $lastname, $email, $address, $cost, $quantity  );
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_close($stmt);

                    $stmt = mysqli_prepare($connection, "	
                    	UPDATE stockitemholdings
                        set quantityonhand = quantityonhand - ?
                        where stockitemid = ? 
");
                    mysqli_stmt_bind_param($stmt, "ii", $quantity, $item_id);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);


                }
                session_destroy();
                mysqli_stmt_close($stmt);
            }else{
                foreach ($_SESSION["shopping_cart"] as $item) {
                    $item_id = $item["item_id"];
                    $firstname = $_SESSION["firstname"];
                    $lastname = $_SESSION["lastname"];
                    $email = $_SESSION["email"];
                    $address = $_SESSION["address"];
                    $cost = $item["item_price"];
                    $quantity = $item["item_quantity"];
                    $stmt = mysqli_prepare($connection, "
                    insert into paymentnew (stockitemid, FirstName, LastName, Email, Address, PayAmount, PayQuantity)
                    values(?,?,?,?,?,?,?);
                    ");
                    mysqli_stmt_bind_param($stmt, "issssii",$item_id , $firstname, $lastname, $email, $address, $cost, $quantity  );
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_close($stmt);

                    $stmt = mysqli_prepare($connection, "	
                    	UPDATE stockitemholdings
                        set quantityonhand = quantityonhand - ?
                        where stockitemid = ? 
");
                    mysqli_stmt_bind_param($stmt, "ii", $quantity, $item_id);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);

                }
                session_destroy();
                mysqli_stmt_close($stmt);
            }

            }
            ?>
        </form>
    </div>

</div>
</body>