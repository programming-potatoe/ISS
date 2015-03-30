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
   
   //0=Startseite; 1=Detailansicht; 2=Schema bearbeiten; 3=Schema insert; 4=Schema loeschen; 5=Schema loeschen delete; 6=neu Anlegen Schritt 1; 7=neu Anlegen Schritt 2; 8=neu Anlegen insert;
   switch ($_GET['art']){
   	 
   	case 0:
   		//Startseite
   
   		echo 'Hier k&ouml;nnen Schemata angelegt & bearbeitet werden <br> Neues Schema <a href="content/user_pruefungs_schemata.php?art=6" data-change="main">hinzuf&uuml;gen</a>?<br><br>';
   		
   		$query = "SELECT p.SchemaID, p.SchemaBez, p.PruefGenauigkeit, COUNT(a.ANr) AS Anzahl FROM pruefungsschema p LEFT OUTER JOIN  aufgaben a ON p.SchemaID = a.SchemaID GROUP BY p.SchemaID";
   		$result = mysql_query($query);
   			
   		echo "<table><tr><td>SchemaID</td><td>SchemaBez</td><td>PruefGenauigkeit</td><td>Aufgabenanzahl</td><td></td></tr>";
   			
   		while ($row = mysql_fetch_assoc($result)) {
   			echo '<tr><td>'.$row['SchemaID'].'</td><td>'.$row['SchemaBez'].'</td><td>'.$row['PruefGenauigkeit'].'</td><td>'.$row['Anzahl'].'</td><td><a href="content/user_pruefungs_schemata.php?art=1&schemaid='.$row['SchemaID'].'" data-change="main">anzeigen</a></td></tr>';
   		}
   			
   		echo "</table>";
   		
   		break;
   		
   	case 1:
   		//Detailansicht
   		
   		echo("Detailansicht<br><br>");
   		
   		$query = "SELECT p.SchemaID, p.SchemaBez, p.PruefGenauigkeit, a.ANr, a.AMaxPunkte FROM pruefungsschema p, aufgaben a WHERE p.SchemaID = a.SchemaID AND a.SchemaID = ".$_GET['schemaid']." ORDER BY a.ANr";
   		$result = mysql_query($query);
   		$row = mysql_fetch_array($result);
   		echo "Schema Bezeichnung: ".$row['SchemaBez']."<br>";
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
   		
   		break;
   		
   	case 2:
   		//Schema bearbeiten @TODO
   		
   		//echo("Hier wird bearbeitet! <br><br>");
   		
   		//echo('<a href="content/user_prueflinge.php" data-change="main">zur&uuml;ck</a>');
   		
   		break;
   		
   	case 3:
   		//Schema insert  @TODO
   		
   		//insert blablabla
   		
   		break;
   		
   	case 4:
   		//Schema loeschen @TODO
   		
   		
   		
   		break;
   		
   	case 5:
   		//Schema loeschen delete @TODO
   		
   		/*$query = "DELETE FROM pruefling WHERE PrID = ".$_GET['llid'];
   		if(mysql_query($query))
   		{
   			echo "Gel&ouml;scht!<br>";
   		}
   		else
   		{
   			echo "Fehler - nicht gel&ouml;scht!<br>";
   		}
   		echo('<a href="content/user_prueflinge.php" data-change="main">zur&uuml;ck</a>');*/
   		
   		break;
   		
   	case 6:
   		//Anlegen Schritt 1 
   		
   		echo("Hier wird ein neues Schema hinzugef&uuml;gt! <br><br>");
   		?>
   						<form action="content/user_pruefungs_schemata.php?art=7">
   								Schemabezeichung: <br> <input type="text" placeholder="Schemabezeichnung" name="schemabez" /> <br>
   								Pr&uuml;fungsgenauigkeit: <br> <input type="text" placeholder="Pr&uuml;fungsgenauigkeit" name="pruegenau" /> <br>
   								Anzahl Aufgaben: <br> <input type="text" placeholder="Anzahl" name="anzahl" /> <br>						
   								<button type="submit">Weiter</button>
   						</form>
   		<?php		
   						echo('<a href="content/user_pruefungs_schemata.php" data-change="main">zur&uuml;ck</a>');	
   		
   		break;
   		
   	case 7:
   		//neu Anlegen Schritt 2
   		
   		$schemabez = mysql_real_escape_string($_POST['schemabez']);
   		$pruegenau = mysql_real_escape_string($_POST['pruegenau']);
   		$anzahl = mysql_real_escape_string($_POST['anzahl']);
   		
   		echo '<form action="content/user_pruefungs_schemata.php?art=8">';
   		echo 'Schemabezeichnung: <br> <input type="text" value="'.$schemabez.'" name="schemabez" readonly/> <br>';
   		echo 'Pr&uuml;fungsgenauigkeit: <br> <input type="text" value="'.$pruegenau.'" name="pruegenau" readonly/> <br>';
   		echo 'Anzahl Aufgaben: <br> <input type="text" value="'.$anzahl.'" name="anzahl" readonly/> <br>	';
   		echo 'Maximale Punktezahlen: <br> Aufgabe: <br>';
   		for ($i = 1; $i <= $anzahl; $i++)
   		{
   		echo $i.' : <input type="text" placeholder="a'.$i.'" name="'.$i.'" /> <br>	';
				}
   							
   						echo '		<button type="submit">Anlegen</button>';
				echo '</form>';
   		
   						echo '<a href="content/user_pruefungs_schemata.php" data-change="main">Abbrechen</a>';
   		
   		break;
   		
   	case 8: 
   		//neu Anlegen insert
   		
   		$schemabez = mysql_real_escape_string($_POST['schemabez']);
   		$pruegenau = mysql_real_escape_string($_POST['pruegenau']);
   		$anzahl = mysql_real_escape_string($_POST['anzahl']);
   		
   		$query = 'INSERT INTO pruefungsschema VALUES (NULL, "'. $schemabez .'",'.$pruegenau.')';
   		
   		if(mysql_query($query))
   		{
   			echo "Ein neues Schema wurde angelegt.<br><br>";
   				
   			//TODO HIER KANN ICH MIR NICHT ANDERS HELFEN DA ICH DEN AUTO_INCREMENT NICHT KENNE!!!
   				
   			//Hier werden die einzelnen Aufgaben hinzuefügt
   				
   			$schemaID = mysql_fetch_array(mysql_query('SELECT MAX(SchemaID) AS Max FROM pruefungsschema;'));
   				
   			for ($i = 1; $i <= $anzahl; $i++)
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
   		
   		break;
   }
   

		
?>