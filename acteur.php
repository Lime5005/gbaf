<?php

    require_once('session.php');

    $today = date("Y-m-d");

    if(isset($_GET['acteur'])) {

      setcookie("acteur_id", $_GET['acteur'], time()+(60*60*24));

      require_once('connect.php');
      require_once('Vote.php');

      // Show if the user voted for this acteur, green or red
      $vote = false;
      $reqVote = $connection->prepare('SELECT * FROM votes WHERE ref=? AND ref_id=? AND user_id=(SELECT id FROM accounts WHERE username=?)');
      $reqVote->execute(['acteurs', $_GET['acteur'], $_SESSION['username']]);

      $vote = $reqVote->fetch();

      // Get the acteur
      $req = $connection->prepare('SELECT * FROM acteurs WHERE id=?');
      $req->execute([$_GET['acteur']]);
      $data = $req->fetch();

      include_once('header.php');
      // var_dump($data);
      ?>
        <button class="return-home"><a href="index.php">Retour à la page d'accueil</a></button>
        <div class="post">
          <p><img src="images/<?php echo $data['logo']; ?>" alt="logo" height="130" width="450"></p>
          <h2>
            <?php echo $data['name']; ?>
          </h2>
          <p>
            <?php echo nl2br(htmlspecialchars($data['description'])); ?>
          </p>
          <br>
          <button class="comment_btn" onclick="myComment()">Nouveau commentaire</button>
          <div class="vote-comment-btns">
            <div class="vote_btns <?= Vote::getClass($vote) ?>">
              <form action="insertVote.php?ref=acteurs&ref_id=<?= $data['id']; ?>&vote=1" method="POST">
                <button type="submit" class="vote_btn vote_like">
                  <i class="fas fa-thumbs-up"></i>&nbsp;<?= $data['like_count'] ?>
                </button>
              </form>
              <form action="insertVote.php?ref=acteurs&ref_id=<?= $data['id']; ?>&vote=-1" method="POST">
                <button type="submit" class="vote_btn vote_dislike">
                  <i class="fa fa-thumbs-down" aria-hidden="true"></i>&nbsp;<?= $data['dislike_count'] ?>
                </button>
              </form>
            </div>
          </div>
          <br>
        </div>
        <script>
          function myComment() {
            let form = document.getElementById("comment");
            if (form.style.display === "none") {
              form.style.display = "block";
            } else {
              form.style.display = "none";
            }
          }
        </script>
        <div id="comment" style="display: none">
          <form action="insertPost.php" method="POST">
              <p><label for="author">Prénom:</label><input type="text" name="author" id="author" value="<?= $firstname ?>"></p>
              <p><label for="date">Date:</label><input type="date" name="date" id="date" value="<?= $today ?>"></p>

              <p><label for="comment">Votre commentaire:</label><textarea name="comment" id="comment" cols="50" rows="5"></textarea></p>
              <p><input type="submit" value="Envoyer"></p>
          </form>
          </div>
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

        $req->execute([$_GET['acteur']]);
        while ($data = $req->fetch()) { ?>
          <p><?php echo $data['first_name']; ?></p>
          <p><?php echo $data['date']; ?></p>
          <p><?php echo htmlspecialchars($data['comment']) ?></p>
        <?php }
        $req->closeCursor();
    }
include_once('footer.php');
?>
