<?php
require_once("./functions.php");

//Holt sich email und passwort
$email = mysql_real_escape_string($_REQUEST['email']);
$password = mysql_real_escape_string($_REQUEST['password']);

//Holt die Zeile mit Userdaten
$result = mysql_query("SELECT PName, PVName, PEmail, PPwd, PID, PArt FROM pruefer WHERE PEmail = '".$email."' UNION SELECT PrName, PrVName, PrEmail, PrPwd, PrID, '4' AS PArt FROM pruefling WHERE PrEmail = '".$email."'");

$row = mysql_fetch_array($result);

//echo $row['PEmail']."  ".$row['PPwd']."  ".$row['PID']."  ".$row['PArt'];

if ($row['PPwd'] == $password)
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
              echo "Login invalid for ".$email;
}

mysql_close($link);
?>