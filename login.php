<?php
session_start();
include("PHP/connectdb.php");
include("PHP/functions.php");
include("header.php");

if ( ! empty( $_POST ) ) {
    if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) ) {
        // Getting submitted user data from database
        $con = new mysqli($db_host, $db_user, $db_pass, $db_name);
        $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_object();

        // Verify user password and set $_SESSION
        if ( password_verify( $_POST['password'], $user->password ) ) {
            $_SESSION['user_id'] = $user->ID;
        }
    }
}

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
            <form action="" method="post">
                Emailadres<br><input type="email" name="email" placeholder="Enter your email adres"><br><br>
                Wachtwoord<br><input type="password" name="password" placeholder="Enter your password"><br><br>
                <input type="submit" name="submit" class="button"><br><br>
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