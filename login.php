<?php
session_start();
include ("PHP/connectdb.php");
include ("PHP/functions.php");
include ("header.php");
?>

<body>
<div class="login">
<h2>Inloggen</h2><br>
    <form>
    <input type="text" name="Naam"><br><br>
    <input type="password" name="passwd"><br><br>
    <input type="submit" name="verzenden"><br><br>
</form>
</div>
<div class="login2"><h3>Nog geen account? Maak dan nu een account aan.</h3>
    <a href="accaanmaken.php"><h4>Account aanmaken</h4></a></div>
</body>
<footer class="footer">
    <h3>© Copyrights 2019 - World Wide Importers</h3>
</footer>
</html>