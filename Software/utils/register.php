<!-- Derzeit nicht benutzt! Später für das Hinzufügen von neuen Usern benutzen-->

<?php
require_once("./functions.php");
$email = mysql_real_escape_string($_REQUEST['email']);
$password = mysql_real_escape_string($_REQUEST['password']);
$first_name = "";
$last_name = "";
$title = 0;
$birthday = '2000-02-19';

$query = "INSERT INTO user VALUES (NULL, '".$last_name."', '".$first_name."', ".$title.", '".$email."', '".$password."', '".$birthday."',0,0)";

$result = mysql_query($query);

if ($result) {
         echo "Thanks for using US";
} else {
         echo "An error occured, please try it again!".mysql_error();
}

mysql_close($link);
?>