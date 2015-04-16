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
		
		//0=Startseite; 1=Bewertung abgeben; 2=Bewertung eintragen; 3=Bewertung speichern; 4=Feedback eingeben; 5=Feedback speichern;6=Feedback+visible; 7=wirklich visible schalten?; 8=visible update; 9=Ansicht fuer Prueflinge
		switch ($_GET['art']){
				
			case 0:
				//Startseite
				check_berechtigung('j', 'j', 'j', 'j', 'n');
				
				echo"Hier k&ouml;nnen Bewertungen eingesehen und bearbeitet werden <br> <br>";
				
				break;
				
			case 1:
				//Bewertung abgeben
				check_berechtigung('j', 'j', 'j', 'j', 'n');
				
				echo 'Hier kann eine Bewertung abgeben werden.<br><br>';
					
				echo '<form action="content/user_bewertungen.php?art=2">';
				echo 'Pr&uuml;fungs ID: <br> <input type="text" value="'.$_GET['id'].'" name="pruefID" readonly/><br>';
				
				//	drop down liste für Student
				$query ="SELECT pr.PrID, pr.PrName
								FROM pruefling pr, pruefungsleistungsobjekt po
								WHERE pr.PrID = po.PrID
								AND po.pruefstatus = 0
								AND po.PruefID = ".$_GET['id'];
				
				$result = mysql_query($query);
				echo('Student: <br> <select name="prid">');
				while($row = mysql_fetch_assoc($result))
				{
					echo('<option value='.$row['PrID'].'>'.$row['PrName'].'</option>');
						
				}
				echo('</select><br><br>');
					
				echo '<button type="submit">Erstellen</button>';
				echo '</form>';
				echo '<a href="content/user_pruefungen.php" data-change="main">zur&uuml;ck</a>';
				
				break;
				
			case 2:
				//Bewertung eintragen
				check_berechtigung('j', 'j', 'j', 'j', 'n');
				
				$pruefID = mysql_real_escape_string($_POST['pruefID']);
				$prID = mysql_real_escape_string($_POST['prid']);
					
				$row = mysql_fetch_array(mysql_query("SELECT PruefObjID FROM pruefungsleistungsobjekt WHERE PruefID = ".$pruefID." AND PrID = ".$prID));
				$pruefObjID = $row['PruefObjID'];
					
				echo "Pr&uuml;fling mit ID ".$prID." wird hier bewertet<br><br>";
					
				//	Array mit bissherigen Bewertungen erstellen
					
				$query ="SELECT b.BPunkte, a.ANr, b.bbewertungsstufe FROM bewertungen b, aufgaben a
								WHERE b.PruefObjID = ".$pruefObjID."
								AND b.AID = a.AID
								ORDER BY a.ANr ASC , b.bbewertungsstufe ASC";
				
				$result = mysql_query($query);
					
				while($row = mysql_fetch_assoc($result))
				{
					$barray[$row['ANr']][$row['bbewertungsstufe']] = $row['BPunkte'];
				}
					
					
				$query ="SELECT ps.SchemaID, ps.PruefGenauigkeit, a.ANr, a.AMaxPunkte, a.AID
								FROM pruefungsleistungen pl, pruefungsleistungsobjekt po, pruefungsschema ps, aufgaben a
								WHERE ps.SchemaID = a.SchemaID
								AND ps.SchemaID = pl.SchemaID
								AND po.PruefID = pl.PruefID
								AND po.PruefObjID = ".$pruefObjID."
								ORDER BY a.ANr";
				
				$result = mysql_query($query);
				$row = mysql_fetch_array($result);
					
					
				echo '<form action="content/user_bewertungen.php?art=3">';
				echo 'Schema ID: <br> <input type="text" value="'.$row['SchemaID'].'" name="bewschemaID" readonly/><br>';
				echo 'Pr&uuml;fungs ID: <br> <input type="text" value="'.$pruefID.'" name="bewpruefID" readonly/><br>';
				echo 'Pr&uuml;flings ID: <br> <input type="text" value="'.$prID.'" name="bewprID" readonly/><br><br>';
					
				echo "<table><tr><td>Aufgaben NR</td><td>MaxPunkte</td>";
				for($i=0; $i < $row['PruefGenauigkeit']; $i++)
				{
				echo "<td>".$i."</td>";
				}
					
						echo "</tr>";
					
				do{
				echo '<tr><td>'.$row['ANr'].'</td><td>'.$row['AMaxPunkte'].'</td>';
				for($i=0; $i < $row['PruefGenauigkeit']; $i++)
				{
				echo '<td><input type="text" name="'.$row['AID'].'_'.$i.'" value="';
						
							if(isset($barray[$row['ANr']][$i]))
											{
													echo $barray[$row['ANr']][$i];
				}
														
													echo '" size="5" maxlength="5"></input></td>';
													}
														
													echo "</tr>";
					} while ($row = mysql_fetch_assoc($result));
														
													echo "</table><br><br>";
														
													echo '<button type="submit">Erstellen</button>';
													echo '</form>';
					echo '<a href="content/user_pruefungen.php" data-change="main">abbrechen</a>';
				
				break;
		
			case 3:
				//Bewertung speichern
				check_berechtigung('j', 'j', 'j', 'j', 'n');
				
				$schemaID = mysql_real_escape_string($_POST['bewschemaID']);
				$pruefID = mysql_real_escape_string($_POST['bewpruefID']);
				$prID = mysql_real_escape_string($_POST['bewprID']);
					
				$row = mysql_fetch_array(mysql_query("SELECT PruefObjID FROM pruefungsleistungsobjekt WHERE PruefID = ".$pruefID." AND PrID = ".$prID));
				$pruefObjID = $row['PruefObjID'];
					
				$row = mysql_fetch_array(mysql_query("SELECT PruefGenauigkeit FROM pruefungsschema WHERE SchemaID = ".$schemaID));
				$pruefGenau = $row['PruefGenauigkeit'];
					
				$AIDresult = mysql_query("SELECT a.AID FROM aufgaben a, pruefungsschema ps WHERE ps.SchemaID = ".$schemaID." AND a.SchemaID = ps.SchemaID");
					
					
					
				//@TODO: Validierung Punktezahl fehlt!
				//@TODO: Bewertungen werden mehrfach angelegt.
				
					
				while($row = mysql_fetch_assoc($AIDresult))
				{
					for($i = 0; $i < $pruefGenau; $i++)
					{
					if($_POST[$row['AID'].'_'.$i] > 0)
					{
						$query = 'INSERT INTO bewertungen VALUES(NULL, '.$row['AID'].', '.$pruefObjID.', '.$_SESSION['user_ID'].', '.$_POST[$row['AID'].'_'.$i].', '.$i.')';
				
						if(mysql_query($query))
					{
					echo $row['AID'].'_'.$i.'  '.$_POST[$row['AID'].'_'.$i].'<br>';
					}
					}
					}
					}
				
				
				break;
				
			case 4:
				//Feedback eingeben
				check_berechtigung('j', 'j', 'j', 'j', 'n');
				
				//@TODO: Feedbacks werden gnadenlos überschrieben.. muss so
				
					
				echo '<form action="content/user_bewertungen.php?art=5">';
				echo 'Pr&uuml;fungs ID: <br> <input type="text" value="'.$_POST['pruefID'].'" name="pruefID" readonly/><br>';
				echo 'Pr&uuml;flings ID: <br> <input type="text" value="'.$_POST['prid'].'" name="prID" readonly/><br>';
				echo 'Feedback: <br> <textarea placeholder="Feedback" name="dozfeedback"/><br> Maximal 200 Zeichen <br><br>';
				echo '<button type="submit">Absenden</button><br><br>';
				echo '</form>';
				
				break;
				
			case 5:
				//Feedback speichern
				check_berechtigung('j', 'j', 'j', 'j', 'n');
				
				$feedback = mysql_real_escape_string($_POST['dozfeedback']);
				$query = "UPDATE pruefungsleistungsobjekt po, pruefungsleistungen pl SET po.PruefObjKommentar = '".$feedback."' WHERE pl.pruefID = po.pruefID AND po.prID = ".$_POST['prid']." AND po.pruefID = ".$_POST['adozpruefID'];
					
				if(mysql_query($query))
				{
					echo "Success";
						
					echo '<br><a href="content/user_pruefungen.php" data-change="main">zur&uuml;ck</a>';
				}
				else
				{
					echo "Failed - Just try again";
					echo '<br><a href="content/user_pruefungen.php" data-change="main">zur&uuml;ck</a>';
				}
				
				break;
				
			case 6:
				//Feedback+visible
				check_berechtigung('j', 'j', 'j', 'j', 'n');
				
				echo "Hier wird Feedback eingetragen und visible geschaltet.";
				
				echo '<form action="content/user_bewertungen.php">';
				echo 'Pr&uuml;fungs ID: <br> <input type="text" value="'.$_GET['pruef'].'" name="pruefID" readonly/><br>';
				
				//drop down liste für Student
				$query ='SELECT pr.PrID, pr.PrName
								FROM pruefling pr, pruefungsleistungsobjekt po
								WHERE pr.PrID = po.PrID
								AND po.pruefstatus = 0
								AND po.PruefID = '.$_GET['pruefid'];
				
				$result = mysql_query($query);
				echo('Student: <br> <select name="prid">');
				while($row = mysql_fetch_assoc($result))
				{
					echo('<option value='.$row['PrID'].'>'.$row['PrName'].'</option>');
						
				}
				echo('</select><br><br>');
					
				echo '<button type="submit">Feedback geben</button><br><br>';
				echo '</form>';
					
				echo '<form action="content/user_bewertungen.php?art=7">';
				echo 'Pr&uuml;fungs ID: <br> <input type="text" value="'.$_GET['pruefid'].'" name="pruefid" readonly/><br>';
				echo '<button type="submit">Visible schlten</button>';
				echo '</form>';
					
				echo '<br><a href="content/user_pruefungen.php" data-change="main">zur&uuml;ck</a>';
				
				break;
				
			case 7:
				//wirklich visible schalten?
				check_berechtigung('j', 'j', 'j', 'j', 'n');
				
				$pruefid = mysql_real_escape_string($_POST['pruefid']);
					
				$query = "SELECT PruefBez FROM pruefungsleistungen WHERE PruefID = ".$pruefid;
					
				$row = mysql_fetch_array(mysql_query($query));
					
				echo "Sicher, dass Sie die Pr&uuml;fung ".$row['PruefBez']." visible schalten wollen? <br><br> Diese Aktion kann <b>nicht</b> r&uuml;ckg&auml;ngig gemacht werden! <br><br>";
					
				echo '<br><a href="content/user_bewertungen.php?art=8&pruefid='.$pruefid.'" data-change="main">JA</a><br>';
				echo '<br><a href="content/user_pruefungen.php" data-change="main">NEIN, Abbrechen</a><br>';
				
				break;
				
			case 8:
				//visible update
				check_berechtigung('j', 'j', 'j', 'j', 'n');
				
				$pruefid = mysql_real_escape_string($_GET['pruefid']);
					
				$query = "SELECT po.PruefObjID FROM pruefungsleistungsobjekt po, pruefungsleistungen pl WHERE pl.pruefID = po.pruefID AND pl.pruefID = ".$pruefid;
				$result = mysql_query($query);
					
				while($row = mysql_fetch_assoc($result))
				{
					$query = "UPDATE pruefungsleistungsobjekt SET pruefstatus = 1 WHERE PruefObjID = ".$row['PruefObjID'];
						
					if(mysql_query($query))
					{
							
					}
					else
					{
						echo "An Error occured - Try Again! PruefObjID = ".$row['PruefObjID'];
					}
				}
					
					
				echo '<br><a href="content/user_pruefungen.php" data-change="main">OK, zur&uuml;ck</a><br>';
				
				break;
				
			case 9:
				//Ansicht fuer Prueflinge
				check_berechtigung('j', 'j', 'j', 'j', 'j');
				
				$pruefID = mysql_real_escape_string($_GET['pruefid']);
				$prID = $_SESSION['user_ID'];
				
				$row = mysql_fetch_array(mysql_query("SELECT PruefObjID FROM pruefungsleistungsobjekt WHERE PruefID = ".$pruefID." AND PrID = ".$prID));
				$pruefObjID = $row['PruefObjID'];
				
				//Array mit Punkten erstellen
				
				$query ="SELECT b.BPunkte, a.ANr, b.bbewertungsstufe FROM bewertungen b, aufgaben a
							WHERE b.PruefObjID = ".$pruefObjID."
							AND b.AID = a.AID
							ORDER BY a.ANr ASC , b.bbewertungsstufe ASC";
					
				$result = mysql_query($query);
				
				while($row = mysql_fetch_assoc($result))
				{
					$barray[$row['ANr']][$row['bbewertungsstufe']] = $row['BPunkte'];
					if(isset($sarray[$row['bbewertungsstufe']]))
					{
						$sarray[$row['bbewertungsstufe']] = $sarray[$row['bbewertungsstufe']] + $row['BPunkte'];
					}
					else
					{
						$sarray[$row['bbewertungsstufe']] = $row['BPunkte'];
					}
				}
					
				
				$query ="SELECT ps.SchemaID, ps.PruefGenauigkeit, a.ANr, a.AMaxPunkte, a.AID
							FROM pruefungsleistungen pl, pruefungsleistungsobjekt po, pruefungsschema ps, aufgaben a
							WHERE ps.SchemaID = a.SchemaID
							AND ps.SchemaID = pl.SchemaID
							AND po.PruefID = pl.PruefID
							AND po.PruefObjID = ".$pruefObjID."
							ORDER BY a.ANr";
					
				$result = mysql_query($query);
				$row = mysql_fetch_array($result);
				$pruefgenau =	$row['PruefGenauigkeit'];
				$summe = 0;
				
				
				//Ausgabe der Tabelle:
				
				echo "<br><br><br><table><tr><td>Aufgaben NR</td><td>MaxPunkte</td>";
				for($i=0; $i < $pruefgenau; $i++)
				{
				echo "<td>".$i."</td>";
				}
				
						echo "</tr>";
				
				do{
				echo '<tr><td>'.$row['ANr'].'</td><td>'.$row['AMaxPunkte'].'</td>';
					
				$summe = $summe + $row['AMaxPunkte']; //für Berechnung
					
				for($i=0; $i < $pruefgenau; $i++)
				{
				echo '<td>';
				
				if(isset($barray[$row['ANr']][$i]))
				{
					echo $barray[$row['ANr']][$i];
				}
				
				echo '</td>';
				}
				
						echo "</tr>";
				} while ($row = mysql_fetch_assoc($result));
				
				
				echo '<tr><td>Summe:</td><td>'.$summe.'</td>';
				for($i=0; $i < $pruefgenau; $i++)
				{
				echo '<td>';
				
				if(isset($sarray[$i]))
				{
				echo $sarray[$i];
						}
				
						echo '</td>';
				}
								echo "</tr>";
				
								echo '<tr><td>Prozent:</td><td></td>';
										$prozentsatze = array();
										for($i=0; $i < $pruefgenau; $i++)
										{
										echo '<td>';
				
										if(isset($sarray[$i]))
										{
												$prozentsatze[$i] = ($sarray[$i]*100)/$summe);
												echo ($prozentsatze[$i].'%';
				}
				
				echo '</td>';
					}
				echo "</tr>";
				
								echo "</table><br><br>";
				//Berechnung
				$score = 0;
				//von links aufsummiert:
				include(gamma.php);
				$leftarray = array();
				$rightarray = array();
				for($i = 0; $i < $pruefgenau; $i++)
				{
					$leftarray[] = $prozentsatze[$i];
					$rightarray[] = $prozentsatze[$pruefgenau-$i-1];
				}
				$r_left = 0.0;
				$r_right = 0.0;
				for($i = 0; $i < $pruefgenau; $i++)
				{
								if($leftarray[$i] > 0)
								{
									$r_left += exp(log_gamma($i+$leftarray[$i])-log_gamma($i+1)-log_gamma($leftarray[$i]));
								}
								if($rightarray[$i] > 0)
								{
									$r_right += exp(log_gamma($i+$rightarray[$i])-log_gamma($i+1)-log_gamma($rightarray[$i]));
								}
				}
				$r_left = 1- $r_left/($pruefgenau-5);
				$r_right = $r_right/($pruefgenau-5);
				$score = (1-$tolerance) * min($r_left,$r_right) + $tolerance * max($r_left,$r_right);
				
				//Endgültige Score:
				echo "Score:   ".$score."% <br><br>";
				
				
				
				
				
				//@TODO: FEEDBACK beantworten!
				
				
				
				
				
				
				$query = "SELECT PruefObjKommentar FROM pruefungsleistungsobjekt WHERE pruefObjID = ".$pruefObjID;
				
				$row = mysql_fetch_array(mysql_query($query));
				
				echo "Feedback des Dozenten:<br><br>";
				
				echo "<i>".$row['PruefObjKommentar']."</i><br><br>";
				
				echo '<a href="content/user_pruefungen.php" data-change="main">zur&uuml;ck</a>';
				
				
				break;
				
			case 999:
				echo 'Was willst DU denn hier?!?';
				break;
		}
		


		
?>













