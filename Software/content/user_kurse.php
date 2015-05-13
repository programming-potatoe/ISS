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
   					

   				?>
   				   		   		
   				   		   		<h2 class="headline">Kurse</h2>
   				   		   		
   				   		   		
   				   		   	<?php 
   				
   				$query = "SELECT KID, KBez FROM kurse";
   				$result = mysql_query($query);
   					
   				echo '<table class="pure-table"><tr><th>KursBez</th><th>Bearbeiten</th><th>L&ouml;schen</th></tr>';
   					
   				while ($row = mysql_fetch_assoc($result)) {
   					echo '<tr><td>'.$row['KBez'].'</td><td><a href="content/user_kurse.php?art=1&kid='.$row['KID'].'" data-change="main"><i class="fa fa-pencil"></i></a></td><td><a href="content/user_kurse.php?art=3&kid='.$row['KID'].'" data-change="main"><i class="fa fa-trash-o"></i></a></td></tr>';
   				}
   					
   				echo "</table>";
   				
   				break;
   				
   			case 1:
				//neuen Kurs bearbeiten
				$query = 'SELECT KBez FROM kurse where kid='.$_GET['kid'];
				$result = mysql_fetch_array(mysql_query($query));
				
?>
				<h2 class="headline">Kurs bearbeiten</h2>
				<form class="pure-form"  action="content/user_kurse.php?art=2">
					<table class="formtable">
						<tr>
							<td>alte Kurs Bezeichnung:</td><td><input type="text" value="<?php echo $result['KBez'];?>" name="kursid" readonly> 
							
							<input type="hidden" value="<?php echo $_GET['kid']?>" name="kid"/></td>
						</tr>
						<tr>
							<td>Neue Kursbezeichnung: </td><td> <input type="text" placeholder="Kursbezeichnung" name="nkbez" required/> </td>
						</tr>
						<tr>
							<td>&nbsp;</td><td><button type="submit" class="pure-button pure-button-primary">&Auml;ndern</button></td>
						</tr>
					</table>
				</form>
<?php	
				
							
		break;
		
			case 2: 
				//Kurs updaten
		
				$kid = $_POST['kid'];
				$nkbez = mysql_real_escape_string($_POST['nkbez']);
				
				$query = 'UPDATE kurse SET KBez = "'.$nkbez.'" WHERE KID = '.$kid;
				if(mysql_query($query))
				{
						create_dialog('Der Kurs wurde erfolgreich aktualisiert!',  'content/user_kurse.php');
						
				}
				else 
				{
						create_dialog('Fehler - Der Kurs wurde nicht erfolgreich aktualisiert!', 'content/user_kurse.php');
				}
				
		break;

			case 3:
				//Kurs loeschen auswaehlen
				
				
				
				$query = "SELECT KBez FROM kurse WHERE KID = ".$_GET['kid'];				
				$row = mysql_fetch_array(mysql_query($query));
				create_confirm('Wollen Sie '.$row['KBez'].' wirklich entfernen?', 'content/user_kurse.php?art=4&kid='.$_GET['kid'], 'content/user_kurse.php');
				
				
		break;
		
			case 4:
				//Kurs loeschen
				
				$query = "DELETE FROM kurse WHERE KID = ".$_GET['kid'];
				if(mysql_query($query)) 
				{
					create_dialog('Der Kurs wurde erfolgreich entfernt!', 'content/user_kurse.php');
				}
				else 
				{
					create_dialog('Fehler - Der Kurs wurde nicht erfolgreich entfernt!', 'content/user_kurse.php');
					
				}
				
		break;
			case 5:
				//neuen Kurs anlegen
				
				
?>
				<h2 class="headline">Neuen Kurs anlegen</h2>
				<form class="pure-form"  action="content/user_kurse.php?art=6">
					<table class="formtable">
						<tr>
							<td>Kursbezeichnung: </td>
							<td><input type="text" placeholder="Kursbezeichnung" name="kbez" required/> </td>
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
					create_dialog('Ein neuer Kurs wurde erfolgreich erstellt!', 'content/user_kurse.php');
			   	
				}
				else 
				{
					create_dialog('Fehler - Der neue Kurs wurde nicht erfolgreich erstellt!', 'content/user_kurse.php');
				}
		break;
   		}	

?>