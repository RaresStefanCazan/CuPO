<?php
session_start();
session_unset();
session_destroy();
setcookie(session_name(), '', time() - 3600, '/');


setcookie('user_email', '', time() - 3600, '/');

setcookie('currentListId', '', time() - 3600, '/');

header("Location: /home/home");
exit();
?>
