<?php
	if(!defined('__ROOT__'))
   {
          define('__ROOT__', dirname(dirname(__FILE__)));
   }
   require_once(__ROOT__ ."/utils/functions.php");

	echo"Hier k&ouml;nnen Mitarbeiter bearbeitet werden <br> <br>";
	
	
			$query = "SELECT PID, PName, PVName FROM pruefer";
			$result = mysql_query($query);
			
			while ($row = mysql_fetch_assoc($result)) {
				echo ''.$row['PID'].'   '.$row['PName'].'   '.$row['PVName'].' <br>';
			}

?>