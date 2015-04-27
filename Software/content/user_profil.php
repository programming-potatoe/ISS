<?php
	if(!defined('__ROOT__'))
   {
          define('__ROOT__', dirname(dirname(__FILE__)));
   }
   require_once(__ROOT__ ."/utils/functions.php");

   
   check_berechtigung('j', 'j', 'j', 'j', 'j');
    
    
   if(!isset($_GET['art'])){
   	 
   	$_GET['art'] = 0;
   	 
   }
   
   //0=Startseite; 1=Passwort speichern;
   switch ($_GET['art']){
   	 
   	case 0:
   		//Startseite
   
   		//echo"Hier k&ouml;nnen Nutzereinstellungen gemacht werden <br><br>";
   		
   		?>
   		<h2 class="headline">Profileinstellungen &auml;ndern</h2>
   		<h4 class="headline">Password &auml;ndern:</h4>
   		
   		<form class="pure-form" action="./content/user_profil.php?art=1">
   			<table class="formtable">
   				<tr>
   					<td>Aktuelles Passwort: </td><td><input type="password" placeholder="Altes Passwort" name="opassword" /> </td>
   				</tr>
   				<tr>
   					<td>Neues Passwort:</td> <td><input type="password" placeholder="Neues Passwort" name="password1" /> </td>
   				</tr>
   				<tr>
   					<td>Passwort wiederholen:</td> <td><input type="password" placeholder="Passwort wiederholen" name="password2" /> </td>
   				</tr>
   				<tr>
   					<td>&nbsp;</td><td><button type="submit" class="pure-button pure-button-primary">Abschicken</button></td>
   				</tr>
   			</table>
   		</form>
   		
   		<?php
   		
   		break;
   	case 1:
   		//Passwort speichern
   		
   		$opassword = mysql_real_escape_string($_POST['opassword']);
   		$password1 = mysql_real_escape_string($_POST['password1']);
   		$password2 = mysql_real_escape_string($_POST['password2']);
   		
   		if(strcmp($password1, $password2) == 0)
   		{
   			if($_SESSION['user_rights'] == 4)
   			{
   				$row = mysql_fetch_array(mysql_query("SELECT PrPWD FROM pruefling WHERE PrID = ".$_SESSION['user_ID']));
   				if(strcmp($row['PrPWD'], $opassword) == 0)
   				{
   					$query = "UPDATE pruefling SET PrPwd = '".$password1."' WHERE PrID = ".$_SESSION['user_ID'];
   		
   					if(mysql_query($query) == 1)
   					{
   						echo "Passwort ge&auml;ndert!";
   					}
   				}
   				else { echo "Falsches Passwort!"; }
   			}
   			else
   			{
   				$row = mysql_fetch_array(mysql_query("SELECT PPWD FROM pruefer WHERE PID = ".$_SESSION['user_ID']));
   				if(strcmp($row['PPWD'], $opassword) == 0)
   				{
   					$query = "UPDATE pruefer SET PPwd = '".$password1."' WHERE PID = ".$_SESSION['user_ID'];
   		
   					if(mysql_query($query))
   					{
   						echo "Passwort ge&auml;ndert!";
   					}
   				}
   				else { echo "Falsches Passwort!"; }
   			}
   		}
   		else { echo "Neue Passw&ouml;rter stimmen nicht &uuml;berein."; }
   		
   		break;
   }

?>