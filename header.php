<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="style.css">
  <title>GBAF | Le Groupement Banque-Assurance Français</title>
  <meta name="description" content="Groupement des ressources pour les salariés
des différentes banques françaises.">
  <meta name="keywords" content="banques français, groupement de des banques, sociétés d'assurances">

</head>
<body>
<div id="container">
  <div id="top-header">
    <header>
      <ul>
        <?php if(isset($firstname) && isset($lastname)) echo '<p>Bonjour ' . $lastname . ' ' . $firstname . '</p>';?>
        <?php if(isset($_SESSION['username']) || isset($_SESSION['firstname']) || isset($_SESSION['lastname'])) {?> 
          <li><a href="logout.php">Se déconnecter</a></li>
          <br>
          <li><a href="modify_account.php">Paramètres du compte</a></li>
          <br>
       <?php } ?>

      </ul>
    </header>
  </div>
  <div id="middle-page">