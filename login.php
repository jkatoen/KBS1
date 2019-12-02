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
        <div class="inlog">
            <h1>Inloggen</h1><br>
            <form method="post" action="authenticate.php">
                Emailadres<br><input type="email" name="email" required><br><br>
                Wachtwoord<br><input type="password" name="passwd" required><br><br>
                <input type="submit" name="verzenden" class="button" required><br><br>
            </form>
            <h3>Nog geen account? Maak dan nu een account aan.</h3>
            <a href="accaanmaken.php"><h4>Account aanmaken</h4></a>
        </div>
    </div>

</body>
<footer class="footer">
    <h3>© Copyrights 2019 - World Wide Importers</h3>
</footer>
</html>