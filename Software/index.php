<?php

require_once("./utils/functions.php");

if (!isset($_SESSION['date'])) {
        $timestamp = time();
        $_SESSION['date'] = date('H:i:s', $timestamp);
}

if (!isset($_SESSION['login'])) {
        $_SESSION['login'] = 0;
}
?>

<!doctype html>

<html>
        <head>
                <meta charset="UTF-8">


                <title>Intelligent Score System</title>
                <link rel="stylesheet" href="design/design.css" type="text/css">

                <script src="./js/jquery-latest.pack.js" type="text/javascript"></script>
                <!--<script src="./js/md5.js" type="text/javascript"></script>-->
                <script src="./js/main.js" type="text/javascript"></script>
                
            <link rel="stylesheet" href="design/design.css" type="text/css">  
              
  			<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
  				
  			<link rel="stylesheet" href="design/frameworks/font-awesome-4.3.0/css/font-awesome.min.css">
  				
  				
  				
  				
  				
        </head>
        <body>
              <div class="ISS_Content">  
                        <?php
                        if ($_SESSION['login'] == 1) {
                                include ('./content/user_interface.php');
                        } else {
                                include ('./content/login_screen.php');
                        }
                        ?>
                </div>
        </body>
</html>
<?php
mysql_close($link);
?>
