<?php
// "Mot de passe oublié?" a link to set new password
// pass all the info from this page to other pages:
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

  if (isset($username)) {
    header("Location: ./index.php", true, 302);
    exit();
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $connection = new PDO('mysql:host=localhost;dbname=gbaf;charset=UTF8', '', ''); // Change password for test
    $req = $connection->prepare('SELECT last_name, first_name, username, password FROM accounts WHERE username=?');
    $req->execute([$_POST['username']]);

    if ($req->rowCount() == 0) {
      echo "Incorrect username/mot de passe<br />";
    } else {
      // $row = all the info as an object
      $row = $req->fetch(PDO::FETCH_ASSOC);
      // print_r($row);
      if (!password_verify($_POST['password'], $row['password'])) {
        echo "Incorrect mot de passe<br />";
      } else {
        $_SESSION['lastname'] = $row['last_name'];
        $_SESSION['firstname'] = $row['first_name'];
        $_SESSION['username'] = $row['username'];

        if (!empty($_POST['remember_me'])) {
          setcookie('username', $row['username'], time() + 3600 * 24 * 30);
          setcookie('lastname', $row['last_name'], time() + 3600 * 24 * 30);
          setcookie('firstname', $row['first_name'], time() + 3600 * 24 * 30);

        }

        header("Location: ./index.php", true, 302);
        exit();
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page Login</title>
</head>
<body>
  <div>
    <h1>Login form:</h1>
    <form action="" method="POST">
      <label for="email">Username: </label>
      <input type="text" name="username">
      <br>
      <label for="password">Mot de passe: </label>
      <input type="password" name="password">
      <br>
      <input type="checkbox" name="remember_me">Remember me<br>
      <input type="submit" name="submit" value="Submit">
      <br>
      <p><a href="forget_password.php">Mot de passe oublié?</a></p>
    </form>
  </div>
</body>
</html>