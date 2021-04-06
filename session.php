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


if (!isset($username) || !isset($firstname) || !isset($lastname)) {
  header("Location: ./login.php", true, 302);
  exit();
}

?>