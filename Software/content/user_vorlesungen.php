<?php
	if(!defined('__ROOT__'))
   {
          define('__ROOT__', dirname(dirname(__FILE__)));
   }
   require_once(__ROOT__ ."/utils/functions.php");

   check_berechtigung('j', 'j', 'j', 'n', 'n');
   
   
   if(!isset($_GET['art'])){
   
   	$_GET['art'] = 0;
   	 
   }
    
   //0=Startseite; 1=Vorlesung bearbeiten; 2=Vorlesung update; 3=Vorlesung loeschen; 4=Vorlesung loeschen delete; 5= neue Vorlesung anlegen; 6= neue Vorlesung anlegen insert;
   switch ($_GET['art']){
   
   	case 0:
   		//Startseite
     	
   		echo 'Hier k&ouml;nnen Vorlesungseinstellungen gemacht werden <br> <br>Neue Vorlesung <a href="content/user_vorlesungen.php?art=5" data-change="main">hinzuf&uuml;gen</a>?<br><br>';
   			
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
   			
   		echo '<table class="pure-table"><tr><th>Vorlesungs ID</th><th>VorlesungsBez</th><th>Dozent</th><th>Kursbez</th><th>Bearbeiten</th><th>L&ouml;schen</th></tr>';
   			
   		while ($row = mysql_fetch_assoc($result)) {
   			echo '<tr><td>'.$row['VID'].'</td><td>'.$row['VBez'].'</td><td>'.$row['PName'].'</td><td>'.$row['KBez'].'</td><td><a href="content/user_vorlesungen.php?art=1&vid='.$row['VID'].'" data-change="main"><i class="fa fa-pencil"></i></a></td><td><a href="content/user_vorlesungen.php?art=3&vid='.$row['VID'].'" data-change="main"><i class="fa fa-trash-o"></i></a></td></tr>';
   		}
   			
   		echo "</table>";
   		
   		break;
   		
   	case 1:
   		//Vorlesung bearbeiten
   		
   		echo("Hier wird bearbeitet! <br><br>Vorlesung bearbeiten:<br>");
   		?>
   						<form class="pure-form"  action="content/user_vorlesungen.php?art=2">
   								Vorlesungs ID: <br><input type="text" value="<?php echo $_GET['vid']?>" name="vid" readonly/><br>
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
   						$query = "SELECT PruefID, Pruefbez FROM pruefungsleistungen p, vorlesungen v WHERE v.VID = p.VID AND p.VID = ".$_GET['vid'];
   						$result = mysql_query($query);
   						
   						echo '<table class="pure-table"><tr><th>Pr&uuml;fungs ID</th><th>Pr&uuml;fungsBez</th><th>Bearbeiten</th><th>L&ouml;schen</th></tr>';					
   					
   						while ($row = mysql_fetch_assoc($result)) {
   							echo '<tr><td>'.$row['PruefID'].'</td><td>'.$row['Pruefbez'].'</td><td>';			
   							
   							if($_SESSION['user_rights'] == 0 | $_SESSION['user_rights'] == 1 | $_SESSION['user_rights'] == 2)
   							{
   									echo '<a href="content/user_pruefungen.php?art=1&pruefid='.$row['PruefID'].'" data-change="main"><i class="fa fa-pencil"></i></a></td><td><a href="content/user_pruefungen.php?art=3&pruefid='.$row['PruefID'].'" data-change="main"><i class="fa fa-trash-o"></i></a>';
   							}
   							
   							echo '</td></tr>';
   						}
   					
   						echo "</table>";
   						
   						echo '<br>Eine Pr&uuml;fung <a href="content/user_pruefungen.php?art=5&vid='.$_GET['vid'].'" data-change="main">hinzuf&uuml;gen</a>?<br><br>';		
   										
   						echo('<a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>');			
   		   		
   			 
   			break;
   			
   	case 2:
   		//Vorlesung update
     	
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
   		
   		break;
   		
   	case 3:
   		//Vorlesung loeschen
     	
   		echo("Hier wird gel&ouml;scht! <br><br>");
   		
   		$query = "SELECT VBez FROM vorlesungen WHERE VID = ".$_GET['vid'];
   		$row = mysql_fetch_array(mysql_query($query));
   		echo $row['VBez'].' wirklich loeschen? <a href="content/user_vorlesungen.php?art=4&vid='.$_GET['vid'].'" data-change="main">Ja, l&ouml;schen</a><br><br>';
   		
   		echo('<a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>');
   		
   		break;
   		
   	case 4:
   		//Vorlesung loeschen delete
     	
   		$query = "DELETE FROM vorlesungen WHERE VID = ".$_GET['vid'];
   		if(mysql_query($query))
   		{
   			echo "Gel&ouml;scht!<br>";
   		}
   		else
   		{
   			echo "Fehler - nicht gel&ouml;scht!<br>";
   		}
   		echo('<a href="content/user_vorlesungen.php" data-change="main">OK, zur&uuml;ck</a>');
   		
   		break;
   		
   	case 5:
   		//neue Vorlesung anlegen
     	
   		echo("Hier wird eine neue Vorlesung hinzugef&uuml;gt! <br><br>");
   		
   		?>
   						<form class="pure-form"  action="content/user_vorlesungen.php?art=6">
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
   		
   		break;
   		
   	case 6:
   			//neue Vorlesung anlegen insert
   		
   		$kbez = mysql_real_escape_string($_POST['vbez']);
   		$pid = mysql_real_escape_string($_POST['PID']);
   		$kid = mysql_real_escape_string($_POST['KID']);
   		
   		$query = 'INSERT INTO vorlesungen VALUES (NULL, '.$kid.','.$pid.', "'.$kbez.'")';
   		
   		if(mysql_query($query))
   		{
   			echo "Ein neue Vorlesung wurde hinzugef&uuml;gt.<br><br>";
   		
   			echo 'Eine weitere Vorlesung <a href="content/user_vorlesungen.php?art=5" data-change="main">hinzuf&uuml;gen</a>?<br>';
   			echo '<a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>';
   		}
   		else
   		{
   			echo 'Error - Try Again';
   			echo($pid);
   			echo '<a href="content/user_vorlesungen.php" data-change="main">zur&uuml;ck</a>';
   		}
   			 
   			break;
   		
   
   }
		

?>
















