<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="style.css">
  <title>GBAF | Le Groupement Banque-Assurance Français</title>
  <meta name="description" content="Groupement des ressources pour les salariés
des différentes banques françaises.">
  <meta name="keywords" content="banques français, groupement de des banques, sociétés d'assurances">

</head>
<body>
  <div id="nav-bar">
    <div class="logo">
      <a href="index.php"><img class="primary-icon" src="images/logo_gbaf.png" alt="logo"></a>
    </div>
    <nav>
        <ul>
          <?php if(isset($firstname) && isset($lastname)) echo '<li><i class="fas fa-user-alt"></i>&nbsp; ' . $lastname . ' ' . $firstname . '</li>';?>
          <?php if(isset($_SESSION['username']) && isset($_SESSION['firstname']) && isset($_SESSION['lastname'])) {?>
            <li><a href="logout.php">Se déconnecter</a></li>
            <li><a href="modify_account.php">Paramètres du compte</a></li>
          <?php } ?>
        </ul>
    </nav>
  </div>
<div id="container">
  <div id="middle-page">