<?php

function get_user_id($link,$s_w_obj,$hash_sess_id){
	$user_agent=strtolower($_SERVER['HTTP_USER_AGENT']);
	$query='SELECT id,isable FROM ussd WHERE hash_sess_id=? LIMIT 1;';
	$result=false;
	if($just_array=$s_w_obj->get_info_in_new_array(
	$link,
	$query,
	array($hash_sess_id),
	's'
	)){
		if($just_array['interval']>0){
			if($just_array[0]['isable']===1){
				$result=$just_array[0]['id'];
			}else{
				$result='not_able';
			}}else{
				$result=false;
		}}else{
			$result=false;
	}
	return $result;
}


?>