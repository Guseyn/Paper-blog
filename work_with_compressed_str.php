<?php
function get_compressed_str($link,$s_w_object,$id,$type_str,$table){
	$result=false;
	$types_array=explode(',',$type_str);
	$t_len=count($types_array);
	$query='SELECT '.$type_str.' FROM '.$table.' WHERE id=? LIMIT 1';
	if($pre_result=$s_w_object->get_info_in_new_array(
	$link,
	$query,
	array($id),
	'i'
	)){
		if($pre_result['interval']>0){
			if($t_len===1){
				$result=$pre_result[0][$type_str];	
			}else{
				for($i=0;$i<$t_len;$i++){
					$result[$types_array[$i]]=$pre_result[0][$types_array[$i]];
				}
			}	
		}else{
			$result=false;
		}
	}else{
		$result=false;
	}
	return $result;
}

function is_elm_in_compressed_str($compressed_str,$checking_elm){
	$array=json_decode(gzuncompress($compressed_str));
	if(in_array($checking_elm, $array)){
		$result=true;
	}else{
		$result=false;
	}
	return $result;
}

function add_to_compressed_str($compressed_str,$adding_elm){
	$result=false;$array=json_decode(gzuncompress($compressed_str));
	if(in_array($adding_elm, $array)){
		$result=false;
	}else{
		array_push($array, $adding_elm);
		sort($array);
		$result= gzcompress(json_encode($array));
	}
	return $result;
}

function delete_from_compressed_str($compressed_str,$deleting_elm){
	$result=false;$array=json_decode(gzuncompress($compressed_str));
	$index=array_search($deleting_elm, $array);
	if($index===false){
		$result=false;
	}else{
		unset($array[$index]);
		$array=array_values($array);
		$result=gzcompress(json_encode($array));
	}
	return $result;
}

function change_compressed_str($compressed_str,$changing_elm){
	$result=false;$array=json_decode(gzuncompress($compressed_str));
	$index=array_search($changing_elm, $array);
	if($index===false){
		array_push($array, $changing_elm);
		sort($array);
		$result['compr_str']= gzcompress(json_encode($array));
		$result['act']='added';
	}else{
		unset($array[$index]);
		$array=array_values($array);
		$result['compr_str']=gzcompress(json_encode($array));
		$result['act']='deleted';
	}
	return $result;	
}

function update_compressed_str($link,$s_w_object,$id,$type_str,$table,$new_compressed_str,$q,$plus_minus){
	$result=true;$query_types='';$params=array();
	$query='UPDATE '.$table.' SET ';
	if(is_array($q)&&is_array($plus_minus)){
		$q_count=count($q);$plus_minus_count=count($plus_minus);
		if($q_count===$plus_minus_count){
			for($i=0;$i<$q_count;$i++){
				if(($q[$i]!=='')&&($plus_minus[$i]!==0)){
					$query.=$q[$i].'='.$q[$i].$plus_minus[$i].'1, ';
				}
			}
		}else{
			$result=false;
		}
	}else{
		if(($q!=='')&&($plus_minus!==0)){
			$query.=$q.'='.$q.$plus_minus.'1, ';
		}
	}
	$types_array=explode(',',$type_str);$t_len=count($types_array);
	if(is_array($new_compressed_str)){
		$n_c_count=count($new_compressed_str);
		if($n_c_count===$t_len){
			$query.=$types_array[0].'=?';
			$query_types.='s';
			for($i=1;$i<$t_len;$i++){
				$query.=' ,'.$types_array[$i].'=?';
				$query_types.='s';
			}
            $query.=' WHERE id=? LIMIT 1;';
			$query_types.='i';
			$params=$new_compressed_str;
			array_push($params,$id);
		}else{
			$result=false;
		}
	}else{
		$query.=$type_str.'=? WHERE id=? LIMIT 1;';
		$query_types='si';
		$params=array($new_compressed_str,$id);
	}
	if($s_w_object->manipulation(
	$link,
	$query,
	$params,
	$query_types
	)&&$result){
		$result=true;
	}else{
		$result=false;
	}
	return $result;
}
?>