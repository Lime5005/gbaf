<?php

session_start();

$lastname;
$firstname;
$username;
if (isset($_SESSION['lastname'])) $lastname = $_SESSION['lastname'];
if (isset($_SESSION['firstname'])) $firstname = $_SESSION['firstname'];
if (isset($_SESSION['username'])) $username = $_SESSION['username'];
if (isset($_COOKIE['lastname'])) $lastname = $_COOKIE['lastname'];
if (isset($_COOKIE['firstname'])) $firstname = $_COOKIE['firstname'];
if (isset($_COOKIE['username'])) $username = $_COOKIE['username'];


if (!isset($username)) {
  header("Location: ./login.php", true, 302);
  exit();
}

echo 'Bonjour ' . $lastname . ' ' . $firstname;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page d'accueil</title>
</head>
<body>
  <header>
    <ul>
      <li><a href="logout.php">Se déconnecter</a></li>
      <br>
      <li><a href="modify_account.php">Paramètres du compte</a></li>
      <br>
    </ul>
  </header>
</body>
</html>