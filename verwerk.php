<?php
session_start();
if(isset($_POST["email"]) && $_POST["email"] == "" &&
     isset($_POST["passwd"]) && $_POST["passwd"] == ""){
    $_SESSION["ingelogd"] = "true";
    $_SESSION["email"] = $_POST["email"];
} else {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Controleer formulier</title>
  </head>
  <body>
  	<?php
		print("Ingelogd als ".$_POST["naam"]);
  	?>
  	<br>
  	<a href="indexIn.php">Ga verder...</a>
  </body>
</html>