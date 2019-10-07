<?php
include "db_details.php";
$tunnel = mysqli_connect($server, $user, $pw, $db) or die ("<p><string>PHP INFO: </string>Probleme beim Verbindungsaufbau mit der Datenbank.</p>");
?>