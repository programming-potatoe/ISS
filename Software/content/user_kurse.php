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
		 *  1=neuen Kurs bearbeiten;
		 *  2=Kurs updaten;
		 *  3=Kurs loeschen auswaehlen;
		 *  4=Kurs loeschen;
		 *  5= neuen Kurs anlegen;
		 *  6= Kurs insert;
		 */
		 
   		switch ($_GET['art']){
   		
   			case 0:
   				//Standardanzeige:
   					
   				echo 'Hier k&ouml;nnen Kurseinstellungen gemacht werden <br >Neuen Kurs <a href="content/user_kurse.php?art=5" data-change="main">hinzuf&uuml;gen</a>?<br><br>';
   				
   				$query = "SELECT KID, KBez FROM kurse";
   				$result = mysql_query($query);
   					
   				echo '<table class="pure-table"><tr><th>Kurs ID</th><th>KursBez</th><th>Bearbeiten</th><th>L&ouml;schen</th></tr>';
   					
   				while ($row = mysql_fetch_assoc($result)) {
   					echo '<tr><td>'.$row['KID'].'</td><td>'.$row['KBez'].'</td><td><a href="content/user_kurse.php?art=1&kid='.$row['KID'].'" data-change="main"><i class="fa fa-pencil"></i></a></td><td><a href="content/user_kurse.php?art=3&kid='.$row['KID'].'" data-change="main"><i class="fa fa-trash-o"></i></a></td></tr>';
   				}
   					
   				echo "</table>";
   				
   				break;
   				
   			case 1:
				//neuen Kurs bearbeiten
		
				echo("Hier wird bearbeitet! <br><br>");
?>
				<form class="pure-form"  action="content/user_kurse.php?art=2">
						Kurs ID: <input type="text" value="<?php echo $_GET['kid']?>" name="kid" readonly/><br>
						Neue Kursbezeichnung: <br> <input type="text" placeholder="Kursbezeichnung" name="nkbez" /> <br>
						<button type="submit">&Auml;ndern</button>
				</form>
<?php	
				
				echo('<a href="content/user_kurse.php" data-change="main">zur&uuml;ck</a>');			
		break;
		
			case 2: 
				//Kurs updaten
		
				$kid = $_POST['kid'];
				$nkbez = mysql_real_escape_string($_POST['nkbez']);
				
				$query = 'UPDATE kurse SET KBez = "'.$nkbez.'" WHERE KID = '.$kid;
				if(mysql_query($query))
				{
						echo "&Auml;nderung erfolgreich!<br>";
				}
				else 
				{
						echo "Error - Try Again<br>";
				}
				echo('<a href="content/user_kurse.php" data-change="main">zur&uuml;ck</a>');
		break;

			case 3:
				//Kurs loeschen auswaehlen
				
				echo("Hier wird gel&ouml;scht! <br><br>");
				
				$query = "SELECT KBez FROM kurse WHERE KID = ".$_GET['kid'];				
				$row = mysql_fetch_array(mysql_query($query));
				echo $row['KBez'].' wirklich loeschen? <a href="content/user_kurse.php?art=4&kid='.$_GET['kid'].'" data-change="main">Ja, l&ouml;schen</a><br><br>';
				
				echo('<a href="content/user_kurse.php" data-change="main">zur&uuml;ck</a>');	
		break;
		
			case 4:
				//Kurs loeschen
				
				$query = "DELETE FROM kurse WHERE KID = ".$_GET['kid'];
				if(mysql_query($query)) 
				{
					echo "Gel&ouml;scht!<br>";
				}
				else 
				{
					echo "Fehler - nicht gel&ouml;scht!<br>";
				}
				echo('<a href="content/user_kurse.php" data-change="main">OK, zur&uuml;ck</a>');
		break;
			case 5:
				//neuen Kurs anlegen
				
				
?>
				<h2 class="formheadline">Neuen Kurs anlegen</h2>
				<form class="pure-form"  action="content/user_kurse.php?art=6">
					<table class="formtable">
						<tr>
							<td>Kursbezeichnung: </td>
							<td><input type="text" placeholder="Kursbezeichnung" name="kbez" /> </td>
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
				//Kurs insert
		
				$kbez = mysql_real_escape_string($_POST['kbez']);
			   
			   $query = 'INSERT INTO kurse VALUES (NULL, "'.$kbez.'")';		   
			   
			   if(mysql_query($query))
			   { 
					echo "Ein neuer Kurs wurde hinzugef&uuml;gt.<br><br>";
				
					echo 'Einen weiteren Kurs <a href="content/user_kurse.php?art=5" data-change="main">hinzuf&uuml;gen</a>?<br>';
					echo '<a href="content/user_kurse.php" data-change="main">zur&uuml;ck</a>';
				}
				else 
				{
					echo 'Error - Try Again';
				}
		break;
   		}	

?>