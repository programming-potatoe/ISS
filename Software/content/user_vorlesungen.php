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
    
   /* 0=Startseite; 
    * 1=Vorlesung bearbeiten; 
    * 2=Vorlesung update; 
    * 3=Vorlesung loeschen; 
    * 4=Vorlesung loeschen delete; 
    * 5= neue Vorlesung anlegen; 
    * 6= neue Vorlesung anlegen insert;
    */
    
   switch ($_GET['art']){
   
   	case 0:
   		//Startseite
     	
   		   			?>
   		   		
   		   		<h2 class="headline">Vorlesungen</h2>
   		   		
   		   		
   		   	<?php 
   			
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
   			echo '<tr>
   					<td>'.$row['VID'].'</td>
   					<td>'.$row['VBez'].'</td>
   					<td>'.$row['PName'].'</td>
   					<td>'.$row['KBez'].'</td>
   					<td><a href="content/user_vorlesungen.php?art=1&vid='.$row['VID'].'" data-change="main"><i class="fa fa-pencil"></i></a></td>
   					<td><a href="content/user_vorlesungen.php?art=3&vid='.$row['VID'].'" data-change="main"><i class="fa fa-trash-o"></i></a></td>
   				</tr>';
   		}
   			
   		echo "</table>";
   		
   		break;
   		
   	case 1:
   		//Vorlesung bearbeiten
   		
   		
   		?>
   		   		<h2 class="headline">Vorlesung bearbeiten</h2>
   		   
   						<form class="pure-form"  action="content/user_vorlesungen.php?art=2">
   							<table class="formtable">
   								<tr>
   									<td>Vorlesungs ID:</td> <td><input type="text" value="<?php echo $_GET['vid']?>" name="vid" readonly/></td>
   								</tr>
   								<tr>
   		<?php						
		   						//drop down liste für Dozent
		   						$query='SELECT PID, PName FROM pruefer WHERE PArt IN (0,1,2)';
		   						$result=mysql_query($query);
		   						echo('<td>Dozent:</td> <td><select name="nPID">');
		   						while($row=mysql_fetch_assoc($result))
		   						{
		   								echo('<option value='.$row['PID'].'>'.$row['PName'].'</option>');
		   						
		   						}
		   						echo('</select></td>');
   								
   						//drop down liste für Kurs
   						$query='SELECT KID, KBez FROM kurse';
   						$result=mysql_query($query);
   						echo('</tr><tr><td>Kurs:</td> <td><select name="nKID">');
   						while($row=mysql_fetch_assoc($result))
   						{
   								echo('<option value='.$row['KID'].'>'.$row['KBez'].'</option>');
   						
   						}
   						echo('</select> </td>');						
   		?>				</tr>
   						<tr>			
   							<td>Neue Vorlesungsbezeichnung:</td><td> <input type="text" placeholder="Vorlesungsbezeichnung" name="nvbez" /> </td>
   						</tr>
   						<tr>
   							<td>&nbsp;</td><td><button type="submit" class="pure-button pure-button-primary">&Auml;ndern</button></td>
   						</tr>
   					</table>
   					
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
   									echo '	<a href="content/user_pruefungen.php?art=1&pruefid='.$row['PruefID'].'" data-change="main"><i class="fa fa-pencil"></i></a></td>
   											<td><a href="content/user_pruefungen.php?art=5&pruefid='.$row['PruefID'].'" data-change="main"><i class="fa fa-trash-o"></i></a>';
   							}
   							
   							echo '</td></tr>';
   						}
   					
   						echo "</table>";
   						
   						echo '<br>Eine Pr&uuml;fung <a href="content/user_pruefungen.php?art=3&vid='.$_GET['vid'].'" data-change="main">hinzuf&uuml;gen</a>?<br><br>';		
   							
   		   		
   			 
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
     	
   		
   		?>				<h2 class="headline">Neue Vorlesung anlegen</h2>
   						<form class="pure-form"  action="content/user_vorlesungen.php?art=6">
   							<table class="formtable">
   								<tr>
   									<td>Vorlesungsbezeichung:</td><td> <input type="text" placeholder="Vorlesungsbezeichnung" name="vbez" /> </td>
   								</tr>
   								<tr>
   									<td>
   										<?php
					   						//	drop down liste für Dozent
					   						$query='SELECT PID, PName FROM pruefer WHERE PArt IN (0,1,2)';
					   						$result=mysql_query($query);
					   						echo('Dozent:</td><td> <select name="PID" class="hallo">');
					   						while($row=mysql_fetch_assoc($result))
					   						{
					   								echo('<option value='.$row['PID'].'>'.$row['PName'].'</option>');
					   						
					   						}
					   						echo('</select></td></tr><tr><td>');
   												
					   						//drop down liste für Kurs
					   						$query='SELECT KID, KBez FROM kurse';
					   						$result=mysql_query($query);
					   						echo('Kurs:</td><td> <select name="KID">');
					   						while($row=mysql_fetch_assoc($result))
					   						{
					   								echo('<option value='.$row['KID'].'>'.$row['KBez'].'</option>');
   											
					   						}
					   						echo('</select>');
					   					?>
   									</td>	
   								</tr>
   								<tr>
   									<td>&nbsp;</td>
   									<td><button type="submit" class="pure-button pure-button-primary">Anlegen</button></td>
   								</tr>
   							</table>
   						</form>
   		<?php		
   						
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
















