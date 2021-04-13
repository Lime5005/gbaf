<?php

session_start();

$username;
$secretQuestion;
$answer;
if (isset($_SESSION['username'])) $username = $_SESSION['username'];
if (isset($_SESSION['secret_question'])) $secretQuestion = $_SESSION['secret_question'];
if (isset($_SESSION['answer'])) $answer = $_SESSION['answer'];

if (!isset($username) || !isset($secretQuestion) || !isset($answer)) {
  header("Location: ./login.php", true, 302);
  exit();
}

include_once('connect.php');

// Find id of the user:
$req = $connection->prepare('SELECT * FROM accounts WHERE username=? AND secret_question=? AND answer=?');

$req-> execute([$username, $secretQuestion, $answer]);

$row = $req->fetch(PDO::FETCH_ASSOC);
// print_r($row);

// Update all for this user:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['recover'])) {

    $error = null;
    try {
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

      if (!isset($_POST['secret_question']) || $_POST['secret_question'] == '' || strlen($_POST['answer']) < 3) {
        throw new Exception("Question secrete ou réponse non valides");
      }
      $newSecretQuestion = $_POST['secret_question'];
      $newAnswer = $_POST['answer'];

      $modify = $connection->prepare('UPDATE accounts SET last_name=?, first_name=?, username=?, password=?,  secret_question=?, answer=? WHERE id=? LIMIT 1');
      $modify->execute([$newLastName, $newFirstName, $newUserName, $newPassword, $newSecretQuestion,  $newAnswer, $row['id']]);

      $_SESSION['lastname'] = $newLastName;
      $_SESSION['firstname'] = $newFirstName;
      $_SESSION['username'] = $newUserName;

      header("Location: ./index.php", true, 302);
      exit();
    } catch (Exception $e) {
      $error = $e->getMessage() . '<br>';
    }
  }
}
include_once('header.php');
?>

  <form class="form-box" action="" method="POST">
    <h1>Ajouter un nouveau mot de passe:</h1>
    <p>(Ou tous les champs nécessaires)</p><br>

    <?php
        if (isset($error)) {
          echo '<div class="alert-danger" role="alert">' . $error . '</div><br>';
        }
    ?>
    <label for="lastname">Nouveau nom(Entre 2 à 10 charactères): </label><input type="text" name="lastname"><br>
    <label for="firstname">Nouveau prénom(Entre 2 à 10 charactères): </label><input type="text" name="firstname"><br>
    <label for="username">Nouveau nom d'utilisateur(Entre 2 à 10 charactères): </label><input type="text" name="username" value="<?php if(isset($username)) echo $username; ?>"><br>

    <label for="password">Nouveau mot de passe(Entre 3 à 10 charactères): </label><input type="password" name="password"><br>

    <label for="secret_question">Choisir une question secrete: </label>
    <select name="secret_question" id="secret_question">
      <option value="">--Choisir une option--</option>
      <option value="Quelle est votre couleur préférée?">Quelle est votre couleur préférée?</option>
      <option value="Quel est le nom de votre mère?">Quel est le nom de votre mère?</option>
      <option value="Où se trouve votre ville natale?">Où se trouve votre ville natale?</option>
    </select><br>
    <label for="answer">Votre réponse(Plus que 2 charactères): </label><input type="text" name="answer" value="<?= $answer ?>"><br>
    <input type="submit" name="recover" value="Enregistrer">
  </form>
<?php
include_once('footer.php');
?>
