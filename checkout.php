
<?php
session_start();
include("PHP/connectdb.php");
include("PHP/functions.php");
include ("header.php");
?>

<!DOCTYPE html>
<html>
<body>
<div class="row">

    <div class="leftcolumn">
        <div class="card">
            <h2>Category</h2>
            <div class="category-container">
                <?php displayLeftCategories($connection); ?>
            </div>
        </div>
    </div>
    <div class="midcolumn">
            <div class="Checkout" class="Checkout">
                <h1>Checkout</h1>
            <?php
            if (!isset($_SESSION["ingelogd"])) {
            ?>
                <form action="https://www.ideal.nl/" method="POST">
                    Voornaam <br><input type="text" name="voornaam" class="textinbox" value="" required/><br><br>
                    Achternaam <br><input type="text" name="achternaam" class="textinbox" value="" required/><br><br>
                    Adres <br><input type="text" name="adres" class="textinbox" value="" required/><br><br>
                    Emailadres <br><input type="email" name="emailadres" class="textinbox" value=""
                                          required/><br><br><br>

                    <link rel="stylesheet" type="text/css" href="CSS/mystyle.css">
                    <div class="header">
                        <img src="IMG/iDeal.png" width="20%"
                        <br><br><br>
                        <input type="submit" value="Pay with iDeal" class="button" required/>
                </form>
            <?php
            }
            else {
            ?>
                Voornaam: <br><?php print($_SESSION["firstname"])?><br><br>
                Achternaam: <br><?php print($_SESSION["lastname"])?><br><br>
                Adres: <br><?php print($_SESSION["address"])?><br><br>
                Emailadres: <br><?php print($_SESSION["email"])?><br><br><br>
                <form action="https://www.ideal.nl" method="POST">
                    <link rel="stylesheet" type="text/css" href="CSS/mystyle.css">
                    <div class="header">
                        <img src="IMG/iDeal.png" width="20%"
                        <br><br><br>
                        <input type="submit" value="Pay with iDeal" class="button" required/>
                </form>
            <?php
            }
            ?>




            </div>
        </div>
    </div>
</div>
</body>
</html>
