<?php
  
  require_once('session.php');

  // Insert new comment:
  if (isset($_COOKIE['acteur_id'])) {
    // echo "value is :" . $_COOKIE['acteur_id'];
      if (!isset($_POST['submit']) && ($_POST['author'] == '' || $_POST['date'] == '' || $_POST['comment'] == '') ) {
        echo "Entrer votre prénom, la date, et votre commentaire";
      } else {
        require_once('connect.php');
        $req = $connection->prepare('INSERT INTO posts (bank_id, date_created, comment,user_id) VALUES (?, NOW(), ?,  (SELECT accounts.id FROM accounts WHERE accounts.first_name=?))');
        $data = $req->execute([$_COOKIE['acteur_id'], $_POST['comment'], $_POST['author']]);

        header('Location: acteur.php?acteur='. $_COOKIE['acteur_id'] .'');
      }

    }
?>
