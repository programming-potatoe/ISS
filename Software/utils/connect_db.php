
<?php
  $user_name = "scoresystem";
  $password = "inteligent";
  $database = "scoresystem";
  $server = "localhost";
	
	
	
  $link = mysql_connect($server,$user_name,$password);

  //echo "Connection to the Server opened";

  $db_found = mysql_select_db($database);

  
?>
