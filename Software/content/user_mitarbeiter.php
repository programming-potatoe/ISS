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
   
   /*  0=Startseite;
    *  1=Mitarbeiter bearbeiten;
    *  2=Mitarbeiter bearbeiten update;
    *  3=Mitarbeiter loeschen;
    *  4=Mitarbeiter delete;
    *  5=neuen Mitarbeiter anlegen;
    *  6=Mitarbeiter insert;
    */
    
   switch ($_GET['art']){
   	
   	case 0:
   //Startseite
	
	
   			?>
   		   		
   		   		<h2 class="headline">Mitarbeiter</h2>
   		   		
   		   		
   		   	<?php 
			
			$query = "SELECT p.PID, p.PName, PVName, p.PEmail, p.PArt FROM pruefer p where p.part in(1, 2, 3)";
			$result = mysql_query($query);
				
			echo '<table class="pure-table"><tr><th>PrID</th><th>Name</th><th>Vorname</th><th>Art</th><th>Bearbeiten</th><th>L&ouml;schen</th></th>';
				
			while ($row = mysql_fetch_assoc($result)) {
				echo '<tr><td>'.$row['PName'].'</td><td>'.$row['PVName'].'</td><td>'.$row['PEmail'].'</td><td>';
				
				switch ($row['PArt']){
					
					case 1:
						echo 'Dozent und Pr&uuml;fer';
						break;
					case 2:
						echo 'Pr&uuml;fer';
						break;
					case 3: 
						echo 'Dozent';
						break;
				}
				
				echo '</td><td><a href="content/user_mitarbeiter.php?art=1&pid='.$row['PID'].'" data-change="main"><i class="fa fa-pencil"></i></a></td><td><a href="content/user_mitarbeiter.php?art=3&pid='.$row['PID'].'" data-change="main"><i class="fa fa-trash-o"></i></a></td></tr>';
			}
				
			echo "</table>";
			
			break;
			
   	case 1:
   		//Mitarbeiter bearbeiten   		
   		
   		echo '<h2 class="headline">Mitarbeiter bearbeiten</h2>';
   		
   		$query = "SELECT PID, PName, PVName, PPwd, PEmail, PArt FROM pruefer WHERE PID = ".$_GET['pid'];
   		$row = mysql_fetch_array(mysql_query($query));
   		 
   		
   		echo '
   		<form class="pure-form"  action="content/user_mitarbeiter.php?art=2">
   			<table class="formtable">
		
   				<input type="hidden" name="pid" value="'.$row['PID'].'">
   				<tr>
			
   					<td>Nachname: </td><td><input type="text" placeholder="Nachname" name="nname" value="'.$row['PName'].'" required/></td>
				</tr>
				<tr>
					<td>Vorname: </td><td><input type="text" placeholder="Vorname" name="vname"  value="'.$row['PVName'].'" required/> </td>
   				</tr>
   				<tr>
   					<td>Email-Adresse: </td> <td><input type="text" placeholder="Email" name="email" value="'.$row['PEmail'].'"  required/> </td>
   				</tr>
   				<tr>
   		
   					<td>Art:</td>
				   	<td><select name="art">
				   				';
				   		if ($row['PArt']==1)
				   		{
				   			echo '<option selected="selected" value="1">Dozent und Pr&uuml;fer</option>';
				   		}
				   		else 
				   		{
				   			echo '<option value="1">Dozent und Pr&uuml;fer</option>';
				   		}
				   		if ($row['PArt']==2)
				   		{
				   			echo '<option selected="selected" value="2">Pr&uuml;fer</option>';
				   		}
				   		else
				   		{
				   			echo '<option value="2">Pr&uuml;fer</option>';
				   		}
				   		if ($row['PArt']==3)
				   		{
				   			echo '<option selected="selected" value="3">Dozent</option>';
				   		}
				   		else
				   		{
				   			echo '<option value="3">Dozent</option>';
				   		}
				
				   		
				   		echo'</select> </td>
				</tr>
				<tr>   
   					<td>Neues Passwort: </td><td> <input type="text" placeholder="Neues Passwort" name="password"/> </td>
				</tr>
				<tr>
				   	<td colspan="2" style="padding-bottom: 10px;"> Wenn Sie kein neues Passwort vergeben m&ouml;chten, lassen Sie dieses Feld einfach leer.</td>
				</tr>
				<tr>
   					<td>&nbsp;</td>
					<td><button type="submit" class="pure-button pure-button-primary">Speichern</button></td>
				</tr>
			</table>
   		
   		</form>';
   		
   		break;
   	case 2:
		//Mitarbeiter bearbeiten update
      		if (!isset($_POST['nname']) || !isset($_POST['vname']) || !isset($_POST['email']))
   		{
   			
   			echo 'Sie m&uuml;ssen alle Felder ausf&uuml;llen! <br>';
   			echo '<a href="content/user_mitarbeiter.php?" data-change="main">Zur&uuml;ck</a>';
   			
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
   			$password = mysql_real_escape_string($_POST['password']);
   			$query = 'Update pruefer set PName="'.$nname.'", PVName="'.$vname.'", PPwd="'.$password.'", PEmail="'.$email.'", PArt="'.$art.'"  where PID="'.$pid.'"';
   		} 
   		else 
   		{
   			$query = 'Update pruefer set PName="'.$nname.'", PVName="'.$vname.'", PEmail="'.$email.'", PArt="'.$art.'"  where PID="'.$pid.'"';
   		}
   		
   		if(mysql_query($query))
   		{
   			$text = "Der Mitarbeiter wurde erfolgreich aktualisiert und es wurde ";
   			if($_POST['password']=="")
   			{
   				$text = $text. "k";
   			}
   			 $text = $text. 'ein neues Passwort gesetzt.';
   			 create_dialog($text, 'content/user_mitarbeiter.php');

   		}
   		else
   		{
   			
   			create_dialog('Fehler - Der Mitarbeite konnte nicht aktualisert werden!', 'content/user_mitarbeiter.php');
   			
   		}
   		}
   		break;
   	case 3:
   		//Mitarbeiter loeschen
   		 
   		$query = "SELECT PName, PVName FROM pruefer WHERE PID = ".$_GET['pid'];
   		$row = mysql_fetch_array(mysql_query($query));

   		create_dialog($row['PName'], $link);
   		create_dialog($row, $link);
   		
   		create_confirm('Wollen Sie '.$row['PName'].', '.$row['PVName'].' wirklich loeschen?', 'content/user_mitarbeiter.php?art=4&pid='.$_GET['pid'], 'content/user_mitarbeiter.php');
   		

    	break;	

   	case 4:
   		//Mitarbeiter delete
   		
   		$query = "DELETE FROM pruefer WHERE PID = ".$_GET['pid'];
   		if(mysql_query($query))
   		{
   			create_dialog('Der Mitarbeiter wurde erfolgreich entfernt!', 'content/user_mitarbeiter.php');
   		}
   		else
   		{
   			create_dialog('Fehler - Der Mitarbeiter konnte nicht erfolgreich entfernt werden!', 'content/user_mitarbeiter.php');
   		}
   		echo('<a href="content/user_mitarbeiter.php" data-change="main">zur&uuml;ck</a>');
   		
   		break;
   		

   	case 5:
   		//neuen Mitarbeiter anlegen
   		
   		
   		?>
   		<h2 class="headline">Neuen Mitarbeiter anlegen</h2>
   		   		<form class="pure-form"  action="content/user_mitarbeiter.php?art=6">
   		   			<table class="formtable">
   		   				<tr>
   		   					<td>Nachname: </td><td><input type="text" placeholder="Nachname" name="nname" required/> </td>
   		   				</tr>
   		   				<tr>
   		   					<td>Vorname: </td>
   		   					<td><input type="text" placeholder="Vorname" name="vname" required/> </td>
   		   				</tr>
   		   				<tr>
   		   					<td>Email-Adresse:</td>
   		   					<td><input type="text" placeholder="Email" name="email" required/> </td>
   		   				</tr>
   		   				<tr>
   		   					<td>Art:</td> 
   		   					<td><select name="art">
   		   						<option selected="selected" value="1">Dozent und Pr&uuml;fer</option>
   		   						<option value="2">Pr&uuml;fer</option>
   		   						<option value="3">Dozent</option>
   		   						</select>
   		   					</td>
   		   				</tr>
   		   				<tr>
   		   					<td>Initialpasswort:</td> 
   		   					<td><input type="text" placeholder="Initialpasswort" name="password" required/> </td>
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
   		//neuen Mitarbeiter anlegen insert

   		
	   		$nname = mysql_real_escape_string($_POST['nname']);
	   		$email = mysql_real_escape_string($_POST['email']);
	   		$vname = mysql_real_escape_string($_POST['vname']);
	   		$art = mysql_real_escape_string($_POST['art']);
	   		$password = mysql_real_escape_string($_POST['password']);
	   		 
	   		$query = 'INSERT INTO pruefer(PID, PName, PVName, PPwd, PEmail, PArt) VALUES(NULL, "'.$nname.'", "'.$vname.'", "'.$password.'", "'.$email.'", "'.$art.'")';
	   		 
   		
   		
   		if(mysql_query($query))
   		{
   			
   			create_dialog('Der Mitarbeiter wurde erfolgreich erstellt!', 'content/user_mitarbeiter.php');
   			
   		}
   		else
   		{
   			create_dialog('Der Mitarbeiter konnte nicht erstellt werden!', 'content/user_mitarbeiter.php');
   			
   		}
   		
   		break;
			
   }		

