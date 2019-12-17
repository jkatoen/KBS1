<?php
session_start();
include ("PHP/connectdb.php");
include("PHP/functions.php");
include("header.php");
?>

    <body xmlns="http://www.w3.org/1999/html">
    <div class="row">
        <div class="leftcolumn">
            <div class="card">
            </div>
        </div>
        <div class="midcolumn">
            <div class="inlog" class="inlog">
                <h1>Contact opnemen</h1>
                    <form action="mail.php"" method="POST" id="contact">
                        E-mail: <br><input type="text" name="to" class="textinbox" value="<?php ?>" required/><br><br>
                       Onderwerp: <br><input type="text" name="subject" class="textinbox" value="" required/><br><br>
                        Bericht: <br><textarea rows="8" cols="50" form="contact" name="message" class="textinbox" required></textarea><br><br>
                        <input type="submit" value="Versturen" name="send" class="button" required/>
                    </form>
            </div>
        </div>
    </div>
    </body>
<?php

?>