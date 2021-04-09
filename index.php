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
    include_once('header.php');?>
    <section class="gbaf-description">
      <article>
        <h1>Le Groupement Banque Assurance Français</h1><p>(GBAF) est une fédération
        représentant les 6 grands groupes français :</p>
        <ul>
          <li>BNP Paribas ;</li>
          <li>BPCE ;</li>
          <li>Crédit Agricole ;</li>
          <li>Crédit Mutuel-CIC ;</li>
          <li>Société Générale ;</li>
          <li>La Banque Postale.</li>
        </ul>
        <p>Même s’il existe une forte concurrence entre ces entités, elles vont toutes travailler
        de la même façon pour gérer près de 80 millions de comptes sur le territoire
        national.</p>
        <p>Nous souhaitons proposer aux salariés des grands groupes
        français un point d’entrée unique, répertoriant un grand nombre d’informations
        sur les partenaires et acteurs du groupe ainsi que sur les produits et services
        bancaires et financiers.
        Chaque salarié pourra ainsi poster un commentaire et donner son avis.</p>
      <br>
    </section>
    <?php foreach ($row as $entry) {
      ?>
      <div class="card">
        <div class="card-image">
          <img src="images/<?php echo $entry['logo']; ?>" alt="Acteur-image">
        </div>
        <div class="card-content">
          <h3><?php echo $entry['name']; ?></h3>
          <p><?php echo $entry['detail']; ?></p>
          <span><a class="card-link" href="acteur.php?acteur=<?php echo $entry['id']; ?>">Afficher la suite</a></span>
        </div>
      </div>
    <?php }
  }
}

include_once('footer.php');
?>
