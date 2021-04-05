<?php
session_start();
// Verify the username and the secret question:

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['recover'])) {
    $error = null;
    try {
      if (!isset($_POST['username']) || strlen($_POST['username']) < 2 ){
        throw new Exception("Username non valide");
      } else if (!isset($_POST['secret_question']) || strlen(($_POST['answer'])) < 3) {
        throw new Exception("Question secrète ou réponse non valide");
      } else {
        require_once('connect.php');

        $req = $connection->prepare('SELECT * FROM accounts WHERE username=? AND secret_question=? AND answer=? LIMIT 1');
        $req->execute([$_POST['username'], $_POST['secret_question'], $_POST['answer']]);
      
        if ($req->rowCount() == 0) {
          throw new Exception("Username ou question secrète/réponse incorrect");
        } else {
          $row = $req->fetch(PDO::FETCH_ASSOC);
          // print_r($row);
          if ($_POST['username'] == $row['username'] && $_POST['secret_question'] == $row['secret_question'] && $_POST['answer'] == $row['answer']) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['firstname'] = $row['first_name'];
            $_SESSION['lastname'] = $row['last_name'];
            header("Location: ./modify_account.php", true, 302);
            exit();
          }
        }
      }
    } catch (Exception $e) {
      $error = $e->getMessage() . '<br>';
    }
  }
}
include_once('header.php');
?>
  <h2>Récupérer votre compte avec question secrète:</h2>
  <?php
      if (isset($error)) {
        echo '<div class="alert-danger" role="alert">' . $error . '</div><br>';
      }
  ?>
  <form action="" method="POST">
    <label for="username">Username:</label><input type="text" name="username">
    <br>
    <label for="secret_question">Choisir la question secrète que vous avez enregistré: </label><select name="secret_question" id="secret_question">
      <option value="">--Choisir une option--</option>
      <option value="Quelle est votre couleur préférée?">Quelle est votre couleur préférée?</option>
      <option value="Quel est le nom de votre mère?">Quel est le nom de votre mère?</option>
      <option value="Où se trouve votre ville natale?">Où se trouve votre ville natale?</option>
    </select><br>
    <label for="answer">Votre réponse que vous avez enregistrée: </label><input type="text" name="answer" placeholder="Entrer votre réponse"><br>
    <input type="submit" name="recover" value="Récupérer mon compte">
  </form>
<?php
include_once('footer.php');
?>