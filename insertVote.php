<?php

    require_once('session.php');

    require_once('connect.php');

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
      http_response_code(403); // Status Code: 403 Forbidden
      die();
    }

    $sqlUser = $connection->prepare('SELECT id FROM accounts WHERE username=?');
    $sqlUser->execute([$_SESSION['username']]);
    $userData = $sqlUser->fetch();
    // print_r($userData['id']);
    $userId = $userData['id'];

    require_once('Vote.php');

    $vote = new Vote($connection);

    // $_GET['ref_id'] = 2000;

    if ($_GET['vote'] == 1) {
      $vote->like('acteurs', $_GET['ref_id'], $userId);
      // How do all the functions been used?
      // like()->vote()->recordExists()
    } else if ($_GET['vote'] == -1) {
      $vote->dislike('acteurs', $_GET['ref_id'], $userId);
    }

    header('Location: acteur.php?acteur='. $_COOKIE['acteur_id'] .'');

?>