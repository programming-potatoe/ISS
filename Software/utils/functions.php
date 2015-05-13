<?php
        if (!isset($_SESSION)) {
                session_start();
        }
        require_once("connect_db.php");
        
        
        // checkt, ob der user eine gueltige session besitzt und ob die Seite fuer das Berechtigunglevel des Users freigegeben ist, dafuer muss die variable ==j sein
        function check_berechtigung($leiter, $dozent, $pruefer, $dozent_pruefer, $pruefling)
        {
        	//echo 'Berechtigungslevel ist '.$_SESSION['user_rights'].' und die Variablen sind: '.$leiter.' '.$dozent.' '.$pruefer.' '.$dozent_pruefer.' '.$pruefling;
        	if(!isset ($_SESSION['login'])) 
        	{
        		echo 'permission denied';		
        		exit();
        	}
        	else
        	{
        		if (!isset ($_SESSION['user_rights']))
        		{
        			echo 'permission denied';
        			exit();
        		}
        		else
        		{
        			if ($leiter!='j' && $_SESSION['user_rights']==0) 
        			{
        				echo 'permission denied';
        				exit();
        			}
        			if ($dozent!='j' && $_SESSION['user_rights']==1)
        			{
        				echo 'permission denied';
        				exit();
        			}
        			if ($pruefer!='j' && $_SESSION['user_rights']==2)
        			{
        				echo 'permission denied';
        				exit();
        			}
        			if ($dozent_pruefer!='j' && $_SESSION['user_rights']==3)
        			{
        				echo 'permission denied';
        				exit();
        			}
        			if ($pruefling!='j' && $_SESSION['user_rights']==4)
        			{
        				echo 'permission denied';
        				exit();
        			}
        		}
        	}
        }
        function create_dialog($text, $link){

        	echo '<script type="text/javascript">
   				
					alert(\''.$text.'\');
							
							
					$.post(\''.$link.'\', function(data) {
                        	    
                    	$(\'.pageContent\').html(data);
                        
                    });
                   </script>';
        	
        }
        function create_confirm($text, $link1, $link2){
        
        	echo '<script type="text/javascript">
   	
					
					if(confirm("'.$text.'")){
				
						$.post(\''.$link1.'\', function(data) {
               
                        	$(\'.pageContent\').html(data);
        
                        });
					}else{
        
        				$.post(\''.$link2.'\', function(data) {
               
                        	$(\'.pageContent\').html(data);
        
                        });
        
        			}
                   </script>';
        	 
        }
		function show_vorlage($vorlagenid){
			
		$query = "SELECT p.SchemaID, p.SchemaBez, p.PruefGenauigkeit, a.ANr, a.AMaxPunkte FROM pruefungsschema p, aufgaben a WHERE p.SchemaID = a.SchemaID AND a.SchemaID = ".$vorlagenid." ORDER BY a.ANr";
   		$result = mysql_query($query);
   		$row = mysql_fetch_array($result);
			echo '<table class="pure-table"><tr><th>Aufgaben NR</th><th>MaxPunkte</th>';
   		for($i=0; $i < $row['PruefGenauigkeit']; $i++)
   		{
   		echo "<th>$i</th>";
				}
   		
   						echo "</tr>";
   		
   						do{
   				echo '<tr><td>'.$row['ANr'].'</td><td>'.$row['AMaxPunkte'].'</td>';
   				for($i=0; $i < $row['PruefGenauigkeit']; $i++)
   					{
						echo "<td>--</td>";
   				}
   		
   				echo "</tr>";
				} while ($row = mysql_fetch_assoc($result));
   		
   				echo "</table><br><br>";
			
		}

        
			/*Returns row with all user Information.*/
        /*function selectUserData()
        {
                 $result = mysql_query("SELECT * FROM user WHERE user_ID = '".$_SESSION['user_ID']."'");
                 return $result;
        }
		
		function insert_db($table, $column, $row)
		{	
				$query = "INSERT INTO $table ($column) VALUES ($row);";	
				return mysql_query($query);					 
		}
		
		function select_db1($table, $column, $conditions)
		{
				$conditions = implode("AND ", $conditions);
				$query = "SELECT $column FROM $table WHERE $conditions";
				return mysql_query($query);
		}*/
		
?>
