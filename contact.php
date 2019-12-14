<?php
session_start();
include ("PHP/connectdb.php");
include ("PHP/functions.php");
include ("footer.php");
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
                    <form action="index.php" method="POST">
                        E-mail: <br><input type="text" name="email" class="textinbox" value="" required/><br><br>
                       Telefoonnummer: <br><input type="text" name="telefoonnummer" class="textinbox" value="" required/><br><br>
                       Onderwerp: <br><input type="text" name="onderwerp" class="textinbox" value="" required/><br><br>
                        Bericht: <br><input type="text" name="bericht" class="textinbox"  value="" required/><br><br>
                        <input type="submit" value="Contact opnemen" name="Opnemen" class="button" required/>
                    </form>
            </div>
        </div>
    </div>

<?php
