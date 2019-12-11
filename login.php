<?php
session_start();
include("PHP/connectdb.php");
include("PHP/functions.php");
include("header.php");

?>
<body>
<div class="row">

    <div class="leftcolumn">
    </div>
    <div class="midcolumn">
        <div class="inlog">
            <h1>Inloggen</h1><br>
            <form action="authenticate.php" method="POST">
                Emailadres<br><input type="email" name="email" placeholder="Enter your email adres"><br><br>
                Wachtwoord<br><input type="password" name="passwd" placeholder="Enter your password"><br><br>
                <input type="submit" name="submit" class="button"><br><br>
            </form>
            <h3>Nog geen account? Maak dan nu een account aan.</h3>
            <a href="accaanmaken.php"><h4>Account aanmaken</h4></a>
        </div>
    </div>

</body>
</html>