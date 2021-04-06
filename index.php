<?php

require_once('session.php');

// Find and list down all the acteurs
require_once('connect.php');
$req = $connection->prepare('SELECT id, logo, name, SUBSTRING(description, 1, 220) as detail FROM acteurs');
$req->execute();
if ($req->rowCount() == 0) {
  echo "<br>Pas d'acteur.<br />";
} else {
  while ($row = $req->fetchAll()) {
    //var_dump($row);
    // How to show blob data to HTML? = just save the image name in database
    include_once('header.php');
    foreach ($row as $entry) {
      ?>
      <div class="card">
        <p><img src="images/<?php echo $entry['logo']; ?>" alt="acteur-logo" width="600"></p>
        <h3><?php echo $entry['name']; ?></h3>
        <p><?php echo $entry['detail']; ?></p><span><a href="acteur.php?acteur=<?php echo $entry['id']; ?>">Afficher la suite</a></span>
      </div>
    <?php }
  }
}

include_once('footer.php');
?>
