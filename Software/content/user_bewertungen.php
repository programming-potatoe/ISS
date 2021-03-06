<?php
	if(!defined('__ROOT__'))
   {
          define('__ROOT__', dirname(dirname(__FILE__)));
   }
   require_once(__ROOT__ ."/utils/functions.php");
   require(__ROOT__ ."/utils/gamma.php");
	
	check_berechtigung('j', 'j', 'j', 'j', 'j');
	
		
		if(!isset($_GET['art'])){			
			$_GET['art'] = 0;	
		}
		
		/*  0=Startseite;
		 *  1=Bewertung abgeben;
		 *  2=Bewertung eintragen;
		 *  3=Bewertung speichern;
		 *  4=Abfrage Feedback für welchen Student;
		 *  5=Feedback erstellen;
		 *  6=Feedback speichern;
		 *  OBSOLET 7=Feedback+visible;
		 *  8=wirklich visible schalten?;
		 *  9=visible update;
		 *  10=Ansicht fuer Prueflinge
		 *  999=dummy;
		 */
		 
switch ($_GET['art']){
				
			case 0:
				//Startseite
				check_berechtigung('j', 'j', 'j', 'j', 'n');
				
				echo"Hier k&ouml;nnen Bewertungen eingesehen und bearbeitet werden <br> <br>";
				
				break;
				
			case 1:
				//Bewertung abgeben
				check_berechtigung('j', 'j', 'j', 'j', 'n');
				
				$pid = htmlspecialchars($_GET['pruefid']);
				
				//Pruefungsname holen
				$query ='SELECT PruefBez FROM pruefungsleistungen WHERE PruefID = '.$pid;
				$result = mysql_fetch_array(mysql_query($query));
				
				
				echo '<h2 class="headline">Hier kann eine Bewertung f&uuml;r "'.$result['PruefBez'].'" abgegeben werden</h2>';
					
				echo '<form class="pure-form" action="content/user_bewertungen.php?art=2">';
				echo '<table class="formtable">';
				echo '<input type="hidden" value="'.$pid.'" name="pruefID">';
				
				//	drop down liste für Student
				$query ="SELECT pr.PrID, pr.PrVName, pr.PrName
								FROM pruefling pr, pruefungsleistungsobjekt po
								WHERE pr.PrID = po.PrID
								AND po.pruefstatus = 0
								AND po.PruefID = ".$pid;
				
				$result = mysql_query($query);
				echo('<tr><td>Student: </td><td><select name="prid">');
				while($row = mysql_fetch_assoc($result))
				{
					echo("<option value='{$row['PrID']}'>{$row['PrVName']} {$row['PrName']}</option>");
						
				}
				echo('</select></td></tr>');
					
				echo '<tr><td>&nbsp;</td><td><button type="submit" class="pure-button pure-button-primary">Erstellen</button>&nbsp;&nbsp;';
				echo '<a href="content/user_pruefungen.php" data-change="main" class="pure-button">Abbrechen</a></td>';
				echo '</tr></table></form>';
				
				
				break;
				
			case 2:
				//Bewertung eintragen
				check_berechtigung('j', 'j', 'j', 'j', 'n');
				
				$pruefID = htmlspecialchars($_POST['pruefID']);
				$prID = htmlspecialchars($_POST['prid']);
					
				$row = mysql_fetch_array(mysql_query("SELECT PruefObjID FROM pruefungsleistungsobjekt WHERE PruefID = $pruefID AND PrID = $prID"));
				$pruefObjID = $row['PruefObjID'];
					
				echo '<h2 class="headline">Bewertung abgeben</h2>';
				//echo "Pr&uuml;fling mit ID $prID wird hier bewertet <br /><br />";
					
				//	Array mit bissherigen Bewertungen erstellen
					
				$query ="		SELECT b.BPunkte, a.ANr, b.bbewertungsstufe 
								FROM bewertungen b, aufgaben a
								WHERE b.PruefObjID = $pruefObjID
								AND b.AID = a.AID
								AND b.PID = {$_SESSION['user_ID']}
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
					
				$query = 'select PruefBez from pruefungsleistungen where PruefID = '.$pruefID;
				$pruefbez=mysql_fetch_array(mysql_query($query));
				$query = 'select PrName, PrVName from pruefling where PrID = '.$prID;
				$pruefling=mysql_fetch_array(mysql_query($query));
				
					
				echo '<form class="pure-form" action="content/user_bewertungen.php?art=3">';
				echo '<table class="formtable">';
				echo '<input type="hidden" value="'.$row['SchemaID'].'" name="bewschemaID"/>';
				echo '<input type="hidden" value="'.$pruefID.'" name="bewpruefID"/>';
				echo '<input type="hidden" value="'.$prID.'" name="bewprID"/>';
				
				echo '<tr><td>Pr&uuml;fung:</td><td> <input type="text" value="'.$pruefbez['PruefBez'].'" readonly/></td></tr>';
				echo '<tr><td>Pr&uuml;fling:</td><td> <input type="text" value="'.$pruefling['PrName'].', '.$pruefling['PrVName'].'" readonly/></td></tr>';
					
				echo '<tr colspan="2"><table class="pure-table"><tr><th>Aufgaben NR</th><th>MaxPunkte</th>';
				for($i=0; $i < $row['PruefGenauigkeit']; $i++)
				{
					echo "<th>".$i."</th>";
				}
					
				echo "</tr>";
					
				do{
					echo '<tr><td>'.$row['ANr'].'</td><td>'.$row['AMaxPunkte'].'</td>';
					for($i=0; $i < $row['PruefGenauigkeit']; $i++)
					{
						echo '<td><input type="number" name="'.$row['AID'].'_'.$i.'" value="';
						if(isset($barray[$row['ANr']][$i]))
						{
							echo $barray[$row['ANr']][$i];
						}							
						echo '" size="5" maxlength="5"></input></td>';
					}							
					echo "</tr>";
					
					} while ($row = mysql_fetch_assoc($result));
														
					echo "</table></td></tr>";
					echo '<tr><td>&nbsp;</td><td><button type="submit" class="pure-button pure-button-primary">Erstellen</button>';
					echo '&nbsp;&nbsp;<a href="content/user_pruefungen.php" class="pure-button" data-change="main">Abbrechen</a></td></tr></table>';
					echo '</form>';
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
					
				$AIDresult = mysql_query("SELECT a.AID, a.AMaxPunkte FROM aufgaben a, pruefungsschema ps WHERE ps.SchemaID = ".$schemaID." AND a.SchemaID = ps.SchemaID");
							
				$status = 0;	
					
				while($row = mysql_fetch_assoc($AIDresult))
				{
					$sum = 0;	
					
					//Eingabevalidierung
					for($i = 0; $i < $pruefGenau; $i++)
					{
						if($_POST[$row['AID'].'_'.$i] >= 0)
						{
							$sum += $_POST[$row['AID'].'_'.$i];
						}
					}
					
					if($sum != $row['AMaxPunkte'])
					{
						$status = 1;
					}
					else 
					{
						for($i = 0; $i < $pruefGenau; $i++)
						{					
							if($_POST[$row['AID'].'_'.$i] >= 0)
							{
								//Eintrag bereits vorhanden?
								
								$query = "	SELECT BID FROM bewertungen
											WHERE AID = {$row['AID']} 
											AND PruefObjID = $pruefObjID 
											AND BBewertungsstufe = $i 
											AND PID = {$_SESSION['user_ID']}
											";
								
								$BIDresult = mysql_query($query);
								$BIDrow = mysql_fetch_array($BIDresult);
								
								if($BIDrow != "")
								{//Eintrag vorhanden, update:
										
									if($_POST[$row['AID'].'_'.$i] == "")
									{
										$_POST[$row['AID'].'_'.$i] = 0;
									}
									
									
									$query = "	UPDATE bewertungen 
												SET BPunkte = ".$_POST[$row['AID'].'_'.$i]." 
												WHERE BID = {$BIDrow['BID']} ";
										
									if(mysql_query($query))
									{
										//Erfolgsmeldung
									}
									else
									{
										$status = 2;
									}
								}
								else
								{//Eintrag nicht vorhanden, neu anlegen:
									if($_POST[$row['AID'].'_'.$i] == "")
									{
											//Do nothing -> Keine Bewertung vorhanden.
									}
									else 
									{									
										$query = 'INSERT INTO bewertungen VALUES(NULL, '.$row['AID'].', '.$pruefObjID.', '.$_SESSION['user_ID'].', '.$_POST[$row['AID'].'_'.$i].', '.$i.')';
					
										if(mysql_query($query))
										{
											//Erfolgsmeldung
										}else{
											$status = 2;
										}
									}
								}
							}
						}
					}
				}
				
				if($status == 0){
					create_dialog('Erfolgreich gespeichert!', 'content/user_pruefungen.php');
				}
				else if($status == 1)
				{
					create_dialog('Maximale Punktzahl falsch!', 'content/user_bewertungen.php?art=1&pruefid='.$pruefID);
				}
				else
				{
					create_dialog('Beim Speichern ist ein Fehler unterlaufen!', 'content/user_pruefungen.php');
				}
				
				break;
				
			case 4:
				//Feedback eingeben für welchen Student
				check_berechtigung('j', 'j', 'j', 'j', 'n');
				
				//@TODO: Feedbacks werden gnadenlos überschrieben.. muss so sein.
				
				$pid = htmlspecialchars($_GET['pruefid']);
				echo('<h2 class="headline">Feedback eintragen</h2>');
				echo '<form class="pure-form" action="content/user_bewertungen.php?art=5">';
				echo '<table class="formtable">';
				$query = "SELECT PruefBez FROM pruefungsleistungen WHERE PruefID = ".$_GET['pruefid'];
				$row = mysql_fetch_array(mysql_query($query));
				echo '<tr><td>Pr&uuml;fung:</td><td><input type="text" value="'.$row['PruefBez'].'" readonly> <input type="hidden" value="'.$pid.'" name="pruefid" /></td></tr>';
				//drop down liste für Student
				$query ='SELECT pr.PrID, pr.PrName, pr.PrVName
								FROM pruefling pr, pruefungsleistungsobjekt po
								WHERE pr.PrID = po.PrID
								AND po.pruefstatus = 0
								AND po.PruefID = '.$pid;
				
				$result = mysql_query($query);
				echo('<tr><td>Student:</td><td> <select name="prid">');
				while($row = mysql_fetch_assoc($result))
				{
					echo("<option value='{$row['PrID']}'>{$row['PrVName']} {$row['PrName']}</option>");	
				}
				echo('</select></td></tr>');
				
				echo '<tr><td>&nbsp;</td><td><button type="submit" class="pure-button pure-button-primary">Absenden</button></td></tr></table>';
				echo '</form>';
				
				break;
				
			case 5:
				$query = "SELECT PruefBez FROM pruefungsleistungen WHERE PruefID = ".$_POST['pruefid'];
				$result = mysql_fetch_array(mysql_query($query));
				echo('<h2 class="headline">Feedback eintragen</h2>');
				echo '<form class="pure-form" action="content/user_bewertungen.php?art=6">';
				echo '<table class="formtable">';
				echo '<tr><td>Pr&uuml;fung:</td><td> <input type="text" value="'.$result['PruefBez'].'" readonly> <input type="hidden" value="'.$_POST['pruefid'].'" name="pruefid"/></td></tr>';
				
				$query = "SELECT PrName, PrVName FROM pruefling WHERE PrID = ".$_POST['prid'];
				$result = mysql_fetch_array(mysql_query($query));
								
				echo '<tr><td>Pr&uuml;fling:</td><td> <input type="text" value="'.$result['PrName'].', '.$result['PrVName'].'" readonly> <input type="hidden" value="'.$_POST['prid'].'" name="prid"/></td></tr>';
				echo '<tr><td>Feedback:</td><td><textarea placeholder="Feedback" maxlength="200" name="dozfeedback"/><br> Maximal 200 Zeichen </td></tr>';
				echo '<tr><td>&nbsp;</td><td><button type="submit" class="pure-button pure-button-primary">Absenden</button></td></tr></table>';
				//echo '<a href="content/user_pruefungen.php" class="pure-button" data-change="main">Abbrechen</a>';
				echo '</form>';
				
				
			break;
							
			case 6:
				//Feedback speichern
				check_berechtigung('j', 'j', 'j', 'j', 'n');
				
				$pid = htmlspecialchars($_POST['pruefid']);
				$prid = htmlspecialchars($_POST['prid']);
				$feedback = htmlspecialchars($_POST['dozfeedback']);
				
				$query = "UPDATE pruefungsleistungsobjekt po, pruefungsleistungen pl SET po.PruefObjKommentar = '".$feedback."' WHERE pl.pruefID = po.pruefID AND po.prID = ".$prid." AND po.pruefID = ".$pid;
					
				if(mysql_query($query))
				{
					create_dialog('Feedback erfolgreich abgeschickt!', 'content/user_pruefungen.php');
						
					//echo '<br><a href="content/user_pruefungen.php" data-change="main">zur&uuml;ck</a>';
				}
				else
				{
					
					
					create_dialog('Das Feedback konnte nicht erfolgreich abgeschickt werden.', 'content/user_pruefungen.php');
					
					//echo "Failed - Just try again";
					//echo '<br><a href="content/user_pruefungen.php" data-change="main">zur&uuml;ck</a>';
				}
				
				break;
				
			/*case 7:
				//Feedback+visible
				check_berechtigung('j', 'j', 'j', 'j', 'n');
				
				$pid = htmlspecialchars($_GET['pruefid']);
				
				echo "Hier wird Feedback eingetragen und visible geschaltet.";
				
				echo '<form action="content/user_bewertungen.php">';
				echo 'Pr&uuml;fungs ID: <br> <input type="text" value="'.$pid.'" name="pruefid" readonly/><br>';
				
				//drop down liste für Student
				$query ='SELECT pr.PrID, pr.PrName
								FROM pruefling pr, pruefungsleistungsobjekt po
								WHERE pr.PrID = po.PrID
								AND po.pruefstatus = 0
								AND po.PruefID = '.$pid;
				
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
				
				break;*/
				
			case 8:
				//wirklich visible schalten?
				
				check_berechtigung('j', 'j', 'j', 'j', 'n');
				
				$pruefid = htmlspecialchars($_GET['pruefid']);
					
				$query = "SELECT PruefBez FROM pruefungsleistungen WHERE PruefID = ".$pruefid;
					
				$row = mysql_fetch_array(mysql_query($query));

				create_confirm('Wollen Sie das Objekt '.$row['PruefBez'].' wirklich sichtbar schalten? Diese Aktion kann nicht revidiert werden.', 'content/user_bewertungen.php?art=9&pruefid='.$pruefid, 'content/user_pruefungen.php');
				
				//echo "Sicher, dass Sie die Pr&uuml;fung ".$row['PruefBez']." visible schalten wollen? <br><br> Diese Aktion kann <b>nicht</b> r&uuml;ckg&auml;ngig gemacht werden! <br><br>";
					
				//echo '<br><a href="content/user_bewertungen.php?art=9&pruefid='.$pruefid.'" data-change="main">JA</a><br>';
				//echo '<br><a href="content/user_pruefungen.php" data-change="main">NEIN, Abbrechen</a><br>';
				
				break;
				
			case 9:
				//visible update
				check_berechtigung('j', 'j', 'j', 'j', 'n');
				
				$pruefid = mysql_real_escape_string($_GET['pruefid']);
					
				$query = "SELECT po.PruefObjID FROM pruefungsleistungsobjekt po, pruefungsleistungen pl WHERE pl.pruefID = po.pruefID AND pl.pruefID = ".$pruefid;
				$result = mysql_query($query);
					
				$counter = 0;
				
				while($row = mysql_fetch_assoc($result))
				{
					$query = "UPDATE pruefungsleistungsobjekt SET pruefstatus = 1 WHERE PruefObjID = ".$row['PruefObjID'];
						
					if(mysql_query($query))
					{
						//echo "Score erfolgreich visible geschalten!<br />";
					}
					else
					{
						$counter = $counter + 1;
						//echo "An Error occured - Try Again! PruefObjID = ".$row['PruefObjID']."<br />";
					}
				}
					
				if($counter == 0){
					
					create_dialog('Die Score wurde erfolgreich sichtbar geschaltet!', 'content/user_pruefungen.php');
					
				}else{
					
					create_dialog('Es ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.', 'content/user_pruefungen.php');
					
				}
				//echo '<br /><a href="content/user_pruefungen.php" data-change="main">OK, zur&uuml;ck</a>';
				
				break;
				
			case 10:
				//Ansicht fuer Prueflinge
				check_berechtigung('n', 'n', 'n', 'n', 'j');
				
				$pruefID = mysql_real_escape_string($_GET['pruefid']);
				$prID = $_SESSION['user_ID'];
				
				//Bestimme PrüfungsleistungsobjektsID
				$row = mysql_fetch_array(mysql_query("SELECT PruefObjID FROM pruefungsleistungsobjekt WHERE PruefID = ".$pruefID." AND PrID = ".$prID));
				$pruefObjID = $row['PruefObjID'];
				
				//Bestimmung der verschiedenen Prüfer
				$query1 = "	SELECT pp.PID
							FROM pruefer_pruefungsleistungen pp
							WHERE pp.PruefID = $pruefID
							ORDER BY pp.PID ASC";
						
				$result1 = mysql_query($query1);
				
				$anzprufer = 0;
				$gesamtscore = 0;
				
				while($row1 = mysql_fetch_assoc($result1))
				{
					$pid =  $row1['PID'];
				
					//Array mit Punkten erstellen
					
					$query ="SELECT b.BPunkte, a.ANr, b.bbewertungsstufe, b.PID FROM bewertungen b, aufgaben a
								WHERE b.PruefObjID = ".$pruefObjID."	
								AND b.AID = a.AID
								AND b.PID = $pid
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
						
					
					$query ="SELECT ps.SchemaID, ps.PruefGenauigkeit, a.ANr, a.AMaxPunkte, a.AID, pl.toleranz
								FROM pruefungsleistungen pl, pruefungsleistungsobjekt po, pruefungsschema ps, aufgaben a
								WHERE ps.SchemaID = a.SchemaID
								AND ps.SchemaID = pl.SchemaID
								AND po.PruefID = pl.PruefID
								AND po.PruefObjID = ".$pruefObjID."
								ORDER BY a.ANr";
						
					$result = mysql_query($query);
					$row = mysql_fetch_array($result);
					$tolerance = $row['toleranz'];
					$pruefgenau = $row['PruefGenauigkeit'];
					$summe = 0;
					
					
					//Ausgabe der Tabelle:
					
					echo "<br /><table class='pure-table'><tr><th>Aufgaben NR</th><th>MaxPunkte</th>";
					for($i=0; $i < $pruefgenau; $i++)
					{
					echo "<th>".$i."</th>";
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
						else {
							$sarray[$i] = 0;
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
									$prozentsatze[2*$i] = ($sarray[$i])/$summe;
									echo (round($prozentsatze[2*$i]*100,2) .'%');
							}
							echo '</td>';
							}
					echo "</tr>";
					$sarray = array();
					echo "</table><br />";
					//Berechnung
					$score = 0;
					//arrays von links und rechts aufsummiert erstellen
					
					$leftarray[1] = $prozentsatze[0];
					$rightarray[1] = $prozentsatze[(2*$pruefgenau-2)];
					for($i = 3; $i < 2*$pruefgenau; $i += 2)
					{
						$leftarray[$i] = $leftarray[$i-2]+$prozentsatze[$i-1];
						$rightarray[$i] = $rightarray[$i-2]+$prozentsatze[(2*$pruefgenau-1)-$i];
					}
					$r_left = 0.0;
					$r_right = 0.0;
					for($i = 1; $i < 2*$pruefgenau-2; $i += 2)
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
					$r_left = 1- $r_left/($pruefgenau-1);
					$r_right = $r_right/($pruefgenau-1);
					$score = (1-$tolerance) * min($r_left,$r_right) + $tolerance * max($r_left,$r_right);
					$score = round($score,4);
					
					echo "Toleranz: $tolerance &nbsp;&nbsp;&nbsp;&nbsp;";
					
					//Zwischenergebnis
					echo "Teilscore:   ".$score*100 ."% <br /><br />";
					
					$gesamtscore += $score;
					$anzprufer++;
				}
				
				
				echo "<br />Endscore: ".round(($gesamtscore/$anzprufer),4)*100 ."% <br /><br />";
				
				
				
				
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













