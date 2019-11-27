<?php
session_start();
include ("PHP/connectdb.php");
include ("PHP/functions.php");
include ("header.php");
?>

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
        <div class="inlog" class="inlog">
            <h1>Account aanmaken</h1>
            <form action="index.php" method="POST">
                Voornaam <br><input type="text" name="voornaam" class="textinbox" value="" required/><br><br>
                Achternaam <br><input type="text" name="achternaam" class="textinbox" value="" required/><br><br>
                Adres <br><input type="text" name="adres" class="textinbox" value="" required/><br><br>
                Wachtwoord <br><input type="password" name="ww" class="textinbox" value="" required/><br><br>
                Emailadres <br><input type="email" name="emailadres" class="textinbox" value="" required/><br><br><br>
                <input type="submit" value="Account aanmaken" name="register" class="button" required/>
            </form>
            <div class="product-container">
                <?php
                if (isset($_POST['register'])) {
                    if (isset($_POST["voornaam"]) && isset($_POST["achternaam"]) && isset($_POST["adres"]) && isset($_POST["ww"]) && isset($_POST["emailadres"])) {
                        accountAanmaken($connection);
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="footer">
    <h2>Footer</h2>
</div>

</body>
</html>
