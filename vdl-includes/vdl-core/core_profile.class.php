<?php
require_once 'core_user.class.php';

class CORE_PROFILE extends CORE_USER{
	/*Private*/

	/*Public*/

	public function __construct (){
		parent::__construct();
	}

	public function create($_user_id,$_passwd,$_nickname,$_name,$_location,$_genre,$_bday,$_email,$_bio){
		$query = ("INSERT INTO vdl_users (user_id,passwd,nickname,name,location,genre,bday,email,bio,img_prof) VALUES
					 ('$_user_id','$_passwd','$_nickname','$_name','$_location','$_genre','$_bday','$_email','$_bio','prof_def')");
		$result = mysql_query($query,$connection);
		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message = 'Whole query: ' . $query;
			die($message);
		}
	}
	
	public function get_profile($_user,$_refer){
		$connection = parent::connect();
		$a_result1 = parent::get_user($_user, $_refer);
		$query = sprintf("SELECT vdl_users.id FROM vdl_users WHERE vdl_users.user_id='%s'", $_user);
		$result=mysql_query($query,$connection);
		$id = mysql_fetch_assoc($result);
		$a_result1 = array();
		$query = sprintf("SELECT vdl_user_net.id_net FROM vdl_user_net WHERE vdl_user_net.id_user='%s'", $id["id"]);
		$result=mysql_query($query,$connection);
		while ($rowa = mysql_fetch_assoc($result)){
			$query1 = ("SELECT net_name,net_sdesc,net_img FROM vdl_net WHERE vdl_net.id='".$rowa["id_net"]."'");
			$result1 = mysql_query($query1,$connection);
			while ($row = mysql_fetch_assoc($result1)){
				array_push($a_result1,$row);
			}
		}
		$a_result2 = array();
		$query = ("SELECT id_friend,id_main FROM vdl_friends WHERE vdl_friends.id_main='".$id["id"]."' OR vdl_friends.id_friend='".$id["id"]."'");
		$result = mysql_query($query,$connection);
		while ($rowa = mysql_fetch_assoc($result)){
			if($rowa["id_main"] == $id["id"])
				$query1 = sprintf("SELECT nickname,img_prof FROM vdl_users WHERE vdl_users.id='%s'", $rowa["id_friend"]);
			else
				$query1 = sprintf("SELECT nickname,img_prof FROM vdl_users WHERE vdl_users.id='%s'", $rowa["id_main"]);
			$result1=mysql_query($query1,$connection);
			while ($row = mysql_fetch_assoc($result1)){
				array_push($a_result2,$row);
			}
		}
		
/*		//Calculamos la edad y la fecha del cumplea�os siguiente
		if ($client){
			if(substr($a_result[0]['bday'], 5, 2) > date("n")){
				$a_result[0]['age']= date("Y")-substr($a_result[0]['bday'], 0, 4)-1;
				$a_result[0]['bday'] = substr($a_result[0]['bday'], 8, 2).'/'.substr($a_result[0]['bday'], 5, 2).'/'.(substr($a_result[0]['bday'], 0, 4)+$a_result[0]['age']+1);
			}else{
				if(date("n")==substr($a_result[0]['bday'], 5, 2) AND substr($a_result[0]['bday'], 8, 2)>date("j")){
					$a_result[0]['age']= date("Y")-substr($a_result[0]['bday'], 0, 4)-1;
					$a_result[0]['bday'] = substr($a_result[0]['bday'], 8, 2).'/'.substr($a_result[0]['bday'], 5, 2).'/'.(substr($a_result[0]['bday'], 0, 4)+$a_result[0]['age']+1);
				}else{
					$a_result[0]['age']= date("Y")-substr($a_result[0]['bday'], 0, 4);
					$a_result[0]['bday'] = substr($a_result[0]['bday'], 8, 2).'/'.substr($a_result[0]['bday'], 5, 2).'/'.(substr($a_result[0]['bday'], 0, 4)+$a_result[0]['age']+1);
				}
			}
		}*/
		$result = array();
		array_push($result,$a_result1);
		array_push($result,$a_result2);
		return $result;
	}

	public function get_updates($_user){
		$connection = parent::connect();
		$query = sprintf("SELECT * FROM vdl_updates WHERE user_id = '%s' ORDER BY id DESC LIMIT 0, 30", $_user);
		$result=mysql_query($query,$connection);
		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $query;
			die($message);
		}
		//mostrar resultado
		$arresult=array();
		while ($row = mysql_fetch_array($result)) {
			array_push($arresult,$row);
		}
		return $arresult;
	}
	
	
	public function delete(){
		
	}

	public function modify(){
	
	}
	
	public function set_privacy(){
	
	}
	
	public function update($_user,$_message,$_s_id){
		$connection = parent::connect();
		date_default_timezone_set('Europe/London');
		$date = date("Y-m-d G:i:s");
		$query = ("SELECT user_id FROM  `vdl_users` WHERE  `session_id` =  '".$_s_id."'");
		$result = mysql_query($query,$connection) or die(mysql_error('Ups, algo falla a la hora de postear...prueba luego.'));
		$user = mysql_fetch_assoc($result);
		if( $user["user_id"] == $_user)
			$query = ("INSERT INTO vdl_updates (user_id,upd_msg,date) VALUES ('$_user','$_message', '$date')");
		else
			$query = ("INSERT INTO vdl_updates (user_id,upd_msg,date) VALUES ('".$user["user_id"]."','$_message', '$date')");
		$result = mysql_query($query,$connection) or die(mysql_error('Ups, algo falla a la hora de postear...prueba luego.'));	
		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $query;
			die($message);
		}
	}
	
	public function join_network(){
	
	}
	
	public function add_friend(){
	
	}
	
	public function delete_friend(){
	
	}
	
	public function add_note(){
	
	}
	
	public function add_file(){
	
	}
	
}

?>