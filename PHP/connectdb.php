<?php
// Connectie database maken
mysqli_report(MYSQLI_REPORT_STRICT); //initieer reporting
$host = "localhost";
$databasename = "wideworldimporters";
$user = "root";
$pass = "ISc00l221$"; //eigen password invullen
$port = 3306;

// Proberen connectie maken, als het niet lukt geef error
try {
    $connection = mysqli_connect($host, $user, $pass, $databasename, $port);
} catch (mysqli_sql_exception $e) {
    echo "Geen verbinding gemaakt...<br>";
    echo $e;
    exit;
}