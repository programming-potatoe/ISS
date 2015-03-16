<?php
        if (!isset($_SESSION)) {
                session_start();
        }
        require_once("connect_db.php");
			
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
