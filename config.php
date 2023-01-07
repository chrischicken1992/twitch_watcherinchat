<?php
try {
  $pdo_twitch = new PDO("mysql:host=localhost;dbname=xxxx;charset=utf8", "username", "password");
}
catch (PDOException $e) {
  echo 'Fehler bei der PDO-Verbindung:  ' . $e->getMessage();
  exit();
}
?>