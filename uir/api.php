<?php

header('Content-Type: text/html; charset=utf-8');
class sql_work{

	var $server ="p:localhost";
	var $user = "paperbloru_1";
	var $pass="09ec78fb";
	var $db="paperbloru_1";

	function connect(){//connect to dtb//using
		$qobj=mysqli_connect($this->server,$this->user,$this->pass,$this->db);
		if(mysqli_connect_errno()){
			$qobj=false;
		}
		return $qobj;
	}

    function sql_close($qobj){//close connection//using
    	$c=mysqli_close($qobj);
    	return $c;
	}

	function bind($stmt,$params,$types){//just_bind_params
		$status=false;
		$light_params=array();
		$count_of_params=count($params);
		for($i=0;$i<$count_of_params;$i++){
			$light_params[$i]=&$params[$i];
		}
		array_unshift($light_params,$types);
		call_user_func_array(array($stmt,'bind_param'),$light_params);
		if(mysqli_stmt_execute($stmt)){
			$status=true;
		}
		return $status;
	}

	function get_info_without_closing($stmt,$params,$types){//SELECT common_without_closing
		if($this->bind($stmt, $params, $types)){
			if($res=mysqli_stmt_get_result($stmt)){}
			else{
				$res=false;
			}
		}
		return $res;
	}

	/****************************************USING FUNCTIONS***********************************************************/

	function manipulation($link,$query,$params,$types){//INSERT,UPDATE,DELETE//using
		$status=false;
		if($stmt=mysqli_prepare($link,$query)){
			if($this->bind($stmt,$params,$types)){
				mysqli_stmt_close($stmt);
				$status=true;
			}else{
				$status=false;
			}
		}else{
			$status=false;
		}
		return $status;
	}

	function get_info_in_new_array($link,$query,$params,$types){//just_return_select_array//using//return new array
		$response_array=array();
		if ($stmt = mysqli_prepare($link, $query)){
			if($r=$this->get_info_without_closing($stmt,$params,$types)){
				$count=0;
				while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
					foreach ($row as $key => $value){
						$response_array[$count][$key]=$value;
					}
					$count++;
				}
				$response_array['interval']=$count;
				$res=$response_array;
				mysqli_stmt_close($stmt);
			}else{
				$res=false;
			}
		}else{
			$res=false;
		}
		return $res;
	}

	function multi_query_in_new_array($link,$query,$keys){//select_by_multi_query//using//return new array
		if (mysqli_multi_query($link, $query)){
			$j=0;$l=count($keys);$response_array=array();
			do{
				if ($result = mysqli_store_result($link)) {
					while ($row = mysqli_fetch_row($result)){
						for($i=0;$i<$l;$i++){
							$response_array[$j][$keys[$i]]=$row[$i];
						}
					}
					mysqli_free_result($result);
				}
				if (mysqli_more_results($link)){
					$j+=1;
				}
			} while (mysqli_next_result($link));
			$response_array['interval']=count($response_array);
		}else{
			$response_array=false;
		}
		return $response_array;
	}
}

/************************************************CHILD FUNCTIONS*************************************************************/

function isset_elm_in_table($link,$s_w_obj,$elm_id,$table){
	$res=false;
	$query='SELECT id FROM '.$table.' WHERE id=? LIMIT 1';
	$params=array($elm_id*1);$types='i';
	if($just_array=$s_w_obj->get_info_in_new_array($link,$query,$params,$types)){
		if($just_array['interval']>0){
			$res=true;
	}}else{
		$res=null;
	}
	return $res;
}
##########################################################################################

function are_next_items($link, $s_w_object, $curCount) {
	$query = "SELECT COUNT(*) as count FROM ads";
	if($result=$s_w_object->get_info_in_new_array($link,$query,array(),'')){
    return $result[0]['count'] > $curCount;
  }
	return null;
}

function are_next_items_by_date($link, $s_w_object, $date, $curCount) {
	$fDate = date_create_from_format('Y-m-d', $date);
	$yDate = date_create_from_format('Y', $date);
	$mDate = date_create_from_format('m', $date);
	$dDate = date_create_from_format('d', $date);
	$query = "SELECT COUNT(CASE WHEN (DATE(date) = ? OR YEAR(date) = ? OR MONTH(date) = ?  OR DAY(date) = ?) THEN 1 END) as count FROM ads";
	if($result=$s_w_object->get_info_in_new_array($link,$query,
	array(
		   date_format($fDate, 'Y-m-d'),
			 date_format($yDate, 'Y'),
			 date_format($mDate, 'm'),
			 date_format($dDate, 'd')),'ssss')){
    return $result[0]['count'] > $curCount;
  }
	return null;
}

function are_next_aep($link, $s_w_object, $curCount) {
	$query = "SELECT COUNT(DISTINCT author, email, phone_number) as count FROM ads";
	if($result=$s_w_object->get_info_in_new_array($link,$query,array(),'')){
		return $result[0]['count'] > $curCount;
	}
	return null;
}

function get_all_items($link,$s_w_object,$step) {
  $query = "SELECT * FROM ads ORDER BY date desc LIMIT ?, ?";
  if($result=$s_w_object->get_info_in_new_array($link,$query,array($step*20, ($step + 1)*20),'ii')) {
		$result['next'] = are_next_items($link, $s_w_object, ($step + 1)*20);
    echo 'for(;;);'.json_encode($result);
  }
}

function aep($link,$s_w_object,$step) {
	$query = "SELECT id, fio, email, pn FROM users LIMIT ?, ?";
	if($result=$s_w_object->get_info_in_new_array($link,$query,array($step*20, ($step + 1)*20),'ii')) {
		$result['next'] = are_next_aep($link, $s_w_object, ($step + 1)*20);
		echo 'for(;;);'.json_encode($result);
	}
}

function get_item_by_id($link,$s_w_object,$id) {
	$query = "SELECT * FROM ads WHERE id = ? LIMIT 1";
	if($result=$s_w_object->get_info_in_new_array($link,$query,array($id),'i')){
		$result['next'] = false;
		echo 'for(;;);'.json_encode($result);
	}
}

function get_item_by_date($link,$s_w_object, $date, $step) {
	$fDate = date_create_from_format('Y-m-d', $date);
	$yDate = date_create_from_format('Y', $date);
	$mDate = date_create_from_format('m', $date);
	$dDate = date_create_from_format('d', $date);
	$query = "SELECT * FROM ads WHERE DATE(date) = ? OR YEAR(date) = ? OR MONTH(date) = ?  OR DAY(date) = ? ORDER BY date desc LIMIT ?, ?";
	if($result=$s_w_object->get_info_in_new_array($link, $query,
	 array(
		 date_format($fDate, 'Y-m-d'),
		 date_format($yDate, 'Y'),
		 date_format($mDate, 'm'),
		 date_format($dDate, 'd'),
		 $step*20, ($step + 1)*20),'ssssii')){
			 $result['next'] = are_next_items_by_date($link, $s_w_object, $date, ($step + 1)*20);
			 echo 'for(;;);'.json_encode($result);
	}
}

function add_item($link,$s_w_object,$title, $author, $email, $phone_number, $text) {
	if($s_w_object->manipulation($link,
	"INSERT INTO ads (title,author,email,phone_number,text) VALUES (?,?,?,?,?)",
	array($title, $author, $email, $phone_number, $text),'sssis'
	)){
		$query = "SELECT * FROM ads ORDER BY date desc LIMIT 1";
		if($result=$s_w_object->get_info_in_new_array($link,$query,array(),'')){
			echo 'for(;;);'.json_encode($result);
		}
	} else {
		echo $title;//'for(;;);'.json_encode(array($title, $author, $email, $phone_number, $text));
	}
}

function add_user($link,$s_w_object, $fio, $email, $pn, $password) {
	if (preg_replace('/\s+/', '', $fio) != ''
	&& preg_replace('/\s+/', '', $email) != ''
	&& preg_replace('/\s+/', '', $pn) != ''
	&& preg_replace('/\s+/', '', $password) != '' ) {
		$pn = intval($pn);
		if($s_w_object->manipulation($link,
		"INSERT INTO users (fio, email, pn, password) VALUES (?,?,?,?)",
		array($fio, $email, $pn, $password),'ssss'
		)){
			$query = "SELECT id, fio, email, pn FROM users WHERE fio = ? LIMIT 1";
			if($result=$s_w_object->get_info_in_new_array($link,$query, array($fio),'s')){
				$result['status'] = 'good';
				echo 'for(;;);'.json_encode($result);
			} else {
				$result = array();
				$result['status'] = 'bad';
				echo 'for(;;);'.json_encode($result);
			}
		} else {
			echo "fail";
		}
	} else {
		$result = array();
		$result['status'] = 'bad';
		echo 'for(;;);'.json_encode($result);
	}
}

function sign_in($link,$s_w_object, $fio, $password) {
	if (preg_replace('/\s+/', '', $fio) != ''
	&& preg_replace('/\s+/', '', $password) != '' ) {
		$query = "SELECT id, fio, email, pn FROM users WHERE fio = ? AND password = ? LIMIT 1";
		if($result=$s_w_object->get_info_in_new_array($link,$query, array($fio, $password),'ss')){
			if ($result['interval'] != 0) {
				$result['status'] = 'good';
				echo 'for(;;);'.json_encode($result);
			} else {
				$result['status'] = 'wrong name/password';
				echo 'for(;;);'.json_encode($result);
			}
		} else {
			$result = array();
			$result['status'] = 'bad query';
			echo 'for(;;);'.json_encode($result);
		}
	} else {
		$result = array();
		$result['status'] = 'bad params';
		echo 'for(;;);'.json_encode($result);
	}
}

function du($link, $s_w_object, $id) {
	if($s_w_object->manipulation($link,
	"DELETE from users where id = ? LIMIT 1",
	array($id),'i'
	)){
		$result = array();
		$result['status'] = 'good';
		echo 'for(;;);'.json_encode($result);
	} else {
		$result = array();
		$result['status'] = 'bad';
		$result['id'] = $id;
		echo 'for(;;);'.json_encode($result);
	}
}

function eu($link, $s_w_object, $id, $em, $data) {
	$pieces = explode(',', $data);
	$name = explode(':', $pieces[0])[1];
	$email = explode(':', $pieces[1])[1];
	$pn = intval(explode(':', $pieces[2])[1]);
  echo "$name: " .$name;
	if($s_w_object->manipulation($link,
	"UPDATE users SET fio = ?, pn = ?, email = ? WHERE id = ? LIMIT 1",
	array($name, $pn, $email, $id),'sisi'
	)){
		if($s_w_object->manipulation($link,
		"UPDATE ads SET author = ?, phone_number = ?, email = ? WHERE email = ?",
		array($name, $pn, $email, $em),'siss'
		)){
			$result = array();
			$result['status'] = 'good';
			echo 'for(;;);'.json_encode($result);
		} else {
			$result = array();
			$result['status'] = 'bad 2';
			echo 'for(;;);'.json_encode($result);
		}
	} else {
		$result = array();
		$result['status'] = 'bad';
		echo 'for(;;);'.json_encode($result);
	}
}

$post_array=json_decode(urldecode($_POST['action']));
$s_w = new sql_work();
if ($post_array -> type == 'get_all_items') {
	if($NP=$s_w->connect()){
		get_all_items($NP,$s_w,$post_array -> step);
	} else {
		echo "fuck";
	}
} else if ($post_array -> type == 'add_item') {
	if($NP=$s_w->connect()){
		$pn = intval($post_array-> phone_number);
		add_item($NP,$s_w, $post_array-> title, $post_array-> author, $post_array-> email, $pn, $post_array-> text);
	} else {
		echo $post_array;
	}
} else if ($post_array -> type == 'search_items') {
	if ($post_array -> searchType == 'by_id') {
		if($NP=$s_w->connect()){
			get_item_by_id($NP,$s_w,$post_array -> request);
		} else {
			echo "fuck";
		}
	} else if ($post_array -> searchType == 'by_date') {
		if($NP=$s_w->connect()){
			get_item_by_date($NP,$s_w,$post_array -> request, $post_array -> step);
		} else {
			echo "fuck";
		}
	}
} else if ($post_array -> type == 'aep') {
	if($NP=$s_w->connect()){
		aep($NP,$s_w,$post_array -> request, $post_array -> step);
	} else {
		echo "fuck";
	}
} else if ($post_array -> type == 'add_user') {
	if($NP=$s_w->connect()){
		add_user($NP, $s_w, $post_array -> fio, $post_array -> email, $post_array -> pn, $post_array -> password);
	} else {
		echo "fuck";
	}
} else if ($post_array -> type == 'sign_in') {
	if($NP=$s_w->connect()){
		sign_in($NP, $s_w, $post_array -> fio, $post_array -> password);
	} else {
		echo "fuck";
	}
} else if ($post_array -> type == 'du') {
	if($NP=$s_w->connect()){
		du($NP, $s_w, $post_array -> id);
	} else {
		echo "fuck";
	}
} else if ($post_array -> type == 'eu') {
	if($NP=$s_w->connect()){
		eu($NP, $s_w, $post_array -> id, $post_array-> em, $post_array -> data);
	} else {
		echo "fuck";
	}
}


 ?>
