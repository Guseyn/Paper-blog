<?php 

class sql_work{
	
	var $server ="p:localhost";
	var $user = "paperbloru_main";
	var $pass="dvfvdrg4545hebrsh5rbt5erdf";
	var $db="paperbloru_main";
	
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
?>