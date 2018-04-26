<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Atavism Installation Script</title>
</head>

<body>

<?php

ini_set('max_execution_time', 300);

if (isset($_POST['submit'])) {

	include("../atavismfunctions.inc.php");
	//include("../config.inc.php");
	//include("../conndb_atavism.inc.php");
	if (empty($_POST['username']) || empty($_POST['password'])) {
		$error = "Username or Password is invalid";
	}else{
		$dbuser = $_POST['username']; //Gettting root MySQL Username from POST
		$dbpass = $_POST['password']; //Getting MySQL Password from POST
		$dns = $_POST['dns'];
    $email = $_POST['email'];
    $serverkey = $_POST['serverkey'];
		$installtype = $_POST['installtype'];
		
		echo "Install Type is: $installtype<br>";
	
		if($installtype == "NewInstall"){	
			
			$dbrestore_master = restoreMySQLTableSpecialTrigger("localhost",$dbuser,$dbpass,"master","./sql/auth.sql");
			$dbrestore_admin = restoreMySQLTableSpecialTrigger("localhost",$dbuser,$dbpass,"admin","./sql/admin.sql");
			$dbrestore_atavism = restoreMySQLTableSpecialTrigger("localhost",$dbuser,$dbpass,"atavism","./sql/install.sql");
			$dbrestore_world_content = restoreMySQLTableSpecialTrigger("localhost",$dbuser,$dbpass,"world_content","./sql/world_content.sql");
			
			//Now to insert the dns into the master world table ADDED STATIC localhost
			/*$conndb_master_static = mysql_connect("localhost", $dbuser, $dbpass, true) or die                      ('Error connecting to mysql');
			mysql_select_db("master", $conndb_master_static);
			$query_master_tables  = "UPDATE world SET server_name='" . $dns . "' WHERE world_id=1";
			$result_master_tables = mysql_query($query_master_tables, $conndb_master_static);
			
			if($result_master_tables){
					echo "Updated value of server_name to $dns<br>";
			}	else{
					echo "Error updating record: " . $conndb_master_static->error;
			}*/
		}else{
			
			
			// Backup Master	
			echo "Start Backup of Master DB<br>";
			$backup_file = ".\backups\sql\Atavism-Master" .date("m-d-Y-h_i_s_a", time()) . ".sql";
	
			// get backup Master
			$mybackup = backup_tables("localhost",$dbuser,$dbpass,"master");
			
			// save to file
			$handle = fopen($backup_file,"w+");
			fwrite($handle,$mybackup);
			fclose($handle);	
			echo "Made Backup of Master DB<br>";
			
			// Backup Admin	
			echo "Start Backup of Admin DB<br>";
			$backup_file = ".\backups\sql\Atavism-Admin" .date("m-d-Y-h_i_s_a", time()) . ".sql";
	
			// get backup Admin
			$mybackup = backup_tables("localhost",$dbuser,$dbpass,"admin");
			
			// save to file
			$handle = fopen($backup_file,"w+");
			fwrite($handle,$mybackup);
			fclose($handle);	
			echo "End Backup of Master DB<br>";		
			
			// Backup Atavism
			echo "Start Backup of Atavism DB<br>";
			$backup_file = ".\backups\sql\Atavism-Atavism" .date("m-d-Y-h_i_s_a", time()) . ".sql";
	
			// get backup Atavism
			$mybackup = backup_tables("localhost",$dbuser,$dbpass,"atavism");
			
			// save to file
			$handle = fopen($backup_file,"w+");
			fwrite($handle,$mybackup);
			fclose($handle);	
			echo "End Backup of Atavism DB<br>";
			
			// Backup World_Content	
			echo "Start Backup of Master DB<br>";
			$backup_file = ".\backups\sql\Atavism-World_Content" .date("m-d-Y-h_i_s_a", time()) . ".sql";
	
			// get backup World_Content
			$mybackup = backup_tables("localhost",$dbuser,$dbpass,"world_content");
			
			// save to file
			$handle = fopen($backup_file,"w+");
			fwrite($handle,$mybackup);
			fclose($handle);	
			
				
			// Do the Update	
			$dbupdate = restoreMySQLTable("localhost",$dbuser,$dbpass,"admin","./sql/updates/update_commands2_6.sql");
			echo "End Backup of Master DB<br>";
		}
		
		//Do it either NewInstall or Update
		replace_in_file("./configfiles/auth.properties", "[USERNAME]", $dbuser);
		replace_in_file("./configfiles/auth.properties", "[PASSWORD]", $dbpass);
    replace_in_file("./configfiles/auth.properties", "[DNSNAME]", $dns);
		replace_in_file("./configfiles/world.properties", "[USERNAME]", $dbuser);
		replace_in_file("./configfiles/world.properties", "[PASSWORD]", $dbpass);
		replace_in_file("./configfiles/world.properties", "[DNSNAME]", $dns);
    replace_in_file("./configfiles/world.properties", "[EMAIL]", $email);
    replace_in_file("./configfiles/world.properties", "[SERVERKEY]", $serverkey);
		// Depriciated 2.6
		//replace_in_file("./configfiles/content_admin_connection.ini", "[USERNAME]", $dbuser);
		//replace_in_file("./configfiles/content_admin_connection.ini", "[PASSWORD]", $dbpass);
		// End Depriciated 2.6
		replace_in_file("../config.inc.php", "[USERNAME]", $dbuser);
		replace_in_file("../config.inc.php", "[PASSWORD]", $dbpass);
		replace_in_file("../connection.inc.php", "[USERNAME]", $dbuser);
		replace_in_file("../connection.inc.php", "[PASSWORD]", $dbpass);
		
		
		echo "<br><br>If you do not see any errors, everything has been done correctly<br>Don't forget to move the generated files from the configfiles into your bin folder of the atavism server ";			
	}

}else{ ?>
    <div id="main" align="center">
    <h1>Enter credential to access your MySQL</h1>
    <br>This will reinstall the default Database OR Update to the latest Atavism, make sure to Delete the folder if you plan to have a live server <br>
    Note: You can find your Atavism Server Key in the <a href="http://www.neojac.com/accountmanagement" target="_blank">Atavism Account Management Website</a><br>
    or use your Order/Invoice Number from Unity Asset Store or Steam.<br>
    <div id="login">
    <h2>Login Form</h2>
    <form action="" method="post">
    <label>Atavism Account Email :</label>
    <input id="email" name="email" placeholder="email" type="text"><br>
    <label>Atavism Server Key :</label>
    <input id="serverkey" name="serverkey" placeholder="serverkey" type="text"><label></<br>
    <label>UserName :</label>
    <input id="name" name="username" placeholder="username" type="text"><br>
    <label>Password :  </label>
    <input id="password" name="password" placeholder="**********" type="password"><br>
    <label>Your DNS :  </label>
    <input id="dns" name="dns" type="text" value="localhost"><br>
    <label>Install Type : </label>
    <input id="installtype" name="installtype" type="radio" value="NewInstall" checked>New Install
    <input id="installtype" name="installtype" type="radio" value="Update">Update<br>
    <input name="submit" type="submit" value="Install"><br>
    <span><?php if(isset($error))echo $error; ?></span>
    </form>
    </div>
    </div>
<?php
	
}


?>

</body>
</html>