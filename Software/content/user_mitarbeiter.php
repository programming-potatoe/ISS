<?php
	if(!defined('__ROOT__'))
   {
          define('__ROOT__', dirname(dirname(__FILE__)));
   }
   require_once(__ROOT__ ."/utils/functions.php");

	echo"Hier k&ouml;nnen Mitarbeiter bearbeitet werden <br> <br>";
	
			echo 'Hier k&ouml;nnen Mitarbeiter bearbeitet werden <br> Neuen Mitarbeiter <a href="content/user_mitarbeiter?new=1" data-change="main">hinzuf&uuml;gen</a>?<br><br>';
			
			
			
			
			$query = "SELECT p.PID, p.PName, PVName, p.PEmail, p.PArt FROM pruefer p where p.part in(1, 2, 3)";
			$result = mysql_query($query);
				
				
			echo "<table><tr><td>PrID</td><td>Name</td><td>Vorname</td><td>Art</td><td></td><td></td></tr>";
				
			while ($row = mysql_fetch_assoc($result)) {
				echo '<tr><td>'.$row['PName'].'</td><td>'.$row['PVName'].'</td><td>'.$row['PEmail'].'</td><td>';
				
				switch ($row['PArt']){
					
					case 1:
						echo 'Dozent und Pr&uuml;fer';
						break;
					case 2:
						echo 'Pr&uuml;fer';
						break;
					case 3: 
						echo 'Dozent';
						break;
				}
				
				echo '</td><td><a href="content/user_mitarbeiter.php?id='.$row['PID'].'" data-change="main">bearbeiten</a></td><td><a href="content/user_mitarbeiter.php?lid='.$row['PID'].'" data-change="main">l&ouml;schen</a></td></tr>';
			}
				
			echo "</table>";
			
		//	while ($row = mysql_fetch_assoc($result)) {
		//		echo ''.$row['PID'].'   '.$row['PName'].'   '.$row['PVName'].' <br>';
		//	}

