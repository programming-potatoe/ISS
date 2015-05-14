<?php
require_once("./functions.php");

//Holt sich email und passwort
$email = mysql_real_escape_string($_REQUEST['email']);
$password = mysql_real_escape_string($_REQUEST['password']);

//Holt die Zeile mit Userdaten
$result = mysql_query("SELECT PName, PVName, PEmail, PPwd, PID, PArt FROM pruefer WHERE PEmail = '".$email."' UNION SELECT PrName, PrVName, PrEmail, PrPwd, PrID, '4' AS PArt FROM pruefling WHERE PrEmail = '".$email."'");

$row = mysql_fetch_array($result);

//echo $row['PEmail']."  ".$row['PPwd']."  ".$row['PID']."  ".$row['PArt'];

if ($row['PPwd'] == $password  && $password != "")
{
                   //$now = date('Y-m-d H:i:s');

                   $_SESSION['login'] = 1;
						 
						 $_SESSION['user_name'] = $row['PName'];						 
						 $_SESSION['user_vname'] = $row['PVName'];	
                   $_SESSION['user_ID'] = $row['PID'];
                   $_SESSION['user_rights'] = $row['PArt'];

                   include('../content/user_interface.php');
}
else
{
	echo '<div class="loginContent">
			<div class="login_header">
			<h1>ISS</h1>
			<h2><u>I</u>ntelligent <u>S</u>core <u>S</u>ystem</h2>
			<br>
			<i class="fa fa-leaf fa-5x"></i>
		</div>';
	
              echo '<div class="login_fail" style="margin-top: 3em; ">Der Login mit der Email-Adresse "'.$email.'" konnte leider nicht durchgef&uuml;hrt werden. Bitte versuchen Sie es <a href="#" onclick="location.reload();">erneut.</a></div>';
              echo '</div>';
}

mysql_close($link);
?>