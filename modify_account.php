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

if (!isset($lastname) || !isset($firstname) || !isset($username)) {
  header("Location: ./index.php", true, 302);
  exit();
}

require_once('connect.php');

// Find id of the user:
$req = $connection->prepare('SELECT * FROM accounts WHERE last_name=? AND first_name=? and username = ?');

$req-> execute([$lastname, $firstname, $username]);

$row = $req->fetch(PDO::FETCH_ASSOC);
// print_r($row);

// Update all info for this user:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['modify'])) {
    if (strlen($_POST['lastname']) < 2 || strlen($_POST['lastname']) > 10) {
      throw new Exception("Nom non valide");
    }
    $newLastName = $_POST['lastname'];

    if (strlen($_POST['firstname']) < 2 || strlen($_POST['firstname']) > 10) {
      throw new Exception("Prénom non valide");
    }
    $newFirstName = $_POST['firstname'];

    if (strlen($_POST['username']) < 2 || strlen($_POST['username']) > 10) {
      throw new Exception("Username non valide");
    }
    $newUserName = $_POST['username'];

    if (strlen($_POST['password']) < 3 || strlen($_POST['password']) > 10) {
      throw new Exception("Mot de passe non valide");
    }
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (!isset($_POST['secret_question']) || strlen($_POST['answer']) < 3) {
      throw new Exception("Question secrete ou réponse non valides");
    }
    $newSecretQuestion = $_POST['secret_question'];
    $newAnswer = $_POST['answer'];

    $modify = $connection->prepare('UPDATE accounts SET last_name=?, first_name=?, username=?, password=?, secret_question=?, answer=? WHERE id=? LIMIT 1');
    $modify->execute([$newLastName, $newFirstName, $newUserName, $newPassword, $newSecretQuestion, $newAnswer, $row['id']]);

    $_SESSION['lastname'] = $newLastName;
    $_SESSION['firstname'] = $newFirstName;
    $_SESSION['username'] = $newUserName;
 
    header("Location: ./index.php", true, 302);
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page des Paramètres</title>
</head>
<body>
  <h1>Form Pour Modifier:</h1>
  <form action="" method="POST">
    <label for="lastname">Nouveau nom: </label><input type="text" name="lastname" value="<?= $row['last_name']; ?>"><br>
    <label for="firstname">Nouveau prénom: </label><input type="text" name="firstname" value="<?= $row['first_name']; ?>"><br>
    <label for="username">Nouveau username: </label><input type="text" name="username" value="<?= $row['username']; ?>"><br>

    <label for="password">Nouveau mot de passe: </label><input type="password" name="password"><br>

    <label for="secret_question">Choisir une question secrete: </label><select name="secret_question" id="secret_question">
      <option value="">--Choisir une option--</option>
      <option value="Quelle est votre couleur préférée?">Quelle est votre couleur préférée?</option>
      <option value="Quel est le nom de votre mère?">Quel est le nom de votre mère?</option>
      <option value="Où se trouve votre ville natale?">Où se trouve votre ville natale?</option>  
    </select><br>
    <label for="answer">Votre réponse: </label><input type="text" name="answer" value="<?= $row['answer']; ?>"><br>
    <input type="submit" name="modify" value="Modifier">
  </form>
</body>
</html>