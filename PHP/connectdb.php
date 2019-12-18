<?php
// Connectie database maken
mysqli_report(MYSQLI_REPORT_STRICT); //initieer reporting
$host = "localhost";
$databasename = "wideworldimporters";
$user = "root";
$pass = ""; //eigen password invullen
$port = 3306;

// Proberen connectie maken, als het niet lukt geef error
try {
    $connection = mysqli_connect($host, $user, $pass, $databasename, $port);
    mysqli_set_charset($connection, "utf8mb4");
} catch (mysqli_sql_exception $e) {
    echo "Geen verbinding gemaakt...<br>";
    echo $e;
    exit;
}