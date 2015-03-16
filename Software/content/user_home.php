<?php
     if(!defined('__ROOT__'))
     {
          define('__ROOT__', dirname(dirname(__FILE__)));
     }
     require_once(__ROOT__ ."/utils/functions.php");
     

     echo "Hallo ".$_SESSION['user_vname']." ".$_SESSION['user_name'];

?>

