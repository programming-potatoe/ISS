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
	 *  3=neue Pruefung hinzufügen mit VID bekannt/unbekannt;
	 *  4=neue Pruefung insert und Objekte erstellen;
	 *  5=Pruefung loeschen;
	 *  6=Pruefung loeschen delete;
	 *  7=neuen Pruefer zuordnen;
	 *  8=neuen Pruefer zuordnen insert;
	 *  9=dummy;
	 * 	OBSOLET 10=neue Prüfung hinzufügen mit VID unbekannt;
	 *  11=Prüfungsdetails ansehen;
	 *  21=Liste für Visible + Feedback + Pruefer zurodnen rights=(0|1|2)
	 *  22=Bewertungen abgeben
	 *  23=Ansicht Leiter alle Prüfungen
	 */
	switch ($_GET['art']){
		 
		case 0:
			//Startseite
			echo '<h2 class="headline">Bitte w&auml;hlen Sie einen Men&uuml;punkt aus</h2>';
			echo '<table class="pure-table" style="border: 0px solid white;">';
			//Visible + Feedback + Prüfer zuordnen
			if($rights == 0 | $rights == 1 | $rights == 2)
			{
				echo '<tr><td><a href="content/user_pruefungen.php?art=21" data-change="main" class="pure-button pure-button-primary">Pr&uuml;fungen bearbeiten</a></td></tr>';
			}
			
				
			//Bewertung abgeben
			if($rights == 0 | $rights == 1 | $rights == 3)
			{
				echo '<tr><td><a href="content/user_pruefungen.php?art=22" data-change="main" class="pure-button pure-button-primary">Bewertungen abgeben</a></td></tr>';
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
					
				echo '<table class="pure-table"><tr><th>Pr&uuml;fungsBez</th><th>Vorlesung</th><th>Kurs</th><th>Anzeigen</th></tr>';
					
				while($row = mysql_fetch_assoc($result))
				{
					echo '<tr><td>'.$row['PruefBez'].'</td><td>'.$row['vbez'].'</td><td>'.$row['kbez'].'</td><td><a href="content/user_bewertungen.php?art=10&pruefid='.$row['PruefID'].'" data-change="main"><i class="fa fa-eye"></i></a></td></tr>';
				}
					

			}
			
			//Leiter sehen alle Prüfungen
			if($rights == 0)
			{
				echo '<tr><td><a href="content/user_pruefungen.php?art=23" data-change="main" class="pure-button pure-button-primary">Alle Pr&uuml;fungen ansehen</a></td></tr>';
			}
			echo "</table>";
			break;
			
		case 1:
			//Pruefung bearbeiten
			
			echo '<h2 class="headline">Pr&uuml;fung bearbeiten</h2>';
			
			
			
			//@TODO: Hier fehlt: Pruefer hinzufuegen, Bewertungsschema ändern
			
			
			
			?>
							<form class="pure-form"  action="content/user_pruefungen.php?art=2">
									Pr&uuml;fungs ID: <br><input type="text" value="<?php echo $_GET['pruefid']?>" name="pruefid" readonly/><br>
									Neue Pr&uuml;fungsbezeichnung: <br> <input type="text" placeholder="Pr&uuml;fungsbezeichnung" name="npruefbez" /> <br><br>
									
									<button type="submit">&Auml;ndern</button>
							</form>
			<?php	
							echo '<br> Pr&uuml;fer <a href="content/user_pruefungen.php?art=9&pruefid='.$_GET['id'].'" data-change="main">zuordnen</a> <br><br>';
							
							//echo('<a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>');
			
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
			//neue Pruefung hinzufügen
			
			echo('<h2 class="headline">Neue Pr&uuml;fung anlegen</h2>');
			
			echo'<form class="pure-form" action="content/user_pruefungen.php?art=4">';
			
			echo '<table class="formtable"><tr>';
			
			if(isset($_GET['vid']))
			{
				//Vorlesungs ID bekannt
				$query = 'select VBez from vorlesungen where vid='.$_GET['vid'];
				$result = mysql_fetch_array(mysql_query($query));
				echo"<td>Vorlesungs Bezeichnung:</td> <td><input type='text' value='{$result['VBez']}' readonly/></td><td></td><input type='hidden' name='vid' value='{$_GET['vid']}'";	
			}
			else
			{
				//Vorlesung unbekannt
				//Dropdown für Vorlesungen:
				echo'<td>Vorlesung:</td>';
				$query='SELECT VID, VBez FROM vorlesungen';
				$result=mysql_query($query);
				echo('<td><select name="vid">');
				while($row=mysql_fetch_assoc($result))
				{
					echo('<option value='.$row['VID'].'>'.$row['VBez'].'</option>');
	
				}
				echo('</select></td><td></td>');
			}
						
			echo'</tr><tr><td>Pr&uuml;fungsbezeichnung:</td> <td><input type="text" placeholder="Pr&uuml;fungsbezeichnung" name="pruefbez" /> </td><td></td>';	
			echo'</tr><tr><td>Toleranz:</td> <td><input type="text" placeholder="Toleranz" name="toleranz" /> </td><td></td>';						
			echo'</tr><tr><td>Bewertungschema:</td>';													
			$nummer = 0;
			//drop down liste Bewertungsschema-ID und Bezeichnung
			$query='SELECT SchemaID, SchemaBez FROM pruefungsschema';
			$result=mysql_query($query);
			echo('<td><select name="SchemaID" id="vorlagenaenderung">');
			while($row=mysql_fetch_assoc($result))
			{
					if($nummer==0){
						$nummer = $row['SchemaID'];
					}
					echo('<option value='.$row['SchemaID'].'>'.$row['SchemaBez'].'</option>');
			}
			echo('</select></td></<tr>');
			echo('<tr><td colspan="2">');
			echo('<div id="vorlagen_vorschau">');
			show_vorlage($nummer);			
			echo('</div></td></tr>');
			
			// Hier wird das Schema nachgeladen  (Woher bekommt der die Schema ID?) Woher soll ich das wissen?
			 
			// Hier kann ein neues Schema angelegt werden
			//echo('<a href="content/user_pruefungs_schemata.php?new=1" data-change="inline">Neues Schema anlegen</a><br /><br />'); //@TODO switch case ged�ns
			
			//@TODO: Schema anzeigen
									
			?>		
									<tr>
										<td>&nbsp;</td>
										<td><button type="submit" class="pure-button pure-button-primary">Pr&uuml;fung anlegen</button></td><td></td>
									</tr>
								</table>
							</form>
			<?php		
							//echo('<br /><br /><a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>');	
			
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
			
				//echo '<a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>';
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
			
			//drop down liste für Prüfer
			$query = 'SELECT PID, PName FROM pruefer WHERE PArt IN (0,1,3) AND PID NOT IN (SELECT p.PID
						FROM pruefer_pruefungsleistungen pp, pruefer p, pruefungsleistungen pl 
						WHERE pp.pruefID = pl.pruefID 
						AND pp.PID = p.PID 
						AND pp.pruefID = '.$_GET['pruefid'].')';
						
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
			
		/*case 10:
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
			
			//@TODO: Schema anzeigen
						
			?>		
									<button type="submit">Pr&uuml;fung anlegen</button>
							</form>
			<?php		
							echo('<br /><br /><a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>');	
			
			break;*/

		case 11:
			
			$pruefid = htmlspecialchars($_GET['pruefid']);
			
			$query = "SELECT p.PruefBez FROM pruefungsleistungen p WHERE PruefID = ".$pruefid;
			
			$row = mysql_fetch_array(mysql_query($query));
			
			echo '<h2 class="headline">Bearbeiten der Pr&uuml;fung</h2>';
			
			
			echo '<table class="pure-table" style="border: 0px solid white;">';
			echo "<tr><td><a href='content/user_pruefungen.php?art=7&pruefid=$pruefid' data-change='main' class='pure-button'>Pr&uuml;fer zuordnen</a></td></tr>";
			echo "<tr><td><a href='content/user_bewertungen.php?art=4&pruefid=$pruefid' data-change='main' class='pure-button'>Feedback geben</a></td></tr>";
			echo "<tr><td><a href='content/user_bewertungen.php?art=8&pruefid=$pruefid' data-change='main' class='pure-button'>Visible schalten</a></td></tr>";
			echo '</table>';
		break;	
	
		case 21:
			//21=Liste für Visible + Feedback + Pruefer zurodnen rights=(0|1|2)
			
			check_berechtigung('j', 'j', 'j', 'n', 'n');
			
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
					
				echo '<table class="pure-table"><tr><th>Pr&uuml;fungsBez</th><th>Vorlesung</th><th>Kurs</th><th>Bearbeiten</th></tr>';
					
				while($row = mysql_fetch_assoc($result))
				{
					echo '<tr><td>'.$row['PruefBez'].'</td><td>'.$row['vbez'].'</td><td>'.$row['kbez'].'</td><td>
					<a href="content/user_pruefungen.php?art=11&pruefid='.$row['PruefID'].'" data-change="main"><i class="fa fa-pencil"></i></a></td></tr>';
				}
					
			echo "</table>";
			
			break;
			
		case 22:
			//22=Bewertungen abgeben
			
			check_berechtigung('j', 'j', 'n', 'j', 'n');
			
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
					
				echo '<table class="pure-table"><tr><th>Pr&uuml;fungsBez</th><th>Vorlesung</th><th>Kurs</th><th>Bewerten</th></tr>';
					
				while($row = mysql_fetch_assoc($result))
				{
					echo '	<tr>
							<td>'.$row['PruefBez'].'</td>
							<td>'.$row['vbez'].'</td>
							<td>'.$row['kbez'].'</td>
							<td><a href="content/user_bewertungen.php?art=1&pruefid='.$row['PruefID'].'" data-change="main"><i class="fa fa-edit"></i></a></td>
							</tr>';
				}
					
				echo "</table>";
			
		break;
	
		case 23:
		//23=Ansicht Leiter alle Prüfungen
		
		check_berechtigung('j', 'n', 'n', 'n', 'n');
			
		echo 'Alle vorhandenen P&uuml;fungen: <br><br>';
		$query = "SELECT PruefID, PName, VBez, Pruefbez, SchemaID FROM pruefer p, pruefungsleistungen pr, vorlesungen v WHERE pr.PID = p.PID AND v.VID = pr.VID";
		$result = mysql_query($query);
			
		echo '<table class="pure-table"><tr><th>Ersteller</th><th>Vorlesung</th><th>Pr&uuml;fungsbez</th><th>Schema ID</th></tr>';
			
		while ($row = mysql_fetch_assoc($result)) {
			echo '<tr><td>'.$row['PName'].'</td><td>'.$row['VBez'].'</td><td>'.$row['Pruefbez'].'</td><td>'.$row['SchemaID'].'</td></tr>';
		}
					
		echo "</table>";	
			
		echo "<a href='content/user_pruefungen.php?art=0' data-change='main'>Zur&uuml;ck</a><br />";
		break;
												

	}

?>

















