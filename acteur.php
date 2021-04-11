<?php

    include_once('session.php');

    $today = date("Y-m-d");

    if(isset($_GET['acteur'])) {

      setcookie("acteur_id", $_GET['acteur'], time()+(60*60*24));

      include_once('connect.php');
      include_once('Vote.php');

      // Show if the user voted for this acteur, green or red
      $vote = false;
      $reqVote = $connection->prepare('SELECT * FROM votes WHERE ref=? AND ref_id=? AND user_id=(SELECT id FROM accounts WHERE username=?)');
      $reqVote->execute(['acteurs', $_GET['acteur'], $_SESSION['username']]);

      $vote = $reqVote->fetch();

      // Get the acteur by id
      $req = $connection->prepare('SELECT * FROM acteurs WHERE id=?');
      $req->execute([$_GET['acteur']]);
      $data = $req->fetch();

      include_once('header.php');
      // var_dump($data);
      ?>
      <!-- Acteur's post -->
        <article class="post">
          <div class="large-logo">
            <img class="post-image" src="images/<?php echo $data['logo']; ?>" alt="logo">
          </div>
          <h2>
            <?php echo $data['name']; ?>
          </h2>
          <p>
            <?php echo nl2br(htmlspecialchars($data['description'])); ?>
          </p>
          <br>
          <!-- Votes and comment btn -->
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
            <!-- Comment btn -->
            <div class="comment_btn">
              <button class="btnOnClick" onclick="myComment()">Nouveau Commentaire</button>
            </div>
          </div>
          <br>
          <?php
            if(isset($_SESSION['error'])) {
              echo '<div class="alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
              unset($_SESSION['error']);
            }
          ?>
        </article>
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
        <!-- Comment form -->
        <div id="comment" style="display: none">
          <form class="comment-form" action="insertPost.php" method="POST">
              <label for="author">Prénom:</label><br><input type="text" name="author" id="author" value="<?= $firstname ?>"><br>
              <label for="date">Date:</label><br><input type="date" name="date" id="date" value="<?= $today ?>"><br>

              <label for="comment">Votre Commentaire:</label><br><textarea name="comment" id="comment" cols="80" rows="8"></textarea><br>
              <input type="submit" value="Envoyer">
          </form>
        </div>
          <!-- Comments list -->
        <div class="comments-list">
          <h2>Commentaires</h2>
          <?php
          // Show all the comments by time but ONLY show date from every user for this bank
          // Find the firstname from `accounts` by user_id from `posts`
          // How to get date from type`datetime`: SELECT DATE_FORMAT(column_name, '%d-%m/%b-%Y') FROM tablename
          $req = $connection->prepare("SELECT bank_id, user_id, comment, DATE_FORMAT(date_created, '%d-%m-%Y') as date, first_name FROM posts
            JOIN accounts
            ON posts.user_id = accounts.id
            WHERE posts.bank_id = ?
            ORDER BY date_created DESC");

            $req->execute([$_GET['acteur']]);
            while ($data = $req->fetch()) { ?>
              <p><?php echo '<i class="fas fa-user-circle"></i> ' . '<span class="comment-name">' .
              $data['first_name'] . '</span>'; ?><span class="comment-date"><?php echo '&nbsp;&nbsp;' . $data['date']; ?></span></p>
              <p class="comment-text"><?php echo htmlspecialchars($data['comment']) ?></p>
            <?php }
            $req->closeCursor();
            ?>
        </div><?php
    }
include_once('footer.php');
?>