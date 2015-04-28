<?php
     if(!defined('__ROOT__'))
     {
          define('__ROOT__', dirname(dirname(__FILE__)));
     }
     require_once(__ROOT__ ."/utils/functions.php");
     

     echo '<div class="homeseite">Herzlich Willkommen, '.$_SESSION['user_vname'].' '.$_SESSION['user_name'].', im <i class="fa fa-leaf "></i> Intelligent Score System - ISS</div>';

?>


