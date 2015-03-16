<?php
	if(!defined('__ROOT__'))
   {
          define('__ROOT__', dirname(dirname(__FILE__)));
   }
   require_once(__ROOT__ ."/utils/functions.php");

	
		if(isset($_GET['id']))
		{
				echo("Hier wird bearbeitet! <br><br>Vorlesung bearbeiten:<br>");
?>
				<form action="content/user_vorlesungen.php">
						Vorlesungs ID: <br><input type="text" value="<?php echo $_GET['id']?>" name="vid" readonly/><br>
<?php						
				//drop down liste für Dozent
				$query='SELECT PID, PName FROM pruefer WHERE PArt IN (0,1,2)';
				$result=mysql_query($query);
				echo('Dozent: <br> <select name="nPID">');
				while($row=mysql_fetch_assoc($result))
				{
						echo('<option value='.$row['PID'].'>'.$row['PName'].'</option>');
				
				}
				echo('</select><br>');
					
				//drop down liste für Kurs
				$query='SELECT KID, KBez FROM kurse';
				$result=mysql_query($query);
				echo('Kurs: <br> <select name="nKID">');
				while($row=mysql_fetch_assoc($result))
				{
						echo('<option value='.$row['KID'].'>'.$row['KBez'].'</option>');
				
				}
				echo('</select> <br>');						
?>							
						Neue Vorlesungsbezeichnung: <br> <input type="text" placeholder="Vorlesungsbezeichnung" name="nvbez" /> <br>
						<button type="submit">&Auml;ndern</button>
				</form>
			
<?php	
				echo "<br><br>Pr&uuml;fungen für diese Vorlesung:";
				$query = "SELECT PruefID, Pruefbez FROM pruefungsleistungen p, vorlesungen v WHERE v.VID = p.VID AND p.VID = ".$_GET['id'];
				$result = mysql_query($query);
				
				echo "<table><tr><td>Pr&uuml;fungs ID</td><td>Pr&uuml;fungsBez</td><td></td><td></td></tr>";					
			
				while ($row = mysql_fetch_assoc($result)) {
					echo '<tr><td>'.$row['PruefID'].'</td><td>'.$row['Pruefbez'].'</td><td>';			
					
					if($_SESSION['user_rights'] == 0 | $_SESSION['user_rights'] == 1 | $_SESSION['user_rights'] == 2)
					{
							echo '<a href="content/user_pruefungen.php?id='.$row['PruefID'].'" data-change="main">bearbeiten</a></td><td><a href="content/user_pruefungen.php?lid='.$row['PruefID'].'" data-change="main">l&ouml;schen</a>';
					}
					
					echo '</td></tr>';
				}
			
				echo "</table>";
				
				echo '<br>Eine Pr&uuml;fung <a href="content/user_pruefungen.php?new='.$_GET['id'].'" data-change="main">hinzuf&uuml;gen</a>?<br><br>';		
								
				echo('<a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>');			
		}
		else if(isset($_POST['nvbez']))
		{
				$vid = $_POST['vid'];
				$nvbez = mysql_real_escape_string($_POST['nvbez']);
				$npid = mysql_real_escape_string($_POST['nPID']);			   
			   $nkid = mysql_real_escape_string($_POST['nKID']);
				
				$query = 'UPDATE vorlesungen SET VBez = "'.$nvbez.'", PID = '.$npid.', KID = '.$nkid.' WHERE VID = '.$vid;
				if(mysql_query($query))
				{
						echo "&Auml;nderung erfolgreich!<br>";
				}
				else 
				{
						echo "Error - Try Again<br>";
				}
				echo('<a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>');
		}				
		else if(isset($_GET['lid']))
		{
				echo("Hier wird gel&ouml;scht! <br><br>");
				
				$query = "SELECT VBez FROM vorlesungen WHERE VID = ".$_GET['lid'];				
				$row = mysql_fetch_array(mysql_query($query));
				echo $row['VBez'].' wirklich loeschen? <a href="content/user_vorlesungen.php?llid='.$_GET['lid'].'" data-change="main">Ja, l&ouml;schen</a><br><br>';
				
				echo('<a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>');	
		}
		else if(isset($_GET['llid']))
		{
				$query = "DELETE FROM vorlesungen WHERE VID = ".$_GET['llid'];
				if(mysql_query($query)) 
				{
					echo "Gel&ouml;scht!<br>";
				}
				else 
				{
					echo "Fehler - nicht gel&ouml;scht!<br>";
				}
				echo('<a href="content/user_vorlesungen.php" data-change="main">OK, zur&uuml;ck</a>');
		}
		else if(isset($_GET['new']))
		{
				echo("Hier wird eine neue Vorlesung hinzugef&uuml;gt! <br><br>");
				
?>
				<form action="content/user_vorlesungen.php">
						Vorlesungsbezeichung: <br> <input type="text" placeholder="Vorlesungsbezeichnung" name="vbez" /> <br>
	
<?php
				//drop down liste für Dozent
				$query='SELECT PID, PName FROM pruefer WHERE PArt IN (0,1,2)';
				$result=mysql_query($query);
				echo('Dozent: <br> <select name="PID">');
				while($row=mysql_fetch_assoc($result))
				{
						echo('<option value='.$row['PID'].'>'.$row['PName'].'</option>');
				
				}
				echo('</select><br>');
					
				//drop down liste für Kurs
				$query='SELECT KID, KBez FROM kurse';
				$result=mysql_query($query);
				echo('Kurs: <br> <select name="KID">');
				while($row=mysql_fetch_assoc($result))
				{
						echo('<option value='.$row['KID'].'>'.$row['KBez'].'</option>');
				
				}
				echo('</select>');
?>
						<br>	
						<button type="submit">Anlegen</button>
				</form>
<?php		
				echo('<a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>');	
		}
		else if(isset($_POST['vbez']))
		{	
				$kbez = mysql_real_escape_string($_POST['vbez']);
				$pid = mysql_real_escape_string($_POST['PID']);			   
			   $kid = mysql_real_escape_string($_POST['KID']);
			   
			   $query = 'INSERT INTO vorlesungen VALUES (NULL, '.$kid.','.$pid.', "'.$kbez.'")';		   
			   
			   if(mysql_query($query))
			   { 
					echo "Ein neue Vorlesung wurde hinzugef&uuml;gt.<br><br>";
				
					echo 'Eine weitere Vorlesung <a href="content/user_vorlesungen.php?new=1" data-change="main">hinzuf&uuml;gen</a>?<br>';
					echo '<a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>';
				}
				else 
				{
					echo 'Error - Try Again';
					echo($pid);
				}
		}	
		else		
		{	
			//Standardanzeige:
			
			echo 'Hier k&ouml;nnen Vorlesungseinstellungen gemacht werden <br> <br>Neue Vorlesung <a href="content/user_vorlesungen.php?new=1" data-change="main">hinzuf&uuml;gen</a>?<br><br>';
			
			if($_SESSION['user_rights'] == 0)
			{
					$query = "SELECT v.VID, v.VBez, PName, k.KBez FROM vorlesungen v, kurse k, pruefer p WHERE k.KID = v.KID AND v.PID = p.PID";
			}
			else if($_SESSION['user_rights'] == 1 | $_SESSION['user_rights'] == 2)
			{
					$query = "SELECT v.VID, v.VBez, PName, k.KBez FROM vorlesungen v, kurse k, pruefer p WHERE k.KID = v.KID AND v.PID = p.PID AND p.PID = ".$_SESSION['user_ID'];				
			}
			else
			{
					$query = "";
			}
				
			$result = mysql_query($query);
			
			echo "<table><tr><td>Vorlesungs ID</td><td>VorlesungsBez</td><td>Dozent</td><td>Kursbez</td><td></td><td></td></tr>";					
			
			while ($row = mysql_fetch_assoc($result)) {
				echo '<tr><td>'.$row['VID'].'</td><td>'.$row['VBez'].'</td><td>'.$row['PName'].'</td><td>'.$row['KBez'].'</td><td><a href="content/user_vorlesungen.php?id='.$row['VID'].'" data-change="main">bearbeiten</a></td><td><a href="content/user_vorlesungen.php?lid='.$row['VID'].'" data-change="main">l&ouml;schen</a></td></tr>';
			}
			
			echo "</table>";
		}

?>
















