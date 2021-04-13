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
  <meta name="keywords" content="banques françaises, groupement des banques, sociétés d'assurances">

</head>
<body>
<div id="container">
  <header class="header">
    <div class="header-container">
      <div class="row align-items-center justify-content-around">
        <div class="logo-user">
          <a href="index.php"><img src="images/gbaf.png" alt="logo" class="image-keep-ratio" width="50" height="50"></a>
          <div class="row align-items-center">
            <?php if(isset($firstname) && isset($lastname)) echo '<span class="username"><i class="fas fa-user-alt"></i>&nbsp; ' . $lastname . ' ' . $firstname . '</span>';?>
          </div>
        </div>
        <input type="checkbox" id="nav-check">
        <label for="nav-check" class="nav-toggler">
          <span></span>
        </label>
        <nav class="nav">
          <ul>
            <li><a href="index.php" class="active">Page d'accueil</a></li>
            <?php if(isset($_SESSION['username']) && isset($_SESSION['firstname']) && isset($_SESSION['lastname'])) {?>
              <li><a href="modify_account.php">Paramètres du compte</a></li>
              <li><a href="logout.php">Se déconnecter</a></li>
            <?php } ?>
          </ul>
        </nav>
      </div>
    </div>
  </header>
  <div id="content">
    <div id="middle-page">