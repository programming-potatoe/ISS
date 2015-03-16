<?php

  require_once("./functions.php");

  session_destroy();

  //echo "Goodbye, see you soon!";

  include('../content/login_screen.php');

  mysql_close($link);
?>
