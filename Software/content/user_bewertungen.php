<?php
	if(!defined('__ROOT__'))
   {
          define('__ROOT__', dirname(dirname(__FILE__)));
   }
   require_once(__ROOT__ ."/utils/functions.php");

	//Prüfung anzeigen + bewerten
	if($_SESSION['user_rights'] == 0 | $_SESSION['user_rights'] == 1 | $_SESSION['user_rights'] == 2 | $_SESSION['user_rights'] == 3)	
	{
	if(isset($_GET['id']))
	{
			echo 'Hier kann eine Bewertung abgeben werden.<br><br>';
								
				echo '<form action="content/user_bewertungen.php">';
				echo 'Pr&uuml;fungs ID: <br> <input type="text" value="'.$_GET['id'].'" name="pruefID" readonly/><br>';

				//drop down liste für Student
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
	}
	// Bewertung eintragen
	else if(isset($_POST['prid'])) 
	{
				$pruefID = mysql_real_escape_string($_POST['pruefID']);
				$prID = mysql_real_escape_string($_POST['prid']);		
				
				$row = mysql_fetch_array(mysql_query("SELECT PruefObjID FROM pruefungsleistungsobjekt WHERE PruefID = ".$pruefID." AND PrID = ".$prID));
				$pruefObjID = $row['PruefObjID'];		
				
				echo "Pr&uuml;fling mit ID ".$prID." wird hier bewertet<br><br>";
				
				//Array mit bissherigen Bewertungen erstellen
				
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
				
				
				echo '<form action="content/user_bewertungen.php">';
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
	}
	else if(isset($_POST['bewpruefID']))
	{
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
				
	}
	else if(isset($_GET['dozid']))
	{
				echo "Hier wird Feedback eingetragen und visible geschaltet.";
			
				echo '<form action="content/user_bewertungen.php">';
				echo 'Pr&uuml;fungs ID: <br> <input type="text" value="'.$_GET['dozid'].'" name="dozpruefID" readonly/><br>';

				//drop down liste für Student
				$query ='SELECT pr.PrID, pr.PrName 
							FROM pruefling pr, pruefungsleistungsobjekt po 
							WHERE pr.PrID = po.PrID
							AND po.pruefstatus = 0
							AND po.PruefID = '.$_GET['dozid'];
							
				$result = mysql_query($query);
				echo('Student: <br> <select name="dozprid">');
				while($row = mysql_fetch_assoc($result))
				{
						echo('<option value='.$row['PrID'].'>'.$row['PrName'].'</option>');
				
				}
				echo('</select><br><br>');
				
				echo '<button type="submit">Feedback geben</button><br><br>';
				echo '</form>';
				
				echo '<form action="content/user_bewertungen.php">';
				echo 'Pr&uuml;fungs ID: <br> <input type="text" value="'.$_GET['dozid'].'" name="visibleID" readonly/><br>';
				echo '<button type="submit">Visible schlten</button>';
				echo '</form>';	
				
				echo '<br><a href="content/user_pruefungen.php" data-change="main">zur&uuml;ck</a>';
	}
	else if(isset($_POST['dozpruefID']))
	{
		
				//@TODO: Feedbacks werden gnadenlos überschrieben..

		
				echo '<form action="content/user_bewertungen.php">';
				echo 'Pr&uuml;fungs ID: <br> <input type="text" value="'.$_POST['dozpruefID'].'" name="adozpruefID" readonly/><br>';
				echo 'Pr&uuml;flings ID: <br> <input type="text" value="'.$_POST['dozprid'].'" name="adozprID" readonly/><br>';
				echo 'Feedback: <br> <textarea placeholder="Feedback" name="dozfeedback"/><br> Maximal 200 Zeichen <br><br>';
				echo '<button type="submit">Absenden</button><br><br>';
				echo '</form>';
	}
	else if(isset($_POST['dozfeedback']))
	{
				$feedback = mysql_real_escape_string($_POST['dozfeedback']);
				$query = "UPDATE pruefungsleistungsobjekt po, pruefungsleistungen pl SET po.PruefObjKommentar = '".$feedback."' WHERE pl.pruefID = po.pruefID AND po.prID = ".$_POST['adozprID']." AND po.pruefID = ".$_POST['adozpruefID'];
				
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
	}
	else if(isset($_POST['visibleID']))
	{
				$visibleID = mysql_real_escape_string($_POST['visibleID']);
						
				$query = "SELECT PruefBez FROM pruefungsleistungen WHERE PruefID = ".$visibleID;
				
				$row = mysql_fetch_array(mysql_query($query));
		
				echo "Sicher, dass Sie die Pr&uuml;fung ".$row['PruefBez']." visible schalten wollen? <br><br> Diese Aktion kann <b>nicht</b> r&uuml;ckg&auml;ngig gemacht werden! <br><br>";
				
				echo '<br><a href="content/user_bewertungen.php?visibleID='.$visibleID.'" data-change="main">JA</a><br>';
				echo '<br><a href="content/user_pruefungen.php" data-change="main">NEIN, Abbrechen</a><br>';
	}
	else if(isset($_GET['visibleID']))
	{
				$visibleID = mysql_real_escape_string($_GET['visibleID']);
				
				$query = "SELECT po.PruefObjID FROM pruefungsleistungsobjekt po, pruefungsleistungen pl WHERE pl.pruefID = po.pruefID AND pl.pruefID = ".$visibleID;
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
	}
	else 
	{		
			echo"Hier k&ouml;nnen Bewertungen eingesehen und bearbeitet werden <br> <br>";
	}
	}
	
	//Ansicht fuer Prueflinge
	else if($_SESSION['user_rights'] == 4)
	{
			if(isset($_GET['showid']))
			{
				$pruefID = mysql_real_escape_string($_GET['showid']);
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
				for($i=0; $i < $pruefgenau; $i++) 
					{
						echo '<td>';
						
						if(isset($sarray[$i]))
						{
								echo (($sarray[$i]*100)/$summe).'%';
						}
						
						echo '</td>';
					}	
				echo "</tr>";
				
				echo "</table><br><br>";
				
				$score = 0;
				
				for($i = 0; $i < $pruefgenau; $i++)
				{
						if(isset($sarray[$i]))
						{
							$score = $score + (($i)/($pruefgenau-1))*(($sarray[$i]*100)/$summe);
						}
				}
				
				
				//Endgültige Score:
				echo "Score:   ".$score."% <br><br>";
				
				
				
				
				
				//@TODO: FEEDBACK beantworten!
				
				
				
				
				
				
				$query = "SELECT PruefObjKommentar FROM pruefungsleistungsobjekt WHERE pruefObjID = ".$pruefObjID;
				
				$row = mysql_fetch_array(mysql_query($query));
								
				echo "Feedback des Dozenten:<br><br>";
				
				echo "<i>".$row['PruefObjKommentar']."</i><br><br>";
				
				echo '<a href="content/user_pruefungen.php" data-change="main">zur&uuml;ck</a>';
			}
			else
			{
				echo "Nix zu melden.";
			} 
	}
	else 
	{
			echo "Permission denied!";
	}
		
?>













