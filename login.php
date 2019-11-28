<?php
session_start();
include ("PHP/connectdb.php");
include ("PHP/functions.php");
include ("header.php");
?>

<body>
<<<<<<< HEAD
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
        <div class="inlog">
            <h1>Inloggen</h1><br>
            <form method="get" action="">
                Emailadres<br><input type="email" name="Emailadres"><br><br>
                Wachtwoord<br><input type="password" name="passwd"><br><br>
                <input type="submit" name="verzenden" class="button"><br><br>
            </form>
            <h3>Nog geen account? Maak dan nu een account aan.</h3>
            <a href="accaanmaken.php"><h4>Account aanmaken</h4></a>
        </div>
    </div>
=======
<div class="login">
<h2>Inloggen</h2><br>
    <form method="get" action="">
    <input type="text" name="Naam"><br><br>
    <input type="password" name="passwd"><br><br>
    <input type="submit" name="verzenden"><br><br>
</form>
>>>>>>> b58a8101b45badcd471c11d6f19e2509f20858a3
</div>
</body>
<footer class="footer">
    <h3>© Copyrights 2019 - World Wide Importers</h3>
</footer>
</html>