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
<div class="login">
<h2>Inloggen</h2><br>
    <form>
    <input type="text" name="Naam"><br><br>
    <input type="password" name="passwd"><br><br>
    <input type="submit" name="verzenden"><br><br>
</form>
</div>
<div class="login2"><h3>Nog geen account? Maak dan nu een account aan.</h3>
    <a href="account.php"><h4>Account aanmaken</h4></a></div>
</body>
<footer class="footer">
    <h3>© Copyrights 2019 - World Wide Importers</h3>
</footer>
</html>