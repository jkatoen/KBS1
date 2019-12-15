<?php
session_start();
include ("PHP/connectdb.php");
include ("PHP/functions.php");
include ("header.php");
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
                    <form action="index.php" method="POST" id="contact">
                        E-mail: <br><input type="text" name="email" class="textinbox" value="" required/><br><br>
                       Telefoonnummer: <br><input type="text" name="telefoonnummer" class="textinbox" value=""/><br><br>
                       Onderwerp: <br><input type="text" name="onderwerp" class="textinbox" value="" required/><br><br>
                        Bericht: <br><textarea form="contact" name="bericht" class="textinbox" required></textarea><br><br>
                        <input type="submit" value="Versturen" name="verstuur" class="button" required/>
                    </form>
            </div>
        </div>
    </div>

