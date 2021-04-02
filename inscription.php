<?php

include_once('User.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user = new User();
  try {
    $user->setLastName($_POST['lastname']);
    $user->setFirstName($_POST['firstname']);
    $user->setUserName($_POST['username']);
    $user->setPassword($_POST['password']);
    $user->setSecretQuestion($_POST['secret_question'], $_POST['answer']);

    $user->saveToDatabase();

    echo 'User created<br>';
    header("Location: ./index.php", true, 302);
    exit();
  } catch (Exception $e) {
    echo $e->getMessage() . '<br>';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page d'Inscription</title>
</head>
<body>
  <h1>Registration form:</h1>
  <form action="" method="POST">
    <label for="lastname">Nom(Entre 2 à 10 charactères): </label><input type="text" name="lastname" placeholder="Entrer votre nom"><br>
    <label for="firstname">Prénom(Entre 2 à 10 charactères): </label><input type="text" name="firstname" placeholder="Entrer votre prénom"><br>
    <label for="username">Username(Entre 2 à 10 charactères): </label><input type="text" name="username" placeholder="Entrer votre username"><br>

    <label for="password">Mot de passe(Entre 3 à 10 charactères): </label><input type="password" name="password" placeholder="Entrer votre mot de passe"><br>
    <label for="secret_question">Choisir une question secrete: </label><select name="secret_question" id="secret_question">
      <option value="">--Choisir une option--</option>
      <option value="1">Quelle est votre couleur préférée?</option>
      <option value="2">Quel est le nom de votre mère?</option>
      <option value="3">Où se trouve votre ville natale?</option>  
    </select><br>
    <label for="answer">Votre réponse(Au moins 3 charactères): </label><input type="text" name="answer" placeholder="Entrer votre réponse"><br>
    <input type="submit" name="submit" value="S'inscrire">
  </form>
</body>
</html>