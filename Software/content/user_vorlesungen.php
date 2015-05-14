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
   			
   		echo '<table class="pure-table"><tr><th>VorlesungsBez</th><th>Dozent</th><th>Kursbez</th><th>Bearbeiten</th><th>L&ouml;schen</th></tr>';
   			
   		while ($row = mysql_fetch_assoc($result)) {
   			echo '<tr>
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
   		
   		$query='SELECT VBez, PID, KID, VID FROM vorlesungen where VID = '.$_GET['vid']; 
   		$vorlesungs_result = mysql_fetch_assoc(mysql_query($query));
   		?>
   		   		<h2 class="headline">Vorlesung "<?php echo $vorlesungs_result['VBez'];?>" bearbeiten</h2>
   		   
   		
   		   
   						<form class="pure-form"  action="content/user_vorlesungen.php?art=2">
   							<table class="formtable" style="margin-bottom: 20px;">
   								<input type="hidden" value="<?php echo $_GET['vid']?>" name="vid" readonly/></td>
   								<tr>
   		<?php						
		   						//drop down liste für Dozent, richtiger Dozent wird ausgewaehlt
		   						$query='SELECT PID, PName FROM pruefer WHERE PArt IN (0,1,2)';
		   						$result=mysql_query($query);
		   						echo('<td>Dozent:</td> <td><select name="nPID">');
		   						while($row=mysql_fetch_assoc($result))
		   						{
		   								if ($vorlesungs_result['PID'] = $row['PID']){
		   									
		   									echo('<option value='.$row['PID'].' selected="selected">'.$row['PName'].'</option>');
		   								}
		   								else{
		   									echo('<option value='.$row['PID'].'>'.$row['PName'].'</option>');
		   								}
		   						
		   						}
		   						echo('</select></td>');
   								
   						//drop down liste für Kurs
   						$query='SELECT KID, KBez FROM kurse';
   						$result=mysql_query($query);
   						echo('</tr><tr><td>Kurs:</td> <td><select name="nKID">');
   						while($row=mysql_fetch_assoc($result))
   						{
   							if ($vorlesungs_result['KID'] = $row['KID']){
   							
   								echo('<option value='.$row['KID'].' selected="selected">'.$row['KBez'].'</option>');
   							}
   							else{
   								echo('<option value='.$row['KID'].' selected="selected">'.$row['KBez'].'</option>');
   							}
   							
   						
   						}
   						echo('</select> </td>');						
   		?>				</tr>
   						<tr>			
   							<td>Vorlesungsbezeichnung:</td><td> <input type="text" placeholder="Vorlesungsbezeichnung" name="nvbez" required value="<?php echo $vorlesungs_result['VBez'];?>"/> </td>
   						</tr>
   						<tr>
   							<td>&nbsp;</td><td><button type="submit" class="pure-button pure-button-primary">&Auml;ndern</button></td>
   						</tr>
   						<tr>
   						<td colspan="2">&nbsp;</td>
   						</tr>
						<tr><td colspan="2" style="margin-top:10px;">
   					
   		<?php	
   						echo "<b>Pr&uuml;fungen für diese Vorlesung:</b>";
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
   								
   						echo '</td></tr>
   					
   					<tr><td colspan="2">&nbsp;</td></tr>
   					
   					<tr><td>&nbsp;</td><td><a href="content/user_pruefungen.php?art=3&vid='.$_GET['vid'].'" data-change="main" class="pure-button">Pr&uuml;fung f&uuml;r diese Vorlesung anlegen</a></td>
   					
   					</table>
   								</form>';
   			 
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
   			create_dialog('Erfolgreich gespeichert!', 'content/user_vorlesungen.php');
   		}
   		else
   		{
   			
   			create_dialog('Beim Speichern ist leider ein Fehler unterlaufen!', 'content/user_vorlesungen.php');
   		}
   		
   		
   		break;
   		
   	case 3:
   		//Vorlesung loeschen
     	
   		
   		
   		$query = "SELECT VBez FROM vorlesungen WHERE VID = ".$_GET['vid'];
   		$row = mysql_fetch_array(mysql_query($query));
   		
   		
   		create_confirm('Wollen Sie '.$row['VBez'].' wirklich entfernen?', 'content/user_vorlesungen.php?art=4&vid='.$_GET['vid'], 'content/user_vorlesungen.php');
   		
   		break;
   		
   	case 4:
   		//Vorlesung loeschen delete
     	
   		$query = "DELETE FROM vorlesungen WHERE VID = ".$_GET['vid'];
   		if(mysql_query($query))
   		{
   			create_dialog('Die Vorlesung wurde erfolgreich entfernt!', 'content/user_vorlesungen.php');
   		}
   		else
   		{
   			create_dialog('Die Vorlesung konnte nicht erfolgreich entfernt werden!', 'content/user_vorlesungen');
   		}
   		
   		
   		break;
   		
   	case 5:
   		//neue Vorlesung anlegen
     	
   		
   		?>				<h2 class="headline">Neue Vorlesung anlegen</h2>
   						<form class="pure-form"  action="content/user_vorlesungen.php?art=6">
   							<table class="formtable">
   								<tr>
   									<td>Vorlesungsbezeichung:</td><td> <input type="text" placeholder="Vorlesungsbezeichnung" name="vbez" required/> </td>
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
   			create_dialog('Die Vorlesung wurde erfolgreich erstellt!', 'content/user_vorlesungen.php');	
   		}
   		else
   		{
   			create_dialog('Die Vorlesung konnte nicht erfolgreich erstellt werden!', 'content/user_vorlesungen.php');
   		}
   			 
   			break;
   		
   
   }
		

?>
















