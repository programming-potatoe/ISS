<?php
	if(!defined('__ROOT__'))
   {
          define('__ROOT__', dirname(dirname(__FILE__)));
   }
   require_once(__ROOT__ ."/utils/functions.php");

	if(isset($_POST['password1'])) 
	{
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
	}
	else 
	{

	echo"Hier k&ouml;nnen Nutzereinstellungen gemacht werden <br><br>";
	
?>

<p>Password &auml;ndern:</p>

<form action="./content/user_profil.php">
				Aktuelles Passwort: <br> <input type="password" placeholder="Altes Passwort" name="opassword" /> <br>
				Neues Passwort: <br> <input type="password" placeholder="Neues Passwort" name="password1" /> <br>
				Passwort wiederholen: <br> <input type="password" placeholder="Passwort wiederholen" name="password2" /> <br>
				<button type="submit">Abschicken</button>
</form>

<?php
}
?>