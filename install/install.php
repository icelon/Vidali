<?php
//Comprobar la integridad de los datos
$error= false;
$aux = 0;
$empty = "";
foreach ($_POST as $id => $data){
	if(empty($_POST[$id])){
		$aux++;
		$empty = $empty . "&emp=$id";
		$error = true;
	}
}
	if($error == true)
		header("Location:index.php?empty=$aux"."$empty");
	else{
		//crear el db.ini
		$connection = mysql_connect($_POST["db_dir"], $_POST["db_uname"] , $_POST["db_upass"]) or die ("<p class='error'>Epic fail...no conecta al server.</p>");
		$sql = "CREATE DATABASE " . $_POST["db_name"];
		$result = mysql_query($sql,$connection);
		if (!$result) {
				 $message  = 'Invalid query: ' . mysql_error() . "\n";
				 $message = 'Whole query: ' . $sql;
				 die($message);			 
		}
		else{
			mysql_select_db($_POST["db_name"], $connection) or die ("<p class='error'>La base de datos NO EXISTE.</p>");
			include ("../vdl-includes/vdl-core/core_security.class.php");
			$core= new CORE_SECURITY();
			$config = $core->set_db($_POST["db_dir"],$_POST["db_uname"],$_POST["db_upass"],$_POST["db_name"]);
			$nombre_archivo = 'db_struct.sql';
			$sql = explode(";",file_get_contents($nombre_archivo));// 
			foreach($sql as $query){
				$result = mysql_query($query,$connection);
				if (!$result) {
						 $message  = 'Invalid query: ' . mysql_error() . "\n";
						 $message = 'Whole query: ' . $query;
						 die($message);			 
				}
			}
		}
		//Crear usuario
		$admin = $_POST["nickname"];
		$password = mysql_real_escape_string(sha1(md5(trim($_POST["password"]))));
		$date = date($_POST["birthdate"]);
		$core= new CORE_SECURITY();
		$core-> add_user($admin,$password,$_POST["nickname"],$_POST["name"],$_POST["location"],
						 $_POST["sex"],$date,$_POST["email"],$_POST["bio"]);
		$result = mysql_query("INSERT INTO `vdl_config` (`config_id`, `config_name`, `config_value`) VALUES (4, 'ADMIN', '$admin')",$connection);
		//Fijar configuración
		$core-> login($_POST["nickname"],$_POST["password"]);
		header("Location:../index.php?pg=home&wellcome=true");
	}
?>