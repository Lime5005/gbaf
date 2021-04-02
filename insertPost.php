<?php
  
  // Insert new comment:
  if (isset($_COOKIE['acteur_id'])) {
    // echo "value is :" . $_COOKIE['acteur_id'];
      if (isset($_POST['submit'])){
        try {
          if ($_POST['author'] == '' || $_POST['date'] == '' || $_POST['comment'] == '') {
            throw new Exception ("Entrer votre commentaire.");
          } else {
            require_once('connect.php');
    
            $req = $connection->prepare('INSERT INTO posts (bank_id, date_created, comment,user_id) VALUES (?, NOW(), ?,  (SELECT accounts.id FROM accounts WHERE accounts.first_name=?))');
            $data = $req->execute([$_COOKIE['acteur_id'], $_POST['comment'], $_POST['author']]);
    
            header('Location: acteur.php?acteur='. $_COOKIE['acteur_id'] .'');
          }
        } catch (Exception $e) {
          echo $e->getMessage() . '<br>';
        }
      } 
  }
?>