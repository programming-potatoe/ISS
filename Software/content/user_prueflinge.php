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
   
   /* 0=Startseite; 
    * 1=Pruefling bearbeiten; 
    * 2=Pruefling update;
    * 3=Pruefling loeschen; 
    * 4=Pruefling loeschen delete; 
    * 5=neuen Pruefling anlegen; 
    * 6= neuen Pruefling anlegen insert;
    */
    
   switch ($_GET['art']){
   	
   	case 0:
   		//Startseite
   		
   		?>
   		
   		<h2 class="headline">Pr&uuml;flinge</h2>
   		
   		
   		<?php 
		$query = "SELECT PrID, PrName, PrVName, Kbez FROM pruefling, kurse WHERE PID = ".$_SESSION['user_ID']." AND kurse.KID = pruefling.KID ORDER BY PrID";
   		$result = mysql_query($query);
   			
   		echo '<table class="pure-table"><tr><th>PrName</th><th>PrVName</th><th>Kurs</th><th>Bearbeiten</th><th>L&ouml;schen</th</tr>';
   			
   		while ($row = mysql_fetch_assoc($result)) {
   			echo '<tr><td>'.$row['PrName'].'</td><td>'.$row['PrVName'].'</td><td>'.$row['Kbez'].'</td><td><a href="content/user_prueflinge.php?art=1&prid='.$row['PrID'].'" data-change="main"><i class="fa fa-pencil"></i></a></td><td><a href="content/user_prueflinge.php?art=3&prid='.$row['PrID'].'" data-change="main"><i class="fa fa-trash-o"></i></a></td></tr>';
   		}
   			
   		echo "</table>";
   		
		break;
   	case 1:
   		//Pruefling bearbeiten 		
   		
   		echo '<h2 class="headline">Pr&uuml;fling bearbeiten</h2>';
   		
   		$query = "SELECT PrID, PrName, PrVName, PrEmail FROM pruefling WHERE PrID = ".$_GET['prid'];
   		$row = mysql_fetch_array(mysql_query($query));
   		 
   		
   		echo '
   		<form class="pure-form"  action="content/user_prueflinge.php?art=2">
   			<table class="formtable">
		
   				<input type="hidden" name="pid" value="'.$row['PrID'].'">
   				<tr>
			
   					<td>Nachname: </td><td><input type="text" placeholder="Nachname" name="nname" value="'.$row['PrName'].'" required/></td>
				</tr>
				<tr>
					<td>Vorname: </td><td><input type="text" placeholder="Vorname" name="vname"  value="'.$row['PrVName'].'" required/> </td>
   				</tr>
   				<tr>
   					<td>Email-Adresse: </td> <td><input type="text" placeholder="Email" name="email" value="'.$row['PrEmail'].'"  required/> </td>
   				</tr>
   				
				<tr>   
   					<td>Neues Passwort*: </td><td> <input type="password" placeholder="Neues Passwort" name="password"/> </td>
				</tr>
				<tr>   
   					<td>Neues Passwort wiederholen*: </td><td> <input type="password" placeholder="Neues Passwort" name="password2"/> </td>
				</tr>
				
				<tr>
				   	<td colspan="2" style="padding-bottom: 10px;"> *Wenn Sie kein neues Passwort vergeben m&ouml;chten, lassen Sie dieses Feld einfach leer.</td>
				</tr>
				<tr>
   					<td>&nbsp;</td>
					<td><button type="submit" class="pure-button pure-button-primary">Speichern</button></td>
				</tr>
			</table>
   		
   		</form>';
   		
   		break;
   	case 2:
   		//Pruefling update
      		if (!isset($_POST['nname']) || !isset($_POST['vname']) || !isset($_POST['email']))
   		{
   			
   			//echo 'Sie m&uuml;ssen alle Felder ausf&uuml;llen! <br>';
   			//echo '<a href="content/user_mitarbeiter.php?" data-change="main">Zur&uuml;ck</a>';
			create_dialog('Bitte lassen Sie keine Felder leer', 'content/user_prueflinge.php');
   			
   		}
   		else
   		{
   		$pid = mysql_real_escape_string($_POST['pid']);
   		$nname = mysql_real_escape_string($_POST['nname']);
   		$email = mysql_real_escape_string($_POST['email']);
   		$vname = mysql_real_escape_string($_POST['vname']);
   		$art = mysql_real_escape_string($_POST['art']);
   		
   		 
   		if ($_POST['password']!="")
   		{
   			if ($_POST['password']!="" && $_POST['password']== $_POST['password2']){
   				$password = mysql_real_escape_string($_POST['password']);
   				$query = 'Update pruefling set PrName="'.$nname.'", PrVName="'.$vname.'", PrPwd="'.md5($password).'", PrEmail="'.$email.'"  where PrID="'.$pid.'"';
			}
			else{
				
				create_dialog('Das Passwort muss korrekt wiederholt werden.' , 'content/user_prueflinge.php');
			}
   			
   			
   		} 
   		else 
   		{
   			$query = 'Update pruefling set PrName="'.$nname.'", PrVName="'.$vname.'", PrEmail="'.$email.'" where PrID="'.$pid.'"';
   		}
   		
   		if(mysql_query($query))
   		{
   			$text = "Der Prüfling wurde erfolgreich aktualisiert und es wurde ";
   			if($_POST['password']=="")
   			{
   				$text = $text. "k";
   			}
   			 $text = $text. 'ein neues Passwort gesetzt.';
   			 create_dialog($text, 'content/user_prueflinge.php');

   		}
   		else
   		{
   			
   			create_dialog('Fehler - Der Prüfling konnte nicht aktualisert werden! - '.$query , 'content/user_prueflinge.php');
   			
   		}
   		}
   		break;

   	case 3:
   		//Pruefling loeschen   	
   		
   		echo("Hier wird gel&ouml;scht! <br><br>");
   		
   		$query = "SELECT PrName, PrVName FROM pruefling WHERE PrID = ".$_GET['prid'];
   		$row = mysql_fetch_array(mysql_query($query));
   		echo $row['PrName'].'  '.$row['PrVName'].' wirklich loeschen? <a href="content/user_prueflinge.php?art=4&prid='.$_GET['prid'].'" data-change="main">Ja, l&ouml;schen</a><br><br>';
   		
   		
   		create_confirm('Wollen Sie '.$row['PrVName'].'  '.$row['PrName'].' wirklich entfernen?', 'content/user_prueflinge.php?art=4&prid='.$_GET['prid'], 'content/user_prueflinge.php');
   		 
   		break;
   	case 4:
   		//Pruefling loeschen delete
   		
   		$query = "DELETE FROM pruefling WHERE PrID = ".$_GET['prid'];
   		
   		if(mysql_query($query))
   		{
   			create_dialog('Erfolgeich entfernt!', 'content/user_prueflinge.php');
   		}
   		else
   		{
   			create_dialog('Fehler - Der Eintrag konnte nicht entfernt werden!', 'content/user_prueflinge.php');
   		}
   		
   		
   		break;
   		
   	case 5:
   		//neuen Pruefling anlegen
   		
   		
   		?>
   						<h2 class="headline">Neuen Pr&uuml;fling anlegen</h2>
   						<form class="pure-form"  action="content/user_prueflinge.php?art=6" class="pure-form">
   								<table class="formtable">
   									<tr>
   										<td>Nachname: </td>
   										<td><input type="text" placeholder="Nachname" name="nname" required="required"/> </td>
   									</tr>
   									<tr>
   										<td>Vorname: </td>
   										<td><input type="text" placeholder="Vorname" name="vname" required="required" /> </td>
   								</tr>
   								<tr>
   									<td>Email-Adresse: </td>
   									<td><input type="email" placeholder="Email" name="email" required="required"/> </td>
   								</tr>
   								<tr>
   									<td>
   										<?php												
			   								//drop down liste für Kurs
		   									$query='SELECT KID, KBez FROM kurse';
			   								$result=mysql_query($query);
			   								echo('Kurs: </td><td><select name="KID">');
			   								while($row=mysql_fetch_assoc($result))
			   								{
		   										echo('<option value='.$row['KID'].'>'.$row['KBez'].'</option>');
			   						
   											}
   											echo('</select><br>');
   										?>						
   									</td>
   								</tr>
   								<tr>
   									<td>Initialpasswort: </td>
   									<td><input type="text" placeholder="Initialpasswort" name="password" required="required"/> </td>
   								</tr>	
   								<tr>
   									<td></td>
   									<td><button class="pure-button pure-button-primary" type="submit">Anlegen</button></td>
   								</tr>
   								<tr>
   									<td>&nbsp;</td>
   								</tr>
   							</table>
   						</form>
   		<?php		

   		
   		break;
   		
   	case 6:
   		//neuen Pruefling anlegen insert
   		
   		$nname = mysql_real_escape_string($_POST['nname']);
   		$email = mysql_real_escape_string($_POST['email']);
   		$vname = mysql_real_escape_string($_POST['vname']);
   		$kursid = mysql_real_escape_string($_POST['KID']);
   		$password = mysql_real_escape_string($_POST['password']);
   		
   		$query = 'INSERT INTO pruefling VALUES (NULL, "'.$nname.'", "'.$vname.'", "'.md5($password).'", '.$_SESSION['user_ID'].', '.$kursid.', "'.$email.'")';
   		
   		if(mysql_query($query))
   		{

   			create_dialog('Erfolgreich angelegt!', 'content/user_prueflinge.php');
   			
   		}
   		else
   		{
   			create_dialog('Fehler! Bitte erneut versuchen.', 'content/user_prueflinge.php');
   		}
   		 
   		
   		break;
   }
		

?>