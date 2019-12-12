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
        </div>
    </div>
    <div class="midcolumn">
        <div class="inlog" class="inlog">
            <h1>Contact opnemen</h1>
            <form action="accaanmaken.php" method="POST">
                <form action="index.php" method="POST">
                    Achter: <br><input type="text" name="voornaam" class="textinbox" value="" required/><br><br>
                    Voornaam: <br><input type="text" name="achternaam" class="textinbox" value="" required/><br><br>
                    Adres: <br><input type="text" name="adres" class="textinbox" value="" required/><br><br>
                    Email adres: <br><input type="email" name="emailadres" class="textinbox" value="" required/><br><br>
                    Bericht: <br><input type="text" name="ww" class="textinbox" value="" required/><br><br>
                    <input type="submit" value="Account aanmaken" name="register" class="button" required/>
                </form>
        </div>
    </div>
</div>

<?php
