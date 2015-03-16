<?php
	if(!defined('__ROOT__'))
   {
          define('__ROOT__', dirname(dirname(__FILE__)));
   }
   require_once(__ROOT__ ."/utils/functions.php");
			
			
		if(isset($_GET['aid']))
		{
				echo("Detailansicht<br><br>");
				
				$query = "SELECT p.SchemaID, p.PruefGenauigkeit, a.ANr, a.AMaxPunkte FROM pruefungsschema p, aufgaben a WHERE p.SchemaID = a.SchemaID AND a.SchemaID = ".$_GET['aid']." ORDER BY a.ANr";
				$result = mysql_query($query);
				$row = mysql_fetch_array($result);
				echo "Schema NR: ".$row['SchemaID']."<br><br>";
				
				echo "<table><tr><td>Aufgaben NR</td><td>MaxPunkte</td>";
				for($i=0; $i < $row['PruefGenauigkeit']; $i++) 
				{
						echo "<td></td>";
				}	
				
				echo "</tr>";		
								
				 do{
					echo '<tr><td>'.$row['ANr'].'</td><td>'.$row['AMaxPunkte'].'</td>';
					for($i=0; $i < $row['PruefGenauigkeit']; $i++) 
					{
						echo "<td></td>";
					}	
				
					echo "</tr>";	
				} while ($row = mysql_fetch_assoc($result));
				
				echo "</table><br><br>";
				
				//@TODO: echo "Hier fehlt: bearbeiten, löschen<br><br>";
				
				echo('<a href="content/user_pruefungs_schemata.php" data-change="main">zur&uuml;ck</a>');
		}	
			
		//Just copy & paste	
		/*else if(isset($_GET['id']))
		{
				echo("Hier wird bearbeitet! <br><br>");
				
				echo('<a href="content/user_prueflinge.php" data-change="main">zur&uuml;ck</a>');			
		}
		else if(isset($_GET['lid']))
		{
				echo("Hier wird gel&ouml;scht! <br><br>");
				
				$query = "SELECT PrName, PrVName FROM pruefling WHERE PrID = ".$_GET['lid'];				
				$row = mysql_fetch_array(mysql_query($query));
				echo $row['PrName'].'  '.$row['PrVName'].' wirklich loeschen? <a href="content/user_prueflinge.php?llid='.$_GET['lid'].'" data-change="main">Ja, l&ouml;schen</a><br><br>';
				
				echo('<a href="content/user_prueflinge.php" data-change="main">zur&uuml;ck</a>');	
		}
		else if(isset($_GET['llid']))
		{
				$query = "DELETE FROM pruefling WHERE PrID = ".$_GET['llid'];
				if(mysql_query($query)) 
				{
					echo "Gel&ouml;scht!<br>";
				}
				else 
				{
					echo "Fehler - nicht gel&ouml;scht!<br>";
				}
				echo('<a href="content/user_prueflinge.php" data-change="main">zur&uuml;ck</a>');
		}*/
		else if(isset($_GET['new']))
		{
				echo("Hier wird ein neues Schema hinzugef&uuml;gt! <br><br>");
?>
				<form action="content/user_pruefungs_schemata.php">
						Pr&uuml;fungsgenauigkeit: <br> <input type="text" placeholder="Pr&uuml;fungsgenauigkeit" name="pruegenau" /> <br>
						Anzahl Aufgaben: <br> <input type="text" placeholder="Anzahl" name="anzahl" /> <br>						
						<button type="submit">Weiter</button>
				</form>
<?php		
				echo('<a href="content/user_pruefungs_schemata.php" data-change="main">zur&uuml;ck</a>');	
		}
		else if(isset($_POST['pruegenau']))
		{	
				$pruegenau = mysql_real_escape_string($_POST['pruegenau']);
				$anzahl = mysql_real_escape_string($_POST['anzahl']);
			   	   
				echo '<form action="content/user_pruefungs_schemata.php">';
				echo '		Pr&uuml;fungsgenauigkeit: <br> <input type="text" value="'.$pruegenau.'" name="pruegenau2" readonly/> <br>';
				echo '		Anzahl Aufgaben: <br> <input type="text" value="'.$anzahl.'" name="anzahl2" readonly/> <br>	';
				echo 'Maximale Punktezahlen: <br> Aufgabe: <br>';
				for ($i = 1; $i <= $anzahl; $i++)
				{
						echo $i.' : <input type="text" placeholder="a'.$i.'" name="'.$i.'" /> <br>	';		
				}
									
				echo '		<button type="submit">Anlegen</button>';
				echo '</form>';

			   echo '<a href="content/user_pruefungs_schemata.php" data-change="main">Abbrechen</a>';	
			 
		}				
		else if(isset($_POST['pruegenau2']))
		{
				$pruegenau2 = mysql_real_escape_string($_POST['pruegenau2']);
				$anzahl2 = mysql_real_escape_string($_POST['anzahl2']);
				
				$query = 'INSERT INTO pruefungsschema VALUES (NULL, '.$pruegenau2.')';
				
				if(mysql_query($query))
			   { 
					echo "Ein neues Schema wurde angelegt.<br><br>";
					
					//HIER KANN ICH MIR NICHT ANDERS HELFEN DA ICH DEN AUTO_INCREMENT NICHT KENNE!!!
					
					$schemaID = mysql_fetch_array(mysql_query('SELECT MAX(SchemaID) AS Max FROM pruefungsschema;'));					
					
					for ($i = 1; $i <= $anzahl2; $i++)
					{
						$maxpunkte = mysql_real_escape_string($_POST[$i]);
						$query = 'INSERT INTO aufgaben VALUES (NULL, '.$i.', '.$maxpunkte.', '.$schemaID['Max'].')';
						if(mysql_query($query))
						{
							 	echo $i.' wurde hinzugef&uuml;gt!<br>';
						}
						else 
						{
								echo $i.' liefert Fehler!<br>';
						}						
					}
				}
				else 
				{
					echo 'Error - Try Again';
				}
					
				echo '<a href="content/user_pruefungs_schemata.php" data-change="main">zur&uuml;ck</a>';			
		}		
		else		
		{			
				//Standardanzeige:
			
				echo 'Hier k&ouml;nnen Schemata angelegt & bearbeitet werden <br> Neues Schema <a href="content/user_pruefungs_schemata.php?new=1" data-change="main">hinzuf&uuml;gen</a>?<br><br>';
				
				$query = "SELECT p.SchemaID, p.PruefGenauigkeit, COUNT(a.ANr) AS Anzahl FROM pruefungsschema p LEFT OUTER JOIN  aufgaben a ON p.SchemaID = a.SchemaID GROUP BY p.SchemaID";
				$result = mysql_query($query);
			
				echo "<table><tr><td>SchemaID</td><td>PruefGenauigkeit</td><td>Aufgabenanzahl</td><td></td></tr>";			
			
				while ($row = mysql_fetch_assoc($result)) {
					echo '<tr><td>'.$row['SchemaID'].'</td><td>'.$row['PruefGenauigkeit'].'</td><td>'.$row['Anzahl'].'</td><td><a href="content/user_pruefungs_schemata.php?aid='.$row['SchemaID'].'" data-change="main">anzeigen</a></td></tr>';
				}
			
				echo "</table>";
		}

?>