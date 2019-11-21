<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="CSS/mystyle.css">
    <div class="header">
        <a href="index.php"><img src="IMG/wwi-logo.png"></a>
    </div>
    <div class="topnav">
        <a href="cart.php"><img src="IMG/winkelmand.png" width="65" height="59.5"></a>
        <a href="login.php"><h3>Login</h3></a>
        <a href="contact.php"><h3>Contact</h3></a>
        <form class="nav-search" method="get" action="search.php">
            <input class="text" type="text" name="searchinput">
            <input type="submit" name="submitinput" value="Search">
        </form>
    </div>
</head>

<body>
<h2>Inloggen</h2><br><br>
    <form>
    <input type="text" name="Naam"><br>
    <input type="password" name="passwd"><br>
    <input type="submit" name="verzenden"><br><br>
    <h3>Nog geen account? Maak dan nu een account aan.</h3><br>
        <a href="account.php"><h4>Account aanmaken</h4></a>
</form>
</body>
<footer class="footer">
    <h3>© Copyrights 2019 - World Wide Importers</h3>
</footer>
</html>