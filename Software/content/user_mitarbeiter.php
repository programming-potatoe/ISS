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
	
	
			echo 'Hier k&ouml;nnen Mitarbeiter bearbeitet werden <br> Neuen Mitarbeiter <a href="content/user_mitarbeiter.php?art=5" data-change="main">hinzuf&uuml;gen</a>?<br><br>';
			
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
   		
   		echo 'Hier k&ouml;nnen die Mitarbeiter bearbeitet werden';
   		
   		$query = "SELECT PID, PName, PVName, PPwd, PEmail, PArt FROM pruefer WHERE PID = ".$_GET['pid'];
   		$row = mysql_fetch_array(mysql_query($query));
   		 
   		
   		echo'
   		<form class="pure-form"  action="content/user_mitarbeiter.php?art=2">
   				
   		<input type="hidden" name="pid" value="'.$row['PID'].'">
   				
   		Nachname: <br> <input type="text" placeholder="Nachname" name="nname" value="'.$row['PName'].'" required/> <br>
   		Vorname: <br> <input type="text" placeholder="Vorname" name="vname"  value="'.$row['PVName'].'" required/> <br>
   		Email-Adresse: <br> <input type="text" placeholder="Email" name="email" value="'.$row['PEmail'].'"  required/> <br>
   		
   		Art:<br>
   		<select name="art">
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

   		
   		echo'</select>	<br>
   
   		Neues Passwort: <br> <input type="text" placeholder="Neues Passwort" name="password"/> <br> Wenn Sie kein neues Passwort vergeben m&ouml;chten, lassen Sie dieses Feld einfach leer.
   		<br>
   		<button type="submit">Speichern</button>
   				<br>
   		<a href="content/user_mitarbeiter.php?" data-change="main">Abbrechen</a>
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
   			echo "Der Mitarbeiter wurde erfolgreich aktualisiert und es wurde ";
   			if($_POST['password']=="")
   			{
   				echo "k";
   			}
   			 echo 'ein neues Passwort gesetzt.<br><br>';
   			 //echo $query;
   			 echo '<a href="content/user_mitarbeiter.php" data-change="main">zur&uuml;ck</a>';
   		}
   		else
   		{
   			echo 'Error - Try Again<br>';
   			//echo $query;
   			echo '<br><a href="content/user_mitarbeiter.php" data-change="main">zur&uuml;ck</a>';
   		}
   		}
   		break;
   	case 3:
   		//Mitarbeiter loeschen
   		
   		echo("Hier wird gel&ouml;scht! <br><br>");
   		 
   		$query = "SELECT PName, PVName FROM pruefling WHERE PID = ".$_GET['pid'];
   		$row = mysql_query($query);
   		echo $row['PrName'].'  '.$row['PrVName'].' wirklich loeschen? <a href="content/user_mitarbeiter.php?art=4&pid='.$_GET['pid'].'" data-change="main">Ja, l&ouml;schen</a><br><br>';
   		 
   		echo('<a href="content/user_mitarbeiter.php" data-change="main">zur&uuml;ck</a>');
   		 
    	break;	

   	case 4:
   		//Mitarbeiter delete
   		
   		$query = "DELETE FROM pruefer WHERE PID = ".$_GET['pid'];
   		if(mysql_query($query))
   		{
   			echo "Gel&ouml;scht!<br>";
   		}
   		else
   		{
   			echo "Fehler - nicht gel&ouml;scht!<br>";
   		}
   		echo('<a href="content/user_mitarbeiter.php" data-change="main">zur&uuml;ck</a>');
   		
   		break;
   		

   	case 5:
   		//neuen Mitarbeiter anlegen
   		
   		echo 'Neuen Mitarbeiter anlegen';
   		?>
   		   		<form class="pure-form"  action="content/user_mitarbeiter.php?art=6">
   		   		Nachname: <input type="text" placeholder="Nachname" name="nname" required/> <br>
   		   		Vorname: <input type="text" placeholder="Vorname" name="vname" required/> <br>
   		   		Email-Adresse: <input type="text" placeholder="Email" name="email" required/> <br>
   		   		
   		   		Art: 
   		   		<select name="art">
   		   		<option selected="selected" value="1">Dozent und Pr&uuml;fer</option>
   		   		<option value="2">Pr&uuml;fer</option>
   		   		<option value="3">Dozent</option>
   		   		</select>
   		   		<br>
   		   		Initialpasswort: <input type="text" placeholder="Initialpasswort" name="password" required/> <br>
   		   		<button type="submit">Anlegen</button>
   		   		</form>
   		   		<?php 
   		
   		break;
   		
   	case 6:
   		//neuen Mitarbeiter anlegen insert
   		if (!isset($_POST['nname']) || !isset($_POST['vname']) || !isset($_POST['email']) || !isset($_POST['password']))
   		{
   			
   			echo 'Sie m&uuml;ssen alle Felder ausf&uuml;llen! <br>';
   			echo '<a href="content/user_mitarbeiter.php?art=5" data-change="main">Zur&uuml;ck</a>';
   			
   		}
   		else
   		{
   		
	   		$nname = mysql_real_escape_string($_POST['nname']);
	   		$email = mysql_real_escape_string($_POST['email']);
	   		$vname = mysql_real_escape_string($_POST['vname']);
	   		$art = mysql_real_escape_string($_POST['art']);
	   		$password = mysql_real_escape_string($_POST['password']);
	   		 
	   		$query = 'INSERT INTO pruefer(PID, PName, PVName, PPwd, PEmail, PArt) VALUES(NULL, "'.$nname.'", "'.$vname.'", "'.$password.'", "'.$email.'", "'.$art.'")';
	   		 
   		
   		
   		if(mysql_query($query))
   		{
   			echo "Ein neuer Mitarbeiter wurde hinzugef&uuml;gt.<br><br>";
   			 
   			echo 'Einen weiteren Mitarbeiter <a href="content/user_mitarbeiter.php?art=6" data-change="main">hinzuf&uuml;gen</a>?<br>';
   			echo '<a href="content/user_mitarbeiter.php" data-change="main">zur&uuml;ck</a>';
   		}
   		else
   		{
   			echo 'Error - Try Again<br>';
   			//echo 'INSERT INTO mitarbeiter(PID, PName, PVName, PPwd, PEmail, PArt) VALUES (NULL, "'.$nname.'", "'.$vname.'", "'.$password.'", "'.$email.'", "'.$art.'")<br>';
   			echo '<a href="content/user_mitarbeiter.php" data-change="main">zur&uuml;ck</a>';
   		}
   		}
   		break;
			
   }		

