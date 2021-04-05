<?php

class Vote {

  private $pdo;
  private $formerVote;

  public function __construct(PDO $pdo) {
    $this->pdo = $pdo;
  }

  private function recordExists($ref, $ref_id) {
    $req = $this->pdo->prepare("SELECT * FROM $ref WHERE id=?");
    $req->execute([$ref_id]);
    if ($req->rowCount() == 0) {
      throw new Exception('Impossible de voter pour un acteur qui n\'exist pas');
    }
  }

  public function like($ref, $ref_id, $user_id) {

    // Changed $ref, $ref_id, $user_id in table `votes` to index key, (not primary key)
    if ($this->vote($ref, $ref_id, $user_id, 1)) {

      // Vote and update `like_count/dislike_count` in table `acteurs`
      $sql_part = "";
      if ($this->formerVote) {
        // Put `,` here for in case we don't need to update
        // In SQL, `dislike_count -= 1` doesn't work
        $sql_part = ", dislike_count = dislike_count - 1";
      }

      $req = $this->pdo->prepare("UPDATE $ref SET like_count = like_count + 1 $sql_part WHERE id=?");
      $req->execute([$ref_id]);
      return true;
    }

    return false;

  }

  public function dislike($ref, $ref_id, $user_id) {

    if ($this->vote($ref, $ref_id, $user_id, -1)) {

      $sql_part = "";
      if ($this->formerVote) {
        $sql_part = ", like_count = like_count - 1";
      }

      $req = $this->pdo->prepare("UPDATE $ref SET dislike_count = dislike_count + 1 $sql_part WHERE id=?");
      $req->execute([$ref_id]);
      return true;
    }

    return false;

  }

  private function vote($ref, $ref_id, $user_id, $vote) {

    $this->recordExists($ref, $ref_id);

    $req = $this->pdo->prepare("SELECT id, vote FROM votes WHERE ref=? AND ref_id=? AND user_id=?");
    $req->execute([$ref, $ref_id, $user_id]);
    $vote_row = $req->fetch();

    // If user already liked/disliked this acteur, no like/dislike again
    if ($vote_row) {
      if ($vote_row['vote'] == $vote) {
        return false;
      }

      $this->formerVote = $vote_row;
      // If user changed like to dislike or vise versa, update this id in `votes`
      $this->pdo->prepare("UPDATE votes SET vote = ? WHERE id = {$vote_row['id']}")->execute([$vote]);
      return true;
    }

    $req = $this->pdo->prepare("INSERT INTO votes SET ref=?, ref_id=?, user_id=?, vote = $vote"); // To add variables in SQL query, using "double quotes"
    $req->execute([$ref, $ref_id, $user_id]);
    return true;
  }

  // Option 2: Add and update the count in table `acteurs`
  /*public function updateCount($ref, $ref_id) {
    $req = $this->pdo->prepare("SELECT COUNT(id) as count, vote FROM votes WHERE ref=? AND ref_id=? GROUP BY vote");
    $req->execute([$ref, $ref_id]);
    $votes = $req->fetchAll();

    // If no votes found, keep 0 as record
    $counts = [
      '-1' => 0,
      '1' => 0
    ];
    foreach ($votes as $vote) {
      // $count['-1'] = $vote[COUNT(id)] = 1
      $counts[$vote['vote']] == $vote['count'];
    }

    $req = $this->pdo->query("UPDATE $ref SET like_count = {$counts[1]}, dislike_count = {$counts[-1]} WHERE id = $ref_id");
    return true;

  }*/

  /**
   * Permet d'ajouter une class is-liked ou is-disliked suivant un enregistrement
   * @param $vote mixed false/enregistrement
  */

  public static function getClass($vote) {
    if ($vote) {
      // Ternaire/ternary operator
      return ($vote['vote'] == 1) ? 'is-liked' : 'is-disliked';
      // `$vote->vote` ne marche pas, car $vote n'est pas un objet, c'est un data vient de fetch
    }
    return null;
  }
}
?>
