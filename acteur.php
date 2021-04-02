<?php

    session_start();

    $lastname;
    $firstname;
    $username;
    $today = date("Y-m-d");

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

    if(isset($_GET['acteur'])) {

      setcookie("acteur_id", $_GET['acteur'], time()+(60*60*24));

      require_once('connect.php');
      $req = $connection->prepare('SELECT id, logo, name, description FROM acteurs WHERE id=?');
      $req->execute([$_GET['acteur']]);
      $data = $req->fetch();?>
        <p><a href="index.php">Retour à la page d'accueil</a></p>
        <div class="post">
          <h3>
            <?php echo $data['name']; ?>
          </h3>
          <p><img src="images/<?php echo $data['logo']; ?>" alt="logo" height="130" width="450"></p>
          <p>
            <?php echo nl2br(htmlspecialchars($data['description'])); ?>
          </p>
        </div>
        <h2>Laisser un commentaire:</h2>
        <form action="insertPost.php" method="POST">
            <p><label for="author">Author:</label><input type="text" name="author" id="author" value="<?= $firstname ?>"></p>
            <p><label for="date">Date:</label><input type="date" name="date" id="date" value="<?= $today ?>"></p>

            <p><label for="comment">Votre commentaire:</label><textarea name="comment" id="comment" cols="50" rows="5"></textarea></p>
            <p><input type="submit" value="Envoyer"></p>
        </form>
        <h2>Commentaires</h2>
    <?php
      // Show all the comments by time but ONLY show date from every user for this bank
      // Find the firstname from `accounts` by user_id from `posts`
      // How to get date from type`datetime`: SELECT DATE_FORMAT(column_name, '%d-%m-%Y') FROM tablename
      $req = $connection->prepare("SELECT bank_id, user_id, comment, DATE_FORMAT(date_created, '%d-%m-%Y') as date, first_name FROM posts
        JOIN accounts
        ON posts.user_id = accounts.id
        WHERE posts.bank_id = ?
        ORDER BY date_created DESC");
      if(isset($_GET['acteur'])){
        $req->execute([$_GET['acteur']]);
        while ($data = $req->fetch()) { ?>
          <p><?php echo $data['first_name']; ?></p>
          <p><?php echo $data['date']; ?></p>
          <p><?php echo htmlspecialchars($data['comment']) ?></p>
        <?php }
          $req->closeCursor();
      }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page d'Acteur</title>
</head>
<body>

</body>
</html>
