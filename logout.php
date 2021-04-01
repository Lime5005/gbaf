<?php
session_start();
session_unset();
session_destroy();

setcookie('username', '', time() - 86400);
setcookie('lastname', '', time() - 86400);
setcookie('firstname', '', time() - 86400);

header("Location: ./login.php", true, 302);
exit();

?>