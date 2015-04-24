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
	
   		echo 'Hier k&ouml;nnen Prueflinge bearbeitet werden <br> Es werden nur selbsterstellte Pr&uuml;flinge angezeigt.<br> Neuen Pr&uuml;fling <a href="content/user_prueflinge.php?art=5" data-change="main">hinzuf&uuml;gen</a>?<br><br>';
   		
   		$query = "SELECT PrID, PrName, PrVName, Kbez FROM pruefling, kurse WHERE PID = ".$_SESSION['user_ID']." AND kurse.KID = pruefling.KID ORDER BY PrID";
   		$result = mysql_query($query);
   			
   		echo '<table class="pure-table"><tr><th>PrID</th><th>PrName</th><th>PrVName</th><th>Kurs</th><th>Bearbeiten</th><th>L&ouml;schen</th</tr>';
   			
   		while ($row = mysql_fetch_assoc($result)) {
   			echo '<tr><td>'.$row['PrID'].'</td><td>'.$row['PrName'].'</td><td>'.$row['PrVName'].'</td><td>'.$row['Kbez'].'</td><td><a href="content/user_prueflinge.php?art=1&prid='.$row['PrID'].'" data-change="main"><i class="fa fa-pencil"></i></a></td><td><a href="content/user_prueflinge.php?art=3&prid='.$row['PrID'].'" data-change="main"><i class="fa fa-trash-o"></i></a></td></tr>';
   		}
   			
   		echo "</table>";
   		
		break;
   	case 1:
   		//Pruefling bearbeiten
   		echo("Hier wird bearbeitet! <br><br>");
   		
   		echo('<a href="content/user_prueflinge.php" data-change="main">zur&uuml;ck</a>');
   		
   		break;
   		
   	case 2:
   		//Pruefling update
   		
   		//TODO hier muss das update rein, nachdem der Pruefling bearbeitet wurde 
   		
   		break;
   		
   	case 3:
   		//Pruefling loeschen   	
   		
   		echo("Hier wird gel&ouml;scht! <br><br>");
   		
   		$query = "SELECT PrName, PrVName FROM pruefling WHERE PrID = ".$_GET['prid'];
   		$row = mysql_fetch_array(mysql_query($query));
   		echo $row['PrName'].'  '.$row['PrVName'].' wirklich loeschen? <a href="content/user_prueflinge.php?art=4&prid='.$_GET['prid'].'" data-change="main">Ja, l&ouml;schen</a><br><br>';
   		
   		echo('<a href="content/user_prueflinge.php" data-change="main">zur&uuml;ck</a>');
   		 
   		break;
   	case 4:
   		//Pruefling loeschen delete
   		
   		$query = "DELETE FROM pruefling WHERE PrID = ".$_GET['prid'];
   		if(mysql_query($query))
   		{
   			echo "Gel&ouml;scht!<br>";
   		}
   		else
   		{
   			echo "Fehler - nicht gel&ouml;scht!<br>";
   		}
   		echo('<a href="content/user_prueflinge.php" data-change="main">zur&uuml;ck</a>');
   		
   		break;
   		
   	case 5:
   		//neuen Pruefling anlegen
   		
   		
   		?>
   						<h2 class="formheadline">Neuen Pr&uuml;fling anlegen</h2>
   						<form class="pure-form"  action="content/user_prueflinge.php?art=6" class="pure-form">
   								<table class="formtable">
   									<tr>
   										<td>Nachname: </td>
   										<td><input type="text" placeholder="Nachname" name="nname" /> </td>
   									</tr>
   									<tr>
   										<td>Vorname: </td>
   										<td><input type="text" placeholder="Vorname" name="vname" /> </td>
   								</tr>
   								<tr>
   									<td>Email-Adresse: </td>
   									<td><input type="text" placeholder="Email" name="email" /> </td>
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
   									<td><input type="text" placeholder="Initialpasswort" name="password" /> </td>
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
   		
   		$query = 'INSERT INTO pruefling VALUES (NULL, "'.$nname.'", "'.$vname.'", "'.$password.'", '.$_SESSION['user_ID'].', '.$kursid.', "'.$email.'")';
   		
   		if(mysql_query($query))
   		{
   			echo "Ein neuer Pr&uuml;fling wurde hinzugef&uuml;gt.<br><br>";
   		
   			echo 'Einen weiteren Pr&uuml;fling <a href="content/user_prueflinge.php?art=5" data-change="main">hinzuf&uuml;gen</a>?<br>';
   			echo '<a href="content/user_prueflinge.php" data-change="main">zur&uuml;ck</a>';
   		}
   		else
   		{
   			echo 'Error - Try Again';
   			echo '<a href="content/user_prueflinge.php" data-change="main">zur&uuml;ck</a>';   			
   		}
   		 
   		
   		break;
   }
		

?>