<?php
session_start();
include ("PHP/connectdb.php");
include("PHP/functions.php");
include("header.php");

$to = $_POST['to'];
$subject = "Bedankt voor uw bericht";
$header = "From: sendmail.kbs1@gmail.com";
$reply = "Geachte heer/mevrouw,

Bedankt voor uw bericht. Wij proberen zo spoedig mogelijk contact met u op te nemen.

Met vriendelijke groet,

World Wide Importers";
mail($to, $subject, $reply, $header);
?>

<body>
    <H2>Het bericht is verstuurd. Er wordt zo spoedig mogelijk contact met u opgenomen.</H2>
    <a class="end" href="index.php">Ga terug naar de homepagina.</a>
</body>
