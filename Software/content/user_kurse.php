<?php
	if(!defined('__ROOT__'))
   {
          define('__ROOT__', dirname(dirname(__FILE__)));
   }
   require_once(__ROOT__ ."/utils/functions.php");

		
		
		if(isset($_GET['id']))
		{
				echo("Hier wird bearbeitet! <br><br>");
?>
				<form action="content/user_kurse.php">
						Kurs ID: <br><input type="text" value="<?php echo $_GET['id']?>" name="kid" readonly/><br>
						Neue Kursbezeichnung: <br> <input type="text" placeholder="Kursbezeichnung" name="nkbez" /> <br>
						<button type="submit">&Auml;ndern</button>
				</form>
<?php	
				
				echo('<a href="content/user_kurse.php" data-change="main">zur&uuml;ck</a>');			
		}
		else if(isset($_POST['nkbez']))
		{
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
		}				
		else if(isset($_GET['lid']))
		{
				echo("Hier wird gel&ouml;scht! <br><br>");
				
				$query = "SELECT KBez FROM kurse WHERE KID = ".$_GET['lid'];				
				$row = mysql_fetch_array(mysql_query($query));
				echo $row['KBez'].' wirklich loeschen? <a href="content/user_kurse.php?llid='.$_GET['lid'].'" data-change="main">Ja, l&ouml;schen</a><br><br>';
				
				echo('<a href="content/user_kurse.php" data-change="main">zur&uuml;ck</a>');	
		}
		else if(isset($_GET['llid']))
		{
				$query = "DELETE FROM kurse WHERE KID = ".$_GET['llid'];
				if(mysql_query($query)) 
				{
					echo "Gel&ouml;scht!<br>";
				}
				else 
				{
					echo "Fehler - nicht gel&ouml;scht!<br>";
				}
				echo('<a href="content/user_kurse.php" data-change="main">OK, zur&uuml;ck</a>');
		}
		else if(isset($_GET['new']))
		{
				echo("Hier wird ein neuer Kurs hinzugef&uuml;gt! <br><br>");
?>
				<form action="content/user_kurse.php">
						Kursbezeichnung: <br> <input type="text" placeholder="Kursbezeichnung" name="kbez" /> <br>
						<button type="submit">Anlegen</button>
				</form>
<?php		
				echo('<a href="content/user_kurse.php" data-change="main">zur&uuml;ck</a>');	
		}
		else if(isset($_POST['kbez']))
		{	
				$kbez = mysql_real_escape_string($_POST['kbez']);
			   
			   $query = 'INSERT INTO kurse VALUES (NULL, "'.$kbez.'")';		   
			   
			   if(mysql_query($query))
			   { 
					echo "Ein neuer Kurs wurde hinzugef&uuml;gt.<br><br>";
				
					echo 'Einen weiteren kurs <a href="content/user_kurse.php?new=1" data-change="main">hinzuf&uuml;gen</a>?<br>';
					echo '<a href="content/user_kurse.php" data-change="main">zur&uuml;ck</a>';
				}
				else 
				{
					echo 'Error - Try Again';
				}
		}	
		else		
		{	
			//Standardanzeige:
			
			echo 'Hier k&ouml;nnen Kurseinstellungen gemacht werden <br >Neuen Kurs <a href="content/user_kurse.php?new=1" data-change="main">hinzuf&uuml;gen</a>?<br><br>';
	
			$query = "SELECT KID, KBez FROM kurse";
			$result = mysql_query($query);
			
			echo "<table><tr><td>Kurs ID</td><td>KursBez</td><td></td><td></td></tr>";					
			
			while ($row = mysql_fetch_assoc($result)) {
				echo '<tr><td>'.$row['KID'].'</td><td>'.$row['KBez'].'</td><td><a href="content/user_kurse.php?id='.$row['KID'].'" data-change="main">bearbeiten</a></td><td><a href="content/user_kurse.php?lid='.$row['KID'].'" data-change="main">l&ouml;schen</a></td></tr>';
			}
			
			echo "</table>";
		}
?>