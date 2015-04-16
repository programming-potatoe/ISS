<?php
	if(!defined('__ROOT__'))
   {
          define('__ROOT__', dirname(dirname(__FILE__)));
   }
   require_once(__ROOT__ ."/utils/functions.php");

	check_berechtigung('j', 'j', 'j', 'j', 'j');
   
	
	
	if(!isset($_GET['art'])){
		 
		$_GET['art'] = 0;
		 
	}
	
	$rights = $_SESSION['user_rights'];
	
	/*  0=Startseite;
	 *  1=Pruefung bearbeiten;
	 *  2=Pruefung update;
	 *  3=neue Pruefung hinzufügen mit VID bekannt;
	 *  4=neue Pruefung insert und Objekte erstellen;
	 *  5=Pruefung loeschen;
	 *  6=Pruefung loeschen delete;
	 *  7=neuen Pruefer zuordnen;
	 *  8=neuen Pruefer zuordnen insert;
	 *  9=dummy;
	 * 	10=neue Prüfung hinzufügen mit VID unbekannt;
	 *  11=Prüfungsdetails ansehen;
	 */
	switch ($_GET['art']){
		 
		case 0:
			//Startseite
				
			//Visible + Feedback + Prüfer zuordnen
			if($rights == 0 | $rights == 1 | $rights == 2)
			{
				echo "<br><br>F&uuml;r folgende Pr&uuml;fungen m&uuml;ssen visible geschaltet und Feedback abgegeben werden:<br><br>";
					
				$query ="SELECT pl.PruefID, pl.PruefBez, v.vbez, k.kbez
								FROM pruefungsleistungen pl, pruefer p, vorlesungen v, kurse k, pruefungsleistungsobjekt po
								WHERE pl.PID = p.PID
								AND pl.vid = v.vid
								AND v.kid = k.kid
								AND p.PID = ".$_SESSION['user_ID']."
								AND po.pruefstatus = 0
								AND po.pruefID = pl.pruefID
								GROUP BY pruefID";
			
				$result = mysql_query($query);
					
				echo '<table class="pure-table"><tr><th>Pruefungs ID</th><th>Pr&uuml;fungsBez</th><th>Vorlesung</th><th>Kurs</th><th>Anzeigen</th></tr>';
					
				while($row = mysql_fetch_assoc($result))
				{
					echo '<tr><td>'.$row['PruefID'].'</td><td>'.$row['PruefBez'].'</td><td>'.$row['vbez'].'</td><td>'.$row['kbez'].'</td><td>
					<a href="content/user_pruefungen.php?art=11&pruefid='.$row['PruefID'].'" data-change="main"><i class="fa fa-eye"></i></a></td></tr>';
				}
					
				echo "</table>";
			}
			
				
			//Bewertung abgeben
			if($rights == 0 | $rights == 1 | $rights == 3)
			{
				echo "<br><br>F&uuml;r folgende Pr&uuml;fungen ist eine Bewertung vorgesehen:<br><br>";
					
				$query ="SELECT pl.PruefID, pl.PruefBez, v.vbez, k.kbez
								FROM pruefungsleistungen pl, pruefer_pruefungsleistungen pp, pruefer p, vorlesungen v, kurse k, pruefungsleistungsobjekt po
								WHERE pl.pruefID = pp.pruefID
								AND pp.PID = p.PID
								AND pl.vid = v.vid
								AND v.kid = k.kid
								AND po.pruefID = pl.pruefID
								AND po.pruefstatus = 0
								AND p.PID = ".$_SESSION['user_ID']."
								GROUP BY pruefID";
			
				$result = mysql_query($query);
					
				echo '<table class="pure-table"><tr><th>Pruefungs ID</th><th>Pr&uuml;fungsBez</th><th>Vorlesung</th><th>Kurs</th><th>Anzeigen</th></tr>';
					
				while($row = mysql_fetch_assoc($result))
				{
					echo '	<tr>
							<td>'.$row['PruefID'].'</td>
							<td>'.$row['PruefBez'].'</td>
							<td>'.$row['vbez'].'</td>
							<td>'.$row['kbez'].'</td>
							<td><a href="content/user_bewertungen.php?art=1&pruefid='.$row['PruefID'].'" data-change="main"><i class="fa fa-eye"></i></a></td>
							</tr>';
				}
					
				echo "</table>";
			}
			
			
				
			//Studenten Anschicht:
			if($rights == 4)
			{
				echo "F&uuml;r folgende Pr&uuml;fungen ist eine Bewertung vorhanden:<br><br>";
					
				$query ="SELECT pl.PruefID, pl.PruefBez, v.vbez, k.kbez
								FROM pruefungsleistungen pl, pruefling pr, vorlesungen v, kurse k, pruefungsleistungsobjekt po
								WHERE pl.pruefID = po.pruefID
								AND po.PrID = pr.PrID
								AND pl.vid = v.vid
								AND v.kid = k.kid
								AND po.pruefstatus = 1
								AND pr.PrID = ".$_SESSION['user_ID'];
			
				$result = mysql_query($query);
					
				echo '<table class="pure-table"><tr><th>Pruefungs ID</th><th>Pr&uuml;fungsBez</th><th>Vorlesung</th><th>Kurs</th><th>Anzeigen</th></tr>';
					
				while($row = mysql_fetch_assoc($result))
				{
					echo '<tr><td>'.$row['PruefID'].'</td><td>'.$row['PruefBez'].'</td><td>'.$row['vbez'].'</td><td>'.$row['kbez'].'</td><td><a href="content/user_bewertungen.php?art=10&pruefid='.$row['PruefID'].'" data-change="main"><i class="fa fa-eye"></i></a></td></tr>';
				}
					
				echo "</table>";
			}
			
			//Leiter sehen alle Prüfungen
			if($rights == 0)
			{
				echo 'Alle vorhandenen P&uuml;fungen: <br><br>';
				$query = "SELECT PruefID, PName, VBez, Pruefbez, SchemaID FROM pruefer p, pruefungsleistungen pr, vorlesungen v WHERE pr.PID = p.PID AND v.VID = pr.VID";
				$result = mysql_query($query);
					
				echo '<table class="pure-table"><tr><th>Pruefungs ID</th><th>Ersteller</th><th>Vorlesung</th><th>Pr&uuml;fungsbez</th><th>Schema ID</th></tr>';
					
				while ($row = mysql_fetch_assoc($result)) {
					echo '<tr><td>'.$row['PruefID'].'</td><td>'.$row['PName'].'</td><td>'.$row['VBez'].'</td><td>'.$row['Pruefbez'].'</td><td>'.$row['SchemaID'].'</td></tr>';
				}
					
				echo "</table>";
			}
			
			break;
			
		case 1:
			//Pruefung bearbeiten
			
			echo("Hier wird bearbeitet! <br><br>");
			
			
			
			//@TODO: Hier fehlt: Pruefer hinzufuegen, Bewertungsschema ändern
			
			
			
			?>
							<form class="pure-form"  action="content/user_pruefungen.php?art=2">
									Pr&uuml;fungs ID: <br><input type="text" value="<?php echo $_GET['pruefid']?>" name="pruefid" readonly/><br>
									Neue Pr&uuml;fungsbezeichnung: <br> <input type="text" placeholder="Pr&uuml;fungsbezeichnung" name="npruefbez" /> <br><br>
									
									<button type="submit">&Auml;ndern</button>
							</form>
			<?php	
							echo '<br> Pr&uuml;fer <a href="content/user_pruefungen.php?art=9&pruefid='.$_GET['id'].'" data-change="main">zuordnen</a> <br><br>';
							
							echo('<a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>');
			
			break;
			
		case 2:
			//Pruefung update
			
			$pruefid = $_POST['pruefid'];
			$npruefbez = mysql_real_escape_string($_POST['npruefbez']);
			
			$query = 'UPDATE pruefungsleistungen SET PruefBez = "'.$npruefbez.'" WHERE PruefID = '.$pruefid;
			if(mysql_query($query))
			{
				echo "&Auml;nderung erfolgreich!<br>";
			}
			else
			{
				echo "Error - Try Again<br>";
			}
			echo('<a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>');

			
			break;

		case 3:
			//neue Pruefung hinzufügen mit VID bekannt
			
			echo("Hier wird eine neue Pr&uuml;fung hinzugef&uuml;gt! <br />");
			
			echo'<form class="pure-form"  action="content/user_pruefungen.php?art=4">';
			echo"Vorlesungs ID: <input type='text' value='{$_GET['vid']}' name='vid' readonly/><br />";						
			echo'Pr&uuml;fungsbezeichnung: <input type="text" placeholder="Pr&uuml;fungsbezeichnung" name="pruefbez" /> <br />';	
			echo'Toleranz: <input type="text" placeholder="Toleranz" name="toleranz" /> <br />';						
			echo'Bewertungschema: '	;													
			//drop down liste Bewertungsschema-ID und Bezeichnung
			$query='SELECT SchemaID, SchemaBez FROM pruefungsschema';
			$result=mysql_query($query);
			echo('<select name="SchemaID">');
			while($row=mysql_fetch_assoc($result))
			{
					echo('<option value='.$row['SchemaID'].'>'.$row['SchemaID'].' - '.$row['SchemaBez'].'</option>');
	
			}
			echo('</select><br />');
			
			/*
			// Hier wird das Schema nachgeladen  (Woher bekommt der die Schema ID?) Woher soll ich das wissen?
			echo('<a href="content/user_pruefungs_schemata.php?aid=11" data-change="inline">Schema anzeigen</a><br />'); //@TODO switch case ged�ns 
			// Hier kann ein neues Schema angelegt werden
			echo('<a href="content/user_pruefungs_schemata.php?new=1" data-change="inline">Neues Schema anlegen</a><br /><br />'); //@TODO switch case ged�ns
			
			//@TODO: Schema anzeigen
			*/						
			?>		
									<button type="submit">Pr&uuml;fung anlegen</button>
							</form>
			<?php		
							echo('<br /><br /><a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>');	
			
			break;
			
		case 4:
			//neue Pruefung insert
			
			$vid = htmlspecialchars($_POST['vid']);
			$pruefbez = mysql_real_escape_string($_POST['pruefbez']);
			$schemaID = mysql_real_escape_string($_POST['SchemaID']);
			$toleranz = htmlspecialchars($_POST['toleranz']);
			
			echo "$vid - $pruefbez - $schemaID; <br>";
			
			
			$query = 'INSERT INTO pruefungsleistungen VALUES (NULL, '.$_SESSION['user_ID'].', '.$vid.', "'.$pruefbez.'", '.$schemaID.', '.$toleranz.')';
			
			if(mysql_query($query))
			{
				echo "Eine neue Pr&uuml;fung wurde hinzugef&uuml;gt.<br><br>";
			
				//Da Auto_increment kann ich mir nicht anders helfen = (
					
				$row = mysql_fetch_array(mysql_query("SELECT MAX(PruefID) AS PruefID FROM pruefungsleistungen"));
				$pruefID = $row['PruefID'];
					
				//Hole relevante Studenten
					
				$query =   "SELECT pr.PrID
									FROM pruefungsleistungen pl, vorlesungen v, kurse k, pruefling pr
									WHERE pl.vid = v.vid
									AND v.kid = k.kid
									AND pr.kid = k.kid
									AND pl.pruefID = ".$pruefID;
					
				$result = mysql_query($query);
					
				while($row = mysql_fetch_assoc($result))
				{
					$query = "INSERT INTO pruefungsleistungsobjekt VALUES(NULL, ".$row['PrID'].", ".$pruefID.", NULL, 0)";
						
					if(mysql_query($query))
					{
						echo $row['PrID'].' erfolgreich <br>';
					}
					else
					{
						echo $row['PrID'].' nicht erfolgreich <br>';
					}
				}
			
				echo '<a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>';
			}
			else
			{
				echo 'Error - Try Again';
			}
			
			break;
			
		case 5:
			//Pruefung loeschen
			
			echo("Hier wird gel&ouml;scht! <br><br>");
			
			$query = "SELECT PruefBez FROM pruefungsleistungen WHERE PruefID = ".$_GET['pruefid'];
			$row = mysql_fetch_array(mysql_query($query));
			echo $row['PruefBez'].' wirklich loeschen? <a href="content/user_pruefungen.php?art=6&pruefid='.$_GET['pruefid'].'" data-change="main">Ja, l&ouml;schen</a><br><br>';
			
			echo('<a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>');
			
			break;
			
		case 6:
			//Pruefung loeschen delet
			
			$query = "DELETE FROM pruefungsleistungen WHERE PruefID = ".$_GET['pruefid'];
			if(mysql_query($query))
			{
				echo "Gel&ouml;scht!<br>";
			}
			else
			{
				echo "Fehler - nicht gel&ouml;scht!<br>";
			}
			echo('<a href="content/user_vorlesungen.php" data-change="main">OK, zur&uuml;ck</a>'); //@TODO warum zurueck zu vorlesungen?
			
			break;
		
		case 7:
			//neuen Pruefer zuordnen
			
			echo "Derzeitige Pr&uuml;fer:<br> <br>";
			
			$query = 'SELECT p.PName, p.PVName FROM pruefer_pruefungsleistungen pp, pruefer p, pruefungsleistungen pl WHERE pp.pruefID = pl.pruefID AND pp.PID = p.PID AND pp.pruefID = '.$_GET['pruefid'];
			$result = mysql_query($query);
			
			while ($row = mysql_fetch_assoc($result))
			{
				echo $row['PVName'].' '.$row['PName'].'<br>';
			}
			
			echo '<br><br> Weiteren Pr&uuml;fer hinzuf&uuml;gen:<br>';
			echo '<form class="pure-form"  action="content/user_pruefungen.php?art=8">';
			echo 'Pr&uuml;fungs ID: <input type="text" value="'.$_GET['pruefid'].'" name="pruefID" readonly/><br>';
			
			//drop down liste für Dozent
			$query = 'SELECT PID, PName FROM pruefer WHERE PArt IN (0,1,2)';
			$result = mysql_query($query);
			echo('Pr&uuml;fer: <select name="nprueid">');
			while($row = mysql_fetch_assoc($result))
			{
				echo('<option value='.$row['PID'].'>'.$row['PName'].'</option>');
			
			}
			echo('</select><br><br>');
			
			echo '<button type="submit">Zuordnen</button>';
			echo '</form>';
			echo '<a href="content/user_pruefungen.php?art=0" data-change="main">zur&uuml;ck</a>';
			
			break;
			
		case 8:
			//neuen Pruefer zuordnen insert
			
			$query = 'INSERT INTO pruefer_pruefungsleistungen VALUES('.$_POST['pruefID'].', '.$_POST['nprueid'].')';
			
			if(mysql_query($query))
			{
				echo 'Neuer Pr&uuml;fer zugeordnet.<br><br>';
					
				echo 'Weiteren Pr&uuml;fer <a href="content/user_pruefungen.php?art=7&pruefid='.$_POST['pruefID'].'" data-change="main">zuordnen</a>?<br>';
				echo '<a href="content/user_pruefungen.php?art=0" data-change="main">zur&uuml;ck</a>';
			}
			else
			{
				echo 'Error - Try Again';
			}
			
			break;
			
		case 9:
			//dummy
			
			echo "Hier gibts noch keine Funktion<br><br>";
			
			echo('<a href="content/user_pruefungen.php" data-change="main">zur&uuml;ck</a>');
			
			
			break;
			
		case 10:
			//neue Pruefung hinzufügen mit VID unbekannt
			
			echo("Hier wird eine neue Pr&uuml;fung hinzugef&uuml;gt! <br><br>");
			
			echo'<form class="pure-form"  action="content/user_pruefungen.php?art=4">';
			//Dropdown für Vorlesungen:
			echo'Vorlesung:';
			$query='SELECT VID, VBez FROM vorlesungen';
			$result=mysql_query($query);
			echo('<select name="vid">');
			while($row=mysql_fetch_assoc($result))
			{
					echo('<option value='.$row['VID'].'>'.$row['VBez'].'</option>');
	
			}
			echo('</select><br />');
							
			echo'Pr&uuml;fungsbezeichnung: <input type="text" placeholder="Pr&uuml;fungsbezeichnung" name="pruefbez" /> <br />';
			echo'Toleranz: <input type="text" placeholder="Toleranz" name="toleranz" /> <br />';							
			echo'Bewertungschema: '	;																				
			
			//drop down liste Bewertungsschema-ID und Bezeichnung
			$query='SELECT SchemaID, SchemaBez FROM pruefungsschema';
			$result=mysql_query($query);
			echo('<select name="SchemaID">');
			while($row=mysql_fetch_assoc($result))
			{
					echo('<option value='.$row['SchemaID'].'>'.$row['SchemaID'].' - '.$row['SchemaBez'].'</option>');
	
			}
			echo('</select><br />');
			
			/*
			// Hier wird das Schema nachgeladen  (Woher bekommt der die Schema ID?) Woher soll ich das wissen?
			echo('<a href="content/user_pruefungs_schemata.php?aid=11" data-change="inline">Schema anzeigen</a><br />'); //@TODO switch case ged�ns 
			// Hier kann ein neues Schema angelegt werden
			echo('<a href="content/user_pruefungs_schemata.php?new=1" data-change="inline">Neues Schema anlegen</a><br /><br />'); //@TODO switch case ged�ns
			
			//@TODO: Schema anzeigen*/
						
			?>		
									<button type="submit">Pr&uuml;fung anlegen</button>
							</form>
			<?php		
							echo('<br /><br /><a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>');	
			
			break;

		case 11:
			
			$pid = htmlspecialchars($_GET['pruefid']);
			
			echo "Hier die Details zur Prüfung:<br /><br />";
			
			echo "<a href='content/user_pruefungen.php?art=7&pruefid=$pid' data-change='main'>Pr&uuml;fer zuordnen</a><br />";
			echo "<a href='content/user_bewertungen.php?art=4&pruefid=$pid' data-change='main'>Feedback geben</a><br />";
			echo "<a href='content/user_bewertungen.php?art=8&pruefid=$pid' data-change='main'>Visible schalten</a><br />";
			
		break;	
	}
														

	

?>

















