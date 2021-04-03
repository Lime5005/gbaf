<?php

session_start();

$lastname;
$firstname;
$username;
if (isset($_SESSION['lastname'])) $lastname = $_SESSION['lastname'];
if (isset($_SESSION['firstname'])) $firstname = $_SESSION['firstname'];
if (isset($_SESSION['username'])) $username = $_SESSION['username'];
if (isset($_COOKIE['lastname'])) $lastname = $_COOKIE['lastname'];
if (isset($_COOKIE['firstname'])) $firstname = $_COOKIE['firstname'];
if (isset($_COOKIE['username'])) $username = $_COOKIE['username'];

if (!isset($username)) {
  header("Location: ./login.php", true, 302);
  exit();
}

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
        <p><img src="images/<?php echo $entry['logo']; ?>" alt="logo" height="130" width="350"></p>
        <h3><?php echo $entry['name']; ?></h3>
        <p><?php echo $entry['detail']; ?></p><span><a href="acteur.php?acteur=<?php echo $entry['id']; ?>">Afficher la suite</a></span>
      </div>
    <?php }
  }
}
include_once('footer.php');
?>
