<?php
        if(!defined('__ROOT__'))
        {
                 define('__ROOT__', dirname(dirname(__FILE__)));
        }
       require_once(__ROOT__ ."/utils/functions.php");
 
?>         
<div class="mainContent">
        <header class="pageHeader">
					<p class="headerFont"><i class="fa fa-leaf "></i> Intelligent Score System - ISS</p>
					<a href="#" class="logout pure-button" style="margin-left: 90%; margin-top: -40px;">Logout</a> 
					<br />
					<div class="user">User: <?php echo $_SESSION['user_vname']." ".$_SESSION['user_name']; ?></div>
        </header>

    <nav class="pageSidebar">
        
<?php        

	switch($_SESSION['user_rights']) {
			//Leiter
			case 0:
					?>
		   
                        <div class="pageSidebarEntry">
                        	<a href="content/user_home.php" data-change="main">Home</a>
                        	<!--<div class="pageSidebarMenu">
                        		<a href="#">Link1</a>
                        		<a href="#">Link2</a>
                        	</div>-->
                        </div>
                        
                        <div class="pageSidebarEntry">
                        	<a href="content/user_prueflinge.php" data-change="main">Pr&uuml;flinge</a>
                        	<div class="pageSidebarMenu">
                        		<a href="content/user_prueflinge.php?art=5" data-change="main">Anlegen</a>
                        	</div>
                        </div>
                        
                        <div class="pageSidebarEntry">
                        <a href="content/user_mitarbeiter.php" data-change="main">Mitarbeiter</a>
                        <div class="pageSidebarMenu">
                        		<a href="content/user_mitarbeiter.php?art=5" data-change="main">Anlegen</a>
                        	</div>
                        </div>
                        
                        <div class="pageSidebarEntry">
                        	<a href="content/user_kurse.php" data-change="main">Kurse</a>
                        	<div class="pageSidebarMenu">
                        		<a href="content/user_kurse.php?art=5" data-change="main">Anlegen</a>
                        	</div>
                        </div>
                        
                        <div class="pageSidebarEntry">
                        	<a href="content/user_vorlesungen.php" data-change="main">Vorlesungen</a>
                        	<div class="pageSidebarMenu">
                        		<a href="content/user_vorlesungen.php?art=5" data-change="main">Anlegen</a>
                        	</div>
                        </div>
                        
                        <div class="pageSidebarEntry">
                        	<a href="content/user_pruefungen.php" data-change="main">Pr&uuml;fungen</a>
                        	<div class="pageSidebarMenu">
                        		<a href="content/user_pruefungen.php?art=10" data-change="main">Anlegen</a>
                        	</div>
                        </div>
                        
                        <div class="pageSidebarEntry">
                        	<a href="content/user_pruefungs_schemata.php" data-change="main">Vorlagen</a>
                        	<div class="pageSidebarMenu">
                        		<a href="#">Link1</a>
                        		<a href="#">Link2</a>
                        	</div>
                        </div>
                        
                        <div class="pageSidebarEntry">
							<a href="content/user_profil.php" data-change="main">Profil</a>
							<div class="pageSidebarMenu">
                        		<a href="#">Link1</a>
                        		<a href="#">Link2</a>
                        	</div>
                        </div>
		      
		   
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
