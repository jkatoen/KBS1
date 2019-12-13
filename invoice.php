<?php
session_start();
include ("PHP/functions.php");
include ("header.php");
checkIfCartEmpty();
// Zijn de gegevens ingevuld?
if (isset($_POST["voornaam"]) && isset($_POST["achternaam"]) && isset($_POST["adres"]) && isset($_POST["emailadres"])) {
    $voornaam = $_POST["voornaam"];
    $achternaam = $_POST["achternaam"];
    $adres = $_POST["adres"];
    $email = $_POST["emailadres"];
} else {
    echo "Gegevens missen";
    exit;
}

?>
<h1>Factuur</h1>
<h3>Gegevens</h3>
<table>
    <tr><td>Naam:   </td><td><?php echo "{$voornaam} {$achternaam}";?>  </td></tr>
    <tr><td>Adres:  </td><td><?php echo "{$adres}";?>                   </td></tr>
    <tr><td>Email:  </td><td><?php echo "{$email}";?>                   </td></tr>
</table>
<h3>Productenlijst</h3>
<table>
    <th>Product</th><th>Aantal</th><th>Prijs</th>
    <?php $totaal = 0;
    foreach ($_SESSION["shopping_cart"] as $item) {
        $prijs = $item['item_price']*$item['item_quantity'];
        $totaal = $totaal + $prijs;
        echo "<tr><td>{$item['item_name']}</td>
                  <td>{$item['item_quantity']}</td>
                  <td>".number_format($prijs,2)."</td></tr>";
    } ?>
    <tr><td></td><td><b>Totaal:</b></td><td><?php echo number_format($totaal,2); ?></td></tr>
</table>
<button class="button">Afrekenen</button>
<?php
include ("footer.php");
?>