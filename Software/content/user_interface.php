<?php
        if(!defined('__ROOT__'))
        {
                 define('__ROOT__', dirname(dirname(__FILE__)));
        }
       require_once(__ROOT__ ."/utils/functions.php");
 
?>         
<div class="mainContent">
        <header class="pageHeader">
					<p class="headerFont">Intelligent Score System - ISS</p>
					<a href="#" class="logout">Logout</a> - <?php echo $_SESSION['user_vname']." ".$_SESSION['user_name']; ?>
        </header>

    <nav class="pageSidebar">
        
<?php        

	switch($_SESSION['user_rights']) {
			//Leiter
			case 0:
					?>
		   <ul>
                        <li><a href="content/user_home.php" data-change="main">Home</a></li>
                        <li><a href="content/user_prueflinge.php" data-change="main">Pr&uuml;flinge</a></li>
                        <li><a href="content/user_mitarbeiter.php" data-change="main">Mitarbeiter</a></li>
                        <li><a href="content/user_kurse.php" data-change="main">Kurse</a></li>
                        <li><a href="content/user_vorlesungen.php" data-change="main">Vorlesungen</a></li>
                        <li><a href="content/user_pruefungen.php" data-change="main">Pr&uuml;fungen</a></li>
                        <li><a href="content/user_pruefungs_schemata.php" data-change="main">Vorlagen</a></li>
								<li><a href="content/user_profil.php" data-change="main">Profil</a></li>
		   </ul>		   
		   
		   <?php
					break;
			//Dozent + Pruefer
		   case 1:
		   		?>
		   <ul>
                        <li><a href="content/user_home.php" data-change="main">Home</a></li>
                        <li><a href="content/user_vorlesungen.php" data-change="main">Vorlesungen</a></li>
                        <li><a href="content/user_pruefungen.php" data-change="main">Pr&uuml;fungen</a></li>
                        <li><a href="content/user_pruefungs_schemata.php" data-change="main">Vorlagen</a></li>
								<li><a href="content/user_profil.php" data-change="main">Profil</a></li>
		   </ul>		   
		   
		   <?php
		   		break;
		   //Dozent
		   case 2:
		   		?>
		   <ul>
                        <li><a href="content/user_home.php" data-change="main">Home</a></li>
                        <li><a href="content/user_vorlesungen.php" data-change="main">Vorlesungen</a></li>
                        <li><a href="content/user_pruefungen.php" data-change="main">Pr&uuml;fungen</a></li>
                        <li><a href="content/user_pruefungs_schemata.php" data-change="main">Vorlagen</a></li>
							   <li><a href="content/user_profil.php" data-change="main">Profil</a></li>
		   </ul>		   
		   
		   <?php
		   		break;
		   //Pruefer
		   case 3:
		   		?>
		   <ul>
                        <li><a href="content/user_home.php" data-change="main">Home</a></li>
                        <li><a href="content/user_pruefungen.php" data-change="main">Pr&uuml;fungen</a></li>
								<li><a href="content/user_profil.php" data-change="main">Profil</a></li>
		   </ul>		   
		   
		   <?php
		   		break;
		   //Pruefling
		   case 4:?>
		   <ul>
                        <li><a href="content/user_home.php" data-change="main">Home</a></li>
                        <li><a href="content/user_pruefungen.php" data-change="main">Pr&uuml;fungen</a></li>
								<li><a href="content/user_profil.php" data-change="main">Profil</a></li>
		   </ul>		   
		   
		   <?php
		   		break;
			case -1:
		   default:break;
}

?>
        </nav>
        <section class="pageContent">
                <?php include('user_home.php'); ?>
        </section>
</div>
