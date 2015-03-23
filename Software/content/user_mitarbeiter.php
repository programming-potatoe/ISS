<?php
	if(!defined('__ROOT__'))
   {
          define('__ROOT__', dirname(dirname(__FILE__)));
   }
   require_once(__ROOT__ ."/utils/functions.php");
	check_berechtigung('j', 'n', 'n', 'n', 'n');
   
   
   if(!isset($_GET['art'])){
   	
   	$_GET['art'] = 0;
   	
   }
   
   //0=Startseite; 1=neuen Mitarbeiter; 2=Mitarbeiter bearbeiten; 3=Mitarbeiter loeschen
   switch ($_GET['art']){
   	
   	case 0:
   //Startseite
	echo"Hier k&ouml;nnen Mitarbeiter bearbeitet werden <br> <br>";
	
			echo 'Hier k&ouml;nnen Mitarbeiter bearbeitet werden <br> Neuen Mitarbeiter <a href="content/user_mitarbeiter.php?art=1" data-change="main">hinzuf&uuml;gen</a>?<br><br>';
			
			
			
			
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
				
				echo '</td><td><a href="content/user_mitarbeiter.php?art=2&id='.$row['PID'].'" data-change="main">bearbeiten</a></td><td><a href="content/user_mitarbeiter.php?&art=3id='.$row['PID'].'" data-change="main">l&ouml;schen</a></td></tr>';
			}
				
			echo "</table>";
			
			break;
			
   	case 1:
   		//neuen Mitarbeiter anlegen
   		echo 'hier neuer Mitarbeiter';
   		?>
   		<form action="content/user_mitarbeiter.php?art=4">
   		Nachname: <br> <input type="text" placeholder="Nachname" name="nname" /> <br>
   		Vorname: <br> <input type="text" placeholder="Vorname" name="vname" /> <br>
   		Email-Adresse: <br> <input type="text" placeholder="Email" name="email" /> <br>
   		
   		Art:<br> 
   		<select name="art">
   		<option selected="selected">Dozent</option>
   		<option>Pr&uuml;fer</option>
   		<option>Dozent und Pr&uuml;fer</option>
   		</select>
   		<br>
   		Initialpasswort: <br> <input type="text" placeholder="Initialpasswort" name="password" /> <br>
   		<button type="submit">Anlegen</button>
   						</form>
   		<?php 
   		break;
   	case 2:
   		//Mitarbeiter bearbeiten
   		echo 'hier mitarbeiter bearbeiten';
   		break;
   	case 3:
   		//Mitarbeiter loeschen
   		echo 'hier loeschen';
   		break;		
			
   }		
		//	while ($row = mysql_fetch_assoc($result)) {
		//		echo ''.$row['PID'].'   '.$row['PName'].'   '.$row['PVName'].' <br>';
		//	}

